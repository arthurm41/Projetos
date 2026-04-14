<?php

namespace App\Observers;

use App\Models\StockWithdrawal;

class StockWithdrawalObserver
{
    /**
     * Handle the StockWithdrawal "creating" event.
     */
    public function creating(StockWithdrawal $stockWithdrawal): void
    {
        $book = $stockWithdrawal->book;
        if ($book && $book->current_stock < $stockWithdrawal->quantity) {
            throw new \Exception('Estoque insuficiente para este livro. Disponível: ' . $book->current_stock);
        }
    }

    /**
     * Handle the StockWithdrawal "created" event.
     */
    public function created(StockWithdrawal $stockWithdrawal): void
    {
        $book = $stockWithdrawal->book;
        $book->current_stock -= $stockWithdrawal->quantity;
        $book->save();
    }

    /**
     * Handle the StockWithdrawal "updated" event.
     */
    public function updated(StockWithdrawal $stockWithdrawal): void
    {
        $book = $stockWithdrawal->book;

        // Reverter o estoque anterior
        $book->current_stock += $stockWithdrawal->getOriginal('quantity');

        // Aplicar o novo estoque
        $book->current_stock -= $stockWithdrawal->quantity;

        $book->save();
    }

    /**
     * Handle the StockWithdrawal "deleted" event.
     */
    public function deleted(StockWithdrawal $stockWithdrawal): void
    {
        $book = $stockWithdrawal->book;
        $book->current_stock += $stockWithdrawal->quantity;
        $book->save();
    }

    /**
     * Handle the StockWithdrawal "restored" event.
     */
    public function restored(StockWithdrawal $stockWithdrawal): void
    {
        $book = $stockWithdrawal->book;
        $book->current_stock -= $stockWithdrawal->quantity;
        $book->save();
    }

    /**
     * Handle the StockWithdrawal "force deleted" event.
     */
    public function forceDeleted(StockWithdrawal $stockWithdrawal): void
    {
        $book = $stockWithdrawal->book;
        $book->current_stock += $stockWithdrawal->quantity;
        $book->save();
    }
}