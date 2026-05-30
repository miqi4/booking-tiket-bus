<?php

namespace Database\Seeders;

use App\Models\Booking;
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
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Users
        $this->seedUsers();

        // 2. Cities & Terminals
        $cities = $this->seedCitiesAndTerminals();

        // 3. Buses
        $buses = $this->seedBuses();

        // 4. Routes & Schedules
        $this->seedRoutesAndSchedules($cities, $buses);

        // 5. Sample Booking for Testing
        $this->seedSampleBooking($passengerUser ?? User::where('role', 'passenger')->first(), $buses[0]);
    }

    private function seedUsers(): void
    {
        User::query()->create([
            'name' => 'Admin AKAS',
            'email' => 'admin@akas.test',
            'phone' => '081234567890',
            'role' => 'admin',
            'email_verified_at' => now(),
            'password' => 'password',
        ]);

        User::query()->create([
            'name' => 'Operator Terminal',
            'email' => 'operator@akas.test',
            'phone' => '081234567891',
            'role' => 'operator',
            'email_verified_at' => now(),
            'password' => 'password',
        ]);

        User::query()->create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '081298765432',
            'role' => 'passenger',
            'email_verified_at' => now(),
            'password' => 'password',
        ]);
    }

    private function seedCitiesAndTerminals(): array
    {
        $citiesData = [
            ['name' => 'Surabaya', 'province' => 'Jawa Timur', 'terminals' => ['Terminal Purabaya (Bungurasih)']],
            ['name' => 'Malang', 'province' => 'Jawa Timur', 'terminals' => ['Terminal Arjosari']],
            ['name' => 'Probolinggo', 'province' => 'Jawa Timur', 'terminals' => ['Terminal Bayuangga']],
            ['name' => 'Jember', 'province' => 'Jawa Timur', 'terminals' => ['Terminal Tawang Alun']],
            ['name' => 'Banyuwangi', 'province' => 'Jawa Timur', 'terminals' => ['Terminal Sritanjung', 'Terminal Karangente']],
            ['name' => 'Sumenep', 'province' => 'Jawa Timur', 'terminals' => ['Terminal Arya Wiraraja']],
            ['name' => 'Situbondo', 'province' => 'Jawa Timur', 'terminals' => ['Terminal Situbondo']],
        ];

        $cities = [];
        foreach ($citiesData as $data) {
            $city = City::query()->create([
                'name' => $data['name'],
                'province' => $data['province'],
                'is_active' => true,
            ]);
            
            $cityTerminals = [];
            foreach ($data['terminals'] as $terminalName) {
                $cityTerminals[] = BusTerminal::query()->create([
                    'city_id' => $city->id,
                    'name' => $terminalName,
                    'is_active' => true,
                ]);
            }
            $city->setAttribute('seeded_terminals', $cityTerminals);
            $cities[$data['name']] = $city;
        }
        return $cities;
    }

    private function seedBuses(): array
    {
        $busesData = [
            ['name' => 'AKAS Mila Executive 01', 'type' => 'executive', 'cap' => 28, 'layout' => '2-2'],
            ['name' => 'AKAS Mila Executive 02', 'type' => 'executive', 'cap' => 28, 'layout' => '2-2'],
            ['name' => 'AKAS Asri Ekonomi 05', 'type' => 'economy', 'cap' => 45, 'layout' => '2-3'],
            ['name' => 'AKAS Asri Ekonomi 06', 'type' => 'economy', 'cap' => 45, 'layout' => '2-3'],
            ['name' => 'AKAS NR Luxury', 'type' => 'executive', 'cap' => 22, 'layout' => '2-1'],
        ];

        $buses = [];
        foreach ($busesData as $data) {
            $bus = Bus::query()->create([
                'name' => $data['name'],
                'plate_number' => 'N ' . rand(1000, 9999) . ' ' . Str::upper(Str::random(2)),
                'capacity' => $data['cap'],
                'seat_layout' => $data['layout'],
                'seat_type' => $data['type'],
                'status' => 'active',
            ]);

            // Create Seats
            $cols = $data['layout'] === '2-3' ? 5 : ($data['layout'] === '2-1' ? 3 : 4);
            $rows = ceil($data['cap'] / $cols);
            for ($r = 1; $r <= $rows; $r++) {
                for ($c = 1; $c <= $cols; $c++) {
                    if (($r - 1) * $cols + $c <= $data['cap']) {
                        Seat::query()->create([
                            'bus_id' => $bus->id,
                            'seat_number' => chr(64 + $c) . $r,
                            'row' => $r,
                            'column' => $c,
                        ]);
                    }
                }
            }
            $buses[] = $bus;
        }
        return $buses;
    }

    private function seedRoutesAndSchedules(array $cities, array $buses): void
    {
        $routesData = [
            // Surabaya - Jember
            ['from' => 'Surabaya', 'to' => 'Jember', 'dist' => 200, 'dur' => 300, 'price' => 150000, 'economy_price' => 85000],
            // Surabaya - Banyuwangi
            ['from' => 'Surabaya', 'to' => 'Banyuwangi', 'dist' => 290, 'dur' => 450, 'price' => 200000, 'economy_price' => 110000],
            // Malang - Jember
            ['from' => 'Malang', 'to' => 'Jember', 'dist' => 180, 'dur' => 240, 'price' => 135000, 'economy_price' => 75000],
            // Surabaya - Sumenep (Madura)
            ['from' => 'Surabaya', 'to' => 'Sumenep', 'dist' => 170, 'dur' => 240, 'price' => 120000, 'economy_price' => 70000],
        ];

        foreach ($routesData as $r) {
            $route = BusRoute::query()->create([
                'origin_city_id' => $cities[$r['from']]->id,
                'destination_city_id' => $cities[$r['to']]->id,
                'origin_terminal_id' => $cities[$r['from']]->seeded_terminals[0]->id,
                'destination_terminal_id' => $cities[$r['to']]->seeded_terminals[0]->id,
                'distance_km' => $r['dist'],
                'duration_minutes' => $r['dur'],
                'is_active' => true,
            ]);

            // Create 3 schedules for each route for today, tomorrow, and day after
            for ($i = 0; $i < 3; $i++) {
                $date = now()->addDays($i);
                
                // Pagi (Executive)
                Schedule::query()->create([
                    'bus_route_id' => $route->id,
                    'bus_id' => $buses[rand(0, 1)]->id,
                    'departure_at' => (clone $date)->setTime(8, 0),
                    'arrival_est' => (clone $date)->setTime(8, 0)->addMinutes($r['dur']),
                    'price' => $r['price'],
                    'status' => 'active',
                    'available_seats' => 28,
                ]);

                // Siang (Economy)
                Schedule::query()->create([
                    'bus_route_id' => $route->id,
                    'bus_id' => $buses[rand(2, 3)]->id,
                    'departure_at' => (clone $date)->setTime(13, 30),
                    'arrival_est' => (clone $date)->setTime(13, 30)->addMinutes($r['dur']),
                    'price' => $r['economy_price'],
                    'status' => 'active',
                    'available_seats' => 45,
                ]);

                // Malam (Executive Luxury)
                Schedule::query()->create([
                    'bus_route_id' => $route->id,
                    'bus_id' => $buses[4]->id, // Luxury
                    'departure_at' => (clone $date)->setTime(21, 0),
                    'arrival_est' => (clone $date)->setTime(21, 0)->addMinutes($r['dur']),
                    'price' => $r['price'] + 50000,
                    'status' => 'active',
                    'available_seats' => 22,
                ]);
            }
        }
    }

    private function seedSampleBooking(User $user, Bus $bus): void
    {
        $schedule = Schedule::query()->where('bus_id', $bus->id)->first();
        if (!$schedule) return;

        $booking = Booking::query()->create([
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
            'booking_code' => 'AKAS-' . now()->format('Ym') . '-' . Str::upper(Str::random(5)),
            'total_price' => $schedule->price,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'expired_at' => now()->addMinutes(30),
            'confirmed_at' => now(),
        ]);

        $seat = Seat::query()->where('bus_id', $bus->id)->first();
        Passenger::query()->create([
            'booking_id' => $booking->id,
            'seat_id' => $seat->id,
            'name' => $user->name,
            'phone' => $user->phone,
            'ticket_code' => 'TKT-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6)),
        ]);

        Payment::query()->create([
            'booking_id' => $booking->id,
            'amount' => $schedule->price,
            'method' => 'qris',
            'status' => 'settlement',
            'paid_at' => now(),
        ]);
    }
}
