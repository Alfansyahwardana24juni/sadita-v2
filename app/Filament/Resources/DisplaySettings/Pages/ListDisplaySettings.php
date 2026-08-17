<?php

namespace App\Filament\Resources\DisplaySettings\Pages;

use App\Filament\Resources\DisplaySettings\DisplaySettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDisplaySettings extends ListRecords
{
    protected static string $resource = DisplaySettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

