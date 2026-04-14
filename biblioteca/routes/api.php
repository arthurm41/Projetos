<?php

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\StockEntryController;
use App\Http\Controllers\Api\StockWithdrawalController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Livros
    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/low-stock', [BookController::class, 'lowStock']);
    Route::get('/books/{book}', [BookController::class, 'show']);
    Route::post('/books', [BookController::class, 'store']);
    Route::put('/books/{book}', [BookController::class, 'update']);

    // Entradas de Estoque
    Route::get('/stock-entries', [StockEntryController::class, 'index']);
    Route::post('/stock-entries', [StockEntryController::class, 'store']);
    Route::get('/stock-entries/{stockEntry}', [StockEntryController::class, 'show']);

    // Retiradas de Estoque
    Route::get('/stock-withdrawals', [StockWithdrawalController::class, 'index']);
    Route::post('/stock-withdrawals', [StockWithdrawalController::class, 'store']);
    Route::get('/stock-withdrawals/{stockWithdrawal}', [StockWithdrawalController::class, 'show']);
});