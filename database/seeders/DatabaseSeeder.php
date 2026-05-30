<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingNotification;
use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\BusTerminal;
use App\Models\City;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin AKAS',
            'email' => 'admin@akas.test',
            'phone' => '081234567890',
            'role' => 'admin',
            'email_verified_at' => now(),
            'password' => 'password',
        ]);

        $passenger = User::query()->create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '081298765432',
            'role' => 'passenger',
            'email_verified_at' => now(),
            'password' => 'password',
        ]);

        $probolinggo = City::query()->create(['name' => 'Probolinggo', 'province' => 'Jawa Timur']);
        $surabaya = City::query()->create(['name' => 'Surabaya', 'province' => 'Jawa Timur']);
        $malang = City::query()->create(['name' => 'Malang', 'province' => 'Jawa Timur']);

        $terminalBayuangga = BusTerminal::query()->create(['city_id' => $probolinggo->id, 'name' => 'Terminal Bayuangga']);
        $terminalPurabaya = BusTerminal::query()->create(['city_id' => $surabaya->id, 'name' => 'Terminal Purabaya']);
        BusTerminal::query()->create(['city_id' => $malang->id, 'name' => 'Terminal Arjosari']);

        $route = BusRoute::query()->create([
            'origin_city_id' => $probolinggo->id,
            'destination_city_id' => $surabaya->id,
            'origin_terminal_id' => $terminalBayuangga->id,
            'destination_terminal_id' => $terminalPurabaya->id,
            'distance_km' => 100,
            'duration_minutes' => 180,
        ]);

        $bus = Bus::query()->create([
            'name' => 'AKAS Executive 01',
            'plate_number' => 'N 1234 AK',
            'capacity' => 28,
            'seat_layout' => '2-2',
            'seat_type' => 'executive',
            'status' => 'active',
        ]);

        foreach (range(1, 7) as $row) {
            foreach (range(1, 4) as $column) {
                Seat::query()->create([
                    'bus_id' => $bus->id,
                    'seat_number' => chr(64 + $column).$row,
                    'row' => $row,
                    'column' => $column,
                ]);
            }
        }

        $schedule = Schedule::query()->create([
            'bus_route_id' => $route->id,
            'bus_id' => $bus->id,
            'departure_at' => now()->addDay()->setTime(8, 0),
            'arrival_est' => now()->addDay()->setTime(11, 0),
            'price' => 125000,
            'status' => 'active',
            'available_seats' => 27,
        ]);

        $booking = Booking::query()->create([
            'user_id' => $passenger->id,
            'schedule_id' => $schedule->id,
            'booking_code' => 'BIS-'.now()->format('Ym').'-00001',
            'total_price' => 125000,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'expired_at' => now()->addMinutes(30),
            'confirmed_at' => now(),
        ]);

        Passenger::query()->create([
            'booking_id' => $booking->id,
            'seat_id' => Seat::query()->where('bus_id', $bus->id)->where('seat_number', 'A1')->value('id'),
            'name' => 'Budi Santoso',
            'phone' => '081298765432',
            'id_number' => '3574010101900001',
            'ticket_code' => 'TKT-'.now()->format('Ymd').'-00001',
        ]);

        Payment::query()->create([
            'booking_id' => $booking->id,
            'midtrans_order_id' => 'MID-'.$booking->booking_code,
            'amount' => 125000,
            'method' => 'qris',
            'status' => 'settlement',
            'paid_at' => now(),
            'payload' => ['source' => 'seed'],
        ]);

        BookingNotification::query()->create([
            'booking_id' => $booking->id,
            'type' => 'ticket_issued',
            'channel' => 'whatsapp',
            'recipient' => '081298765432',
            'status' => 'sent',
            'message' => 'E-tiket AKAS berhasil diterbitkan.',
            'sent_at' => now(),
        ]);
    }
}
