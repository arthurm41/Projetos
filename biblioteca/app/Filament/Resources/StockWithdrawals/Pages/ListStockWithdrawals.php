<?php

namespace App\Filament\Resources\StockWithdrawals\Pages;

use App\Filament\Resources\StockWithdrawals\StockWithdrawalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStockWithdrawals extends ListRecords
{
    protected static string $resource = StockWithdrawalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
