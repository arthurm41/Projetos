<?php

namespace App\Filament\Resources\StockWithdrawals\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StockWithdrawalInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('book.title')
                    ->label('Livro')
                    ->placeholder('-'),
                TextEntry::make('user.name')
                    ->label('Usuário')
                    ->placeholder('-'),
                TextEntry::make('quantity')
                    ->label('Quantidade')
                    ->placeholder('-'),
                TextEntry::make('stock_before')
                    ->label('Estoque Anterior')
                    ->placeholder('-'),
                TextEntry::make('stock_after')
                    ->label('Estoque Atual')
                    ->placeholder('-'),
                TextEntry::make('class_group')
                    ->label('Turma')
                    ->placeholder('-'),
                TextEntry::make('reason')
                    ->label('Motivo')
                    ->placeholder('-'),
                TextEntry::make('withdrawn_at')
                    ->label('Retirado em')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
