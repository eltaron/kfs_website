<?php

namespace App\Filament\Estidama\Widgets;

use App\Models\Enrollment;
use App\Models\TrainingApplication;
use App\Models\TrainingProgram;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EstidamaStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1; // Show first
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        $pendingApplications = TrainingApplication::where('status', 'pending')->count();
        $openPrograms = TrainingProgram::where('status', 'open')->count();
        $ongoingPrograms = TrainingProgram::where('status', 'ongoing')->count();
        $totalEnrollments = Enrollment::count();

        return [
            Stat::make('طلبات التسجيل الجديدة', $pendingApplications)
                ->description('طلبات تنتظر المراجعة والموافقة')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color($pendingApplications > 0 ? 'danger' : 'success'),

            Stat::make('برامج مفتوحة للتسجيل', $openPrograms)
                ->description('متاحة للمواطنين للتقديم عليها الآن')
                ->descriptionIcon('heroicon-m-lock-open')
                ->color('success'),

            Stat::make('برامج جارية حاليًا', $ongoingPrograms)
                ->description('برامج يتم تنفيذها في الوقت الحالي')
                ->descriptionIcon('heroicon-m-arrow-path-rounded-square')
                ->color('info'),
        ];
    }
}
