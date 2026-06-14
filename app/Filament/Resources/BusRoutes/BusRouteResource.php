<?php

namespace App\Filament\Resources\BusRoutes;

use App\Filament\Resources\BusRoutes\Pages\CreateBusRoute;
use App\Filament\Resources\BusRoutes\Pages\EditBusRoute;
use App\Filament\Resources\BusRoutes\Pages\ListBusRoutes;
use App\Filament\Resources\BusRoutes\Pages\ViewBusRoute;
use App\Filament\Resources\BusRoutes\Schemas\BusRouteForm;
use App\Filament\Resources\BusRoutes\Schemas\BusRouteInfolist;
use App\Filament\Resources\BusRoutes\Tables\BusRoutesTable;
use App\Models\BusRoute;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BusRouteResource extends Resource
{
    protected static ?string $model = BusRoute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Rute';

    public static function form(Schema $schema): Schema
    {
        return BusRouteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BusRouteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BusRoutesTable::configure($table);
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
            'index' => ListBusRoutes::route('/'),
            'create' => CreateBusRoute::route('/create'),
            'view' => ViewBusRoute::route('/{record}'),
            'edit' => EditBusRoute::route('/{record}/edit'),
        ];
    }
}
