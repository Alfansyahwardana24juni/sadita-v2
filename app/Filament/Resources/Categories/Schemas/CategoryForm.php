<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Translations')
                    ->tabs([
                        Tabs\Tab::make('Bahasa Indonesia (ID)')
                            ->schema([
                                TextInput::make('name_id')->label('Nama (ID)')->required(),
                                Textarea::make('description_id')->label('Deskripsi (ID)')->columnSpanFull(),
                            ]),
                        Tabs\Tab::make('English (EN)')
                            ->schema([
                                TextInput::make('name_en')->label('Name (EN)')->required(),
                                Textarea::make('description_en')->label('Description (EN)')->columnSpanFull(),
                            ]),
                    ])->columnSpanFull(),
                TextInput::make('slug')
                    ->required(),
                FileUpload::make('image')
                    ->image()
                    ->directory('categories')
                    ->disk('public')
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
