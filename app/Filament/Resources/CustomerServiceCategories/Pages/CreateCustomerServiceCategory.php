<?php

namespace App\Filament\Resources\CustomerServiceCategories\Pages;

use App\Filament\Resources\CustomerServiceCategories\CustomerServiceCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerServiceCategory extends CreateRecord
{
    protected static string $resource = CustomerServiceCategoryResource::class;
}
