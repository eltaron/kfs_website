<?php
namespace App\Filament\Gis\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public static function canAccess(): bool
    {
        // الفني لا يمكنه رؤية هذه اللوحة
        return auth()->user()->hasAnyRole([
            'super_admin', 
            'مدير المركز', 
            'مدير الادارة الهندسية'
            // لا تضف 'فني التنظيم' هنا
        ]);
    }
}