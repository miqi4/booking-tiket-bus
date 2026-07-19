<x-layouts.passenger title="Bus Akas - Pesan Tiket Bis Online">
<main>
    <section class="relative bg-inverse-surface overflow-hidden">
        <div class="absolute inset-0 z-0" style="background: linear-gradient(135deg, rgba(93, 93, 93, 0.39) 0%, rgba(0, 79, 157, 0.6) 50%, rgba(0, 0, 0, 0.3) 100%), url('{{ asset('images/bus.png') }}'); background-size: cover; background-position: center;"></div>
        <div class="relative z-10 max-w-container-max mx-auto px-gutter pt-xl pb-[180px]">
            <div class="max-w-2xl">
                <span class="inline-block bg-primary text-on-primary font-label-form text-[12px] px-sm py-1 rounded-full mb-md tracking-wider uppercase">Terpercaya Sejak 1956</span>
                <h1 class="font-h1-mobile text-[40px] md:text-[56px] text-inverse-on-surface mb-sm leading-[1.1] font-extrabold">Perjalanan Aman,<br>Tiba dengan Nyaman.</h1>
                <p class="font-body text-h3 text-inverse-on-surface opacity-80 max-w-lg">Layanan transportasi antar kota Bus Akas dengan armada modern dan jadwal yang selalu tepat waktu.</p>
            </div>
        </div>
        <div class="relative z-20 max-w-container-max mx-auto px-gutter -mt-[100px] mb-xl">
            <div class="bg-surface rounded-xl shadow-[0px_8px_24px_rgba(0,0,0,0.12)] border border-outline-variant p-md md:p-lg">
                <div class="flex items-center gap-sm border-b border-surface-container-highest pb-sm mb-md">
                    <span class="material-symbols-outlined text-primary" aria-hidden="true">directions_bus</span>
                    <h2 class="font-h3 text-h3 text-on-surface">Cari Tiket Bis</h2>
                </div>
                <form class="grid grid-cols-1 md:grid-cols-12 gap-md items-end" method="GET" action="{{ route('schedules.index') }}" id="searchForm">
                    <div class="md:col-span-3">
                        <label class="flex flex-col gap-xs">
                            <span class="font-label-form text-label-form text-on-surface-variant">Kota Asal</span>
                            <select name="from" required class="rounded-lg border-outline-variant bg-surface py-3 focus:ring-primary focus:border-primary" id="fromCity">
                                <option value="">Pilih kota asal</option>
                                @foreach($cities as $city)<option value="{{ $city->id }}">{{ $city->name }}</option>@endforeach
                            </select>
                        </label>
                    </div>
                    <div class="md:col-span-3">
                        <label class="flex flex-col gap-xs">
                            <span class="font-label-form text-label-form text-on-surface-variant">Kota Tujuan</span>
                            <select name="to" required class="rounded-lg border-outline-variant bg-surface py-3 focus:ring-primary focus:border-primary" id="toCity">
                                <option value="">Pilih kota tujuan</option>
                                @foreach($cities as $city)<option value="{{ $city->id }}">{{ $city->name }}</option>@endforeach
                            </select>
                        </label>
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex flex-col gap-xs">
                            <span class="font-label-form text-label-form text-on-surface-variant">Tanggal</span>
                            <input name="date" required value="{{ now()->addDay()->toDateString() }}" min="{{ now()->toDateString() }}" class="rounded-lg border-outline-variant bg-surface py-3 focus:ring-primary focus:border-primary" type="date">
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
                <script>
                    document.getElementById('searchForm').addEventListener('submit', function(e) {
                        const from = document.getElementById('fromCity').value;
                        const to = document.getElementById('toCity').value;
                        
                        if (from && to && from === to) {
                            e.preventDefault();
                            alert('Kota asal dan tujuan tidak boleh sama');
                            return false;
                        }
                    });
                </script>
            </div>
        </div>
    </section>
    <section class="py-xl bg-background overflow-hidden">
        <div class="max-w-container-max mx-auto px-gutter mb-xl">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-md">
                <div class="max-w-xl">
                    <h2 class="font-h1 text-[32px] text-on-surface mb-xs">Rute Populer</h2>
                    <p class="font-body text-h3 text-on-surface-variant">Pilihan destinasi favorit penumpang kami untuk perjalanan yang nyaman.</p>
                </div>
                <a href="{{ route('schedules.index') }}" class="text-primary font-label-form flex items-center gap-xs hover:underline">Lihat Semua Jadwal <span class="material-symbols-outlined text-[18px]">arrow_forward</span></a>
            </div>
        </div>
            
        <style>
            @keyframes marquee {
                0% { transform: translateX(0); }
                100% { transform: translateX(calc(-50% - 0.5rem)); } /* -50% minus half the gap */
            }
            .animate-marquee {
                animation: marquee 45s linear infinite;
                display: flex;
                width: max-content;
            }
            .animate-marquee:hover {
                animation-play-state: paused;
            }
        </style>
        
        <div class="relative w-full group/marquee">
            @if($popularRoutes->count() > 0)
                <div class="animate-marquee gap-md px-gutter">
                    <!-- We loop twice to create a seamless scrolling effect -->
                    @for($i = 0; $i < 2; $i++)
                        @foreach($popularRoutes as $index => $route)
                            <a href="{{ route('schedules.index', ['from' => $route->origin_city_id, 'to' => $route->destination_city_id, 'date' => now()->addDay()->toDateString(), 'pax' => 1]) }}" 
                               class="group relative flex flex-col overflow-hidden rounded-2xl border border-outline-variant bg-surface-container-lowest transition-all hover:shadow-lg hover:border-primary-container shrink-0 w-[320px] md:w-[380px]">
                                <div class="aspect-[16/9] bg-primary-fixed flex items-center justify-center relative overflow-hidden">
                                    <img src="{{ asset('images/bus.png') }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110 opacity-80" alt="Route">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </div>
                                <div class="p-md relative bg-surface-container-lowest z-10 flex-1 flex flex-col justify-center">
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
                        @endforeach
                    @endfor
                </div>
            @else
                <div class="max-w-container-max mx-auto px-gutter">
                    <div class="bg-surface-container-low rounded-xl border border-dashed border-outline-variant p-xl text-center">
                        <p class="text-on-surface-variant">Belum ada rute aktif yang ditampilkan.</p>
                    </div>
                </div>
            @endif
        </div>
    </section>
    
    <section class="py-xl bg-surface-container-lowest">
        <div class="max-w-full mx-auto px-[150px]">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-xl">
                <div class="flex flex-col gap-md p-xl bg-surface rounded-xl border border-outline-variant hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-[32px]">support_agent</span>
                    </div>
                    <div>
                        <h3 class="font-h3 text-h3 text-on-surface mb-xs">Layanan</h3>
                        <p class="text-body text-on-surface-variant">Kepuasan penumpang adalah prioritas kami. Nikmati kemudahan dan kenyamanan layanan Juragan99.</p>
                    </div>
                </div>
                
                <div class="flex flex-col gap-md p-xl bg-surface rounded-xl border border-outline-variant hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-[32px]">directions_bus</span>
                    </div>
                    <div>
                        <h3 class="font-h3 text-h3 text-on-surface mb-xs">Armada Handal</h3>
                        <p class="text-body text-on-surface-variant">Kami menggunakan armada dengan keluaran terbaru. Dilengkapi dengan fitur-fitur yang akan menemani perjalanan pemudik.</p>
                    </div>
                </div>
                
                <div class="flex flex-col gap-md p-xl bg-surface rounded-xl border border-outline-variant hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-[32px]">sell</span>
                    </div>
                    <div>
                        <h3 class="font-h3 text-h3 text-on-surface mb-xs">Harga Terbaik</h3>
                        <p class="text-body text-on-surface-variant">Dapatkan penawaran harga terbaik dari kami. Juragan99 memberikan harga terbaik dengan layanan terbaik, sultan!</p>
                    </div>
                </div>
                
                <div class="flex flex-col gap-md p-xl bg-surface rounded-xl border border-outline-variant hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-[32px]">confirmation_number</span>
                    </div>
                    <div>
                        <h3 class="font-h3 text-h3 text-on-surface mb-xs">Reservasi Online</h3>
                        <p class="text-body text-on-surface-variant">Semakin mudah untuk melakukan transaksi pembelian tiket dapat dilakukan secara online.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
</x-layouts.passenger>
