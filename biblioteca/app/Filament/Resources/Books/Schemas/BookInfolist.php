<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BookInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->label('Título')
                    ->placeholder('-'),
                TextEntry::make('author')
                    ->label('Autor')
                    ->placeholder('-'),
                TextEntry::make('subject.name')
                    ->label('Assunto')
                    ->placeholder('-'),
                TextEntry::make('current_stock')
                    ->label('Estoque')
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
