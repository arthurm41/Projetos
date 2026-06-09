<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockEntryRequest;
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

        $quantity = $data['quantity'];

        DB::transaction(function () use ($data) {
            StockEntry::create($data);
        });

        return redirect()->route('stock-entries.index')
            ->with('success', "Entrada de {$quantity} unidade(s) registrada com sucesso.");
    }

    public function destroy(StockEntry $stockEntry): RedirectResponse
    {
        abort_unless(Auth::user()->hasRole('almoxarife'), 403);

        $stockEntry->delete();

        return back()->with('success', 'Registro de entrada excluído do histórico.');
    }
}
