<?php

namespace App\Filament\Resources\AiAgentSettings\Pages;

use App\Filament\Resources\AiAgentSettings\AiAgentSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAiAgentSetting extends EditRecord
{
    protected static string $resource = AiAgentSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

