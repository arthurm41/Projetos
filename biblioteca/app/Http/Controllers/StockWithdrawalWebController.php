<?php

namespace App\Http\Controllers;

use App\Mail\SaidaLivroMail;
use App\Models\Book;
use App\Models\StockWithdrawal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class StockWithdrawalWebController extends Controller
{
    public function index(): View
    {
        $withdrawals = StockWithdrawal::with(['book.subject', 'user'])
            ->latest()
            ->paginate(15);

        return view('stock-withdrawals.index', compact('withdrawals'));
    }

    public function create(): View
    {
        $books = Book::with('subject')
            ->orderBy('title')
            ->get();

        return view('stock-withdrawals.create', compact('books'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (! Auth::user()->hasRole('almoxarife')) {
            return redirect()->route('dashboard')
                ->with('error', 'Apenas o almoxarife pode registrar saída de livros.');
        }

        $validated = $request->validate([
            'book_id'     => 'required|exists:books,id',
            'quantity'    => 'required|integer|min:1',
            'class_group' => 'nullable|string|max:100',
            'reason'      => 'nullable|string|max:500',
        ], [
            'book_id.required'  => 'Selecione um livro.',
            'book_id.exists'    => 'Livro inválido.',
            'quantity.required' => 'Informe a quantidade.',
            'quantity.integer'  => 'A quantidade deve ser um número inteiro.',
            'quantity.min'      => 'A quantidade deve ser ao menos 1.',
        ]);

        $withdrawal = null;

        DB::transaction(function () use ($validated, &$withdrawal) {
            $book = Book::lockForUpdate()->findOrFail($validated['book_id']);

            if ($book->current_stock < $validated['quantity']) {
                throw new \Exception(
                    "Estoque insuficiente. Disponível: {$book->current_stock} unidade(s). Solicitado: {$validated['quantity']}."
                );
            }

            $stockBefore = $book->current_stock;
            $stockAfter = $book->current_stock - $validated['quantity'];

            $withdrawal = StockWithdrawal::create([
                'book_id'      => $book->id,
                'user_id'      => Auth::id(),
                'quantity'     => $validated['quantity'],
                'stock_before' => $stockBefore,
                'stock_after'  => $stockAfter,
                'class_group'  => $validated['class_group'] ?? null,
                'reason'       => $validated['reason'] ?? null,
                'withdrawn_at' => now(),
            ]);

            $book->update([
                'current_stock' => $stockAfter,
            ]);
        });

        $withdrawal->load(['book.subject', 'user']);

        Mail::to('almoxarife@senai.br')
            ->send(new SaidaLivroMail($withdrawal));

        return redirect()->route('stock-withdrawals.index')
            ->with('success', 'Saída de livro registrada com sucesso. E-mail enviado para o Mailpit.');
    }
}