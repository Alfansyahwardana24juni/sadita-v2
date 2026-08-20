<?php

namespace App\Filament\Resources\ProductStocks\Schemas;

use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;

class ProductStockInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Text::make('product.name')
                    ->label('Product'),
                Text::make('warehouse.name')
                    ->label('Warehouse'),
                Text::make('stock')
                    ->numeric(),
                Text::make('reserved_stock')
                    ->numeric(),
                Text::make('low_stock_threshold')
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
