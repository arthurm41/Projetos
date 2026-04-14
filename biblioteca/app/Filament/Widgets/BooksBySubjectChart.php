<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use App\Models\Subject;
use Filament\Widgets\ChartWidget;

class BooksBySubjectChart extends ChartWidget
{
    public function getHeading(): string
    {
        return 'Distribuição de Livros por Assunto';
    }

    public function getMaxHeight(): string
    {
        return '300px';
    }

    protected function getData(): array
    {
        $data = Subject::withCount('books')
            ->whereHas('books')
            ->orderBy('books_count', 'desc')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Quantidade de Livros',
                    'data' => $data->pluck('books_count')->toArray(),
                    'backgroundColor' => [
                        '#3b82f6', // blue
                        '#ef4444', // red
                        '#10b981', // emerald
                        '#f59e0b', // amber
                        '#8b5cf6', // violet
                        '#06b6d4', // cyan
                        '#84cc16', // lime
                        '#f97316', // orange
                    ],
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}