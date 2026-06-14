<x-layouts.passenger title="PO. Akas - Pesan Tiket Bis Online">
<main>
    <section class="relative bg-inverse-surface overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="w-full h-full bg-[linear-gradient(to_right,rgba(49,48,45,1)_30%,rgba(24,95,165,.4)),url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=1800&q=80')] bg-cover bg-center opacity-40"></div>
        </div>
        <div class="relative z-10 max-w-container-max mx-auto px-gutter pt-xl pb-[180px]">
            <div class="max-w-2xl">
                <span class="inline-block bg-primary text-on-primary font-label-form text-[12px] px-sm py-1 rounded-full mb-md tracking-wider uppercase">Terpercaya Sejak 1956</span>
                <h1 class="font-h1-mobile text-[40px] md:text-[56px] text-inverse-on-surface mb-sm leading-[1.1] font-extrabold">Perjalanan Aman,<br>Tiket Nyaman.</h1>
                <p class="font-body text-h3 text-inverse-on-surface opacity-80 max-w-lg">Layanan transportasi antar kota PO. Akas dengan armada modern dan jadwal yang selalu tepat waktu.</p>
            </div>
        </div>
        <div class="relative z-20 max-w-container-max mx-auto px-gutter -mt-[100px] mb-xl">
            <div class="bg-surface rounded-xl shadow-[0px_8px_24px_rgba(0,0,0,0.12)] border border-outline-variant p-md md:p-lg">
                <div class="flex items-center gap-sm border-b border-surface-container-highest pb-sm mb-md">
                    <span class="material-symbols-outlined text-primary" aria-hidden="true">directions_bus</span>
                    <h2 class="font-h3 text-h3 text-on-surface">Cari Tiket Bis</h2>
                </div>
                <form class="grid grid-cols-1 md:grid-cols-12 gap-md items-end" method="GET" action="{{ route('schedules.index') }}">
                    <div class="md:col-span-3">
                        <label class="flex flex-col gap-xs">
                            <span class="font-label-form text-label-form text-on-surface-variant">Kota Asal</span>
                            <select name="from" class="rounded-lg border-outline-variant bg-surface py-3 focus:ring-primary focus:border-primary">
                                <option value="">Pilih asal</option>
                                @foreach($cities as $city)<option value="{{ $city->id }}">{{ $city->name }}</option>@endforeach
                            </select>
                        </label>
                    </div>
                    <div class="md:col-span-3">
                        <label class="flex flex-col gap-xs">
                            <span class="font-label-form text-label-form text-on-surface-variant">Kota Tujuan</span>
                            <select name="to" class="rounded-lg border-outline-variant bg-surface py-3 focus:ring-primary focus:border-primary">
                                <option value="">Pilih tujuan</option>
                                @foreach($cities as $city)<option value="{{ $city->id }}">{{ $city->name }}</option>@endforeach
                            </select>
                        </label>
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex flex-col gap-xs">
                            <span class="font-label-form text-label-form text-on-surface-variant">Tanggal</span>
                            <input name="date" value="{{ now()->addDay()->toDateString() }}" class="rounded-lg border-outline-variant bg-surface py-3 focus:ring-primary focus:border-primary" type="date">
                        </label>
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex flex-col gap-xs">
                            <span class="font-label-form text-label-form text-on-surface-variant">Penumpang</span>
                            <select name="pax" class="rounded-lg border-outline-variant bg-surface py-3 focus:ring-primary focus:border-primary">
                                @for($i=1;$i<=6;$i++)<option value="{{ $i }}">{{ $i }} Kursi</option>@endfor
                            </select>
                        </label>
                    </div>
                    <div class="md:col-span-2">
                        <button class="w-full bg-primary text-on-primary py-[13px] rounded-lg font-label-form text-label-form hover:bg-primary-container transition-colors flex items-center justify-center gap-xs shadow-sm" type="submit">
                            <span class="material-symbols-outlined text-[20px]" aria-hidden="true">search</span>
                            Cari Tiket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <section class="py-xl bg-background">
        <div class="max-w-container-max mx-auto px-gutter">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-xl gap-md">
                <div class="max-w-xl">
                    <h2 class="font-h1 text-[32px] text-on-surface mb-xs">Rute Populer</h2>
                    <p class="font-body text-h3 text-on-surface-variant">Pilihan destinasi favorit penumpang kami untuk perjalanan yang nyaman.</p>
                </div>
                <a href="{{ route('schedules.index') }}" class="text-primary font-label-form flex items-center gap-xs hover:underline">Lihat Semua Jadwal <span class="material-symbols-outlined text-[18px]">arrow_forward</span></a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-6 lg:grid-cols-12 gap-md">
                @forelse($popularRoutes as $index => $route)
                    <a href="{{ route('schedules.index', ['from' => $route->origin_city_id, 'to' => $route->destination_city_id, 'date' => now()->addDay()->toDateString(), 'pax' => 1]) }}" 
                       class="group relative overflow-hidden rounded-2xl border border-outline-variant bg-surface-container-lowest transition-all hover:shadow-lg hover:border-primary-container {{ $index === 0 ? 'md:col-span-6 lg:col-span-6' : 'md:col-span-3 lg:col-span-3' }}">
                        <div class="aspect-[16/9] bg-primary-fixed flex items-center justify-center relative overflow-hidden">
                            <span class="material-symbols-outlined text-primary text-[64px] transition-transform duration-500 group-hover:scale-110">route</span>
                            @if($index === 0)
                                <div class="absolute top-4 left-4 bg-secondary text-on-secondary px-sm py-1 rounded-full font-label-form text-[12px] uppercase tracking-wider shadow-sm">Paling Populer</div>
                            @endif
                        </div>
                        <div class="p-md">
                            <div class="font-h3 text-h3 text-on-surface group-hover:text-primary transition-colors">{{ $route->originCity->name }} <span class="text-outline mx-1">→</span> {{ $route->destinationCity->name }}</div>
                            <div class="flex items-center gap-md mt-sm">
                                <div class="flex items-center gap-xs text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[16px]">distance</span>
                                    <span class="font-caption text-caption">{{ $route->distance_km ?? '-' }} km</span>
                                </div>
                                <div class="flex items-center gap-xs text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[16px]">schedule</span>
                                    <span class="font-caption text-caption">{{ $route->duration_minutes ? intdiv($route->duration_minutes, 60).'j '.($route->duration_minutes % 60).'m' : '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full bg-surface-container-low rounded-xl border border-dashed border-outline-variant p-xl text-center">
                        <p class="text-on-surface-variant">Belum ada rute aktif yang ditampilkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</main>
</x-layouts.passenger>
