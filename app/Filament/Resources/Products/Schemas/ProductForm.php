<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Tabs::make('Translations')
                    ->tabs([
                        Tabs\Tab::make('Bahasa Indonesia (ID)')
                            ->schema([
                                TextInput::make('name_id')->label('Nama (ID)')->required(),
                                Textarea::make('description_id')->label('Deskripsi (ID)')->required()->columnSpanFull(),
                                Textarea::make('short_description_id')->label('Deskripsi Singkat (ID)')->columnSpanFull(),
                                Textarea::make('composition_id')->label('Komposisi (ID)')->columnSpanFull(),
                                Textarea::make('indication_id')->label('Indikasi (ID)')->columnSpanFull(),
                                Textarea::make('usage_instruction_id')->label('Instruksi Penggunaan (ID)')->columnSpanFull(),
                                TextInput::make('dosage_id')->label('Dosis (ID)'),
                            ]),
                        Tabs\Tab::make('English (EN)')
                            ->schema([
                                TextInput::make('name_en')->label('Name (EN)')->required(),
                                Textarea::make('description_en')->label('Description (EN)')->required()->columnSpanFull(),
                                Textarea::make('short_description_en')->label('Short Description (EN)')->columnSpanFull(),
                                Textarea::make('composition_en')->label('Composition (EN)')->columnSpanFull(),
                                Textarea::make('indication_en')->label('Indication (EN)')->columnSpanFull(),
                                Textarea::make('usage_instruction_en')->label('Usage Instruction (EN)')->columnSpanFull(),
                                TextInput::make('dosage_en')->label('Dosage (EN)'),
                            ]),
                    ])->columnSpanFull(),
                TextInput::make('withdrawal_time'),
                TextInput::make('registration_number'),
                TextInput::make('pack'),
                TextInput::make('animal_type'),
                Textarea::make('symptom_tags')
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                TextInput::make('compare_at_price')
                    ->numeric()
                    ->prefix('Rp'),
                \Filament\Schemas\Components\Section::make('Dimensi & Pengiriman')
                    ->schema([
                        TextInput::make('weight')
                            ->label('Berat (Gram)')
                            ->required()
                            ->numeric()
                            ->default(500),
                        TextInput::make('length')
                            ->label('Panjang (cm)')
                            ->numeric()
                            ->default(0),
                        TextInput::make('width')
                            ->label('Lebar (cm)')
                            ->numeric()
                            ->default(0),
                        TextInput::make('height')
                            ->label('Tinggi (cm)')
                            ->numeric()
                            ->default(0),
                    ])->columns(4),
                Textarea::make('image')
                    ->columnSpanFull(),
                TextInput::make('rating')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('reviews_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('sold_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
                Toggle::make('is_featured')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
