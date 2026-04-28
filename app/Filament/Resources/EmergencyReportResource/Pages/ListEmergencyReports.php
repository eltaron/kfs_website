<?php

namespace App\Filament\Resources\EmergencyReportResource\Pages;

use App\Filament\Resources\EmergencyReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmergencyReports extends ListRecords
{
    protected static string $resource = EmergencyReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
