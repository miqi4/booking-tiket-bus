<?php

namespace App\Filament\Resources\BookingNotifications\Pages;

use App\Filament\Resources\BookingNotifications\BookingNotificationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBookingNotifications extends ListRecords
{
    protected static string $resource = BookingNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
