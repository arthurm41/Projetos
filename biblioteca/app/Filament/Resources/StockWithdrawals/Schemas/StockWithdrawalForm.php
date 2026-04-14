<?php

namespace App\Filament\Resources\StockWithdrawals\Schemas;

use App\Models\Book;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockWithdrawalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    Select::make('book_id')
                        ->label('Livro')
                        ->options(Book::query()->pluck('title', 'id')->toArray())
                        ->searchable()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $book = Book::find($state);
                                $set('stock_before', $book ? $book->current_stock : 0);
                            }
                        }),

                    Select::make('user_id')
                        ->label('Usuário')
                        ->options(User::query()->pluck('name', 'id')->toArray())
                        ->searchable()
                        ->required(),

                    TextInput::make('quantity')
                        ->label('Quantidade')
                        ->numeric()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $get, callable $set) {
                            $stockBefore = $get('stock_before') ?? 0;
                            $quantity = $state ?? 0;
                            $set('stock_after', $stockBefore - $quantity);
                        }),

                    TextInput::make('stock_before')
                        ->label('Estoque Antes')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(),

                    TextInput::make('stock_after')
                        ->label('Estoque Depois')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(),

                    TextInput::make('class_group')
                        ->label('Turma')
                        ->maxLength(255),

                    Textarea::make('reason')
                        ->label('Motivo')
                        ->rows(3),

                    DateTimePicker::make('withdrawn_at')
                        ->label('Data de Saída')
                        ->required(),
                ]),
            ]);
    }
}
