<?php

namespace App\Filament\Resources\CustomerServices;

use App\Filament\Resources\CustomerServices\Pages\CreateCustomerService;
use App\Filament\Resources\CustomerServices\Pages\EditCustomerService;
use App\Filament\Resources\CustomerServices\Pages\ListCustomerServices;
use App\Filament\Resources\CustomerServices\Schemas\CustomerServiceForm;
use App\Filament\Resources\CustomerServices\Tables\CustomerServicesTable;
use App\Models\CustomerService;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomerServiceResource extends Resource
{
    protected static ?string $model = CustomerService::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Dokter & Admin CS';

    protected static ?string $modelLabel = 'Anggota CS';

    protected static ?string $pluralModelLabel = 'Dokter & Admin CS';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return 'Customer Service & Chat';
    }

    public static function form(Schema $schema): Schema
    {
        return CustomerServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomerServicesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCustomerServices::route('/'),
            'create' => CreateCustomerService::route('/create'),
            'edit'   => EditCustomerService::route('/{record}/edit'),
        ];
    }
}
