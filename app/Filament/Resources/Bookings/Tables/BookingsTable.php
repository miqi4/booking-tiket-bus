<?php

namespace App\Filament\Resources\Bookings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                // Kode booking — identitas utama
                TextColumn::make('booking_code')
                    ->label('Kode Booking')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                // Pemesan
                TextColumn::make('user.name')
                    ->label('Pemesan')
                    ->searchable()
                    ->placeholder('(tamu)'),

                // Rute perjalanan
                TextColumn::make('schedule.busRoute.originCity.name')
                    ->label('Asal')
                    ->searchable(),

                TextColumn::make('schedule.busRoute.destinationCity.name')
                    ->label('Tujuan')
                    ->searchable(),

                // Keberangkatan
                TextColumn::make('schedule.departure_at')
                    ->label('Keberangkatan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                // Kursi yang dipesan — ditampilkan sebagai list badge per penumpang
                TextColumn::make('passengers.seat_number')
                    ->label('Kursi')
                    ->badge()
                    ->color('primary')
                    ->separator(','),

                // Harga total
                TextColumn::make('total_price')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                // Status booking
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'success',
                        'pending'   => 'warning',
                        'cancelled' => 'danger',
                        default     => 'gray',
                    }),

                // Status pembayaran
                TextColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid', 'settlement' => 'success',
                        'unpaid'             => 'danger',
                        'partially_paid'     => 'warning',
                        default              => 'gray',
                    }),

                // Tanggal booking dibuat
                TextColumn::make('created_at')
                    ->label('Dipesan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('confirmed_at')
                    ->label('Dikonfirmasi')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('cancelled_at')
                    ->label('Dibatalkan')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'   => 'Pending',
                        'confirmed' => 'Dikonfirmasi',
                        'cancelled' => 'Dibatalkan',
                    ]),

                SelectFilter::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'unpaid'     => 'Belum Bayar',
                        'paid'       => 'Sudah Bayar',
                        'settlement' => 'Settlement',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
