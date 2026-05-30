<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['origin_city_id', 'destination_city_id', 'origin_terminal_id', 'destination_terminal_id', 'distance_km', 'duration_minutes', 'is_active'])]
class BusRoute extends Model
{
    public function originCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'origin_city_id');
    }

    public function destinationCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'destination_city_id');
    }

    public function originTerminal(): BelongsTo
    {
        return $this->belongsTo(BusTerminal::class, 'origin_terminal_id');
    }

    public function destinationTerminal(): BelongsTo
    {
        return $this->belongsTo(BusTerminal::class, 'destination_terminal_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function name(): Attribute
    {
        return Attribute::get(fn (): string => trim(($this->originCity?->name ?? 'Asal').' - '.($this->destinationCity?->name ?? 'Tujuan')));
    }

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
