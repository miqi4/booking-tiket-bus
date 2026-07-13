<?php

namespace App\Filament\Resources\Schedules\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('bus_route_id')
                    ->label('Rute')
                    ->relationship('busRoute', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record): string => $record->name)
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('bus_id')
                    ->label('Armada')
                    ->relationship('bus', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($set, $state) {
                        if ($state) {
                            $bus = \App\Models\Bus::find($state);
                            if ($bus) {
                                $set('available_seats', $bus->capacity);
                            }
                        }
                    }),
                DateTimePicker::make('departure_at')->label('Berangkat')->seconds(false)->required(),
                DateTimePicker::make('arrival_est')->label('Estimasi Tiba')->seconds(false),
                TextInput::make('price')->label('Harga')->required()->numeric()->prefix('Rp'),
                Select::make('status')
                    ->options(['active' => 'Aktif', 'cancelled' => 'Dibatalkan', 'completed' => 'Selesai'])
                    ->default('active')
                    ->required(),
                TextInput::make('available_seats')->label('Kursi Tersedia')->numeric(),
                Textarea::make('notes')->label('Catatan')->columnSpanFull(),
            ]);
    }
}
