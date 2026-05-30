<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')->label('Pemesan')->relationship('user', 'name')->searchable()->preload(),
                Select::make('schedule_id')
                    ->label('Jadwal')
                    ->relationship('schedule', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record): string => $record->route_label.' - '.$record->departure_at?->format('d M Y H:i'))
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('booking_code')->label('Kode Booking')->required(),
                TextInput::make('total_price')->label('Total')->required()->numeric()->prefix('Rp'),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled', 'expired' => 'Expired'])
                    ->default('pending')
                    ->required(),
                Select::make('payment_status')
                    ->label('Status Bayar')
                    ->options(['unpaid' => 'Belum Bayar', 'paid' => 'Lunas', 'failed' => 'Gagal', 'refunded' => 'Refund'])
                    ->default('unpaid')
                    ->required(),
                DateTimePicker::make('expired_at')->label('Expired'),
                DateTimePicker::make('confirmed_at')->label('Confirmed'),
                DateTimePicker::make('cancelled_at')->label('Cancelled'),
                Textarea::make('notes')->label('Catatan')->columnSpanFull(),
            ]);
    }
}
