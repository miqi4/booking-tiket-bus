<?php

namespace App\Filament\Resources\Seats\Pages;

use App\Filament\Resources\Seats\SeatResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSeat extends ViewRecord
{
    protected static string $resource = SeatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
