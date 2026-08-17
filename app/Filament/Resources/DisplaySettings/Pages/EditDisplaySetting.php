<?php

namespace App\Filament\Resources\DisplaySettings\Pages;

use App\Filament\Resources\DisplaySettings\DisplaySettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDisplaySetting extends EditRecord
{
    protected static string $resource = DisplaySettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

