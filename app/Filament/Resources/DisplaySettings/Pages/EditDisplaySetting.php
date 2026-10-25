<?php

namespace App\Filament\Resources\DisplaySettings\Pages;

use App\Filament\Resources\DisplaySettings\DisplaySettingResource;
use Filament\Resources\Pages\EditRecord;

class EditDisplaySetting extends EditRecord
{
    protected static string $resource = DisplaySettingResource::class;

    protected static ?string $title = 'Pengaturan Website & WhatsApp';

    protected function getHeaderActions(): array
    {
        return [];
    }
}
