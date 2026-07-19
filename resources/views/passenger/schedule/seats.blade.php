<x-layouts.passenger title="Pilih Kursi - Bus Akas">
<main class="flex-grow w-full max-w-container-max mx-auto px-gutter py-md">
    <div class="w-full flex items-center justify-between mb-lg relative max-w-3xl mx-auto">
        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-[2px] bg-surface-container-highest -z-10"></div>
        @foreach(['Pilih Kursi','Data Penumpang','Konfirmasi','Bayar'] as $i => $step)<div class="flex flex-col items-center gap-xs z-10 bg-surface px-xs"><div class="w-8 h-8 rounded-full {{ $i===0 ? 'bg-primary text-on-primary' : 'bg-surface-container-highest text-on-surface-variant' }} flex items-center justify-center font-label-form text-label-form">{{ $i+1 }}</div><span class="font-caption text-caption {{ $i===0 ? 'text-primary' : 'text-on-surface-variant' }} text-[10px] md:text-caption text-center max-w-[56px]">{{ $step }}</span></div>@endforeach
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg">
        <div class="lg:col-span-8 flex flex-col gap-md">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col md:flex-row justify-between gap-sm">
                <div><h1 class="font-h2 text-h2 text-on-surface mb-xs">{{ $schedule->busRoute->originCity->name }} - {{ $schedule->busRoute->destinationCity->name }}</h1><p class="text-on-surface-variant flex items-center gap-xs"><span class="material-symbols-outlined text-[18px]">calendar_month</span>{{ $schedule->departure_at->translatedFormat('d M Y') }} <span class="mx-2">•</span><span class="material-symbols-outlined text-[18px]">schedule</span>{{ $schedule->departure_at->format('H:i') }} WIB</p></div>
                <div class="md:text-right"><p class="font-caption text-caption text-on-surface-variant">Kelas Bus</p><p class="font-label-form text-label-form text-on-surface">{{ ucfirst($schedule->bus->seat_type) }} ({{ $schedule->bus->seat_layout }})</p></div>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-md py-sm" role="list" aria-label="Legenda Kursi">
                <span class="inline-flex items-center gap-xs" role="listitem">
                    <span class="w-5 h-5 bg-surface-container-lowest border border-primary rounded-sm" aria-hidden="true"></span>
                    Tersedia
                </span>
                <span class="inline-flex items-center gap-xs" role="listitem">
                    <span class="w-5 h-5 bg-primary rounded-sm" aria-hidden="true"></span>
                    Dipilih
                </span>
                <span class="inline-flex items-center gap-xs" role="listitem">
                    <span class="w-5 h-5 bg-surface-container-highest rounded-sm" aria-hidden="true"></span>
                    Terisi
                </span>
            </div>
            <form id="seat-form" method="POST" action="{{ route('schedules.seats.store', $schedule) }}" class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col items-center">@csrf
                <div class="w-full max-w-[360px] flex justify-end mb-xl pb-sm border-b-2 border-dashed border-outline-variant">
                    <div class="flex flex-col items-center" aria-label="Posisi Supir">
                        <span class="material-symbols-outlined text-outline" aria-hidden="true">airline_seat_recline_extra</span>
                        <span class="font-caption text-caption text-outline">Supir</span>
                    </div>
                </div>
                @php
                    $gridCols = match($schedule->bus->seat_layout) {
                        '2-2' => 'grid-cols-5',
                        '2-1' => 'grid-cols-4',
                        '1-2' => 'grid-cols-4',
                        default => 'grid-cols-5',
                    };
                @endphp
                <div class="w-full max-w-[360px] grid {{ $gridCols }} gap-y-4 gap-x-2 justify-items-center">
                    @foreach($seats->groupBy(fn($s) => $s['row']) as $row => $rowSeats)
                        @foreach($rowSeats as $seat)
                            @if($schedule->bus->seat_layout === '2-2' && $seat['column'] === 3)
                                <div class="w-10 h-10 md:w-14 md:h-14"></div>
                            @endif
                            @if($schedule->bus->seat_layout === '2-1' && $seat['column'] === 3)
                                <div class="w-10 h-10 md:w-14 md:h-14"></div>
                            @endif
                            @if($schedule->bus->seat_layout === '1-2' && $seat['column'] === 2)
                                <div class="w-10 h-10 md:w-14 md:h-14"></div>
                            @endif
                            
                            @php $occupied = in_array($seat['seat_number'], $occupiedSeatNumbers, true); @endphp
                            <button type="button" 
                                data-seat-number="{{ $seat['seat_number'] }}" 
                                @disabled($occupied) 
                                aria-label="Kursi {{ $seat['seat_number'] }}{{ $occupied ? ' - Terisi' : '' }}"
                                class="seat-btn w-10 h-10 md:w-14 md:h-14 rounded-lg font-label-form flex items-center justify-center text-sm {{ $occupied ? 'bg-surface-container-highest text-outline cursor-not-allowed' : 'bg-surface-container-lowest border border-primary text-primary' }}">
                                {{ $seat['seat_number'] }}
                            </button>
                        @endforeach
                    @endforeach
                </div>
                <div id="seat-inputs"></div>
            </form>
        </div>
        <aside class="hidden lg:block lg:col-span-4"><div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md sticky top-[88px]"><h2 class="font-h3 text-h3 text-on-surface mb-sm">Ringkasan Pemesanan</h2><div class="flex justify-between mb-xs"><span class="text-on-surface-variant">Kursi Dipilih</span><span id="seat-count-desktop" class="font-label-form text-label-form text-primary bg-surface-container-low px-2 py-1 rounded">0 dari {{ $pax }}</span></div><div class="flex flex-wrap gap-xs mb-md min-h-8" id="selected-seats-list-desktop"></div><div class="border-t border-outline-variant pt-sm mb-md"><div class="flex justify-between"><span class="text-on-surface-variant">Harga per kursi</span><span>Rp {{ number_format($schedule->price, 0, ',', '.') }}</span></div></div><div class="flex justify-between items-end mb-md"><span class="font-h3 text-h3">Total</span><span id="total-price-desktop" class="font-h2 text-h2 text-primary">Rp 0</span></div><button form="seat-form" id="continue-button-desktop" disabled class="w-full bg-primary-container text-on-primary disabled:opacity-50 font-label-form text-label-form py-sm rounded-lg flex justify-center items-center gap-xs">Lanjut ke Data Penumpang<span class="material-symbols-outlined text-[18px]">arrow_forward</span></button></div></aside>
        <aside class="lg:hidden"><div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md"><h2 class="font-h3 text-h3 text-on-surface mb-sm">Ringkasan Pemesanan</h2><div class="flex justify-between mb-xs"><span class="text-on-surface-variant">Kursi Dipilih</span><span id="seat-count-mobile" class="font-label-form text-label-form text-primary bg-surface-container-low px-2 py-1 rounded">0 dari {{ $pax }}</span></div><div class="flex flex-wrap gap-xs mb-md min-h-8" id="selected-seats-list-mobile"></div><div class="border-t border-outline-variant pt-sm mb-md"><div class="flex justify-between"><span class="text-on-surface-variant">Harga per kursi</span><span>Rp {{ number_format($schedule->price, 0, ',', '.') }}</span></div></div><div class="flex justify-between items-end mb-md"><span class="font-h3 text-h3">Total</span><span id="total-price-mobile" class="font-h2 text-h2 text-primary">Rp 0</span></div><button form="seat-form" id="continue-button-mobile" disabled class="w-full bg-primary-container text-on-primary disabled:opacity-50 font-label-form text-label-form py-sm rounded-lg flex justify-center items-center gap-xs">Lanjut ke Data Penumpang<span class="material-symbols-outlined text-[18px]">arrow_forward</span></button></div></aside>
    </div>
</main>
@push('scripts')
<script>
const maxSeats = {{ $pax }}; const price = {{ (int) $schedule->price }}; const selected = new Set();
function rupiah(value){ return new Intl.NumberFormat('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits:0}).format(value); }
function render(){
    const inputs=document.getElementById('seat-inputs');
    inputs.innerHTML='';
    selected.forEach(num=>{
        inputs.insertAdjacentHTML('beforeend', `<input type="hidden" name="seat_numbers[]" value="${num}">`);
    });
    const countText=`${selected.size} dari ${maxSeats}`;
    const totalText=rupiah(selected.size*price);
    const isComplete=selected.size===maxSeats;
    
    const deskList=document.getElementById('selected-seats-list-desktop');
    const deskCount=document.getElementById('seat-count-desktop');
    const deskTotal=document.getElementById('total-price-desktop');
    const deskBtn=document.getElementById('continue-button-desktop');
    if(deskList){ deskList.innerHTML=''; selected.forEach(num=>{ deskList.insertAdjacentHTML('beforeend', `<span class="inline-flex items-center px-sm py-1 rounded-full bg-primary-fixed text-on-primary-fixed font-label-form text-label-form">${num}</span>`); }); }
    if(deskCount) deskCount.textContent=countText;
    if(deskTotal) deskTotal.textContent=totalText;
    if(deskBtn) deskBtn.disabled=!isComplete;
    
    const mobList=document.getElementById('selected-seats-list-mobile');
    const mobCount=document.getElementById('seat-count-mobile');
    const mobTotal=document.getElementById('total-price-mobile');
    const mobBtn=document.getElementById('continue-button-mobile');
    if(mobList){ mobList.innerHTML=''; selected.forEach(num=>{ mobList.insertAdjacentHTML('beforeend', `<span class="inline-flex items-center px-sm py-1 rounded-full bg-primary-fixed text-on-primary-fixed font-label-form text-label-form">${num}</span>`); }); }
    if(mobCount) mobCount.textContent=countText;
    if(mobTotal) mobTotal.textContent=totalText;
    if(mobBtn) mobBtn.disabled=!isComplete;
}
document.querySelectorAll('.seat-btn:not(:disabled)').forEach(btn=>btn.addEventListener('click',()=>{ const num=btn.dataset.seatNumber; if(selected.has(num)){ selected.delete(num); btn.className='seat-btn w-10 h-10 md:w-14 md:h-14 rounded-lg font-label-form text-sm flex items-center justify-center bg-surface-container-lowest border border-primary text-primary'; btn.textContent=num; } else { if(selected.size>=maxSeats) return; selected.add(num); btn.className='seat-btn w-10 h-10 md:w-14 md:h-14 rounded-lg font-label-form text-sm flex items-center justify-center bg-primary text-on-primary'; btn.innerHTML='<span class="material-symbols-outlined text-[20px]">check</span>'; } render(); }));
render();
</script>
@endpush
</x-layouts.passenger>
