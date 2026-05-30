<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['booking_id', 'seat_id', 'name', 'phone', 'id_number', 'ticket_code', 'boarded_at'])]
class Passenger extends Model
{
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }

    public function boardingScans(): HasMany
    {
        return $this->hasMany(BoardingScan::class);
    }

    protected function casts(): array
    {
        return ['boarded_at' => 'datetime'];
    }
}
