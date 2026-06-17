<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreStockWithdrawalRequest;
use App\Http\Resources\StockWithdrawalResource;
use App\Models\Book;
use App\Models\StockWithdrawal;
use Illuminate\Http\JsonResponse;

class StockWithdrawalController extends ApiController
{
    public function index(): JsonResponse
    {
        $withdrawals = StockWithdrawal::with(['book.subjects', 'user'])
            ->latest()
            ->paginate(15);

        return $this->paginate($withdrawals);
    }

    public function store(StoreStockWithdrawalRequest $request): JsonResponse
    {
        $data = $request->validated();

        $book = Book::findOrFail($data['book_id']);

        if ($data['quantity'] > $book->current_stock) {
            return $this->error(
                "Estoque insuficiente. Saldo atual: {$book->current_stock} unidade(s). Solicitado: {$data['quantity']}.",
                422
            );
        }

        $data['user_id']      = $request->user()->id;
        $data['withdrawn_at'] = $data['withdrawn_at'] ?? now();
        $data['stock_before'] = $book->current_stock;
        $data['stock_after']  = $book->current_stock - $data['quantity'];

        $withdrawal = StockWithdrawal::create($data);
        $withdrawal->load(['book.subjects', 'user']);

        return $this->success(
            new StockWithdrawalResource($withdrawal),
            'Saída de estoque registrada com sucesso.',
            201
        );
    }

    public function show(StockWithdrawal $stockWithdrawal): JsonResponse
    {
        $stockWithdrawal->load(['book.subjects', 'user']);

        return $this->success(new StockWithdrawalResource($stockWithdrawal));
    }
}
