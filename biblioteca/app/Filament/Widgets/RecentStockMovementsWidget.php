<?php

namespace App\Filament\Widgets;

use App\Models\StockEntry;
use App\Models\StockMovement;
use App\Models\StockWithdrawal;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentStockMovementsWidget extends BaseWidget
{
    protected static ?string $heading = 'Movimentações Recentes';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $entries = StockEntry::query()
            ->selectRaw("
                'entrada' as tipo,
                stock_entries.id as movement_id,
                stock_entries.book_id,
                books.title as book_title,
                stock_entries.quantity,
                stock_entries.received_at as data,
                stock_entries.notes as observacao,
                stock_entries.created_at
            ")
            ->join('books', 'stock_entries.book_id', 'books.id');

        $withdrawals = StockWithdrawal::query()
            ->selectRaw("
                'retirada' as tipo,
                stock_withdrawals.id as movement_id,
                stock_withdrawals.book_id,
                books.title as book_title,
                stock_withdrawals.quantity,
                stock_withdrawals.withdrawn_at as data,
                ('Turma: ' || stock_withdrawals.class_group || ' - Motivo: ' || stock_withdrawals.reason) as observacao,
                stock_withdrawals.created_at
            ")
            ->join('books', 'stock_withdrawals.book_id', 'books.id');

        $query = StockMovement::fromSub($entries->union($withdrawals), 'stock_movements')
            ->select('*');

        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'entrada' => 'success',
                        'retirada' => 'warning',
                    }),

                Tables\Columns\TextColumn::make('book_title')
                    ->label('Livro')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantidade')
                    ->sortable(),

                Tables\Columns\TextColumn::make('data')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('observacao')
                    ->label('Observação')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(false);
    }
}