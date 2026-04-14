<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockBooksWidget extends BaseWidget
{
    protected static ?string $heading = 'Livros com Estoque Baixo';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Book::whereRaw('current_stock < minimum_stock')
                    ->with('subject')
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('author')
                    ->label('Autor')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Assunto')
                    ->sortable(),

                Tables\Columns\TextColumn::make('current_stock')
                    ->label('Estoque Atual')
                    ->sortable()
                    ->color('danger'),

                Tables\Columns\TextColumn::make('minimum_stock')
                    ->label('Estoque Mínimo')
                    ->sortable(),

                Tables\Columns\TextColumn::make('current_stock')
                    ->label('Diferença')
                    ->getStateUsing(function (Book $record) {
                        return $record->minimum_stock - $record->current_stock;
                    })
                    ->color('danger'),
            ])
            ->defaultSort('current_stock', 'asc')
            ->emptyStateHeading('Nenhum livro com estoque baixo!')
            ->emptyStateDescription('Todos os livros estão com estoque adequado.');
    }
}