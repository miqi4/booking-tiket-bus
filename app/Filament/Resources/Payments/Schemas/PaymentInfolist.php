<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('booking.booking_code')
                    ->label('Kode Booking'),
                TextEntry::make('amount')
                    ->label('Jumlah')
                    ->money('IDR'),
                TextEntry::make('method')
                    ->label('Metode')
                    ->badge()
                    ->color('info'),
                TextEntry::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'settlement' => 'success',
                        'failed' => 'danger',
                        'expired' => 'gray',
                        default => 'primary',
                    }),
                ImageEntry::make('payment_proof')
                    ->label('Bukti Pembayaran')
                    ->columnSpanFull(),
                TextEntry::make('paid_at')
                    ->label('Dibayar Pada')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime(),
            ]);
    }
}
