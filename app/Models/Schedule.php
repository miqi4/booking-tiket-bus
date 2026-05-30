<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['bus_route_id', 'bus_id', 'departure_at', 'arrival_est', 'price', 'status', 'available_seats', 'notes'])]
class Schedule extends Model
{
    public function busRoute(): BelongsTo
    {
        return $this->belongsTo(BusRoute::class);
    }

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function routeLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->busRoute?->name ?? '-');
    }

    protected function casts(): array
    {
        return [
            'departure_at' => 'datetime',
            'arrival_est' => 'datetime',
            'price' => 'decimal:2',
        ];
    }
}
