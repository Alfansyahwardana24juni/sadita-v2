<?php

namespace App\Filament\Resources\CustomerServiceCategories\Pages;

use App\Filament\Resources\CustomerServiceCategories\CustomerServiceCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomerServiceCategories extends ListRecords
{
    protected static string $resource = CustomerServiceCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
