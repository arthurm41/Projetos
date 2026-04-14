<?php

namespace App\Http\Controllers\Api;

use App\Models\StockEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockEntryController extends ApiController
{
    public function index(): JsonResponse
    {
        $entries = StockEntry::with(['book', 'user'])
            ->latest()
            ->get();
        
        return $this->success($entries);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'received_at' => 'required|date_format:Y-m-d H:i:s',
        ]);

        try {
            $book = \App\Models\Book::findOrFail($validated['book_id']);
            
            $entry = StockEntry::create([
                'book_id' => $validated['book_id'],
                'user_id' => auth()->id(),
                'quantity' => $validated['quantity'],
                'stock_before' => $book->current_stock,
                'stock_after' => $book->current_stock + $validated['quantity'],
                'notes' => $validated['notes'] ?? null,
                'received_at' => $validated['received_at'],
            ]);

            return $this->success(
                $entry->load(['book', 'user']),
                'Entrada de estoque registrada com sucesso',
                201
            );
        } catch (\Exception $e) {
            return $this->error('Erro ao registrar entrada: ' . $e->getMessage(), 422);
        }
    }

    public function show(StockEntry $entry): JsonResponse
    {
        return $this->success($entry->load(['book', 'user']));
    }
}