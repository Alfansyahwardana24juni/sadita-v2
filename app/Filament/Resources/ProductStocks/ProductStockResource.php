<?php

namespace App\Filament\Resources\ProductStocks;

use App\Filament\Resources\ProductStocks\Pages\CreateProductStock;
use App\Filament\Resources\ProductStocks\Pages\EditProductStock;
use App\Filament\Resources\ProductStocks\Pages\ListProductStocks;
use App\Filament\Resources\ProductStocks\Pages\ViewProductStock;
use App\Filament\Resources\ProductStocks\Schemas\ProductStockForm;
use App\Filament\Resources\ProductStocks\Schemas\ProductStockInfolist;
use App\Filament\Resources\ProductStocks\Tables\ProductStocksTable;
use App\Models\ProductStock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductStockResource extends Resource
{
    protected static ?string $model = ProductStock::class;

    protected static ?string $recordTitleAttribute = 'id';
    
    protected static ?string $navigationLabel = 'Stok Produk';
    
    protected static ?int $navigationSort = 4;

    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedCubeTransparent;
    
    public static function getNavigationGroup(): ?string
    {
        return 'Manajemen Toko';
    }

    public static function form(Schema $schema): Schema
    {
        return ProductStockForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProductStockInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductStocksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductStocks::route('/'),
            'create' => CreateProductStock::route('/create'),
            'view' => ViewProductStock::route('/{record}'),
            'edit' => EditProductStock::route('/{record}/edit'),
        ];
    }
}
