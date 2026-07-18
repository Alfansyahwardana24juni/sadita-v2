<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Tabs;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Artikel')
                    ->columns(2)
                    ->schema([
                        Tabs::make('Translations')
                            ->tabs([
                                Tabs\Tab::make('Bahasa Indonesia (ID)')
                                    ->schema([
                                        TextInput::make('title_id')
                                            ->label('Judul (ID)')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                                        Textarea::make('excerpt_id')
                                            ->label('Ringkasan (ID)')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        Textarea::make('content_id')
                                            ->label('Konten Artikel (ID)')
                                            ->required()
                                            ->rows(12)
                                            ->columnSpanFull(),
                                    ]),
                                Tabs\Tab::make('English (EN)')
                                    ->schema([
                                        TextInput::make('title_en')
                                            ->label('Judul (EN)')
                                            ->required(),
                                        Textarea::make('excerpt_en')
                                            ->label('Ringkasan (EN)')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        Textarea::make('content_en')
                                            ->label('Konten Artikel (EN)')
                                            ->required()
                                            ->rows(12)
                                            ->columnSpanFull(),
                                    ]),
                            ])->columnSpanFull(),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')->required(),
                                TextInput::make('slug')->required(),
                            ]),
                        TextInput::make('author')
                            ->label('Penulis')
                            ->required()
                            ->default('Admin SADITA'),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->default('draft')
                            ->required(),
                        DateTimePicker::make('published_at')
                            ->label('Tanggal Publish')
                            ->columnSpanFull(),
                    ]),

                Section::make('Gambar & Media')
                    ->schema([
                        FileUpload::make('featured_image')
                            ->label('Gambar Utama')
                            ->image()
                            ->directory('articles')
                            ->columnSpanFull(),
                    ]),

                Section::make('SEO')
                    ->collapsed()
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->columnSpanFull(),
                        Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Pengaturan')
                    ->collapsed()
                    ->columns(2)
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                        TextInput::make('views_count')
                            ->label('Jumlah Views')
                            ->numeric()
                            ->default(0)
                            ->disabled(),
                    ]),
            ]);
    }
}
