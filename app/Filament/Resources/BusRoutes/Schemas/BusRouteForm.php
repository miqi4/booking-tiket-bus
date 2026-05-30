<?php

namespace App\Filament\Resources\BusRoutes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BusRouteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('origin_city_id')
                    ->relationship('originCity', 'name')
                    ->required(),
                Select::make('destination_city_id')
                    ->relationship('destinationCity', 'name')
                    ->required(),
                Select::make('origin_terminal_id')
                    ->relationship('originTerminal', 'name'),
                Select::make('destination_terminal_id')
                    ->relationship('destinationTerminal', 'name'),
                TextInput::make('distance_km')
                    ->numeric(),
                TextInput::make('duration_minutes')
                    ->numeric(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
