<?php

namespace App\Filament\Gis\Pages;

use App\Models\RemovalOrder;
use Filament\Pages\Page;

class RemovalReports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'حوكمة قرارات الإزالة';
    protected static ?string $navigationLabel = 'سجل التقارير اليومية';
    protected static ?string $title = 'أرشيف تقارير حوكمة الإزالة';
    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.gis.pages.removal-reports';

    public $reports = [];
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
        // جلب ملخص للعمليات الأخيرة للطباعة
        $this->reports = RemovalOrder::latest()->take(10)->get();
    }
}
