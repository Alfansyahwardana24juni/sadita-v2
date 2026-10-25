<?php

namespace App\Filament\Resources\DisplaySettings;

use App\Filament\Resources\DisplaySettings\Pages\EditDisplaySetting;
use App\Filament\Resources\DisplaySettings\Pages\ListDisplaySettings;
use App\Models\DisplaySetting;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DisplaySettingResource extends Resource
{
    protected static ?string $model = DisplaySetting::class;

    protected static ?string $navigationLabel = 'Pengaturan Website & WA';

    protected static ?string $modelLabel = 'Pengaturan Website & WA';

    protected static ?string $pluralModelLabel = 'Pengaturan Website & WA';

    protected static ?int $navigationSort = 1;

    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    public static function getNavigationGroup(): ?string
    {
        return 'CMS Frontend';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationUrl(): string
    {
        return static::getUrl('edit', ['record' => DisplaySetting::active()]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // 1. BAGIAN UTAMA (PALING ATAS): PENGATURAN WHATSAPP CHAT & KONSULTASI
                Section::make('📱 Pengaturan Utama WhatsApp (Chat & Konsultasi)')
                    ->description('Nomor dan pesan pembuka ini digunakan langsung oleh tombol "Chat" di navigasi bawah serta tombol "Konsultasi ke WhatsApp" di produk.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('store_whatsapp_number')
                            ->label('Nomor WhatsApp Admin / Konsultasi')
                            ->required()
                            ->placeholder('6285161018507')
                            ->helperText('Contoh: 6285161018507 atau 085161018507 (otomatis dikonversi ke kode 62)'),

                        TextInput::make('store_whatsapp_message')
                            ->label('Template Pesan Default WhatsApp')
                            ->required()
                            ->placeholder('Halo admin, saya ingin konsultasi')
                            ->helperText('Pesan otomatis yang muncul saat pengunjung mengklik Chat WhatsApp. Gunakan {product} untuk nama produk otomatis.'),
                    ]),

                // 2. BAGIAN TAMBAHAN (DI BAWAH): PENGATURAN KONTEN HALAMAN WEBSITE
                Section::make('🌐 Pengaturan Konten Halaman Website (Opsional)')
                    ->description('Sesuaikan judul, deskripsi banner, dan teks konten halaman website.')
                    ->schema([
                        Tabs::make('Konten Website')
                            ->columnSpanFull()
                            ->tabs([
                                Tab::make('Toko (toko.sadita.id)')
                                    ->icon('heroicon-o-shopping-bag')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('store_home_title')->label('Judul Beranda Toko'),
                                        TextInput::make('store_home_info_title')->label('Judul Info Toko'),
                                        Textarea::make('store_home_description')->label('Deskripsi Beranda Toko'),
                                        Textarea::make('store_home_info_description')->label('Deskripsi Info Toko'),
                                        TextInput::make('store_katalog_title')->label('Judul Halaman Katalog'),
                                        TextInput::make('store_katalog_subtitle')->label('Subjudul Katalog / Cabang'),
                                    ]),

                                Tab::make('Homepage (sadita.id)')
                                    ->icon('heroicon-o-home')
                                    ->schema([
                                        Section::make('Hero Banner')
                                            ->columns(2)
                                            ->schema([
                                                TextInput::make('home_hero_badge')->label('Badge Hero'),
                                                TextInput::make('home_hero_title')->label('Judul Hero'),
                                                Textarea::make('home_hero_description')->label('Deskripsi Hero')->columnSpanFull(),
                                            ]),

                                        Section::make('Tombol CTA')
                                            ->columns(2)
                                            ->schema([
                                                TextInput::make('home_primary_cta_label')->label('Label Tombol Utama'),
                                                TextInput::make('home_primary_cta_url')->label('URL Tombol Utama'),
                                                TextInput::make('home_secondary_cta_label')->label('Label Tombol Kedua'),
                                                TextInput::make('home_secondary_cta_url')->label('URL Tombol Kedua'),
                                            ]),

                                        Section::make('Statistik')
                                            ->columns(2)
                                            ->schema([
                                                TextInput::make('home_stat_experience_value')->label('Nilai Pengalaman'),
                                                TextInput::make('home_stat_experience_label')->label('Label Pengalaman'),
                                                TextInput::make('home_stat_product_value')->label('Nilai Produk'),
                                                TextInput::make('home_stat_product_label')->label('Label Produk'),
                                                TextInput::make('home_stat_partner_value')->label('Nilai Mitra'),
                                                TextInput::make('home_stat_partner_label')->label('Label Mitra'),
                                                TextInput::make('home_stat_volume_value')->label('Nilai Volume'),
                                                TextInput::make('home_stat_volume_label')->label('Label Volume'),
                                            ]),
                                    ]),

                                Tab::make('Halaman Tentang')
                                    ->icon('heroicon-o-information-circle')
                                    ->schema([
                                        TextInput::make('about_company_title')->label('Judul Perusahaan'),
                                        Textarea::make('about_company_description')->label('Deskripsi Perusahaan'),
                                        Textarea::make('about_vision')->label('Visi'),
                                        Textarea::make('about_mission_points')->label('Poin Misi')->helperText('Isi 1 poin per baris.'),
                                        Textarea::make('about_certification_points')->label('Poin Sertifikasi')->helperText('Isi 1 poin per baris.'),
                                    ]),

                                Tab::make('Identitas')
                                    ->icon('heroicon-o-cog-6-tooth')
                                    ->schema([
                                        TextInput::make('name')->label('Nama Konfigurasi')->required(),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('store_whatsapp_number')->label('No. WhatsApp')->searchable(),
                TextColumn::make('updated_at')->label('Diupdate')->since(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDisplaySettings::route('/'),
            'edit' => EditDisplaySetting::route('/{record}/edit'),
        ];
    }
}
