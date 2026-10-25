<?php

namespace App\Filament\Resources\DisplaySettings\Pages;

use App\Filament\Resources\DisplaySettings\DisplaySettingResource;
use App\Models\DisplaySetting;
use Filament\Resources\Pages\ListRecords;

class ListDisplaySettings extends ListRecords
{
    protected static string $resource = DisplaySettingResource::class;

    public function mount(): void
    {
        $record = DisplaySetting::active();

        $this->redirect(DisplaySettingResource::getUrl('edit', ['record' => $record]));
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
