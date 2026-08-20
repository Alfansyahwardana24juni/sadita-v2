<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Text::make('name'),
                Text::make('slug'),
                Text::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                Text::make('sort_order')
                    ->numeric(),
                IconEntry::make('is_active')
                    ->boolean(),
                Text::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                Text::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
