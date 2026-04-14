<?php

namespace App\Filament\Resources\StockWithdrawals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StockWithdrawalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('book.title')
                    ->label('Livro')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Usuário')
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Quantidade')
                    ->sortable(),
                TextColumn::make('stock_before')
                    ->label('Estoque Anterior')
                    ->sortable(),
                TextColumn::make('stock_after')
                    ->label('Estoque Atual')
                    ->sortable(),
                TextColumn::make('class_group')
                    ->label('Turma')
                    ->sortable(),
                TextColumn::make('withdrawn_at')
                    ->label('Retirado em')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
