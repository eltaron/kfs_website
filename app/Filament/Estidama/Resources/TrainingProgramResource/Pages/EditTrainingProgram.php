<?php

namespace App\Filament\Estidama\Resources\TrainingProgramResource\Pages;

use App\Filament\Estidama\Resources\TrainingProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrainingProgram extends EditRecord
{
    protected static string $resource = TrainingProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
