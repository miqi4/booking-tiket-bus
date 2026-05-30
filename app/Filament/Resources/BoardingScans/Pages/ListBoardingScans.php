<?php

namespace App\Filament\Resources\BoardingScans\Pages;

use App\Filament\Resources\BoardingScans\BoardingScanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBoardingScans extends ListRecords
{
    protected static string $resource = BoardingScanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
