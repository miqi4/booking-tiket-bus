<?php

namespace App\Filament\Resources\Buses\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->label('Nama Armada')->required(),
                TextInput::make('plate_number')->label('Nomor Polisi')->required(),
                TextInput::make('capacity')->label('Kapasitas')->required()->numeric()->minValue(1),
                Select::make('seat_layout')
                    ->label('Layout Kursi')
                    ->options(['2-2' => '2-2 Standard', '2-1' => '2-1 Semi VIP', '1-1' => '1-1 VIP'])
                    ->default('2-2')
                    ->required(),
                Select::make('seat_type')
                    ->label('Tipe Kursi')
                    ->options(['standard' => 'Standard', 'executive' => 'Executive', 'sleeper' => 'Sleeper'])
                    ->default('standard')
                    ->required(),
                Select::make('status')
                    ->options(['active' => 'Aktif', 'maintenance' => 'Perawatan', 'inactive' => 'Nonaktif'])
                    ->default('active')
                    ->required(),
                Textarea::make('description')->label('Catatan')->columnSpanFull(),
            ]);
    }
}
