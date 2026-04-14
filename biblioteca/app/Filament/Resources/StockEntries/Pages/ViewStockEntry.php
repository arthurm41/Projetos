<?php

namespace App\Filament\Resources\StockEntries\Pages;

use App\Filament\Resources\StockEntries\StockEntryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStockEntry extends ViewRecord
{
    protected static string $resource = StockEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
