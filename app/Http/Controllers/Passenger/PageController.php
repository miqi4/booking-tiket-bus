<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BusRoute;
use App\Models\City;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\Seat;
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
            'cities' => City::query()->where('is_active', true)->orderBy('name')->get(),
            'popularRoutes' => BusRoute::query()->with(['originCity', 'destinationCity'])->where('is_active', true)->take(3)->get(),
        ]);
    }

    public function schedules(Request $request): View
    {
        $origin = $request->integer('from');
        $destination = $request->integer('to');
        $date = $request->date('date')?->toDateString() ?? now()->toDateString();
        $pax = max(1, min(6, $request->integer('pax', 1)));

        $schedules = Schedule::query()
            ->with(['bus', 'busRoute.originCity', 'busRoute.destinationCity', 'busRoute.originTerminal', 'busRoute.destinationTerminal'])
            ->where('status', 'active')
            ->whereDate('departure_at', $date)
            ->when($origin, fn (Builder $query) => $query->whereHas('busRoute', fn (Builder $route) => $route->where('origin_city_id', $origin)))
            ->when($destination, fn (Builder $query) => $query->whereHas('busRoute', fn (Builder $route) => $route->where('destination_city_id', $destination)))
            ->orderBy('departure_at')
            ->get();

        return view('passenger.schedule.index', [
            'cities' => City::query()->where('is_active', true)->orderBy('name')->get(),
            'schedules' => $schedules,
            'origin' => $origin,
            'destination' => $destination,
            'date' => $date,
            'pax' => $pax,
        ]);
    }

    public function seats(Schedule $schedule, Request $request): View
    {
        $schedule->load(['bus.seats', 'busRoute.originCity', 'busRoute.destinationCity', 'busRoute.originTerminal', 'busRoute.destinationTerminal']);
        $pax = max(1, min(6, $request->integer('pax', 1)));
        $occupiedSeatIds = Passenger::query()
            ->whereHas('booking', fn (Builder $query) => $query->where('schedule_id', $schedule->id)->whereIn('status', ['pending', 'confirmed']))
            ->pluck('seat_id')
            ->all();

        return view('passenger.schedule.seats', [
            'schedule' => $schedule,
            'seats' => $schedule->bus->seats->sortBy(['row', 'column']),
            'occupiedSeatIds' => $occupiedSeatIds,
            'pax' => $pax,
        ]);
    }

    public function storeSeats(Schedule $schedule, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'seat_ids' => ['required', 'array', 'min:1'],
            'seat_ids.*' => ['integer', 'exists:seats,id'],
        ]);

        $request->session()->put('booking_draft', [
            'schedule_id' => $schedule->id,
            'seat_ids' => array_values($data['seat_ids']),
        ]);

        return redirect()->route('booking.passengers');
    }

    public function passengerForm(Request $request): View|RedirectResponse
    {
        $draft = $request->session()->get('booking_draft');
        if (! $draft) {
            return redirect()->route('schedules.index')->with('error', 'Silakan pilih jadwal dan kursi terlebih dahulu.');
        }

        return view('passenger.booking.passengers', [
            'schedule' => Schedule::query()->with(['busRoute.originCity', 'busRoute.destinationCity', 'bus'])->findOrFail($draft['schedule_id']),
            'seats' => Seat::query()->whereIn('id', $draft['seat_ids'])->orderBy('seat_number')->get(),
        ]);
    }

    public function storePassengers(Request $request): RedirectResponse
    {
        $draft = $request->session()->get('booking_draft');
        abort_if(! $draft, 404);

        $schedule = Schedule::query()->findOrFail($draft['schedule_id']);
        $seatIds = $draft['seat_ids'];
        $data = $request->validate([
            'passengers' => ['required', 'array', 'size:'.count($seatIds)],
            'passengers.*.name' => ['required', 'string', 'max:255'],
            'passengers.*.phone' => ['required', 'string', 'max:30'],
            'passengers.*.id_number' => ['nullable', 'string', 'max:50'],
        ]);

        $booking = DB::transaction(function () use ($schedule, $seatIds, $data) {
            $booking = Booking::query()->create([
                'user_id' => Auth::id(),
                'schedule_id' => $schedule->id,
                'booking_code' => 'BIS-'.now()->format('Ym').'-'.Str::upper(Str::random(5)),
                'total_price' => $schedule->price * count($seatIds),
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'expired_at' => now()->addMinutes(30),
            ]);

            foreach (array_values($data['passengers']) as $index => $passengerData) {
                Passenger::query()->create([
                    'booking_id' => $booking->id,
                    'seat_id' => $seatIds[$index],
                    'name' => $passengerData['name'],
                    'phone' => $passengerData['phone'],
                    'id_number' => $passengerData['id_number'] ?? null,
                    'ticket_code' => 'TKT-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
                ]);
            }

            Payment::query()->create([
                'booking_id' => $booking->id,
                'amount' => $booking->total_price,
                'method' => 'qris',
                'status' => 'pending',
            ]);

            return $booking;
        });

        $request->session()->forget('booking_draft');

        return redirect()->route('booking.confirmation', $booking);
    }

    public function confirmation(Booking $booking): View
    {
        $this->authorizeBooking($booking);

        return view('passenger.booking.confirmation', ['booking' => $booking->load(['schedule.busRoute.originCity', 'schedule.busRoute.destinationCity', 'schedule.bus', 'passengers.seat', 'payments'])]);
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
            'paid_at' => now(),
        ]);

        return redirect()->route('booking.success', $booking->booking_code)->with('success', 'Bukti pembayaran berhasil diunggah. Silakan tunggu konfirmasi operator.');
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
            'bookings' => Booking::query()->with(['schedule.busRoute.originCity', 'schedule.busRoute.destinationCity', 'passengers'])->where('user_id', Auth::id())->latest()->paginate(10),
        ]);
    }

    public function profile(): View
    {
        return view('passenger.dashboard.profile');
    }

    private function bookingByCode(string $code): Booking
    {
        $booking = Booking::query()->with(['schedule.busRoute.originCity', 'schedule.busRoute.destinationCity', 'schedule.bus', 'passengers.seat', 'payments'])->where('booking_code', $code)->firstOrFail();
        $this->authorizeBooking($booking);

        return $booking;
    }

    private function authorizeBooking(Booking $booking): void
    {
        abort_unless($booking->user_id === Auth::id() || Auth::user()?->role === 'admin', 403);
    }
}
