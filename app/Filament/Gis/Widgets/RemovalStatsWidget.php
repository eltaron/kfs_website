<?php

namespace App\Filament\Gis\Widgets;

use App\Models\RemovalOrder;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RemovalStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('قرارات الإزالة الصادرة', RemovalOrder::count())
                ->description('إجمالي قرارات الحوكمة')
                ->descriptionIcon('heroicon-m-scale')
                ->color('gray'),

            Stat::make('مخالفات بدون ترخيص', RemovalOrder::where('violation_type', 'new_violation')->count())
                ->description('بناء عشوائي كلي')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger'),

            Stat::make('عمليات تم تنفيذها', RemovalOrder::where('status', 'تم التنفيذ')->count())
                ->description('نجاح حملات الإزالة الميدانية')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}
