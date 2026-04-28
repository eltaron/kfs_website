<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use App\Concerns\Widgets\SuperAdminOnly;

class PostsByCategoryChart extends ChartWidget
{
    use SuperAdminOnly; // ✅ سطر واحد يخفي الويدجت لغير السوبر أدمن
    protected static ?string $heading = 'توزيع المقالات على الفئات';
    protected static ?int $sort = 2; // Order on the dashboard

    // protected int | string | array $columnSpan = 'full';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'اليوم',
            'last_week' => 'آخر أسبوع',
            'last_month' => 'آخر شهر',
            'all_time' => 'كل الأوقات',
        ];
    }

    protected function getData(): array
    {
        // Get the start date based on the selected filter
        $startDate = match ($this->filter) {
            'today' => Carbon::today(),
            'last_week' => Carbon::now()->subWeek(),
            'last_month' => Carbon::now()->subMonth(),
            'all_time' => null, // No date constraint
            default => null,
        };

        // Query categories with their posts count, applying the date filter if needed
        $data = Category::query()
            ->withCount(['posts' => function ($query) use ($startDate) {
                if ($startDate) {
                    $query->where('published_at', '>=', $startDate);
                }
            }])
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'مقالات',
                    'data' => $data->pluck('posts_count')->toArray(),
                    // Using custom colors for a better look
                    'backgroundColor' => [
                        '#DAA520', // Gold (Primary)
                        '#343a40', // Dark Grey
                        '#6c757d', // Muted Grey
                        '#e9ecef', // Light Grey
                        '#17a2b8', // Blue (Secondary)
                    ],
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut'; // 'pie' or 'bar' are also good options
    }
}
