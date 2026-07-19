<x-layouts.passenger title="Riwayat Pemesanan - Bus Akas">
<main class="flex-grow flex flex-col md:flex-row w-full max-w-container-max mx-auto px-gutter py-md md:py-lg gap-md md:gap-lg">
    <aside class="w-full md:w-64 shrink-0">
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-md">
            <h2 class="font-h3 text-h3 mb-sm">Dashboard</h2>
            <nav class="flex flex-row md:flex-col gap-xs md:gap-sm">
                <a class="flex-1 md:flex-none text-center md:text-left px-sm py-sm rounded-lg bg-primary-container/10 text-primary font-label-form text-label-form" href="{{ route('dashboard.bookings') }}">Riwayat Pemesanan</a>
                <a class="flex-1 md:flex-none text-center md:text-left px-sm py-sm rounded-lg text-on-surface-variant hover:bg-surface-container-low font-label-form text-label-form" href="{{ route('dashboard.profile') }}">Profil Akun</a>
            </nav>
        </div>
    </aside>
    <section class="flex-grow min-w-0">
        <h1 class="font-h2 md:font-h1 text-h2 md:text-h1 text-on-surface mb-xs">Riwayat Pemesanan</h1>
        <p class="text-on-surface-variant mb-md">Lihat dan kelola semua tiket perjalanan Anda.</p>
        <div class="space-y-sm">
            @forelse($bookings as $booking)
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col gap-sm">
                <div class="flex justify-between items-start gap-sm">
                    <div class="min-w-0">
                        <p class="font-h3 text-h3 truncate">{{ $booking->booking_code }}</p>
                        <p class="text-on-surface-variant text-sm">{{ $booking->schedule->busRoute->originCity->name }} - {{ $booking->schedule->busRoute->destinationCity->name }}</p>
                        <p class="text-on-surface-variant text-sm">{{ $booking->schedule->departure_at->translatedFormat('d M Y H:i') }}</p>
                        <p class="font-caption text-caption text-on-surface-variant">{{ $booking->passengers->count() }} penumpang</p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="inline-block px-sm py-xs rounded-full text-caption {{ $booking->status === 'confirmed' ? 'bg-secondary-container text-on-secondary-container' : 'bg-primary-fixed text-primary' }}">{{ ucfirst($booking->status) }}</span>
                        <p class="font-h3 text-h3 text-primary mt-xs">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                    </div>
                </div>
                @if($booking->status === 'pending')
                    <a class="w-full text-center py-2 px-md border border-primary text-primary rounded-lg font-label-form text-label-form hover:bg-primary hover:text-on-primary transition-colors" href="{{ route('booking.confirmation', $booking) }}">Bayar Sekarang</a>
                @elseif($booking->status === 'confirmed')
                    <a class="w-full text-center py-2 px-md bg-secondary-container text-on-secondary-container rounded-lg font-label-form text-label-form hover:opacity-90 transition-colors" href="{{ route('booking.success', $booking->booking_code) }}">Lihat E-Tiket</a>
                @endif
            </div>
            @empty
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md text-on-surface-variant">Belum ada pemesanan.</div>
            @endforelse
        </div>
        <div class="mt-md">{{ $bookings->links() }}</div>
    </section>
</main>
</x-layouts.passenger>
