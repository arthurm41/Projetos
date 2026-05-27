<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockWithdrawalRequest;
use App\Models\Book;
use App\Models\StockWithdrawal;
use Illuminate\Http\RedirectResponse;
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
        $books = Book::with('subject')->orderBy('title')->get();

        return view('stock-withdrawals.create', compact('books'));
    }

    public function store(StoreStockWithdrawalRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $book = Book::findOrFail($data['book_id']);

        if ($data['quantity'] > $book->current_stock) {
            return redirect()->back()->withInput()
                ->with('error', "Estoque insuficiente. Saldo atual: {$book->current_stock} unidade(s).");
        }

        $data['user_id']      = $request->user()->id;
        $data['withdrawn_at'] = $data['withdrawn_at'] ?? now();
        $data['stock_before'] = $book->current_stock;
        $data['stock_after']  = $book->current_stock - $data['quantity'];

        StockWithdrawal::create($data);

        return redirect()->route('stock-withdrawals.index')
            ->with('success', "Saída de {$data['quantity']} unidade(s) registrada com sucesso.");
    }
}
