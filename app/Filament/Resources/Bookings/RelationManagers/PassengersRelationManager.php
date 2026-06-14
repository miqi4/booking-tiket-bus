<?php

namespace App\Filament\Resources\Bookings\RelationManagers;

use App\Models\Booking;
use App\Models\Passenger;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PassengersRelationManager extends RelationManager
{
    protected static string $relationship = 'passengers';
    
    protected static ?string $title = 'Data Penumpang';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('seat_number')
                    ->label('Nomor Kursi')
                    ->required()
                    ->native(false)
                    ->searchable()
                    ->options(function (RelationManager $livewire, ?Passenger $record): array {
                        $bookingId = $livewire->getOwnerRecord()->id;
                        $booking = Booking::with('schedule.bus')->find($bookingId);
                        $bus = $booking?->schedule?->bus;

                        if (! $bus?->seats) {
                            return [];
                        }

                        // Kursi yang sudah terpakai di booking ini (kecuali record saat ini)
                        $takenSeats = Passenger::where('booking_id', $bookingId)
                            ->when($record?->id, fn ($q) => $q->where('id', '!=', $record->id))
                            ->pluck('seat_number')
                            ->all();

                        return $bus->seats
                            ->filter(fn ($s) => $s['type'] === 'passenger'
                                && $s['is_active']
                                && ! in_array($s['seat_number'], $takenSeats)
                            )
                            ->sortBy([['row', 'asc'], ['column', 'asc']])
                            ->mapWithKeys(fn ($s) => [
                                $s['seat_number'] => "Kursi {$s['seat_number']} (baris {$s['row']}, kolom {$s['column']})"
                            ])
                            ->all();
                    }),

                TextInput::make('name')
                    ->label('Nama Penumpang')
                    ->required(),

                TextInput::make('phone')
                    ->label('No. HP')
                    ->tel(),

                TextInput::make('id_number')
                    ->label('No. Identitas'),

                TextInput::make('ticket_code')
                    ->label('Kode Tiket')
                    ->required(),

                DateTimePicker::make('boarded_at')
                    ->label('Waktu Boarding')
                    ->placeholder('Belum boarding'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('seat_number')
                    ->label('Kursi')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('name')
                    ->label('Nama'),
                TextColumn::make('phone')
                    ->label('No. HP')
                    ->placeholder('-'),
                TextColumn::make('ticket_code')
                    ->label('Kode Tiket')
                    ->copyable(),
                TextColumn::make('boarded_at')
                    ->label('Waktu Boarding')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum boarding'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make(),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
