<?php

namespace App\Filament\Resources\Passengers;

use App\Filament\Resources\Passengers\Pages\CreatePassenger;
use App\Filament\Resources\Passengers\Pages\EditPassenger;
use App\Filament\Resources\Passengers\Pages\ListPassengers;
use App\Filament\Resources\Passengers\Pages\ViewPassenger;
use App\Filament\Resources\Passengers\Schemas\PassengerForm;
use App\Filament\Resources\Passengers\Schemas\PassengerInfolist;
use App\Filament\Resources\Passengers\Tables\PassengersTable;
use App\Models\Passenger;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PassengerResource extends Resource
{
    protected static ?string $model = Passenger::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|\UnitEnum|null $navigationGroup = 'Pemesanan';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Penumpang';

    public static function form(Schema $schema): Schema
    {
        return PassengerForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PassengerInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PassengersTable::configure($table);
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
            'index' => ListPassengers::route('/'),
            'create' => CreatePassenger::route('/create'),
            'view' => ViewPassenger::route('/{record}'),
            'edit' => EditPassenger::route('/{record}/edit'),
        ];
    }
}
