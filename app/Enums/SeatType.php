<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum SeatType: string implements HasColor, HasIcon, HasLabel
{
    case Passenger = 'passenger';
    case Driver    = 'driver';
    case Empty     = 'empty';

    public function getLabel(): string
    {
        return match ($this) {
            self::Passenger => 'Penumpang',
            self::Driver    => 'Sopir',
            self::Empty     => 'Kosong',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Passenger => 'success',
            self::Driver    => 'warning',
            self::Empty     => 'gray',
        };
    }

    public function getIcon(): string|null
    {
        return match ($this) {
            self::Passenger => 'heroicon-o-user',
            self::Driver    => 'heroicon-o-truck',
            self::Empty     => 'heroicon-o-x-circle',
        };
    }
}
