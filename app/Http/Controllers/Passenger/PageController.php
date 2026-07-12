<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BusRoute;
use App\Models\City;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('passenger.home', [
            'cities'       => City::query()->where('is_active', true)->orderBy('name')->get(),
            'popularRoutes' => BusRoute::query()->with(['originCity', 'destinationCity'])->where('is_active', true)->take(3)->get(),
        ]);
    }

    public function schedules(Request $request): View
    {
        $validated = $request->validate([
            'from' => ['nullable', 'integer', 'exists:cities,id'],
            'to' => ['nullable', 'integer', 'exists:cities,id', 'different:from'],
            'date' => ['nullable', 'date', 'after_or_equal:today'],
            'pax' => ['nullable', 'integer', 'min:1', 'max:6'],
        ], [
            'to.different' => 'Kota tujuan harus berbeda dengan kota asal.',
            'date.after_or_equal' => 'Tanggal keberangkatan tidak boleh di masa lalu.',
        ]);

        $origin      = $validated['from'] ?? null;
        $destination = $validated['to'] ?? null;
        $date        = $validated['date'] ?? now()->toDateString();
        $pax         = $validated['pax'] ?? 1;

        $schedules = Schedule::query()
            ->with([
                'bus',
                'busRoute.originCity',
                'busRoute.destinationCity',
                'busRoute.originTerminal',
                'busRoute.destinationTerminal',
                'bookings' => fn($query) => $query->whereIn('status', ['pending', 'confirmed'])->with('passengers')
            ])
            ->where('status', 'active')
            ->whereDate('departure_at', $date)
            ->where('departure_at', '>', now()->addHour()) // Hanya tampilkan jadwal lebih dari 1 jam dari sekarang
            ->when($origin, fn (Builder $query) => $query->whereHas('busRoute', fn (Builder $route) => $route->where('origin_city_id', $origin)))
            ->when($destination, fn (Builder $query) => $query->whereHas('busRoute', fn (Builder $route) => $route->where('destination_city_id', $destination)))
            ->orderBy('departure_at')
            ->get()
            ->map(function ($schedule) {
                $occupiedSeats = $schedule->bookings->sum(fn($booking) => $booking->passengers->count());
                $schedule->available_seats = max(0, $schedule->bus->capacity - $occupiedSeats);
                $schedule->is_bookable = $schedule->departure_at->greaterThan(now()->addHour());
                return $schedule;
            })
            ->filter(fn($schedule) => $schedule->available_seats > 0);


        return view('passenger.schedule.index', [
            'cities'      => City::query()->where('is_active', true)->orderBy('name')->get(),
            'schedules'   => $schedules,
            'origin'      => $origin,
            'destination' => $destination,
            'date'        => $date,
            'pax'         => $pax,
        ]);
    }

    public function seats(Schedule $schedule, Request $request): View
    {
        // Validasi: Bus tidak bisa dipesan kurang dari 1 jam sebelum keberangkatan
        abort_if(
            $schedule->departure_at->lessThanOrEqualTo(now()->addHour()),
            403,
            'Pemesanan tidak dapat dilakukan. Bus akan berangkat dalam waktu kurang dari 1 jam.'
        );

        $schedule->load(['bus', 'busRoute.originCity', 'busRoute.destinationCity', 'busRoute.originTerminal', 'busRoute.destinationTerminal']);
        $pax = max(1, min(6, $request->integer('pax', 1)));

        // Kursi yang sudah terisi pada jadwal ini
        $occupiedSeatNumbers = Passenger::query()
            ->whereHas('booking', fn (Builder $query) => $query
                ->where('schedule_id', $schedule->id)
                ->whereIn('status', ['pending', 'confirmed'])
            )
            ->pluck('seat_number')
            ->all();

        return view('passenger.schedule.seats', [
            'schedule'            => $schedule,
            // Daftar kursi diambil langsung dari JSON di bus, bukan tabel terpisah
            'seats'               => $schedule->bus->seats ?? collect(),
            'occupiedSeatNumbers' => $occupiedSeatNumbers,
            'pax'                 => $pax,
        ]);
    }

    public function storeSeats(Schedule $schedule, Request $request): RedirectResponse
    {
        // Validasi: Bus tidak bisa dipesan kurang dari 1 jam sebelum keberangkatan
        abort_if(
            $schedule->departure_at->lessThanOrEqualTo(now()->addHour()),
            403,
            'Pemesanan tidak dapat dilakukan. Bus akan berangkat dalam waktu kurang dari 1 jam.'
        );

        $data = $request->validate([
            'seat_numbers'   => ['required', 'array', 'min:1'],
            'seat_numbers.*' => ['string', 'max:10'],
        ]);

        // Validasi bahwa seat_number memang ada di bus ini
        $busSeatNumbers = ($schedule->bus->seats ?? collect())->pluck('seat_number')->all();
        foreach ($data['seat_numbers'] as $sn) {
            abort_unless(in_array($sn, $busSeatNumbers), 422, "Nomor kursi '$sn' tidak valid.");
        }

        $request->session()->put('booking_draft', [
            'schedule_id'  => $schedule->id,
            'seat_numbers' => array_values($data['seat_numbers']),
        ]);

        return redirect()->route('booking.passengers');
    }

    public function passengerForm(Request $request): View|RedirectResponse
    {
        $draft = $request->session()->get('booking_draft');
        if (! $draft) {
            return redirect()->route('schedules.index')->with('error', 'Silakan pilih jadwal dan kursi terlebih dahulu.');
        }

        $schedule = Schedule::query()
            ->with(['busRoute.originCity', 'busRoute.destinationCity', 'bus'])
            ->findOrFail($draft['schedule_id']);

        // Data kursi diambil dari JSON bus, filter berdasarkan nomor kursi yang dipilih
        $selectedSeats = ($schedule->bus->seats ?? collect())
            ->whereIn('seat_number', $draft['seat_numbers'])
            ->values();

        return view('passenger.booking.passengers', [
            'schedule'      => $schedule,
            'selectedSeats' => $selectedSeats,
        ]);
    }

    public function storePassengers(Request $request): RedirectResponse
    {
        $draft = $request->session()->get('booking_draft');
        abort_if(! $draft, 404);

        $schedule     = Schedule::query()->findOrFail($draft['schedule_id']);
        
        // Validasi: Bus tidak bisa dipesan kurang dari 1 jam sebelum keberangkatan
        abort_if(
            $schedule->departure_at->lessThanOrEqualTo(now()->addHour()),
            403,
            'Pemesanan tidak dapat dilakukan. Bus akan berangkat dalam waktu kurang dari 1 jam.'
        );
        
        $seatNumbers  = $draft['seat_numbers'];

        $data = $request->validate([
            'passengers'             => ['required', 'array', 'size:'.count($seatNumbers)],
            'passengers.*.name'      => ['required', 'string', 'max:255'],
            'passengers.*.phone'     => ['required', 'string', 'max:30'],
            'passengers.*.id_number' => ['nullable', 'string', 'max:50'],
        ]);

        $booking = DB::transaction(function () use ($schedule, $seatNumbers, $data) {
            $booking = Booking::query()->create([
                'user_id'        => Auth::id(),
                'schedule_id'    => $schedule->id,
                'booking_code'   => 'BIS-'.now()->format('Ym').'-'.Str::upper(Str::random(5)),
                'total_price'    => $schedule->price * count($seatNumbers),
                'status'         => 'pending',
                'payment_status' => 'unpaid',
                'expired_at'     => now()->addMinutes(30),
            ]);

            foreach (array_values($data['passengers']) as $index => $passengerData) {
                Passenger::query()->create([
                    'booking_id'  => $booking->id,
                    'seat_number' => $seatNumbers[$index],
                    'name'        => $passengerData['name'],
                    'phone'       => $passengerData['phone'],
                    'id_number'   => $passengerData['id_number'] ?? null,
                    'ticket_code' => 'TKT-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
                ]);
            }

            Payment::query()->create([
                'booking_id' => $booking->id,
                'amount'     => $booking->total_price,
                'method'     => 'qris',
                'status'     => 'pending',
            ]);

            return $booking;
        });

        $request->session()->forget('booking_draft');

        return redirect()->route('booking.confirmation', $booking);
    }

    public function confirmation(Booking $booking): View
    {
        $this->authorizeBooking($booking);

        return view('passenger.booking.confirmation', [
            'booking' => $booking->load(['schedule.busRoute.originCity', 'schedule.busRoute.destinationCity', 'schedule.bus', 'passengers', 'payments']),
        ]);
    }

    public function pay(Booking $booking, Request $request): RedirectResponse
    {
        $this->authorizeBooking($booking);

        $request->validate([
            'payment_proof' => ['required', 'image', 'max:2048'],
        ]);

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $booking->payments()->where('status', 'pending')->latest()->first()?->update([
            'payment_proof' => $path,
            'paid_at'       => now(),
        ]);

        return redirect()->route('booking.success', $booking->booking_code)
            ->with('success', 'Bukti pembayaran berhasil diunggah. Silakan tunggu konfirmasi operator.');
    }

    public function success(string $code): View
    {
        return view('passenger.booking.success', ['booking' => $this->bookingByCode($code)]);
    }

    public function pending(string $code): View
    {
        return view('passenger.booking.pending', ['booking' => $this->bookingByCode($code)]);
    }

    public function history(): View
    {
        return view('passenger.dashboard.history', [
            'bookings' => Booking::query()
                ->with(['schedule.busRoute.originCity', 'schedule.busRoute.destinationCity', 'passengers'])
                ->where('user_id', Auth::id())
                ->latest()
                ->paginate(10),
        ]);
    }

    public function profile(): View
    {
        return view('passenger.dashboard.profile');
    }

    private function bookingByCode(string $code): Booking
    {
        $booking = Booking::query()
            ->with(['schedule.busRoute.originCity', 'schedule.busRoute.destinationCity', 'schedule.bus', 'passengers', 'payments'])
            ->where('booking_code', $code)
            ->firstOrFail();

        $this->authorizeBooking($booking);

        return $booking;
    }

    private function authorizeBooking(Booking $booking): void
    {
        abort_unless($booking->user_id === Auth::id() || Auth::user()?->role === 'admin', 403);
    }
}
