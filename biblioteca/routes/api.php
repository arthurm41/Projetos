<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\StockEntryController;
use App\Http\Controllers\Api\StockWithdrawalController;
use App\Http\Controllers\Api\SubjectController;
use Illuminate\Support\Facades\Route;

// Autenticação pública
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Rotas protegidas por token Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Perfil
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Matérias
    Route::apiResource('subjects', SubjectController::class)->names([
        'index'   => 'api.subjects.index',
        'store'   => 'api.subjects.store',
        'show'    => 'api.subjects.show',
        'update'  => 'api.subjects.update',
        'destroy' => 'api.subjects.destroy',
    ]);

    // Livros — low-stock deve vir antes do resource para não conflitar com {book}
    Route::get('books/low-stock', [BookController::class, 'lowStock'])->name('api.books.low-stock');
    Route::apiResource('books', BookController::class)->names([
        'index'   => 'api.books.index',
        'store'   => 'api.books.store',
        'show'    => 'api.books.show',
        'update'  => 'api.books.update',
        'destroy' => 'api.books.destroy',
    ]);

    // Entradas de estoque (abastecimento)
    Route::get('stock-entries',              [StockEntryController::class, 'index']);
    Route::post('stock-entries',             [StockEntryController::class, 'store']);
    Route::get('stock-entries/{stockEntry}', [StockEntryController::class, 'show']);

    // Saídas de estoque (baixa)
    Route::get('stock-withdrawals',                   [StockWithdrawalController::class, 'index']);
    Route::post('stock-withdrawals',                  [StockWithdrawalController::class, 'store']);
    Route::get('stock-withdrawals/{stockWithdrawal}', [StockWithdrawalController::class, 'show']);
});
