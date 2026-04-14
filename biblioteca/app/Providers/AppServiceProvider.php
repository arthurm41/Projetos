<?php

namespace App\Providers;

use App\Models\StockEntry;
use App\Models\StockWithdrawal;
use App\Observers\StockEntryObserver;
use App\Observers\StockWithdrawalObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        StockEntry::observe(StockEntryObserver::class);
        StockWithdrawal::observe(StockWithdrawalObserver::class);
    }
}
