<?php

namespace App\Filament\Resources\DisplaySettings;

use App\Filament\Resources\DisplaySettings\Pages\EditDisplaySetting;
use App\Filament\Resources\DisplaySettings\Pages\ListDisplaySettings;
use App\Models\DisplaySetting;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DisplaySettingResource extends Resource
{
    protected static ?string $model = DisplaySetting::class;

    protected static ?string $navigationLabel = 'Tampilan Frontend';

    protected static ?int $navigationSort = 1;

    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedPaintBrush;

    public static function getNavigationGroup(): ?string
    {
        return 'CMS Frontend';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ]),

                Section::make('Homepage (sadita.id)')
                    ->columns(2)
                    ->schema([
                        TextInput::make('home_hero_badge'),
                        TextInput::make('home_hero_title'),
                        Textarea::make('home_hero_description')->columnSpanFull(),
                        TextInput::make('home_primary_cta_label'),
                        TextInput::make('home_primary_cta_url'),
                        TextInput::make('home_secondary_cta_label'),
                        TextInput::make('home_secondary_cta_url'),
                        TextInput::make('home_warehouse_cta_label'),
                        TextInput::make('home_warehouse_cta_url'),
                        TextInput::make('home_about_title'),
                        Textarea::make('home_about_description')->columnSpanFull(),
                        TextInput::make('home_stat_experience_value'),
                        TextInput::make('home_stat_experience_label'),
                        TextInput::make('home_stat_product_value'),
                        TextInput::make('home_stat_product_label'),
                        TextInput::make('home_stat_partner_value'),
                        TextInput::make('home_stat_partner_label'),
                        TextInput::make('home_stat_volume_value'),
                        TextInput::make('home_stat_volume_label'),
                    ]),

                Section::make('Toko (toko.sadita.id)')
                    ->columns(2)
                    ->schema([
                        TextInput::make('store_home_title'),
                        Textarea::make('store_home_description'),
                        TextInput::make('store_home_info_title'),
                        Textarea::make('store_home_info_description'),
                        TextInput::make('store_katalog_title'),
                        TextInput::make('store_katalog_subtitle'),
                    ]),

                Section::make('Halaman Tentang')
                    ->columns(2)
                    ->schema([
                        TextInput::make('about_company_title'),
                        Textarea::make('about_company_description')->columnSpanFull(),
                        Textarea::make('about_vision')->columnSpanFull(),
                        Textarea::make('about_mission_points')
                            ->helperText('Isi 1 poin misi per baris.')
                            ->columnSpanFull(),
                        Textarea::make('about_certification_points')
                            ->helperText('Isi 1 poin sertifikasi per baris.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama Setting')
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->since(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDisplaySettings::route('/'),
            'edit' => EditDisplaySetting::route('/{record}/edit'),
        ];
    }
}

