<?php

namespace App\Filament\Gis\Pages;

use App\Models\RemovalOrder;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class RemovalAnalytics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationGroup = 'حوكمة قرارات الإزالة';
    protected static ?string $navigationLabel = 'إحصائيات وتحليلات';
    protected static ?string $title = 'لوحة تحليل المخالفات والإزالات';
    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.gis.pages.removal-analytics';

    public array $chartsData = [];
    public static function canAccess(): bool
    {
        // يمكنك هنا وضع أسماء الأدوار التي يحق لها دخول هذه الصفحة تحديداً
        return auth()->user()->hasAnyRole([
            'super_admin', 
            "مدير المركز",
            "رؤوساء الاقسام",
            "مهندس التنظيم",
            "مدير الادارة الهندسية",
            "مدير التنظيم",
        ]);
    }
    public function mount()
    {
        // إحصائيات نوع المخالفة
        $this->chartsData['types'] = [
            'new' => RemovalOrder::where('violation_type', 'new_violation')->count(),
            'licensed' => RemovalOrder::where('violation_type', 'licensed_violation')->count(),
        ];

        // إحصائيات التكلفة التقديرية حسب المراكز (أعلى 5 مراكز)
        $this->chartsData['top_costs'] = RemovalOrder::select('center', DB::raw('SUM(violation_cost) as total_cost'))
            ->groupBy('center')
            ->orderBy('total_cost', 'desc')
            ->take(5)
            ->get();

        // نسبة الإنجاز
        $total = RemovalOrder::count();
        $completed = RemovalOrder::where('status', 'تم التنفيذ')->count();
        $this->chartsData['completion_rate'] = $total > 0 ? round(($completed / $total) * 100, 1) : 0;
    }
}
