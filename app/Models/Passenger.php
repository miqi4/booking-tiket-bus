<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['booking_id', 'seat_number', 'name', 'phone', 'id_number', 'ticket_code', 'boarded_at'])]
class Passenger extends Model
{
    // ─── Relationships ────────────────────────────────────────────────────────

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function boardingScans(): HasMany
    {
        return $this->hasMany(BoardingScan::class);
    }

    // ─── Casts ────────────────────────────────────────────────────────────────

    protected function casts(): array
    {
        return ['boarded_at' => 'datetime'];
    }
}
