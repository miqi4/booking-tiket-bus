<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['bus_id', 'seat_number', 'row', 'column', 'type', 'is_active'])]
class Seat extends Model
{
    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(Passenger::class);
    }

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
