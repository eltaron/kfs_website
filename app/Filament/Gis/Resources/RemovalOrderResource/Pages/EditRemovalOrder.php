<?php

namespace App\Filament\Gis\Resources\RemovalOrderResource\Pages;

use App\Filament\Gis\Resources\RemovalOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRemovalOrder extends EditRecord
{
    protected static string $resource = RemovalOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
