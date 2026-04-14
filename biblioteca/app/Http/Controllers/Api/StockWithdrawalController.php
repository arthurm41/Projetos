<?php

namespace App\Http\Controllers\Api;

use App\Models\StockWithdrawal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockWithdrawalController extends ApiController
{
    public function index(): JsonResponse
    {
        $withdrawals = StockWithdrawal::with(['book', 'user'])
            ->latest()
            ->get();
        
        return $this->success($withdrawals);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
            'class_group' => 'required|string|max:100',
            'reason' => 'required|string',
            'withdrawn_at' => 'required|date_format:Y-m-d H:i:s',
        ]);

        try {
            $book = \App\Models\Book::findOrFail($validated['book_id']);
            
            // Validar estoque insuficiente
            if ($book->current_stock < $validated['quantity']) {
                return $this->error(
                    "Estoque insuficiente. Disponível: {$book->current_stock} | Solicitado: {$validated['quantity']}",
                    422
                );
            }

            $withdrawal = StockWithdrawal::create([
                'book_id' => $validated['book_id'],
                'user_id' => auth()->id(),
                'quantity' => $validated['quantity'],
                'stock_before' => $book->current_stock,
                'stock_after' => $book->current_stock - $validated['quantity'],
                'class_group' => $validated['class_group'],
                'reason' => $validated['reason'],
                'withdrawn_at' => $validated['withdrawn_at'],
            ]);

            return $this->success(
                $withdrawal->load(['book', 'user']),
                'Retirada de estoque registrada com sucesso',
                201
            );
        } catch (\Exception $e) {
            return $this->error('Erro ao registrar retirada: ' . $e->getMessage(), 422);
        }
    }

    public function show(StockWithdrawal $withdrawal): JsonResponse
    {
        return $this->success($withdrawal->load(['book', 'user']));
    }
}