<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Text::make('order_number'),
                Text::make('warehouse.name')
                    ->label('Warehouse')
                    ->placeholder('-'),
                Text::make('user.name')
                    ->label('User')
                    ->placeholder('-'),
                Text::make('customer_name'),
                Text::make('customer_phone'),
                Text::make('customer_address')
                    ->placeholder('-')
                    ->columnSpanFull(),
                Text::make('customer_city')
                    ->placeholder('-'),
                Text::make('subtotal')
                    ->numeric(),
                Text::make('shipping_cost')
                    ->money(),
                Text::make('total')
                    ->numeric(),
                Text::make('status')
                    ->badge(),
                Text::make('payment_method')
                    ->placeholder('-'),
                Text::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                Text::make('admin_notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                Text::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                Text::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
