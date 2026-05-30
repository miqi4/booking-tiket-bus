<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'province', 'is_active'])]
class City extends Model
{
    public function terminals(): HasMany
    {
        return $this->hasMany(BusTerminal::class);
    }

    public function originRoutes(): HasMany
    {
        return $this->hasMany(BusRoute::class, 'origin_city_id');
    }

    public function destinationRoutes(): HasMany
    {
        return $this->hasMany(BusRoute::class, 'destination_city_id');
    }

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
