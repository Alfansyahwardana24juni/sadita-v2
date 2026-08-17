<?php

namespace App\Filament\Resources\ConsultationLogs\Schemas;

use Filament\Schemas\Components\KeyValue;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\TextEntry;
use Filament\Schemas\Schema;

class ConsultationLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Session Information')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('session_id')
                            ->label('Session ID'),
                        TextEntry::make('animal_type')
                            ->label('Animal Type')
                            ->badge(),
                        TextEntry::make('ip_address')
                            ->label('IP Address'),
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime(),
                    ]),

                Section::make('Conversation History')
                    ->schema([
                        KeyValue::make('messages')
                            ->label('Messages')
                            ->keyLabel('Role')
                            ->valueLabel('Content'),
                    ]),

                Section::make('Recommended Products')
                    ->schema([
                        TextEntry::make('recommended_products')
                            ->label('Products')
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->default('No products recommended'),
                    ]),
            ]);
    }
}
