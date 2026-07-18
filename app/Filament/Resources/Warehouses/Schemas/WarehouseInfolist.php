<?php

namespace App\Filament\Resources\Warehouses\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\TextEntry;
use Filament\Schemas\Schema;

class WarehouseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('code'),
                TextEntry::make('address')
                    ->columnSpanFull(),
                TextEntry::make('city'),
                TextEntry::make('province'),
                TextEntry::make('phone')
                    ->placeholder('-'),
                TextEntry::make('whatsapp')
                    ->placeholder('-'),
                TextEntry::make('service_area')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('delivery_estimate')
                    ->placeholder('-'),
                ImageEntry::make('image')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
