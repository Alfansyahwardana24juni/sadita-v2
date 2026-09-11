<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Informasi Produk')
                    ->description('Kategori dan detail utama produk')
                    ->schema([
                        \Filament\Schemas\Components\Grid::make(2)->schema([
                            Select::make('category_id')->relationship('category', 'name')->required()->label('Kategori Produk'),
                            TextInput::make('slug')->required()->helperText('Biarkan kosong untuk generate otomatis'),
                        ]),
                        Tabs::make('Terjemahan Informasi')
                            ->tabs([
                                Tabs\Tab::make('Bahasa Indonesia (ID)')
                                    ->schema([
                                        TextInput::make('name_id')->label('Nama Produk (ID)')->required(),
                                        Textarea::make('short_description_id')->label('Deskripsi Singkat (ID)')->rows(2),
                                        Textarea::make('description_id')->label('Deskripsi Lengkap (ID)')->required()->rows(4),
                                    ]),
                                Tabs\Tab::make('English (EN)')
                                    ->schema([
                                        TextInput::make('name_en')->label('Product Name (EN)')->required(),
                                        Textarea::make('short_description_en')->label('Short Description (EN)')->rows(2),
                                        Textarea::make('description_en')->label('Full Description (EN)')->required()->rows(4),
                                    ]),
                            ]),
                    ]),

                Section::make('Media & Harga Dasar')
                    ->schema([
                        \Filament\Forms\Components\FileUpload::make('image')
                            ->label('Gambar Utama Produk')
                            ->image()
                            ->disk('public')
                            ->directory('products')
                            ->columnSpan(1),
                        Repeater::make('brochures')
                            ->label('Link Brosur Produk (Google Drive / Web)')
                            ->schema([
                                TextInput::make('name')->label('Nama Brosur')->placeholder('Brosur 1')->required(),
                                TextInput::make('url')->label('URL / Link')->url()->required(),
                            ])
                            ->addActionLabel('Tambah Brosur')
                            ->defaultItems(0)
                            ->columnSpan(1),
                        TextInput::make('price')
                            ->label('Harga Utama (Mulai dari)')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('compare_at_price')
                            ->label('Harga Coret (Diskon)')
                            ->numeric()
                            ->prefix('Rp'),
                    ])->columns(2),

                Section::make('Spesifikasi Medis & Teknis')
                    ->description('Instruksi penggunaan, dosis, dan atribut spesifikasi produk')
                    ->schema([
                        Tabs::make('Terjemahan Medis')
                            ->tabs([
                                Tabs\Tab::make('Bahasa Indonesia (ID)')
                                    ->schema([
                                        Textarea::make('composition_id')->label('Komposisi (ID)')->rows(2),
                                        Textarea::make('indication_id')->label('Indikasi (ID)')->rows(2),
                                        Textarea::make('pharmacology_id')->label('Cara Kerja Obat (ID)')->rows(2),
                                        Textarea::make('usage_instruction_id')->label('Aturan Pakai (ID)')->rows(2),
                                        TextInput::make('dosage_id')->label('Dosis (ID)'),
                                        TextInput::make('storage_instruction_id')->label('Penyimpanan (ID)'),
                                    ])->columns(2),
                                Tabs\Tab::make('English (EN)')
                                    ->schema([
                                        Textarea::make('composition_en')->label('Composition (EN)')->rows(2),
                                        Textarea::make('indication_en')->label('Indication (EN)')->rows(2),
                                        Textarea::make('pharmacology_en')->label('Pharmacology (EN)')->rows(2),
                                        Textarea::make('usage_instruction_en')->label('Usage Instruction (EN)')->rows(2),
                                        TextInput::make('dosage_en')->label('Dosage (EN)'),
                                        TextInput::make('storage_instruction_en')->label('Storage (EN)'),
                                    ])->columns(2),
                            ])->columnSpanFull(),
                        
                        \Filament\Schemas\Components\Grid::make(2)->schema([
                            TextInput::make('animal_type')->label('Cocok Untuk (Jenis Hewan)'),
                            TextInput::make('withdrawal_time')->label('Withdrawal Time'),
                        ]),
                        \Filament\Schemas\Components\Grid::make(2)->schema([
                            TextInput::make('pack')->label('Bentuk Kemasan (Serbuk/Cair/dll)'),
                            Textarea::make('symptom_tags')->label('Tag Gejala (Untuk pencarian/AI)')->rows(1),
                        ]),
                        \Filament\Forms\Components\KeyValue::make('extra_specifications')
                            ->label('Spesifikasi Tambahan (Dinamis)')
                            ->keyLabel('Nama (Cth: Golongan)')
                            ->valueLabel('Nilai (Cth: Keras)')
                            ->columnSpanFull(),
                    ]),

                Section::make('Varian Kemasan (SKU)')
                    ->description('Kelola ukuran kemasan dan harganya. Stok sesungguhnya diatur di menu Product Stocks.')
                    ->schema([
                        Repeater::make('units')
                            ->relationship()
                            ->label('')
                            ->schema([
                                \Filament\Schemas\Components\Grid::make(3)->schema([
                                    TextInput::make('name')->label('Nama Kemasan')->required(),
                                    TextInput::make('price')->label('Harga')->required()->numeric()->prefix('Rp'),
                                    TextInput::make('compare_at_price')->label('Harga Coret')->numeric()->prefix('Rp'),
                                ]),
                                \Filament\Schemas\Components\Grid::make(4)->schema([
                                    TextInput::make('slug')->label('Slug')->unique(table: 'product_units', column: 'slug', ignoreRecord: true),
                                    TextInput::make('sku')->label('SKU'),
                                    TextInput::make('weight')->label('Berat (g)')->numeric()->default(500),
                                    TextInput::make('sort_order')->label('Urutan Tampil')->numeric()->default(0),
                                ]),
                                \Filament\Schemas\Components\Grid::make(3)->schema([
                                    TextInput::make('length')->label('Panjang (cm)')->numeric()->default(0),
                                    TextInput::make('width')->label('Lebar (cm)')->numeric()->default(0),
                                    TextInput::make('height')->label('Tinggi (cm)')->numeric()->default(0),
                                ]),
                                \Filament\Schemas\Components\Grid::make(2)->schema([
                                    Toggle::make('is_default')->label('Jadikan Kemasan Default')->default(false)->inline(false),
                                    Toggle::make('is_active')->label('Status Aktif')->default(true)->inline(false),
                                ]),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->orderColumn('sort_order')
                            ->addActionLabel('Tambah Varian Kemasan')
                            ->defaultItems(1)
                            ->collapsible(),
                    ]),
                
                Section::make('Status & Pengaturan Lanjutan')
                    ->schema([
                        \Filament\Schemas\Components\Grid::make(4)->schema([
                            Select::make('status')
                                ->options([
                                    'active' => 'Aktif (Ditampilkan)',
                                    'draft' => 'Draft (Disembunyikan)',
                                ])
                                ->required()
                                ->default('active')
                                ->label('Status Publikasi'),
                            Toggle::make('is_featured')
                                ->label('Produk Unggulan')
                                ->required()
                                ->inline(false),
                            TextInput::make('sort_order')
                                ->label('Urutan Tampil')
                                ->required()
                                ->numeric()
                                ->default(0),
                            TextInput::make('sold_count')
                                ->label('Terjual (Awal)')
                                ->numeric()
                                ->default(0),
                        ]),
                        
                        Section::make('Dimensi Pengiriman (Default)')
                            ->schema([
                                TextInput::make('weight')->label('Berat (g)')->required()->numeric()->default(500),
                                TextInput::make('length')->label('Panjang (cm)')->numeric()->default(0),
                                TextInput::make('width')->label('Lebar (cm)')->numeric()->default(0),
                                TextInput::make('height')->label('Tinggi (cm)')->numeric()->default(0),
                            ])->columns(4)->collapsed(),
                    ]),
            ]);
    }
}
