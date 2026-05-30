<?php

namespace App\Filament\Resources\BookingNotifications\Pages;

use App\Filament\Resources\BookingNotifications\BookingNotificationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBookingNotification extends EditRecord
{
    protected static string $resource = BookingNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
