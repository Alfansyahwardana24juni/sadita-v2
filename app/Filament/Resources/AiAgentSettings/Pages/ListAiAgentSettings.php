<?php

namespace App\Filament\Resources\AiAgentSettings\Pages;

use App\Filament\Resources\AiAgentSettings\AiAgentSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAiAgentSettings extends ListRecords
{
    protected static string $resource = AiAgentSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

