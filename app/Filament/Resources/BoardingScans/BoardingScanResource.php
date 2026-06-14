<?php

namespace App\Filament\Resources\BoardingScans;

use App\Filament\Resources\BoardingScans\Pages\CreateBoardingScan;
use App\Filament\Resources\BoardingScans\Pages\EditBoardingScan;
use App\Filament\Resources\BoardingScans\Pages\ListBoardingScans;
use App\Filament\Resources\BoardingScans\Pages\ViewBoardingScan;
use App\Filament\Resources\BoardingScans\Schemas\BoardingScanForm;
use App\Filament\Resources\BoardingScans\Schemas\BoardingScanInfolist;
use App\Filament\Resources\BoardingScans\Tables\BoardingScansTable;
use App\Models\BoardingScan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BoardingScanResource extends Resource
{
    protected static ?string $model = BoardingScan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQrCode;

    protected static string|\UnitEnum|null $navigationGroup = 'Operasional';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Scan Boarding';

    public static function form(Schema $schema): Schema
    {
        return BoardingScanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BoardingScanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BoardingScansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBoardingScans::route('/'),
            'create' => CreateBoardingScan::route('/create'),
            'view' => ViewBoardingScan::route('/{record}'),
            'edit' => EditBoardingScan::route('/{record}/edit'),
        ];
    }
}
