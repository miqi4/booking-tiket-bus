<?php

namespace App\Filament\Resources\Passengers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PassengerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('booking_id')
                    ->relationship('booking', 'id')
                    ->required(),
                Select::make('seat_id')
                    ->relationship('seat', 'id')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('id_number'),
                TextInput::make('ticket_code')
                    ->required(),
                DateTimePicker::make('boarded_at'),
            ]);
    }
}
