<?php

namespace App\Filament\Gis\Resources\RemovalOrderResource\Pages;

use App\Filament\Gis\Resources\RemovalOrderResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewRemovalOrder extends ViewRecord
{
    protected static string $resource = RemovalOrderResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('بيانات الموقع 📍')
                    ->schema([
                        Infolists\Components\TextEntry::make('center')->label('المركز'),
                        Infolists\Components\TextEntry::make('local_unit')->label('الوحدة المحلية'),
                        Infolists\Components\TextEntry::make('street')->label('الشارع'),
                        Infolists\Components\TextEntry::make('district')->label('الحي'),
                    ])->columns(4),

                Infolists\Components\Section::make('بيانات المالك 👤')
                    ->schema([
                        Infolists\Components\TextEntry::make('owner_name')->label('الاسم'),
                        Infolists\Components\TextEntry::make('owner_national_id')->label('الرقم القومي'),
                        Infolists\Components\TextEntry::make('owner_governorate')->label('المحافظة'),
                    ])->columns(3),

                Infolists\Components\Section::make('بيانات المخالفة ⚠️')
                    ->schema([
                        Infolists\Components\TextEntry::make('violation_plot')->label('رقم القطعة'),
                        Infolists\Components\TextEntry::make('violation_dimensions')->label('المساحة والأبعاد'),
                        Infolists\Components\TextEntry::make('violation_cost')->label('التكلفة المقدرة')->money('EGP'),
                        Infolists\Components\TextEntry::make('violation_works')->label('الأعمال المخالفة')->columnSpanFull(),
                    ])->columns(3),

                Infolists\Components\Section::make('تعيين العضو الميداني / الحالة')
                    ->schema([
                        Infolists\Components\TextEntry::make('status')
                            ->label('الحالة الحالية')
                            ->badge()
                            ->color('success'),
                        Infolists\Components\ImageEntry::make('photo_file')->label('صورة المخالفة المرفقة'),
                    ])->columns(2)
            ]);
    }
}
