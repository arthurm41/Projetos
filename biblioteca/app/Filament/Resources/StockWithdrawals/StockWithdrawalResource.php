<?php

namespace App\Filament\Resources\StockWithdrawals;

use App\Filament\Resources\StockWithdrawals\Pages\CreateStockWithdrawal;
use App\Filament\Resources\StockWithdrawals\Pages\EditStockWithdrawal;
use App\Filament\Resources\StockWithdrawals\Pages\ListStockWithdrawals;
use App\Filament\Resources\StockWithdrawals\Pages\ViewStockWithdrawal;
use App\Filament\Resources\StockWithdrawals\Schemas\StockWithdrawalForm;
use App\Filament\Resources\StockWithdrawals\Schemas\StockWithdrawalInfolist;
use App\Filament\Resources\StockWithdrawals\Tables\StockWithdrawalsTable;
use App\Models\StockWithdrawal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StockWithdrawalResource extends Resource
{
    protected static ?string $model = StockWithdrawal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Saídas de Estoque';
    protected static ?string $pluralModelLabel = 'Saídas de Estoque';
    protected static ?string $modelLabel = 'Saída de Estoque';
    protected static ?string $recordTitleAttribute = 'quantity';

    public static function form(Schema $schema): Schema
    {
        return StockWithdrawalForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StockWithdrawalInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockWithdrawalsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStockWithdrawals::route('/'),
            'create' => CreateStockWithdrawal::route('/create'),
            'view' => ViewStockWithdrawal::route('/{record}'),
            'edit' => EditStockWithdrawal::route('/{record}/edit'),
        ];
    }
}
