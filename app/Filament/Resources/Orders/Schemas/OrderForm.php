<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Update Status Pesanan')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Menunggu Konfirmasi',
                                'confirmed' => 'Dikonfirmasi',
                                'processing' => 'Diproses',
                                'shipped' => 'Dikirim',
                                'delivered' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->required(),
                        Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                'unpaid' => 'Belum Bayar',
                                'paid_pending_verify' => 'Menunggu Verifikasi',
                                'paid_confirmed' => 'Lunas',
                                'expired' => 'Kadaluarsa',
                            ])
                            ->required(),
                        Textarea::make('admin_notes')
                            ->label('Catatan Admin')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Informasi Pelanggan')
                    ->collapsed()
                    ->columns(2)
                    ->schema([
                        TextInput::make('order_number')->label('No. Pesanan')->disabled(),
                        TextInput::make('customer_name')->label('Nama')->disabled(),
                        TextInput::make('customer_phone')->label('HP')->disabled(),
                        TextInput::make('total')->label('Total')->disabled()->prefix('Rp'),
                        TextInput::make('payment_method')->label('Metode Pembayaran')->disabled(),
                        TextInput::make('payment_status')->label('Status Pembayaran')->disabled(),
                        Textarea::make('customer_address')->label('Alamat')->disabled()->columnSpanFull(),
                        Textarea::make('notes')->label('Catatan Pelanggan')->disabled()->columnSpanFull(),
                    ]),
            ]);
    }
}
