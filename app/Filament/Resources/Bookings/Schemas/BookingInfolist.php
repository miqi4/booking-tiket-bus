<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ── Info Booking ──────────────────────────────────────────────
                Section::make('Informasi Booking')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('booking_code')
                            ->label('Kode Booking')
                            ->copyable()
                            ->weight('bold'),

                        TextEntry::make('user.name')
                            ->label('Pemesan')
                            ->placeholder('(tamu)'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state) => match ($state) {
                                'confirmed' => 'success',
                                'pending'   => 'warning',
                                'cancelled' => 'danger',
                                default     => 'gray',
                            }),

                        TextEntry::make('payment_status')
                            ->label('Status Pembayaran')
                            ->badge()
                            ->color(fn (string $state) => match ($state) {
                                'paid'       => 'success',
                                'unpaid'     => 'danger',
                                'settlement' => 'success',
                                default      => 'gray',
                            }),

                        TextEntry::make('total_price')
                            ->label('Total Harga')
                            ->money('IDR'),

                        TextEntry::make('expired_at')
                            ->label('Kadaluarsa')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),

                        TextEntry::make('confirmed_at')
                            ->label('Dikonfirmasi')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),

                        TextEntry::make('cancelled_at')
                            ->label('Dibatalkan')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),

                        TextEntry::make('notes')
                            ->label('Catatan')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),

                // ── Info Jadwal ───────────────────────────────────────────────
                Section::make('Jadwal Perjalanan')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('schedule.busRoute.originCity.name')
                            ->label('Asal'),

                        TextEntry::make('schedule.busRoute.destinationCity.name')
                            ->label('Tujuan'),

                        TextEntry::make('schedule.departure_at')
                            ->label('Keberangkatan')
                            ->dateTime('d M Y H:i'),

                        TextEntry::make('schedule.bus.name')
                            ->label('Armada'),
                    ]),


            ]);
    }
}
