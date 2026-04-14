<?php

namespace App\Filament\Resources\StockWithdrawals\Pages;

use App\Filament\Resources\StockWithdrawals\StockWithdrawalResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStockWithdrawal extends ViewRecord
{
    protected static string $resource = StockWithdrawalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
