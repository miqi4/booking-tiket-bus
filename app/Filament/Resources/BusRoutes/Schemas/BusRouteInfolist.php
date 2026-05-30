<?php

namespace App\Filament\Resources\BusRoutes\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BusRouteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('originCity.name')
                    ->label('Origin city'),
                TextEntry::make('destinationCity.name')
                    ->label('Destination city'),
                TextEntry::make('originTerminal.name')
                    ->label('Origin terminal')
                    ->placeholder('-'),
                TextEntry::make('destinationTerminal.name')
                    ->label('Destination terminal')
                    ->placeholder('-'),
                TextEntry::make('distance_km')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('duration_minutes')
                    ->numeric()
                    ->placeholder('-'),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
