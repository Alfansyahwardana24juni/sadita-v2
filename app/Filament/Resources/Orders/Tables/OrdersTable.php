<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('customer_name')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer_phone')
                    ->label('HP')
                    ->searchable(),
                TextColumn::make('warehouse.name')
                    ->label('Gudang')
                    ->default('-'),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'processing' => 'primary',
                        'shipped' => 'success',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'confirmed' => 'Dikonfirmasi',
                        'processing' => 'Diproses',
                        'shipped' => 'Dikirim',
                        'delivered' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => $state,
                    }),
                \Filament\Tables\Columns\IconColumn::make('cancel_requested')
                    ->label('Req Batal')
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->trueColor('danger')
                    ->falseIcon('')
                    ->tooltip(fn ($record) => $record->cancel_requested ? $record->cancel_reason : null),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu Konfirmasi',
                        'confirmed' => 'Dikonfirmasi',
                        'processing' => 'Diproses',
                        'shipped' => 'Dikirim',
                        'delivered' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
                SelectFilter::make('warehouse_id')
                    ->label('Gudang')
                    ->relationship('warehouse', 'name'),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
                EditAction::make()->label('Update Status'),
                \Filament\Actions\Action::make('approve_cancel')
                    ->label('Setujui Batal')
                    ->icon('heroicon-o-check-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Pembatalan')
                    ->modalDescription(fn (\App\Models\Order $record) => 'Pelanggan meminta pembatalan dengan alasan: ' . $record->cancel_reason . '. Apakah Anda yakin ingin membatalkan pesanan ini?')
                    ->visible(fn (\App\Models\Order $record) => $record->cancel_requested && $record->status !== 'cancelled')
                    ->action(function (\App\Models\Order $record) {
                        $record->update(['status' => 'cancelled', 'cancel_requested' => false]);
                        \Filament\Notifications\Notification::make()->title('Pesanan berhasil dibatalkan')->success()->send();
                    }),
                \Filament\Actions\Action::make('reject_cancel')
                    ->label('Tolak Batal')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (\App\Models\Order $record) => $record->cancel_requested && $record->status !== 'cancelled')
                    ->action(function (\App\Models\Order $record) {
                        $record->update(['cancel_requested' => false]);
                        \Filament\Notifications\Notification::make()->title('Permintaan batal ditolak')->success()->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
