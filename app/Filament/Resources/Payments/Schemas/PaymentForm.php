<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
                TextInput::make('amount')->label('Jumlah')->required()->numeric()->prefix('Rp'),
                Select::make('method')
                    ->label('Metode')
                    ->options(['qris' => 'QRIS'])
                    ->default('qris')
                    ->required(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'settlement' => 'Berhasil (Settlement)',
                        'failed' => 'Gagal',
                        'expired' => 'Kedaluwarsa'
                    ])
                    ->default('pending')
                    ->required(),
                FileUpload::make('payment_proof')
                    ->label('Bukti Pembayaran')
                    ->image()
                    ->directory('payment-proofs')
                    ->columnSpanFull(),
                DateTimePicker::make('paid_at')->label('Dibayar Pada'),
            ]);
    }
}
