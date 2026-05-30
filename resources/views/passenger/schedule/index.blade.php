<x-layouts.passenger title="PO. Akas - Hasil Pencarian Jadwal">
<main class="flex-grow w-full max-w-container-max mx-auto px-gutter py-md md:py-lg flex flex-col gap-lg">
    <section class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm">
        <form class="grid grid-cols-1 md:grid-cols-5 gap-md items-end" method="GET" action="{{ route('schedules.index') }}">
            <label><span class="font-label-form text-label-form text-on-surface-variant block mb-xs">Asal</span><select name="from" class="w-full rounded-lg border-outline-variant bg-surface-container-lowest py-sm">@foreach($cities as $city)<option value="{{ $city->id }}" @selected($origin===$city->id)>{{ $city->name }}</option>@endforeach</select></label>
            <label><span class="font-label-form text-label-form text-on-surface-variant block mb-xs">Tujuan</span><select name="to" class="w-full rounded-lg border-outline-variant bg-surface-container-lowest py-sm">@foreach($cities as $city)<option value="{{ $city->id }}" @selected($destination===$city->id)>{{ $city->name }}</option>@endforeach</select></label>
            <label><span class="font-label-form text-label-form text-on-surface-variant block mb-xs">Tanggal</span><input name="date" class="w-full rounded-lg border-outline-variant bg-surface-container-lowest py-sm" type="date" value="{{ $date }}"></label>
            <label><span class="font-label-form text-label-form text-on-surface-variant block mb-xs">Penumpang</span><select name="pax" class="w-full rounded-lg border-outline-variant bg-surface-container-lowest py-sm">@for($i=1;$i<=6;$i++)<option value="{{ $i }}" @selected($pax===$i)>{{ $i }} Kursi</option>@endfor</select></label>
            <button class="font-label-form text-label-form bg-primary text-on-primary px-md py-sm rounded-lg hover:opacity-90" type="submit">Ubah Pencarian</button>
        </form>
    </section>
    <section class="flex flex-col md:flex-row justify-between items-start md:items-center gap-sm">
        <h1 class="font-h2 text-h2 text-on-surface">Jadwal tersedia <span class="block font-body text-body text-on-surface-variant mt-xs">{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</span></h1>
        <span class="font-label-form text-label-form text-on-surface-variant">{{ $schedules->count() }} jadwal ditemukan</span>
    </section>
    <section class="flex flex-col gap-sm">
        @forelse($schedules as $schedule)
            @php
                $route = $schedule->busRoute; $available = $schedule->available_seats ?? max(0, $schedule->bus->capacity - $schedule->bookings()->whereIn('status', ['pending','confirmed'])->withCount('passengers')->get()->sum('passengers_count'));
            @endphp
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-md flex flex-col md:flex-row gap-md hover:border-primary transition-colors hover:shadow-sm">
                <div class="flex-grow flex flex-col md:flex-row md:items-center gap-md md:gap-xl">
                    <div class="flex flex-col gap-xs min-w-[150px]"><span class="font-h3 text-h3 text-on-surface">{{ $schedule->bus->name }}</span><span class="bg-primary-container text-on-primary font-caption text-caption px-sm py-xs rounded-full self-start">{{ ucfirst($schedule->bus->seat_type) }}</span></div>
                    <div class="flex items-center gap-md flex-grow">
                        <div class="text-center"><span class="block font-h2 text-h2 text-on-surface">{{ $schedule->departure_at->format('H:i') }}</span><span class="block font-caption text-caption text-on-surface-variant">{{ $route->originTerminal?->name ?? $route->originCity->name }}</span></div>
                        <div class="flex-grow flex flex-col items-center justify-center px-sm"><span class="font-caption text-caption text-outline mb-xs">{{ $route->duration_minutes ? intdiv($route->duration_minutes, 60).'j '.($route->duration_minutes % 60).'m' : '-' }}</span><div class="w-full h-px bg-outline-variant relative"><span class="material-symbols-outlined absolute left-1/2 -translate-x-1/2 top-[-11px] text-outline bg-surface-container-lowest px-xs">directions_bus</span></div></div>
                        <div class="text-center"><span class="block font-h2 text-h2 text-on-surface">{{ $schedule->arrival_est?->format('H:i') ?? '-' }}</span><span class="block font-caption text-caption text-on-surface-variant">{{ $route->destinationTerminal?->name ?? $route->destinationCity->name }}</span></div>
                    </div>
                </div>
                <div class="hidden md:block w-px border-l border-dashed border-outline-variant mx-sm"></div>
                <div class="flex flex-row md:flex-col justify-between items-center md:items-end min-w-[170px] gap-sm">
                    <div class="flex flex-col items-start md:items-end"><span class="font-h2 text-h2 text-primary">Rp {{ number_format($schedule->price, 0, ',', '.') }}</span><span class="{{ $available < $pax ? 'bg-error-container text-on-error-container' : 'bg-secondary-container text-on-secondary-container' }} font-caption text-caption px-sm py-xs rounded-full flex items-center gap-xs mt-xs"><span class="material-symbols-outlined text-[14px]">event_seat</span>{{ $available }} kursi tersedia</span></div>
                    @auth<a class="font-label-form text-label-form bg-primary text-on-primary px-xl py-sm rounded-lg hover:opacity-90 w-full md:w-auto text-center {{ $available < $pax ? 'pointer-events-none opacity-50' : '' }}" href="{{ route('schedules.seats', ['schedule' => $schedule, 'pax' => $pax]) }}">Pilih</a>@else<a class="font-label-form text-label-form bg-primary text-on-primary px-xl py-sm rounded-lg hover:opacity-90 w-full md:w-auto text-center" href="{{ route('login', ['intended' => route('schedules.seats', ['schedule' => $schedule, 'pax' => $pax])]) }}">Masuk</a>@endauth
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-xl px-md text-center bg-surface-container-lowest border border-outline-variant rounded-xl"><span class="material-symbols-outlined text-[64px] text-outline-variant mb-md">search_off</span><h3 class="font-h3 text-h3 text-on-surface mb-sm">Tidak ada jadwal ditemukan</h3><p class="font-body text-body text-on-surface-variant max-w-md mx-auto">Silakan ubah rute atau tanggal pencarian.</p></div>
        @endforelse
    </section>
</main>
</x-layouts.passenger>
