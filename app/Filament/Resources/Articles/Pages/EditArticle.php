<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use Filament\Resources\Pages\EditRecord;

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $translatableFields = ['title', 'excerpt', 'content'];

        foreach ($translatableFields as $field) {
            $data[$field . '_id'] = $this->record->getTranslation($field, 'id', false);
            $data[$field . '_en'] = $this->record->getTranslation($field, 'en', false);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $translatableFields = ['title', 'excerpt', 'content'];

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
