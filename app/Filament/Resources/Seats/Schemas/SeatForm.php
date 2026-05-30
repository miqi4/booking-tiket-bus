<?php

namespace App\Filament\Resources\Seats\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SeatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('bus_id')->label('Armada')->relationship('bus', 'name')->searchable()->preload()->required(),
                TextInput::make('seat_number')->label('Nomor Kursi')->required(),
                TextInput::make('row')->label('Baris')->required()->numeric()->minValue(1),
                TextInput::make('column')->label('Kolom')->required()->numeric()->minValue(1),
                Select::make('type')
                    ->label('Tipe')
                    ->options(['passenger' => 'Penumpang', 'driver' => 'Sopir', 'empty' => 'Kosong'])
                    ->default('passenger')
                    ->required(),
                Toggle::make('is_active')->label('Aktif')->default(true)->required(),
            ]);
    }
}
