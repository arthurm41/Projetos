<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BooksBySubjectChart;
use App\Filament\Widgets\LowStockBooksWidget;
use App\Filament\Widgets\RecentStockMovementsWidget;
use App\Models\Book;
use App\Models\StockEntry;
use App\Models\StockWithdrawal;
use App\Models\Subject;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Dashboard extends BaseDashboard
{
    protected function getHeaderWidgets(): array
    {
        return [
            BooksBySubjectChart::class,
            LowStockBooksWidget::class,
            RecentStockMovementsWidget::class,
        ];
    }

    protected function getStats(): array
    {
        $totalBooks = Book::sum('current_stock');
        $totalBooksCount = Book::count();
        $totalSubjects = Subject::count();
        $lowStockBooks = Book::whereRaw('current_stock < minimum_stock')->count();
        $totalEntries = StockEntry::sum('quantity');
        $totalWithdrawals = StockWithdrawal::sum('quantity');

        return [
            Stat::make('Total de Livros em Estoque', $totalBooks)
                ->description('Quantidade total de exemplares')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('success'),

            Stat::make('Livros Cadastrados', $totalBooksCount)
                ->description('Títulos únicos na biblioteca')
                ->descriptionIcon('heroicon-m-queue-list')
                ->color('primary'),

            Stat::make('Assuntos', $totalSubjects)
                ->description('Matérias/disciplinas')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info'),

            Stat::make('Livros com Estoque Baixo', $lowStockBooks)
                ->description('Abaixo do estoque mínimo')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($lowStockBooks > 0 ? 'danger' : 'success'),

            Stat::make('Entradas Totais', $totalEntries)
                ->description('Exemplares adicionados')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Retiradas Totais', $totalWithdrawals)
                ->description('Exemplares retirados')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('warning'),
        ];
    }
}