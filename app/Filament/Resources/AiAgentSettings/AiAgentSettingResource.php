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
            Section::make('BUSINESS INFORMATION')
                ->columns(2)
                ->schema([
                    TextInput::make('business_name')->label('Business Name'),
                    TextInput::make('business_address')->label('Address')->columnSpanFull(),
                    TextInput::make('business_phone')->label('Phone')->tel(),
                    TextInput::make('business_email')->label('Email')->email(),
                    TextInput::make('business_website')->label('Website')->url(),
                    TextInput::make('business_hours')->label('Hours')->columnSpanFull(),
                ]),
                
            Section::make('IDENTITY & STYLE')
                ->columns(2)
                ->schema([
                    TextInput::make('name')->label('Name')->required(),
                    TextInput::make('role')->label('Role')->columnSpanFull(),
                    TextInput::make('language')->label('Language'),
                    TextInput::make('style')->label('Style')->columnSpanFull(),
                    TextInput::make('tone')->label('Tone')->columnSpanFull(),
                    TextInput::make('addressing')->label('Address'),
                    TextInput::make('allowed_emoji')->label('Emoji'),
                    TextInput::make('no_emoji')->label('No Emoji'),
                    TextInput::make('number_format')->label('Number Format'),
                    Textarea::make('instructions')->label('Instructions')->rows(6)->columnSpanFull(),
                    
                    Toggle::make('is_active')->label('Status Aktif')->default(true)->columnSpanFull(),
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

