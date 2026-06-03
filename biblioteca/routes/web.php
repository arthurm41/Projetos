<?php

use App\Http\Controllers\BookRequisitionController;
use App\Http\Controllers\BookWebController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockEntryWebController;
use App\Http\Controllers\StockWithdrawalWebController;
use App\Http\Controllers\SubjectWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('books', BookWebController::class)->except('show');
    Route::resource('subjects', SubjectWebController::class)->except('show');

    Route::get('/stock-entries', [StockEntryWebController::class, 'index'])->name('stock-entries.index');
    Route::get('/stock-entries/create', [StockEntryWebController::class, 'create'])->name('stock-entries.create');
    Route::post('/stock-entries', [StockEntryWebController::class, 'store'])->name('stock-entries.store');

    Route::get('/stock-withdrawals', [StockWithdrawalWebController::class, 'index'])->name('stock-withdrawals.index');

    Route::get('/low-stock', function () {
        $books = \App\Models\Book::with('subject')
            ->whereColumn('current_stock', '<', 'minimum_stock')
            ->orderBy('current_stock')
            ->paginate(20);

        return view('low-stock', compact('books'));
    })->name('low-stock');

    // Mailpit - Apenas Almoxarife
    Route::get('/mailpit', function () {

        if (! auth()->user()->hasRole('almoxarife')) {
            abort(403, 'Acesso negado.');
        }

        return redirect()->away('http://127.0.0.1:8025');

    })->name('mailpit');

    // Requisições de livros
    Route::get('/requisitions', [BookRequisitionController::class, 'index'])->name('requisitions.index');
    Route::get('/requisitions/create', [BookRequisitionController::class, 'create'])->name('requisitions.create');
    Route::post('/requisitions', [BookRequisitionController::class, 'store'])->name('requisitions.store');
    Route::get('/requisitions/{requisition}', [BookRequisitionController::class, 'show'])->name('requisitions.show');
    Route::post('/requisitions/{requisition}/approve', [BookRequisitionController::class, 'approve'])->name('requisitions.approve');
    Route::post('/requisitions/{requisition}/dispatch', [BookRequisitionController::class, 'dispatch'])->name('requisitions.dispatch');
    Route::post('/requisitions/{requisition}/deliver', [BookRequisitionController::class, 'deliver'])->name('requisitions.deliver');
    Route::post('/requisitions/{requisition}/cancel', [BookRequisitionController::class, 'cancel'])->name('requisitions.cancel');

});

require __DIR__.'/auth.php';