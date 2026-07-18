<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Schemas\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('order_number'),
                TextEntry::make('warehouse.name')
                    ->label('Warehouse')
                    ->placeholder('-'),
                TextEntry::make('user.name')
                    ->label('User')
                    ->placeholder('-'),
                TextEntry::make('customer_name'),
                TextEntry::make('customer_phone'),
                TextEntry::make('customer_address')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('customer_city')
                    ->placeholder('-'),
                TextEntry::make('subtotal')
                    ->numeric(),
                TextEntry::make('shipping_cost')
                    ->money(),
                TextEntry::make('total')
                    ->numeric(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('payment_method')
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('admin_notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
