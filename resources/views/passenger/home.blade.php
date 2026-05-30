<x-layouts.passenger title="PO. Akas - Pesan Tiket Bis Online">
<main>
    <section class="relative bg-surface-variant pb-xl">
        <div class="absolute inset-0 z-0 bg-inverse-surface">
            <div class="w-full h-[500px] bg-[linear-gradient(120deg,rgba(49,48,45,.94),rgba(24,95,165,.58)),url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=1800&q=80')] bg-cover bg-center"></div>
        </div>
        <div class="relative z-10 max-w-container-max mx-auto px-gutter pt-[100px] pb-[160px]">
            <div class="max-w-2xl">
                <h1 class="font-h1-mobile text-h1-mobile md:font-h1 md:text-h1 text-inverse-on-surface mb-sm leading-tight">Pesan Tiket Bis Online, Mudah & Cepat</h1>
                <p class="font-body text-body text-inverse-on-surface opacity-90">Layanan armada PO. Akas untuk perjalanan antar kota dengan jadwal, kursi, dan tiket yang terdata rapi.</p>
            </div>
        </div>
        <div class="relative z-20 max-w-container-max mx-auto px-gutter -mt-[100px]">
            <div class="bg-surface rounded-xl shadow-[0px_4px_12px_rgba(0,0,0,0.08)] border border-outline-variant p-md">
                <div class="flex items-center gap-sm border-b border-surface-container-highest pb-sm mb-md">
                    <span class="material-symbols-outlined text-primary">directions_bus</span><span class="font-h3 text-h3 text-on-surface">Cari Tiket Bis</span>
                </div>
                <form class="grid grid-cols-1 md:grid-cols-5 gap-md items-end" method="GET" action="{{ route('schedules.index') }}">
                    <label class="flex flex-col gap-xs md:col-span-1"><span class="font-caption text-caption text-on-surface-variant">Kota Asal</span><select name="from" class="rounded-lg border-outline-variant bg-surface py-3"><option value="">Pilih asal</option>@foreach($cities as $city)<option value="{{ $city->id }}">{{ $city->name }}</option>@endforeach</select></label>
                    <label class="flex flex-col gap-xs md:col-span-1"><span class="font-caption text-caption text-on-surface-variant">Kota Tujuan</span><select name="to" class="rounded-lg border-outline-variant bg-surface py-3"><option value="">Pilih tujuan</option>@foreach($cities as $city)<option value="{{ $city->id }}">{{ $city->name }}</option>@endforeach</select></label>
                    <label class="flex flex-col gap-xs"><span class="font-caption text-caption text-on-surface-variant">Tanggal</span><input name="date" value="{{ now()->addDay()->toDateString() }}" class="rounded-lg border-outline-variant bg-surface py-3" type="date"></label>
                    <label class="flex flex-col gap-xs"><span class="font-caption text-caption text-on-surface-variant">Penumpang</span><select name="pax" class="rounded-lg border-outline-variant bg-surface py-3">@for($i=1;$i<=6;$i++)<option value="{{ $i }}">{{ $i }} Kursi</option>@endfor</select></label>
                    <button class="w-full bg-primary-container text-on-primary py-3 rounded-lg font-label-form text-label-form hover:opacity-90 flex items-center justify-center gap-xs" type="submit"><span class="material-symbols-outlined text-[20px]">search</span>Cari Tiket</button>
                </form>
            </div>
        </div>
    </section>
    <section class="py-lg bg-background"><div class="max-w-container-max mx-auto px-gutter">
        <h2 class="font-h2 text-h2 text-on-surface mb-xs">Rute Populer</h2><p class="font-body text-body text-on-surface-variant mb-md">Pilihan destinasi favorit penumpang kami.</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-md">
            @forelse($popularRoutes as $route)
                <a href="{{ route('schedules.index', ['from' => $route->origin_city_id, 'to' => $route->destination_city_id, 'date' => now()->addDay()->toDateString(), 'pax' => 1]) }}" class="group bg-surface rounded-xl border border-outline-variant overflow-hidden hover:shadow-[0px_4px_12px_rgba(0,0,0,0.05)] transition-all">
                    <div class="h-28 bg-primary-fixed flex items-center justify-center"><span class="material-symbols-outlined text-primary text-[52px]">route</span></div>
                    <div class="p-sm"><div class="font-h3 text-h3 text-on-surface">{{ $route->originCity->name }} ke {{ $route->destinationCity->name }}</div><p class="font-caption text-caption text-on-surface-variant mt-xs">{{ $route->distance_km ?? '-' }} km, estimasi {{ $route->duration_minutes ? intdiv($route->duration_minutes, 60).'j '.($route->duration_minutes % 60).'m' : '-' }}</p></div>
                </a>
            @empty
                <div class="bg-surface rounded-xl border border-outline-variant p-md text-on-surface-variant">Belum ada rute aktif.</div>
            @endforelse
        </div>
    </div></section>
</main>
</x-layouts.passenger>
