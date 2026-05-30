<?php

namespace App\Filament\Resources\BookingNotifications;

use App\Filament\Resources\BookingNotifications\Pages\CreateBookingNotification;
use App\Filament\Resources\BookingNotifications\Pages\EditBookingNotification;
use App\Filament\Resources\BookingNotifications\Pages\ListBookingNotifications;
use App\Filament\Resources\BookingNotifications\Pages\ViewBookingNotification;
use App\Filament\Resources\BookingNotifications\Schemas\BookingNotificationForm;
use App\Filament\Resources\BookingNotifications\Schemas\BookingNotificationInfolist;
use App\Filament\Resources\BookingNotifications\Tables\BookingNotificationsTable;
use App\Models\BookingNotification;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BookingNotificationResource extends Resource
{
    protected static ?string $model = BookingNotification::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return BookingNotificationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BookingNotificationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BookingNotificationsTable::configure($table);
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
            'index' => ListBookingNotifications::route('/'),
            'create' => CreateBookingNotification::route('/create'),
            'view' => ViewBookingNotification::route('/{record}'),
            'edit' => EditBookingNotification::route('/{record}/edit'),
        ];
    }
}
