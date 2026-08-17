<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $translatableFields = ['name', 'description', 'short_description', 'composition', 'indication', 'usage_instruction', 'dosage'];

        foreach ($translatableFields as $field) {
            $data[$field] = [
                'id' => $data[$field . '_id'] ?? null,
                'en' => $data[$field . '_en'] ?? null,
            ];
            unset($data[$field . '_id'], $data[$field . '_en']);
        }

        return $data;
    }
}
