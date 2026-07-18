<?php

namespace App\Filament\Resources\ConsultationLogs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ConsultationLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('animal_type')
                    ->label('Jenis Ternak')
                    ->badge()
                    ->color('primary')
                    ->default('Tidak diketahui'),
                TextColumn::make('messages')
                    ->label('Jumlah Pesan')
                    ->formatStateUsing(fn ($state) => count($state ?? []) . ' pesan'),
                TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('animal_type')
                    ->label('Jenis Ternak')
                    ->options([
                        'Ayam' => 'Ayam',
                        'Sapi' => 'Sapi',
                        'Kambing' => 'Kambing',
                        'Ikan' => 'Ikan',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([25, 50, 100]);
    }
}
