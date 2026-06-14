<?php

namespace App\Filament\Resources\Passengers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PassengersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking.booking_code')
                    ->label('Kode Booking')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('seat_number')
                    ->label('Nomor Kursi')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama Penumpang')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('No. HP')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('ticket_code')
                    ->label('Kode Tiket')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),
                TextColumn::make('boarded_at')
                    ->label('Waktu Boarding')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('Belum boarding'),
                TextColumn::make('created_at')
                    ->label('Dipesan')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
