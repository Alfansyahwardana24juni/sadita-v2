<?php

namespace App\Filament\Resources\StoreSettings;

use App\Filament\Resources\StoreSettings\Pages\ManageStoreSettings;
use App\Models\StoreSetting;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StoreSettingResource extends Resource
{
    protected static ?string $model = StoreSetting::class;

    protected static ?string $navigationLabel = 'Pengaturan Umum';
    
    protected static ?string $pluralLabel = 'Pengaturan Umum';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Kontak CS')
                    ->schema([
                        TextInput::make('whatsapp_number')
                            ->label('Nomor WhatsApp')
                            ->helperText('Contoh: 628123456789 (Tanpa tanda +)')
                            ->required(),
                    ]),
                
                Section::make('Metode Pembayaran')
                    ->description('Daftar rekening bank dan QRIS yang akan ditampilkan di halaman checkout.')
                    ->schema([
                        Repeater::make('payment_methods')
                            ->label('Daftar Pembayaran')
                            ->schema([
                                Select::make('type')
                                    ->label('Jenis Pembayaran')
                                    ->options([
                                        'bank' => 'Transfer Bank',
                                        'qris' => 'QRIS',
                                    ])
                                    ->required()
                                    ->reactive(),
                                TextInput::make('bank_name')
                                    ->label('Nama Bank (contoh: BCA)')
                                    ->hidden(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('type') !== 'bank')
                                    ->required(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('type') === 'bank'),
                                TextInput::make('bank_account')
                                    ->label('Nomor Rekening')
                                    ->hidden(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('type') !== 'bank')
                                    ->required(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('type') === 'bank'),
                                TextInput::make('bank_holder')
                                    ->label('Atas Nama Rekening')
                                    ->hidden(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('type') !== 'bank')
                                    ->required(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('type') === 'bank'),
                                FileUpload::make('qris_image')
                                    ->label('Gambar QR Code QRIS')
                                    ->image()
                                    ->directory('qris')
                                    ->hidden(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('type') !== 'qris')
                                    ->required(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('type') === 'qris'),
                            ])
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['bank_name'] ?? ($state['type'] === 'qris' ? 'QRIS' : null)),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Pengaturan'),
                TextColumn::make('whatsapp_number')->label('No WhatsApp'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageStoreSettings::route('/'),
        ];
    }
}
