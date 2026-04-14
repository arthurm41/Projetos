<?php

namespace App\Observers;

use App\Models\StockEntry;

class StockEntryObserver
{
    /**
     * Handle the StockEntry "created" event.
     */
    public function created(StockEntry $stockEntry): void
    {
        $book = $stockEntry->book;
        $book->current_stock += $stockEntry->quantity;
        $book->save();
    }

    /**
     * Handle the StockEntry "updated" event.
     */
    public function updated(StockEntry $stockEntry): void
    {
        $book = $stockEntry->book;

        // Reverter o estoque anterior
        $book->current_stock -= $stockEntry->getOriginal('quantity');

        // Aplicar o novo estoque
        $book->current_stock += $stockEntry->quantity;

        $book->save();
    }

    /**
     * Handle the StockEntry "deleted" event.
     */
    public function deleted(StockEntry $stockEntry): void
    {
        $book = $stockEntry->book;
        $book->current_stock -= $stockEntry->quantity;
        $book->save();
    }

    /**
     * Handle the StockEntry "restored" event.
     */
    public function restored(StockEntry $stockEntry): void
    {
        $book = $stockEntry->book;
        $book->current_stock += $stockEntry->quantity;
        $book->save();
    }

    /**
     * Handle the StockEntry "force deleted" event.
     */
    public function forceDeleted(StockEntry $stockEntry): void
    {
        $book = $stockEntry->book;
        $book->current_stock -= $stockEntry->quantity;
        $book->save();
    }
}