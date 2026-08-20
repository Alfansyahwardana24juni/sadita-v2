<?php

namespace App\Filament\Resources\Warehouses\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;

class WarehouseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Text::make('name'),
                Text::make('slug'),
                Text::make('code'),
                Text::make('address')
                    ->columnSpanFull(),
                Text::make('city'),
                Text::make('province'),
                Text::make('phone')
                    ->placeholder('-'),
                Text::make('whatsapp')
                    ->placeholder('-'),
                Text::make('service_area')
                    ->placeholder('-')
                    ->columnSpanFull(),
                Text::make('delivery_estimate')
                    ->placeholder('-'),
                ImageEntry::make('image')
                    ->placeholder('-')
                    ->columnSpanFull(),
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
