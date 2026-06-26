<x-layouts.passenger title="E-Tiket Berhasil - Bus Akas">
<main class="max-w-3xl mx-auto px-gutter py-lg w-full"><div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-lg text-center">        <div class="w-16 h-16 bg-secondary-container text-on-secondary-container rounded-full mx-auto flex items-center justify-center mb-md">
            <span class="material-symbols-outlined text-[40px]">{{ $booking->status === 'confirmed' ? 'confirmation_number' : 'check' }}</span>
        </div>
        @if($booking->status === 'confirmed')
            <h1 class="font-h1 text-h1 text-primary mb-sm">E-Tiket Berhasil</h1>
            <p class="text-on-surface-variant mb-md">Tiket Anda untuk kode booking {{ $booking->booking_code }} sudah siap.</p>
        @else
            <h1 class="font-h1 text-h1 text-primary mb-sm">Bukti Pembayaran Terkirim</h1>
            <p class="text-on-surface-variant mb-md">Kode booking {{ $booking->booking_code }} sedang menunggu konfirmasi operator.</p>
        @endif
        
        <div class="text-left border border-outline-variant rounded-xl p-md mb-md">
            <p class="font-h3 text-h3">{{ $booking->schedule->busRoute->originCity->name }} - {{ $booking->schedule->busRoute->destinationCity->name }}</p>
            <p class="text-on-surface-variant">{{ $booking->schedule->departure_at->translatedFormat('d F Y H:i') }} WIB, {{ $booking->schedule->bus->name }}</p>
            
            <div class="mt-md space-y-sm">
                @foreach($booking->passengers as $passenger)
                    <div class="flex items-center justify-between border-b border-outline-variant pb-sm last:border-0 last:pb-0">
                        <div>
                            <p class="font-label-form font-semibold">{{ $passenger->name }}</p>
                            <p class="text-caption text-on-surface-variant">Kursi {{ $passenger->seat_number }}</p>
                            @if($booking->status === 'confirmed')
                                <p class="font-mono text-xs text-primary mt-xs">{{ $passenger->ticket_code }}</p>
                            @endif
                        </div>
                        @if($booking->status === 'confirmed')
                            <div class="w-20 h-20 bg-white border border-outline-variant p-1">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $passenger->ticket_code }}" alt="QR Ticket" class="w-full h-full">
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div><div class="flex flex-col md:flex-row gap-sm justify-center"><a class="px-md py-sm bg-primary text-on-primary rounded-lg" href="{{ route('dashboard.bookings') }}">Lihat Riwayat</a><a class="px-md py-sm border border-primary text-primary rounded-lg" href="{{ route('home') }}">Pesan Lagi</a></div></div></main>
</x-layouts.passenger>
