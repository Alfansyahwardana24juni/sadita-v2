<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Text::make('category.name')
                    ->label('Category'),
                Text::make('name'),
                Text::make('slug'),
                Text::make('description')
                    ->columnSpanFull(),
                Text::make('short_description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                Text::make('composition')
                    ->placeholder('-')
                    ->columnSpanFull(),
                Text::make('indication')
                    ->placeholder('-')
                    ->columnSpanFull(),
                Text::make('usage_instruction')
                    ->placeholder('-')
                    ->columnSpanFull(),
                Text::make('dosage')
                    ->placeholder('-'),
                Text::make('withdrawal_time')
                    ->placeholder('-'),
                Text::make('registration_number')
                    ->placeholder('-'),
                Text::make('pack')
                    ->placeholder('-'),
                Text::make('animal_type')
                    ->placeholder('-'),
                Text::make('symptom_tags')
                    ->placeholder('-')
                    ->columnSpanFull(),
                Text::make('price')
                    ->money(),
                Text::make('compare_at_price')
                    ->money()
                    ->placeholder('-'),
                ImageEntry::make('image')
                    ->placeholder('-')
                    ->columnSpanFull(),
                Text::make('rating')
                    ->numeric(),
                Text::make('reviews_count')
                    ->numeric(),
                Text::make('sold_count')
                    ->numeric(),
                Text::make('status'),
                IconEntry::make('is_featured')
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
