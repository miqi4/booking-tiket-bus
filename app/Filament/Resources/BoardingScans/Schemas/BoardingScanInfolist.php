<?php

namespace App\Filament\Resources\BoardingScans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BoardingScanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('booking.id')
                    ->label('Booking'),
                TextEntry::make('passenger.name')
                    ->label('Passenger')
                    ->placeholder('-'),
                TextEntry::make('scanned_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('qr_payload'),
                TextEntry::make('status'),
                TextEntry::make('scanned_at')
                    ->dateTime()
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
