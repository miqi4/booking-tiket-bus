<?php

namespace App\Filament\Resources\Schedules\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ScheduleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('busRoute.id')
                    ->label('Bus route'),
                TextEntry::make('bus.name')
                    ->label('Bus'),
                TextEntry::make('departure_at')
                    ->dateTime(),
                TextEntry::make('arrival_est')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('price')
                    ->money(),
                TextEntry::make('status'),
                TextEntry::make('available_seats')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
