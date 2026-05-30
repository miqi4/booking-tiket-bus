<?php

namespace App\Filament\Resources\BookingNotifications\Pages;

use App\Filament\Resources\BookingNotifications\BookingNotificationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBookingNotification extends ViewRecord
{
    protected static string $resource = BookingNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
