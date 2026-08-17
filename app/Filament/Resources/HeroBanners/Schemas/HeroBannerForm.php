<?php

namespace App\Filament\Resources\HeroBanners\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class HeroBannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title'),
                TextInput::make('subtitle'),
                FileUpload::make('image_url')
                    ->image()
                    ->disk('public')
                    ->directory('hero-banners')
                    ->required(),
                TextInput::make('link_url')
                    ->url(),
                TextInput::make('button_text'),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
