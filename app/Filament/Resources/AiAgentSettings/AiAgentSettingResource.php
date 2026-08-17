<?php

namespace App\Filament\Resources\AiAgentSettings;

use App\Filament\Resources\AiAgentSettings\Pages\CreateAiAgentSetting;
use App\Filament\Resources\AiAgentSettings\Pages\EditAiAgentSetting;
use App\Filament\Resources\AiAgentSettings\Pages\ListAiAgentSettings;
use App\Models\AiAgentSetting;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AiAgentSettingResource extends Resource
{
    protected static ?string $model = AiAgentSetting::class;

    protected static ?string $navigationLabel = 'Agent Spec';

    protected static ?int $navigationSort = 1;

    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedCpuChip;

    public static function getNavigationGroup(): ?string
    {
        return 'AI Konsultasi';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identity & Style')
                ->columns(2)
                ->schema([
                    TextInput::make('name')->required(),
                    TextInput::make('role'),
                    TextInput::make('language'),
                    TextInput::make('style'),
                    TextInput::make('tone'),
                    TextInput::make('addressing'),
                    Toggle::make('is_active')->default(true),
                ]),
            Section::make('Agent Instructions')
                ->schema([
                    Textarea::make('scope_rules')->rows(6)->columnSpanFull(),
                    Textarea::make('instructions')->rows(10)->columnSpanFull(),
                    Textarea::make('response_format')->rows(8)->columnSpanFull(),
                ]),
            Section::make('Contact')
                ->columns(2)
                ->schema([
                    TextInput::make('contact_label'),
                    TextInput::make('contact_value'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->weight('bold'),
                TextColumn::make('language'),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('updated_at')->since()->label('Diupdate'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAiAgentSettings::route('/'),
            'create' => CreateAiAgentSetting::route('/create'),
            'edit' => EditAiAgentSetting::route('/{record}/edit'),
        ];
    }
}

