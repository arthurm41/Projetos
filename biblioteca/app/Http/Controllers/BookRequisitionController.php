<?php

namespace App\Http\Controllers;

use App\Mail\NovaRequisicaoMail;
use App\Mail\RequisicaoAprovadaMail;
use App\Mail\RequisicaoDespachadaMail;
use App\Models\Book;
use App\Models\BookRequisition;
use App\Models\StockWithdrawal;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class BookRequisitionController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if ($user->hasRole('almoxarife')) {
            $query = BookRequisition::with(['book.subjects', 'requester'])
                ->orderByRaw("FIELD(status, 'pending', 'approved', 'dispatched', 'delivered', 'cancelled')")
                ->latest();

            if ($search = request('search')) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('book', fn($b) => $b->where('title', 'like', "%{$search}%"))
                      ->orWhereHas('requester', fn($r) => $r->where('name', 'like', "%{$search}%"));
                });
            }

            if ($status = request('status')) {
                $query->where('status', $status);
            }

            if ($from = request('date_from')) {
                $query->whereDate('created_at', '>=', $from);
            }

            if ($to = request('date_to')) {
                $query->whereDate('created_at', '<=', $to);
            }

            $requisitions = $query->paginate(15)->withQueryString();
        } else {
            $query = BookRequisition::with(['book.subjects', 'approver'])
                ->where('requested_by', $user->id)
                ->latest();

            if ($status = request('status')) {
                $query->where('status', $status);
            }

            if ($from = request('date_from')) {
                $query->whereDate('created_at', '>=', $from);
            }

            if ($to = request('date_to')) {
                $query->whereDate('created_at', '<=', $to);
            }

            $requisitions = $query->paginate(15)->withQueryString();
        }

        return view('requisitions.index', compact('requisitions'));
    }

    public function create(): View
    {
        $books = Book::with('subjects')->orderBy('title')->get();

        return view('requisitions.create', compact('books'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'book_id'     => 'required|exists:books,id',
            'quantity'    => 'required|integer|min:1',
            'class_group' => 'nullable|string|max:100',
            'reason'      => 'nullable|string|max:500',
        ], [
            'book_id.required' => 'Selecione um livro.',
            'quantity.min'     => 'A quantidade deve ser ao menos 1.',
        ]);

        $requisition = BookRequisition::create([
            ...$validated,
            'requested_by' => Auth::id(),
            'status'       => 'pending',
        ]);

        $requisition->load(['book.subjects', 'requester']);

        $almoxarifes = User::whereHas('roles', fn($q) => $q->where('slug', 'almoxarife'))->get();

        try {
            foreach ($almoxarifes as $almoxarife) {
                Mail::to($almoxarife->email)->send(new NovaRequisicaoMail($requisition));
            }
        } catch (\Exception $e) {
            \Log::warning("Falha ao enviar e-mail de nova requisição #{$requisition->id}: {$e->getMessage()}");
        }

        return redirect()->route('requisitions.index')
            ->with('success', 'Requisição enviada. Aguarde a aprovação do almoxarife.');
    }

    public function show(BookRequisition $requisition): View
    {
        $requisition->load(['book.subjects', 'requester', 'approver']);

        return view('requisitions.show', compact('requisition'));
    }

    public function approve(BookRequisition $requisition, Request $request): RedirectResponse
    {
        if (! Auth::user()->hasRole('almoxarife')) {
            return redirect()->route('requisitions.index')
                ->with('error', 'Apenas o almoxarife pode aprovar requisições.');
        }

        if (! $requisition->isPending()) {
            return back()->with('error', 'Apenas requisições pendentes podem ser aprovadas.');
        }

        $validated = $request->validate([
            'estimated_delivery_from' => 'required|date|after_or_equal:today',
            'estimated_delivery_to'   => 'required|date|after_or_equal:estimated_delivery_from',
        ], [
            'estimated_delivery_from.required'       => 'Informe a data inicial da previsão de entrega.',
            'estimated_delivery_from.after_or_equal' => 'A data inicial deve ser hoje ou posterior.',
            'estimated_delivery_to.required'         => 'Informe a data final da previsão de entrega.',
            'estimated_delivery_to.after_or_equal'   => 'A data final deve ser igual ou posterior à data inicial.',
        ]);

        try {
            DB::transaction(function () use ($requisition, $validated) {
                $book = Book::lockForUpdate()->findOrFail($requisition->book_id);

                if ($book->current_stock < $requisition->quantity) {
                    throw new \Exception(
                        "Estoque insuficiente. Disponível: {$book->current_stock} unidade(s). Requisitado: {$requisition->quantity}."
                    );
                }

                StockWithdrawal::create([
                    'book_id'        => $requisition->book_id,
                    'user_id'        => Auth::id(),
                    'requisition_id' => $requisition->id,
                    'quantity'       => $requisition->quantity,
                    'stock_before'   => $book->current_stock,
                    'stock_after'    => $book->current_stock - $requisition->quantity,
                    'class_group'    => $requisition->class_group,
                    'reason'         => "Requisição #{$requisition->id}: " . ($requisition->reason ?? 'sem motivo especificado'),
                    'withdrawn_at'   => now(),
                ]);

                $requisition->update([
                    'status'                  => 'approved',
                    'approved_by'             => Auth::id(),
                    'approved_at'             => now(),
                    'estimated_delivery_from' => $validated['estimated_delivery_from'],
                    'estimated_delivery_to'   => $validated['estimated_delivery_to'],
                ]);
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        $requisition->load(['book.subjects', 'requester', 'approver']);

        try {
            Mail::to($requisition->requester->email)
                ->send(new RequisicaoAprovadaMail($requisition));
        } catch (\Exception $e) {
            \Log::warning("Falha ao enviar e-mail de aprovação da requisição #{$requisition->id}: {$e->getMessage()}");
        }

        return redirect()->route('requisitions.index')
            ->with('success', "Requisição #{$requisition->id} aprovada com previsão de entrega registrada.");
    }

    public function dispatch(BookRequisition $requisition, Request $request): RedirectResponse
    {
        if (! Auth::user()->hasRole('almoxarife')) {
            return redirect()->route('requisitions.index')
                ->with('error', 'Apenas o almoxarife pode confirmar a entrega.');
        }

        if (! $requisition->isApproved()) {
            return back()->with('error', 'Apenas requisições aprovadas podem ser confirmadas para entrega.');
        }

        $validated = $request->validate([
            'dispatched_at' => 'required|date',
            'delivered_by'  => 'required|string|max:150',
        ], [
            'dispatched_at.required' => 'Informe a data e hora da entrega.',
            'delivered_by.required'  => 'Informe quem realizou a retirada.',
        ]);

        DB::transaction(function () use ($requisition, $validated) {
            $requisition->update([
                'status'        => 'dispatched',
                'dispatched_at' => $validated['dispatched_at'],
                'delivered_by'  => $validated['delivered_by'],
            ]);
        });

        $requisition->load(['book.subjects', 'requester']);

        try {
            Mail::to($requisition->requester->email)
                ->send(new RequisicaoDespachadaMail($requisition));
        } catch (\Exception $e) {
            \Log::warning("Falha ao enviar e-mail de despacho da requisição #{$requisition->id}: {$e->getMessage()}");
        }

        return redirect()->route('requisitions.show', $requisition)
            ->with('success', 'Entrega registrada. Professor notificado por e-mail.');
    }

    public function deliver(BookRequisition $requisition): RedirectResponse
    {
        if (! $requisition->isDispatched()) {
            return back()->with('error', 'O almoxarife ainda não confirmou a entrega desta requisição.');
        }

        if ($requisition->requested_by !== Auth::id()) {
            return back()->with('error', 'Somente o professor que fez a requisição pode confirmar o recebimento.');
        }

        $requisition->update([
            'status'       => 'delivered',
            'delivered_at' => now(),
        ]);

        return redirect()->route('requisitions.show', $requisition)
            ->with('success', 'Recebimento confirmado! Obrigado.');
    }

    public function destroy(BookRequisition $requisition): RedirectResponse
    {
        abort_unless(Auth::user()->hasRole('almoxarife'), 403);

        if (! in_array($requisition->status, ['cancelled', 'delivered'])) {
            return back()->with('error', 'Apenas requisições canceladas ou entregues podem ser excluídas.');
        }

        $requisition->delete();

        return redirect()->route('requisitions.index', request()->query())
            ->with('success', "Requisição #{$requisition->id} excluída do histórico.");
    }

    public function cancel(BookRequisition $requisition): RedirectResponse
    {
        if ($requisition->isDelivered() || $requisition->isDispatched()) {
            return back()->with('error', 'Não é possível cancelar uma requisição em processo de entrega ou já entregue.');
        }

        $user    = Auth::user();
        $isOwner = $requisition->requested_by === $user->id;
        $isAlmox = $user->hasRole('almoxarife');

        if (! $isOwner && ! $isAlmox) {
            return back()->with('error', 'Você não tem permissão para cancelar esta requisição.');
        }

        DB::transaction(function () use ($requisition) {
            if ($requisition->isApproved()) {
                $withdrawal = StockWithdrawal::where('requisition_id', $requisition->id)->first();
                if ($withdrawal) {
                    $withdrawal->delete(); // observer restaura o estoque
                } else {
                    $requisition->book->increment('current_stock', $requisition->quantity);
                }
            }

            $requisition->update(['status' => 'cancelled']);
        });

        return redirect()->route('requisitions.index')
            ->with('success', 'Requisição cancelada.');
    }
}