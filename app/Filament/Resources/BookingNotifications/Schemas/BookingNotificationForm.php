<?php

namespace App\Filament\Resources\BookingNotifications\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BookingNotificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('booking_id')
                    ->relationship('booking', 'id')
                    ->required(),
                TextInput::make('type')
                    ->required(),
                TextInput::make('channel')
                    ->required()
                    ->default('whatsapp'),
                TextInput::make('recipient'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                Textarea::make('message')
                    ->columnSpanFull(),
                Textarea::make('response')
                    ->columnSpanFull(),
                DateTimePicker::make('sent_at'),
            ]);
    }
}
