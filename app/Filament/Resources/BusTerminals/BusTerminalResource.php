<?php

namespace App\Filament\Resources\BusTerminals;

use App\Filament\Resources\BusTerminals\Pages\CreateBusTerminal;
use App\Filament\Resources\BusTerminals\Pages\EditBusTerminal;
use App\Filament\Resources\BusTerminals\Pages\ListBusTerminals;
use App\Filament\Resources\BusTerminals\Pages\ViewBusTerminal;
use App\Filament\Resources\BusTerminals\Schemas\BusTerminalForm;
use App\Filament\Resources\BusTerminals\Schemas\BusTerminalInfolist;
use App\Filament\Resources\BusTerminals\Tables\BusTerminalsTable;
use App\Models\BusTerminal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BusTerminalResource extends Resource
{
    protected static ?string $model = BusTerminal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Terminal';

    public static function form(Schema $schema): Schema
    {
        return BusTerminalForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BusTerminalInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BusTerminalsTable::configure($table);
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
            'index' => ListBusTerminals::route('/'),
            'create' => CreateBusTerminal::route('/create'),
            'view' => ViewBusTerminal::route('/{record}'),
            'edit' => EditBusTerminal::route('/{record}/edit'),
        ];
    }
}
