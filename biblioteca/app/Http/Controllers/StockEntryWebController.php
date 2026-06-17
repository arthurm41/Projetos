<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockEntryRequest;
use App\Http\Requests\UpdateStockEntryRequest;
use App\Models\Book;
use App\Models\StockEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockEntryWebController extends Controller
{
    public function index(): View
    {
        $query = StockEntry::with(['book.subjects', 'user'])->latest('received_at');

        if ($search = request('search')) {
            $query->whereHas('book', fn($b) => $b->where('title', 'like', "%{$search}%"));
        }

        if ($from = request('date_from')) {
            $query->whereDate('received_at', '>=', $from);
        }

        if ($to = request('date_to')) {
            $query->whereDate('received_at', '<=', $to);
        }

        $entries = $query->paginate(15)->withQueryString();

        return view('stock-entries.index', compact('entries'));
    }

    public function create(): View
    {
        abort_unless(Auth::user()->hasRole('almoxarife'), 403);

        $books = Book::with('subjects')->orderBy('title')->get();

        return view('stock-entries.create', compact('books'));
    }

    public function store(StoreStockEntryRequest $request): RedirectResponse
    {
        abort_unless(Auth::user()->hasRole('almoxarife'), 403);
        $data = $request->validated();
        $book = Book::findOrFail($data['book_id']);

        $data['user_id']      = $request->user()->id;
        $data['received_at']  = $data['received_at'] ?? now();
        $data['stock_before'] = $book->current_stock;
        $data['stock_after']  = $book->current_stock + $data['quantity'];

        $quantity = $data['quantity'];

        DB::transaction(function () use ($data) {
            StockEntry::create($data);
        });

        return redirect()->route('stock-entries.index')
            ->with('success', "Entrada de {$quantity} unidade(s) registrada com sucesso.");
    }

    public function edit(StockEntry $stockEntry): View
    {
        abort_unless(Auth::user()->hasRole('almoxarife'), 403);

        $stockEntry->load('book.subjects');

        return view('stock-entries.edit', compact('stockEntry'));
    }

    public function update(UpdateStockEntryRequest $request, StockEntry $stockEntry): RedirectResponse
    {
        abort_unless(Auth::user()->hasRole('almoxarife'), 403);

        $data = $request->validated();
        $data['received_at'] = $data['received_at'] ?? $stockEntry->received_at;
        $data['stock_after'] = $stockEntry->stock_before + $data['quantity'];

        DB::transaction(function () use ($stockEntry, $data) {
            $stockEntry->update($data);
        });

        return redirect()->route('stock-entries.index')
            ->with('success', 'Entrada de estoque atualizada com sucesso.');
    }

    public function destroy(StockEntry $stockEntry): RedirectResponse
    {
        abort_unless(Auth::user()->hasRole('almoxarife'), 403);

        $stockEntry->delete();

        return back()->with('success', 'Registro de entrada excluído do histórico.');
    }
}
