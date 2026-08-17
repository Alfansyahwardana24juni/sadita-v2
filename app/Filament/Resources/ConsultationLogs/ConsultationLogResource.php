<?php

namespace App\Filament\Resources\ConsultationLogs;

use App\Filament\Resources\ConsultationLogs\Pages\CreateConsultationLog;
use App\Filament\Resources\ConsultationLogs\Pages\EditConsultationLog;
use App\Filament\Resources\ConsultationLogs\Pages\ListConsultationLogs;
use App\Filament\Resources\ConsultationLogs\Pages\ViewConsultationLog;
use App\Filament\Resources\ConsultationLogs\Schemas\ConsultationLogForm;
use App\Filament\Resources\ConsultationLogs\Schemas\ConsultationLogInfolist;
use App\Filament\Resources\ConsultationLogs\Tables\ConsultationLogsTable;
use App\Models\ConsultationLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ConsultationLogResource extends Resource
{
    protected static ?string $model = ConsultationLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $navigationLabel = 'Log AI Konsultasi';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return 'AI Konsultasi';
    }

    public static function form(Schema $schema): Schema
    {
        return ConsultationLogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ConsultationLogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConsultationLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListConsultationLogs::route('/'),
            'create' => CreateConsultationLog::route('/create'),
            'view' => ViewConsultationLog::route('/{record}'),
            'edit' => EditConsultationLog::route('/{record}/edit'),
        ];
    }
}
