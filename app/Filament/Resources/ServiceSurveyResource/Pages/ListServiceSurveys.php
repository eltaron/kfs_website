<?php

namespace App\Filament\Resources\ServiceSurveyResource\Pages;

use App\Filament\Resources\ServiceSurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceSurveys extends ListRecords
{
    protected static string $resource = ServiceSurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
