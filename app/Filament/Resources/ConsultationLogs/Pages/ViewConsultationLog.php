<?php

namespace App\Filament\Resources\ConsultationLogs\Pages;

use App\Filament\Resources\ConsultationLogs\ConsultationLogResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewConsultationLog extends ViewRecord
{
    protected static string $resource = ConsultationLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
