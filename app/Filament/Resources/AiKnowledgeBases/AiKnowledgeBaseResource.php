<?php

namespace App\Filament\Resources\AiKnowledgeBases;

use App\Filament\Resources\AiKnowledgeBases\Pages\CreateAiKnowledgeBase;
use App\Filament\Resources\AiKnowledgeBases\Pages\EditAiKnowledgeBase;
use App\Filament\Resources\AiKnowledgeBases\Pages\ListAiKnowledgeBases;
use App\Models\AiKnowledgeBase;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AiKnowledgeBaseResource extends Resource
{
    protected static ?string $model = AiKnowledgeBase::class;

    protected static ?string $navigationLabel = 'Knowledge Base';

    protected static ?int $navigationSort = 2;

    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedBookOpen;

    public static function getNavigationGroup(): ?string
    {
        return 'AI Konsultasi';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->required()->maxLength(255),
            TextInput::make('category')->maxLength(255),
            TextInput::make('priority')->numeric()->default(0)->required(),
            Toggle::make('is_active')->default(true),
            Textarea::make('content')->rows(16)->required()->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->weight('bold'),
                TextColumn::make('category')->searchable(),
                TextColumn::make('priority')->sortable(),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('updated_at')->since()->label('Diupdate'),
            ])
            ->defaultSort('priority', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAiKnowledgeBases::route('/'),
            'create' => CreateAiKnowledgeBase::route('/create'),
            'edit' => EditAiKnowledgeBase::route('/{record}/edit'),
        ];
    }
}

