<?php

namespace App\Filament\Resources\CustomerServiceCategories;

use App\Filament\Resources\CustomerServiceCategories\Pages\CreateCustomerServiceCategory;
use App\Filament\Resources\CustomerServiceCategories\Pages\EditCustomerServiceCategory;
use App\Filament\Resources\CustomerServiceCategories\Pages\ListCustomerServiceCategories;
use App\Filament\Resources\CustomerServiceCategories\Schemas\CustomerServiceCategoryForm;
use App\Filament\Resources\CustomerServiceCategories\Tables\CustomerServiceCategoriesTable;
use App\Models\CustomerServiceCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomerServiceCategoryResource extends Resource
{
    protected static ?string $model = CustomerServiceCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Kategori CS';

    protected static ?string $modelLabel = 'Kategori';

    protected static ?string $pluralModelLabel = 'Kategori CS';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return 'Customer Service & Chat';
    }

    public static function form(Schema $schema): Schema
    {
        return CustomerServiceCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomerServiceCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCustomerServiceCategories::route('/'),
            'create' => CreateCustomerServiceCategory::route('/create'),
            'edit'   => EditCustomerServiceCategory::route('/{record}/edit'),
        ];
    }
}
