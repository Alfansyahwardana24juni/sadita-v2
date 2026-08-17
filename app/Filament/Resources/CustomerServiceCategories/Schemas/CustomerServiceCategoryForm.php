<?php

namespace App\Filament\Resources\CustomerServiceCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CustomerServiceCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Nama Kategori')
                ->placeholder('Contoh: Dokter Hewan, Admin Kantor')
                ->required()
                ->maxLength(255),
            TextInput::make('sort_order')
                ->label('Urutan Tampil')
                ->numeric()
                ->default(0)
                ->required(),
            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true)
                ->required(),
        ]);
    }
}
