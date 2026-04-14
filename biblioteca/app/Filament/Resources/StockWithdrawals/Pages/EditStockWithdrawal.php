<?php

namespace App\Filament\Resources\StockWithdrawals\Pages;

use App\Filament\Resources\StockWithdrawals\StockWithdrawalResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditStockWithdrawal extends EditRecord
{
    protected static string $resource = StockWithdrawalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
