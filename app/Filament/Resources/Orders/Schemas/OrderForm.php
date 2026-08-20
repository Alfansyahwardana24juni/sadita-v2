<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use App\Models\Product;
use Filament\Support\RawJs;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Daftar Produk yang Dipesan')
                    ->description('Tambahkan atau sesuaikan produk yang dibeli pelanggan.')
                    ->icon('heroicon-o-shopping-bag')
                    ->columnSpanFull() // This makes it full width, expanding the side spaces
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->hiddenLabel()
                            ->addActionLabel('Tambah Produk')
                            ->live(debounce: 500)
                            ->afterStateUpdated(function ($get, $set) {
                                self::updateTotal($get, $set);
                            })
                            ->schema([
                                Select::make('product_id')
                                    ->label('Pilih Produk')
                                    ->options(Product::pluck('name', 'id'))
                                    ->required()
                                    ->reactive()
                                    ->searchable()
                                    ->columnSpan(4)
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $product = Product::find($state);
                                        if ($product) {
                                            $set('product_name', $product->name);
                                            $set('product_sku', $product->sku);
                                            $set('price', $product->price);
                                            $set('subtotal', $product->price);
                                            $set('quantity', 1);
                                        }
                                    }),
                                TextInput::make('price')
                                    ->label('Harga Satuan')
                                    ->required()
                                    ->prefix('Rp')
                                    ->mask(RawJs::make('$money($input, \'.\', \',\')'))
                                    ->stripCharacters(['.', ','])
                                    ->dehydrateStateUsing(fn ($state) => (int) str_replace(['.', ','], '', $state))
                                    ->columnSpan(3) // Increased from 2 to 3 to make it wider
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $get, $set) {
                                        $qty = $get('quantity') ?: 1;
                                        $price = is_numeric($state) ? $state : (int) str_replace(['.', ','], '', $state);
                                        $set('subtotal', $price * $qty);
                                    }),
                                TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->columnSpan(1) // Decreased from 2 to 1 since quantity is just a small number
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $get, $set) {
                                        $rawPrice = $get('price') ?: 0;
                                        $price = is_numeric($rawPrice) ? $rawPrice : (int) str_replace(['.', ','], '', $rawPrice);
                                        $qty = is_numeric($state) ? $state : (int) str_replace(['.', ','], '', $state);
                                        $set('subtotal', $price * $qty);
                                    }),
                                TextInput::make('subtotal')
                                    ->label('Subtotal Harga')
                                    ->required()
                                    ->prefix('Rp')
                                    ->mask(RawJs::make('$money($input, \'.\', \',\')'))
                                    ->stripCharacters(['.', ','])
                                    ->dehydrateStateUsing(fn ($state) => (int) str_replace(['.', ','], '', $state))
                                    ->readOnly()
                                    ->columnSpan(4), // Total columns = 4 + 3 + 1 + 4 = 12
                                    
                                Hidden::make('product_name'),
                                Hidden::make('product_sku'),
                            ])
                            ->columns(12)
                            ->defaultItems(1)
                    ]),

                Group::make()
                    ->columnSpan(['lg' => 2])
                    ->schema([
                        Section::make('Informasi Pelanggan & Pengiriman')
                            ->icon('heroicon-o-user')
                            ->description('Detail kontak dan alamat pengiriman pesanan.')
                            ->collapsed()
                            ->columns(2)
                            ->schema([
                                TextInput::make('customer_name')->label('Nama Lengkap')->required()->prefixIcon('heroicon-m-user'),
                                TextInput::make('customer_phone')->label('Nomor WhatsApp / HP')->required()->prefixIcon('heroicon-m-phone'),
                                Textarea::make('customer_address')->label('Alamat Lengkap')->columnSpanFull()->rows(3),
                                Textarea::make('notes')->label('Catatan dari Pelanggan')->columnSpanFull()->rows(2),
                            ]),
                    ]),

                Group::make()
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        Section::make('Status Pesanan')
                            ->icon('heroicon-o-check-circle')
                            ->schema([
                                Select::make('status')
                                    ->label('Status Pengiriman')
                                    ->options([
                                        'pending' => 'Menunggu Konfirmasi',
                                        'confirmed' => 'Dikonfirmasi',
                                        'processing' => 'Diproses',
                                        'shipped' => 'Dikirim',
                                        'delivered' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                    ])
                                    ->native(false)
                                    ->required(),
                                Select::make('payment_status')
                                    ->label('Status Pembayaran')
                                    ->options([
                                        'unpaid' => 'Belum Bayar',
                                        'paid_pending_verify' => 'Menunggu Verifikasi',
                                        'paid_confirmed' => 'Lunas',
                                        'expired' => 'Kadaluarsa',
                                    ])
                                    ->native(false)
                                    ->required(),
                                TextInput::make('payment_method')
                                    ->label('Metode Pembayaran')
                                    ->required(),
                            ]),
                            
                        Section::make('Ringkasan Total')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                TextInput::make('order_number')
                                    ->label('Nomor Pesanan')
                                    ->required()
                                    ->readOnly(),
                                TextInput::make('subtotal')
                                    ->label('Total Produk (Rp)')
                                    ->required()
                                    ->readOnly()
                                    ->mask(RawJs::make('$money($input, \'.\', \',\')'))
                                    ->stripCharacters(['.', ','])
                                    ->dehydrateStateUsing(fn ($state) => (int) str_replace(['.', ','], '', $state))
                                    ->prefix('Rp'),
                                TextInput::make('shipping_cost')
                                    ->label('Ongkos Kirim (Rp)')
                                    ->default(0)
                                    ->prefix('Rp')
                                    ->mask(RawJs::make('$money($input, \'.\', \',\')'))
                                    ->stripCharacters(['.', ','])
                                    ->dehydrateStateUsing(fn ($state) => (int) str_replace(['.', ','], '', $state))
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(function ($get, $set) {
                                        self::updateTotal($get, $set);
                                    }),
                                TextInput::make('discount_amount')
                                    ->label('Potongan / Diskon (Rp)')
                                    ->default(0)
                                    ->prefix('Rp')
                                    ->mask(RawJs::make('$money($input, \'.\', \',\')'))
                                    ->stripCharacters(['.', ','])
                                    ->dehydrateStateUsing(fn ($state) => (int) str_replace(['.', ','], '', $state))
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(function ($get, $set) {
                                        self::updateTotal($get, $set);
                                    }),
                                TextInput::make('total')
                                    ->label('Total Akhir (Rp)')
                                    ->required()
                                    ->readOnly()
                                    ->prefix('Rp')
                                    ->mask(RawJs::make('$money($input, \'.\', \',\')'))
                                    ->stripCharacters(['.', ','])
                                    ->dehydrateStateUsing(fn ($state) => (int) str_replace(['.', ','], '', $state))
                                    ->extraInputAttributes(['class' => 'text-lg font-bold text-primary-600']),
                                Textarea::make('admin_notes')
                                    ->label('Catatan Internal (Admin)')
                                    ->placeholder('Hanya bisa dilihat oleh admin...')
                                    ->rows(4),
                            ])
                    ]),
            ]);
    }

    public static function updateTotal($get, $set): void
    {
        $items = $get('items') ?? [];
        $subtotal = 0;
        
        foreach ($items as $item) {
            $itemSubtotal = is_numeric($item['subtotal'] ?? 0) ? ($item['subtotal'] ?? 0) : (int) str_replace(['.', ','], '', $item['subtotal'] ?? '0');
            $subtotal += (int) $itemSubtotal;
        }
        
        $shippingRaw = $get('shipping_cost') ?? 0;
        $shipping = is_numeric($shippingRaw) ? $shippingRaw : (int) str_replace(['.', ','], '', $shippingRaw);
        
        $discountRaw = $get('discount_amount') ?? 0;
        $discount = is_numeric($discountRaw) ? $discountRaw : (int) str_replace(['.', ','], '', $discountRaw);
        
        $total = $subtotal + $shipping - $discount;
        
        $set('subtotal', $subtotal);
        $set('total', $total);
    }
}
