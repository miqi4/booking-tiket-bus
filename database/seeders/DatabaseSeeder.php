<?php

namespace Database\Seeders;

use App\Enums\SeatType;
use App\Models\Booking;
use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\BusTerminal;
use App\Models\City;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\Schedule;
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

        // 3. Buses (termasuk data kursi di dalamnya)
        $buses = $this->seedBuses();

        // 4. Routes & Schedules
        $this->seedRoutesAndSchedules($cities, $buses);

        // 5. Sample Booking untuk Testing
        $this->seedSampleBooking(User::where('role', 'passenger')->first(), $buses[0]);
    }

    private function seedUsers(): void
    {
        User::query()->create([
            'name'              => 'Admin AKAS',
            'email'             => 'admin@akas.test',
            'phone'             => '081234567890',
            'role'              => 'admin',
            'email_verified_at' => now(),
            'password'          => 'password',
        ]);

        User::query()->create([
            'name'              => 'Operator Terminal',
            'email'             => 'operator@akas.test',
            'phone'             => '081234567891',
            'role'              => 'operator',
            'email_verified_at' => now(),
            'password'          => 'password',
        ]);

        User::query()->create([
            'name'              => 'Budi Santoso',
            'email'             => 'budi@example.com',
            'phone'             => '081298765432',
            'role'              => 'passenger',
            'email_verified_at' => now(),
            'password'          => 'password',
        ]);
    }

    private function seedCitiesAndTerminals(): array
    {
        $citiesData = [
            ['name' => 'Surabaya',   'province' => 'Jawa Timur', 'terminals' => ['Terminal Purabaya (Bungurasih)']],
            ['name' => 'Malang',     'province' => 'Jawa Timur', 'terminals' => ['Terminal Arjosari']],
            ['name' => 'Probolinggo','province' => 'Jawa Timur', 'terminals' => ['Terminal Bayuangga']],
            ['name' => 'Jember',     'province' => 'Jawa Timur', 'terminals' => ['Terminal Tawang Alun']],
            ['name' => 'Banyuwangi', 'province' => 'Jawa Timur', 'terminals' => ['Terminal Sritanjung', 'Terminal Karangente']],
            ['name' => 'Sumenep',    'province' => 'Jawa Timur', 'terminals' => ['Terminal Arya Wiraraja']],
            ['name' => 'Situbondo',  'province' => 'Jawa Timur', 'terminals' => ['Terminal Situbondo']],
        ];

        $cities = [];
        foreach ($citiesData as $data) {
            $city = City::query()->create([
                'name'      => $data['name'],
                'province'  => $data['province'],
                'is_active' => true,
            ]);

            $cityTerminals = [];
            foreach ($data['terminals'] as $terminalName) {
                $cityTerminals[] = BusTerminal::query()->create([
                    'city_id'   => $city->id,
                    'name'      => $terminalName,
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
        /**
         * Armada bus beserta layout kursinya.
         *
         * Layout 2-2 → 4 kolom, kapasitas 28 → 7 baris × 4 kursi
         * Layout 2-3 → 5 kolom, kapasitas 45 → 9 baris × 5 kursi
         * Layout 2-1 → 3 kolom, kapasitas 22 → 8 baris × 3 kursi (baris terakhir 2 kursi)
         *
         * Data kursi disimpan langsung sebagai JSON di kolom buses.seats.
         * Format tiap kursi: { seat_number, row, column, type, is_active }
         */
        $busesData = [
            ['name' => 'AKAS Mila Executive 01', 'seat_type' => 'executive', 'cap' => 28, 'layout' => '2-2'],
            ['name' => 'AKAS Mila Executive 02', 'seat_type' => 'executive', 'cap' => 28, 'layout' => '2-2'],
            ['name' => 'AKAS Asri Ekonomi 05',  'seat_type' => 'economy',   'cap' => 45, 'layout' => '2-3'],
            ['name' => 'AKAS Asri Ekonomi 06',  'seat_type' => 'economy',   'cap' => 45, 'layout' => '2-3'],
            ['name' => 'AKAS NR Luxury',         'seat_type' => 'executive', 'cap' => 22, 'layout' => '2-1'],
        ];

        $buses = [];
        foreach ($busesData as $data) {
            $bus = Bus::query()->create([
                'name'         => $data['name'],
                'plate_number' => 'N ' . rand(1000, 9999) . ' ' . Str::upper(Str::random(2)),
                'capacity'     => $data['cap'],
                'seat_layout'  => $data['layout'],
                'seat_type'    => $data['seat_type'],
                'status'       => 'active',
                // Kursi di-generate dan disimpan sebagai JSON
                'seats'        => $this->buildSeatsJson($data['layout'], $data['cap']),
            ]);

            $buses[] = $bus;
        }

        return $buses;
    }

    /**
     * Bangun array kursi berdasarkan layout dan kapasitas.
     *
     * Konvensi seat_number: {baris}{huruf_kolom} → "1A", "2C", "7D"
     * Kursi sopir selalu di baris 1 kolom 1 (seat_number = "1A").
     *
     * @return array<int, array{seat_number: string, row: int, column: int, type: string, is_active: bool}>
     */
    private function buildSeatsJson(string $layout, int $capacity): array
    {
        $totalColumns = match ($layout) {
            '2-3'   => 5,
            '2-1'   => 3,
            default => 4, // '2-2'
        };

        $seats = [];
        $count = 0;

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

        return $seats;
    }

    private function seedRoutesAndSchedules(array $cities, array $buses): void
    {
        $routesData = [
            ['from' => 'Surabaya', 'to' => 'Jember',     'dist' => 200, 'dur' => 300, 'price' => 150000, 'economy_price' => 85000],
            ['from' => 'Surabaya', 'to' => 'Banyuwangi', 'dist' => 290, 'dur' => 450, 'price' => 200000, 'economy_price' => 110000],
            ['from' => 'Malang',   'to' => 'Jember',     'dist' => 180, 'dur' => 240, 'price' => 135000, 'economy_price' => 75000],
            ['from' => 'Surabaya', 'to' => 'Sumenep',    'dist' => 170, 'dur' => 240, 'price' => 120000, 'economy_price' => 70000],
        ];

        foreach ($routesData as $r) {
            $route = BusRoute::query()->create([
                'origin_city_id'         => $cities[$r['from']]->id,
                'destination_city_id'    => $cities[$r['to']]->id,
                'origin_terminal_id'     => $cities[$r['from']]->seeded_terminals[0]->id,
                'destination_terminal_id'=> $cities[$r['to']]->seeded_terminals[0]->id,
                'distance_km'            => $r['dist'],
                'duration_minutes'       => $r['dur'],
                'is_active'              => true,
            ]);

            for ($i = 0; $i < 3; $i++) {
                $date = now()->addDays($i);

                // Pagi (Executive)
                Schedule::query()->create([
                    'bus_route_id'   => $route->id,
                    'bus_id'         => $buses[rand(0, 1)]->id,
                    'departure_at'   => (clone $date)->setTime(8, 0),
                    'arrival_est'    => (clone $date)->setTime(8, 0)->addMinutes($r['dur']),
                    'price'          => $r['price'],
                    'status'         => 'active',
                    'available_seats'=> 28,
                ]);

                // Siang (Economy)
                Schedule::query()->create([
                    'bus_route_id'   => $route->id,
                    'bus_id'         => $buses[rand(2, 3)]->id,
                    'departure_at'   => (clone $date)->setTime(13, 30),
                    'arrival_est'    => (clone $date)->setTime(13, 30)->addMinutes($r['dur']),
                    'price'          => $r['economy_price'],
                    'status'         => 'active',
                    'available_seats'=> 45,
                ]);

                // Malam (Luxury)
                Schedule::query()->create([
                    'bus_route_id'   => $route->id,
                    'bus_id'         => $buses[4]->id,
                    'departure_at'   => (clone $date)->setTime(21, 0),
                    'arrival_est'    => (clone $date)->setTime(21, 0)->addMinutes($r['dur']),
                    'price'          => $r['price'] + 50000,
                    'status'         => 'active',
                    'available_seats'=> 22,
                ]);
            }
        }
    }

    private function seedSampleBooking(User $user, Bus $bus): void
    {
        $schedule = Schedule::query()->where('bus_id', $bus->id)->first();
        if (! $schedule) return;

        $booking = Booking::query()->create([
            'user_id'        => $user->id,
            'schedule_id'    => $schedule->id,
            'booking_code'   => 'AKAS-' . now()->format('Ym') . '-' . Str::upper(Str::random(5)),
            'total_price'    => $schedule->price,
            'status'         => 'confirmed',
            'payment_status' => 'paid',
            'expired_at'     => now()->addMinutes(30),
            'confirmed_at'   => now(),
        ]);

        // Ambil kursi penumpang pertama dari JSON bus
        $firstSeat = ($bus->seats ?? collect())
            ->first(fn ($s) => $s['type'] === SeatType::Passenger->value);

        Passenger::query()->create([
            'booking_id'  => $booking->id,
            'seat_number' => $firstSeat['seat_number'],
            'name'        => $user->name,
            'phone'       => $user->phone,
            'ticket_code' => 'TKT-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6)),
        ]);

        Payment::query()->create([
            'booking_id' => $booking->id,
            'amount'     => $schedule->price,
            'method'     => 'qris',
            'status'     => 'settlement',
            'paid_at'    => now(),
        ]);
    }
}
