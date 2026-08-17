<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $translatableFields = ['name', 'description'];

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
