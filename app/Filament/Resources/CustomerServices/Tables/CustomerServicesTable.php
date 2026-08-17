<?php

namespace App\Filament\Resources\CustomerServices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CustomerServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label('Foto')
                    ->circular()
                    ->disk('public')
                    ->defaultImageUrl(fn ($record) => self::getAvatarUrl($record->name))
                    ->size(48),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Jabatan')
                    ->searchable(),
                TextColumn::make('city')
                    ->label('Kota')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'online'  => 'success',
                        'busy'    => 'warning',
                        'offline' => 'gray',
                        default   => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'online'  => 'Online',
                        'busy'    => 'Sibuk',
                        'offline' => 'Offline',
                        default   => $state,
                    }),
                TextColumn::make('whatsapp_number')
                    ->label('WhatsApp')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('working_hours')
                    ->label('Jam Kerja')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                SelectFilter::make('customer_service_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'online'  => 'Online',
                        'busy'    => 'Sedang Sibuk',
                        'offline' => 'Offline',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function getAvatarUrl(string $name): string
    {
        $initial = mb_strtoupper(mb_substr(trim($name), 0, 1));
        $colors  = ['610000', '920703', '8b0000', '7f1d1d', '410000'];
        $index   = max(0, ord($initial) - ord('A')) % count($colors);
        $color   = $colors[$index];
        return 'https://ui-avatars.com/api/?name=' . urlencode($initial)
            . '&background=' . $color
            . '&color=ffffff&size=96&bold=true&font-size=0.5&rounded=true';
    }
}
