<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $translatableFields = ['name', 'description', 'short_description', 'composition', 'pharmacology', 'indication', 'usage_instruction', 'dosage', 'storage_instruction'];

        foreach ($translatableFields as $field) {
            $data[$field . '_id'] = $this->record->getTranslation($field, 'id', false);
            $data[$field . '_en'] = $this->record->getTranslation($field, 'en', false);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $translatableFields = ['name', 'description', 'short_description', 'composition', 'pharmacology', 'indication', 'usage_instruction', 'dosage', 'storage_instruction'];

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
