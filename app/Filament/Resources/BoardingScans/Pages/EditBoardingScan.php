<?php

namespace App\Filament\Resources\BoardingScans\Pages;

use App\Filament\Resources\BoardingScans\BoardingScanResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBoardingScan extends EditRecord
{
    protected static string $resource = BoardingScanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
