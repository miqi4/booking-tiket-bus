<?php

namespace App\Models;

use App\Enums\SeatType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

#[Fillable(['name', 'plate_number', 'capacity', 'seat_layout', 'seat_type', 'status', 'description', 'seats'])]
class Bus extends Model
{
    // ─── Relationships ────────────────────────────────────────────────────────

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Hanya kursi aktif bertipe passenger (untuk pemesanan tiket).
     */
    public function passengerSeats(): Collection
    {
        return ($this->seats ?? collect())
            ->filter(fn ($s) => $s['is_active'] && $s['type'] === SeatType::Passenger->value)
            ->sortBy([['row', 'asc'], ['column', 'asc']])
            ->values();
    }

    /**
     * Generate data kursi otomatis dari layout & kapasitas,
     * lalu simpan ke kolom seats.
     */
    public function generateSeats(): void
    {
        $totalColumns = match ($this->seat_layout) {
            '2-3'   => 5,
            '2-1', '1-2' => 3,
            default => 4, // '2-2'
        };

        $capacity  = $this->capacity;
        $seats     = [];
        $count     = 0;

        for ($row = 1; $row <= ceil($capacity / $totalColumns); $row++) {
            for ($col = 1; $col <= $totalColumns; $col++) {
                if ($count >= $capacity) break 2;

                $seats[] = [
                    'seat_number' => $row . chr(64 + $col),
                    'row'         => $row,
                    'column'      => $col,
                    'type'        => ($row === 1 && $col === 1)
                                        ? SeatType::Driver->value
                                        : SeatType::Passenger->value,
                    'is_active'   => true,
                ];
                $count++;
            }
        }

        $this->seats = $seats;
        $this->save();
    }

    // ─── Casts ────────────────────────────────────────────────────────────────

    protected function casts(): array
    {
        return [
            // seats disimpan sebagai JSON, dibaca sebagai Laravel Collection
            'seats' => AsCollection::class,
        ];
    }
}
