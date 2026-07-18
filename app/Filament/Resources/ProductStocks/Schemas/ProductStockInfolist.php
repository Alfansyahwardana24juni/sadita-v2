<?php

namespace App\Filament\Resources\ProductStocks\Schemas;

use Filament\Schemas\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductStockInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('product.name')
                    ->label('Product'),
                TextEntry::make('warehouse.name')
                    ->label('Warehouse'),
                TextEntry::make('stock')
                    ->numeric(),
                TextEntry::make('reserved_stock')
                    ->numeric(),
                TextEntry::make('low_stock_threshold')
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
