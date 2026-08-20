<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;

class ArticleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Text::make('category.name')
                    ->label('Category')
                    ->placeholder('-'),
                Text::make('title'),
                Text::make('slug'),
                Text::make('excerpt')
                    ->placeholder('-')
                    ->columnSpanFull(),
                Text::make('content')
                    ->columnSpanFull(),
                ImageEntry::make('featured_image')
                    ->placeholder('-'),
                Text::make('author'),
                Text::make('status')
                    ->badge(),
                Text::make('published_at')
                    ->dateTime()
                    ->placeholder('-'),
                Text::make('meta_title')
                    ->placeholder('-'),
                Text::make('meta_description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                Text::make('views_count')
                    ->numeric(),
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
