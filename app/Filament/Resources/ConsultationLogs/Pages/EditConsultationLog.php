<?php

namespace App\Filament\Resources\ConsultationLogs\Pages;

use App\Filament\Resources\ConsultationLogs\ConsultationLogResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditConsultationLog extends EditRecord
{
    protected static string $resource = ConsultationLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
