<?php

namespace App\Filament\Resources\ArticleCategories\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;

class ArticleCategoryInfolist
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
                IconEntry::make('is_active')
                    ->boolean(),
                Text::make('sort_order')
                    ->numeric(),
                Text::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                Text::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
