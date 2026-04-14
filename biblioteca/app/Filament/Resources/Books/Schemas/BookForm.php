<?php

namespace App\Filament\Resources\Books\Schemas;

use App\Models\Subject;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    Select::make('subject_id')
                        ->label('Matéria')
                        ->options(Subject::query()->pluck('name', 'id')->toArray())
                        ->searchable()
                        ->required(),

                    TextInput::make('title')
                        ->label('Título')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('isbn')
                        ->label('ISBN')
                        ->maxLength(50),

                    TextInput::make('author')
                        ->label('Autor')
                        ->maxLength(255),

                    TextInput::make('publisher')
                        ->label('Editora')
                        ->maxLength(255),

                    TextInput::make('edition')
                        ->label('Edição')
                        ->maxLength(100),

                    TextInput::make('current_stock')
                        ->label('Estoque Atual')
                        ->numeric()
                        ->required(),

                    TextInput::make('minimum_stock')
                        ->label('Estoque Mínimo')
                        ->numeric()
                        ->required(),
                ]),
            ]);
    }
}
