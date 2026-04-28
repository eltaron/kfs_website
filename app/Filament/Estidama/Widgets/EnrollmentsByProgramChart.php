<?php

namespace App\Filament\Estidama\Widgets;

use App\Models\TrainingProgram;
use Filament\Widgets\ChartWidget;

class EnrollmentsByProgramChart extends ChartWidget
{
    protected static ?string $heading = 'توزيع المسجلين على البرامج';
    protected static ?int $sort = 2; // Show in the second row
    protected int | string | array $columnSpan = 'full';
    protected function getData(): array
    {
        // Get programs with the count of their enrollments
        $data = TrainingProgram::withCount('enrollments')

            // ===== THE FIX IS HERE =====
            // Use `having` to filter by an aggregated/calculated column.
            ->having('enrollments_count', '>', 0)
            // ============================

            ->orderBy('enrollments_count', 'desc')
            ->take(10) // Show top 10 most popular programs
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'عدد المسجلين',
                    'data' => $data->pluck('enrollments_count')->toArray(),
                    'backgroundColor' => [
                        '#1D4ED8',
                        '#2563EB',
                        '#3B82F6',
                        '#60A5FA',
                        '#93C5FD',
                        // Add more shades if needed
                    ],
                    'borderColor' => '#fff',
                ],
            ],
            'labels' => $data->pluck('title')->toArray(),
        ];
    }
    protected function getType(): string
    {
        return 'bar'; // Bar chart is great for comparing quantities
    }
}
