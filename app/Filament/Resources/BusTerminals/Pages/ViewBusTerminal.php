<?php

namespace App\Filament\Resources\BusTerminals\Pages;

use App\Filament\Resources\BusTerminals\BusTerminalResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBusTerminal extends ViewRecord
{
    protected static string $resource = BusTerminalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
