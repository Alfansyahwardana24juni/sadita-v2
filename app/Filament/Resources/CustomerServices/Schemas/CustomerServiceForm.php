<?php

namespace App\Filament\Resources\CustomerServices\Schemas;

use App\Models\CustomerServiceCategory;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Profil')
                ->description('Data identitas Dokter Hewan atau Admin Kantor')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->placeholder('Contoh: drh. Budi Santoso')
                            ->required()
                            ->maxLength(255),
                        Select::make('customer_service_category_id')
                            ->label('Kategori')
                            ->options(
                                CustomerServiceCategory::where('is_active', true)
                                    ->orderBy('sort_order')
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->required()
                            ->placeholder('Pilih kategori...'),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('title')
                            ->label('Jabatan / Gelar')
                            ->placeholder('Contoh: Dokter Hewan, Admin Kantor')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('experience')
                            ->label('Pengalaman')
                            ->placeholder('Contoh: 5 Tahun Pengalaman')
                            ->required()
                            ->maxLength(255),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('city')
                            ->label('Kota / Lokasi')
                            ->placeholder('Contoh: Makassar')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('working_hours')
                            ->label('Jam Kerja')
                            ->placeholder('Contoh: 08.00 - 17.00 WITA')
                            ->required()
                            ->maxLength(255),
                    ]),
                    TextInput::make('whatsapp_number')
                        ->label('Nomor WhatsApp')
                        ->placeholder('Contoh: 6281234567890 (tanpa +)')
                        ->tel()
                        ->required()
                        ->maxLength(20),
                ]),

            Section::make('Foto Profil')
                ->description('Jika tidak diisi, foto profil akan menampilkan inisial nama secara otomatis')
                ->schema([
                    FileUpload::make('photo')
                        ->label('Foto Profil (Opsional)')
                        ->image()
                        ->disk('public')
                        ->directory('customer-service-photos')
                        ->imageEditor()
                        ->circleCropper()
                        ->maxSize(2048)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->nullable(),
                ]),

            Section::make('Status & Pengaturan')
                ->schema([
                    Grid::make(3)->schema([
                        Select::make('status')
                            ->label('Status Ketersediaan')
                            ->options([
                                'online'  => 'Online',
                                'busy'    => 'Sedang Sibuk',
                                'offline' => 'Offline',
                            ])
                            ->default('offline')
                            ->required(),
                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Tampilkan di Halaman Chat')
                            ->default(true)
                            ->required(),
                    ]),
                ]),
        ]);
    }
}
