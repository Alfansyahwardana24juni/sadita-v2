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
                TextEntry::make('category.name')
                    ->label('Category'),
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('description')
                    ->columnSpanFull(),
                TextEntry::make('short_description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('composition')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('indication')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('usage_instruction')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('dosage')
                    ->placeholder('-'),
                TextEntry::make('withdrawal_time')
                    ->placeholder('-'),
                TextEntry::make('registration_number')
                    ->placeholder('-'),
                TextEntry::make('pack')
                    ->placeholder('-'),
                TextEntry::make('animal_type')
                    ->placeholder('-'),
                TextEntry::make('symptom_tags')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('price')
                    ->money(),
                TextEntry::make('compare_at_price')
                    ->money()
                    ->placeholder('-'),
                ImageEntry::make('image')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('rating')
                    ->numeric(),
                TextEntry::make('reviews_count')
                    ->numeric(),
                TextEntry::make('sold_count')
                    ->numeric(),
                TextEntry::make('status'),
                IconEntry::make('is_featured')
                    ->boolean(),
                TextEntry::make('sort_order')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
