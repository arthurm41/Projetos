<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockEntryRequest;
use App\Models\Book;
use App\Models\StockEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StockEntryWebController extends Controller
{
    public function index(): View
    {
        $entries = StockEntry::with(['book.subject', 'user'])
            ->latest()
            ->paginate(15);

        return view('stock-entries.index', compact('entries'));
    }

    public function create(): View
    {
        $books = Book::with('subject')->orderBy('title')->get();

        return view('stock-entries.create', compact('books'));
    }

    public function store(StoreStockEntryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $book = Book::findOrFail($data['book_id']);

        $data['user_id']      = $request->user()->id;
        $data['received_at']  = $data['received_at'] ?? now();
        $data['stock_before'] = $book->current_stock;
        $data['stock_after']  = $book->current_stock + $data['quantity'];

        StockEntry::create($data);

        return redirect()->route('stock-entries.index')
            ->with('success', "Entrada de {$data['quantity']} unidade(s) registrada com sucesso.");
    }
}
