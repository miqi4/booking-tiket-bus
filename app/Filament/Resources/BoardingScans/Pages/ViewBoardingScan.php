<?php

namespace App\Filament\Resources\BoardingScans\Pages;

use App\Filament\Resources\BoardingScans\BoardingScanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBoardingScan extends ViewRecord
{
    protected static string $resource = BoardingScanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
