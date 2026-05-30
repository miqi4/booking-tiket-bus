<?php

namespace App\Filament\Resources\BoardingScans\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BoardingScanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('booking_id')
                    ->relationship('booking', 'id')
                    ->required(),
                Select::make('passenger_id')
                    ->relationship('passenger', 'name'),
                TextInput::make('scanned_by')
                    ->numeric(),
                TextInput::make('qr_payload')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('valid'),
                DateTimePicker::make('scanned_at'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
