<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('booking_id')
                    ->label('Booking')
                    ->relationship('booking', 'booking_code')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('midtrans_order_id')->label('Midtrans Order ID')->required(),
                TextInput::make('snap_token')->label('Snap Token'),
                TextInput::make('amount')->label('Jumlah')->required()->numeric()->prefix('Rp'),
                Select::make('method')
                    ->label('Metode')
                    ->options(['qris' => 'QRIS', 'gopay' => 'GoPay', 'bank_transfer' => 'Bank Transfer', 'credit_card' => 'Credit Card']),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'capture' => 'Capture', 'settlement' => 'Settlement', 'deny' => 'Deny', 'expire' => 'Expire'])
                    ->default('pending')
                    ->required(),
                Textarea::make('payload')->label('Payload JSON')->columnSpanFull(),
                DateTimePicker::make('paid_at')->label('Dibayar Pada'),
            ]);
    }
}
