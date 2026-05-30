<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\BoardingScan;
use App\Models\Passenger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BoardingController extends Controller
{
    public function scanner(): View
    {
        abort_unless(in_array(Auth::user()->role, ['admin', 'operator']), 403);
        return view('operator.boarding.scanner');
    }

    public function process(Request $request): JsonResponse
    {
        abort_unless(in_array(Auth::user()->role, ['admin', 'operator']), 403);
        $request->validate([
            'ticket_code' => ['required', 'string'],
        ]);

        $passenger = Passenger::query()
            ->with(['booking.schedule.busRoute.originCity', 'booking.schedule.busRoute.destinationCity', 'seat'])
            ->where('ticket_code', $request->ticket_code)
            ->first();

        if (!$passenger) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan.',
            ], 404);
        }

        $booking = $passenger->booking;

        // Validasi status pembayaran
        if ($booking->status !== 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Booking belum dikonfirmasi atau sudah dibatalkan.',
            ], 400);
        }

        // Cek apakah sudah pernah di-scan
        $alreadyScanned = BoardingScan::query()
            ->where('passenger_id', $passenger->id)
            ->where('status', 'valid')
            ->exists();

        if ($alreadyScanned) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket ini sudah pernah di-scan sebelumnya.',
                'passenger' => [
                    'name' => $passenger->name,
                    'seat' => $passenger->seat->seat_number,
                ]
            ], 400);
        }

        // Catat scan baru
        BoardingScan::query()->create([
            'booking_id' => $booking->id,
            'passenger_id' => $passenger->id,
            'scanned_by' => Auth::id(),
            'qr_payload' => $request->ticket_code,
            'status' => 'valid',
            'scanned_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Boarding berhasil!',
            'data' => [
                'passenger_name' => $passenger->name,
                'seat_number' => $passenger->seat->seat_number,
                'route' => $passenger->booking->schedule->busRoute->originCity->name . ' - ' . $passenger->booking->schedule->busRoute->destinationCity->name,
                'time' => now()->format('H:i:s'),
            ]
        ]);
    }
}
