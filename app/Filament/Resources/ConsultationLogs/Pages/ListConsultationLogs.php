<?php

namespace App\Filament\Resources\ConsultationLogs\Pages;

use App\Filament\Resources\ConsultationLogs\ConsultationLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListConsultationLogs extends ListRecords
{
    protected static string $resource = ConsultationLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
