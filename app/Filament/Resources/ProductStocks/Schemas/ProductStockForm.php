<?php

namespace App\Filament\Resources\ProductStocks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductStockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                Select::make('warehouse_id')
                    ->relationship('warehouse', 'name')
                    ->required(),
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('reserved_stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('low_stock_threshold')
                    ->required()
                    ->numeric()
                    ->default(10),
            ]);
    }
}
