<?php

namespace App\Filament\Resources\ConsultationLogs\Schemas;

use Filament\Schemas\Components\KeyValue;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
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
                        Text::make('session_id')
                            ->label('Session ID'),
                        Text::make('animal_type')
                            ->label('Animal Type')
                            ->badge(),
                        Text::make('ip_address')
                            ->label('IP Address'),
                        Text::make('created_at')
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
                        Text::make('recommended_products')
                            ->label('Products')
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->default('No products recommended'),
                    ]),
            ]);
    }
}
