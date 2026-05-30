<?php

namespace App\Filament\Resources\BusTerminals\Pages;

use App\Filament\Resources\BusTerminals\BusTerminalResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBusTerminal extends EditRecord
{
    protected static string $resource = BusTerminalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
