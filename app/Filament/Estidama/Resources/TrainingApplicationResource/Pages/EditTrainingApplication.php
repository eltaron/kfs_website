<?php

namespace App\Filament\Estidama\Resources\TrainingApplicationResource\Pages;

use App\Filament\Estidama\Resources\TrainingApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrainingApplication extends EditRecord
{
    protected static string $resource = TrainingApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
