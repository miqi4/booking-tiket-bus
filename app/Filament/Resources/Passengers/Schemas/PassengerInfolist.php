<?php

namespace App\Filament\Resources\Passengers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PassengerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('booking.booking_code')
                    ->label('Kode Booking'),
                TextEntry::make('seat_number')
                    ->label('Nomor Kursi'),
                TextEntry::make('name'),
                TextEntry::make('phone')
                    ->placeholder('-'),
                TextEntry::make('id_number')
                    ->placeholder('-'),
                TextEntry::make('ticket_code'),
                TextEntry::make('boarded_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
