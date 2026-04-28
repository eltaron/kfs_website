<?php

namespace App\Filament\Gis\Widgets;

use App\Models\RemovalOrder;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ViolationChart extends ChartWidget
{
    protected static ?string $heading = 'توزيع مخالفات البناء حسب المراكز الإدارية';

    // تحديد لون الرسم البياني (ذهبي وكحلي)
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // جلب البيانات: تجميع عدد المخالفات لكل مركز
        $data = RemovalOrder::select('center', DB::raw('count(*) as total'))
            ->groupBy('center')
            ->orderBy('total', 'desc')
            ->pluck('total', 'center')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'عدد القرارات',
                    'data' => array_values($data),
                    'backgroundColor' => [
                        '#e1b12c', // ذهبي
                        '#1e272e', // كحلي
                        '#2f3542',
                        '#718093',
                        '#27ae60'
                    ],
                    'borderColor' => 'transparent',
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // استخدام الـ Bar Chart لسهولة المقارنة بين المراكز
    }

    /**
     * تخصيص خيارات الرسم البياني (دعم الـ Dark Mode)
     */
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
