<?php

namespace App\Filament\Resources\CustomerServiceCategories\Pages;

use App\Filament\Resources\CustomerServiceCategories\CustomerServiceCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomerServiceCategory extends EditRecord
{
    protected static string $resource = CustomerServiceCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
