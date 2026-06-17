<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreStockEntryRequest;
use App\Http\Resources\StockEntryResource;
use App\Models\Book;
use App\Models\StockEntry;
use Illuminate\Http\JsonResponse;

class StockEntryController extends ApiController
{
    public function index(): JsonResponse
    {
        $entries = StockEntry::with(['book.subjects', 'user'])
            ->latest()
            ->paginate(15);

        return $this->paginate($entries);
    }

    public function store(StoreStockEntryRequest $request): JsonResponse
    {
        $data = $request->validated();

        $book = Book::findOrFail($data['book_id']);

        $data['user_id']      = $request->user()->id;
        $data['received_at']  = $data['received_at'] ?? now();
        $data['stock_before'] = $book->current_stock;
        $data['stock_after']  = $book->current_stock + $data['quantity'];

        $entry = StockEntry::create($data);
        $entry->load(['book.subjects', 'user']);

        return $this->success(new StockEntryResource($entry), 'Entrada de estoque registrada com sucesso.', 201);
    }

    public function show(StockEntry $stockEntry): JsonResponse
    {
        $stockEntry->load(['book.subjects', 'user']);

        return $this->success(new StockEntryResource($stockEntry));
    }
}
