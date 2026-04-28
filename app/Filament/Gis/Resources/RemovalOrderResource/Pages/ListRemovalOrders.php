<?php

namespace App\Filament\Gis\Resources\RemovalOrderResource\Pages;

use App\Filament\Gis\Resources\RemovalOrderResource;
use App\Models\RemovalOrder;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListRemovalOrders extends ListRecords
{
    protected static string $resource = RemovalOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('إضافة قرار إزالة جديد'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('كافة القرارات'),
            'new' => Tab::make('الوارد الجديد')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'قيد الإعداد'))
                ->badge(RemovalOrder::where('status', 'قيد الإعداد')->count()),
            'pending' => Tab::make('قيد المراجعة')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'قيد المراجعة'))
                ->badge(RemovalOrder::where('status', 'قيد المراجعة')->count())
                ->badgeColor('info'),
            'completed' => Tab::make('تم التنفيذ')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'تم التنفيذ'))
                ->badge(RemovalOrder::where('status', 'تم التنفيذ')->count())
                ->badgeColor('success'),
        ];
    }
}
