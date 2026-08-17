<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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
