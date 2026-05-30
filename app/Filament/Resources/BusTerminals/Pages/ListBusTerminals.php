<?php

namespace App\Filament\Resources\BusTerminals\Pages;

use App\Filament\Resources\BusTerminals\BusTerminalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBusTerminals extends ListRecords
{
    protected static string $resource = BusTerminalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
