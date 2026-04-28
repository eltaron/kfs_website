<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Concerns\Widgets\SuperAdminOnly;

class FinancialReportStats extends StatsOverviewWidget
{
    use SuperAdminOnly; 
    public ?array $filters = [];
    protected function getStats(): array
    {
        $query = Transaction::query();

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('completed_at', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('completed_at', '<=', $this->filters['date_to']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return [
            Stat::make('إجمالي الإيرادات', number_format(
                (clone $query)->where('status', 'completed')->sum('amount')
            ) . ' جنيه'),

            Stat::make('عدد المعاملات', (clone $query)->count()),

            Stat::make('المعاملات الفاشلة', (clone $query)->where('status', 'failed')->count())
                ->color('danger'),
        ];
    }
}