<x-layouts.passenger title="Pilih Kursi - PO. Akas">
<main class="flex-grow w-full max-w-container-max mx-auto px-gutter py-md">
    <div class="w-full flex items-center justify-between mb-lg relative max-w-3xl mx-auto">
        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-[2px] bg-surface-container-highest -z-10"></div>
        @foreach(['Pilih Kursi','Data Penumpang','Konfirmasi','Bayar'] as $i => $step)<div class="flex flex-col items-center gap-xs z-10 bg-surface px-xs"><div class="w-8 h-8 rounded-full {{ $i===0 ? 'bg-primary text-on-primary' : 'bg-surface-container-highest text-on-surface-variant' }} flex items-center justify-center font-label-form text-label-form">{{ $i+1 }}</div><span class="font-caption text-caption {{ $i===0 ? 'text-primary' : 'text-on-surface-variant' }} hidden md:block">{{ $step }}</span></div>@endforeach
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg">
        <div class="lg:col-span-8 flex flex-col gap-md">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col md:flex-row justify-between gap-sm">
                <div><h1 class="font-h2 text-h2 text-on-surface mb-xs">{{ $schedule->busRoute->originCity->name }} - {{ $schedule->busRoute->destinationCity->name }}</h1><p class="text-on-surface-variant flex items-center gap-xs"><span class="material-symbols-outlined text-[18px]">calendar_month</span>{{ $schedule->departure_at->translatedFormat('d M Y') }} <span class="mx-2">•</span><span class="material-symbols-outlined text-[18px]">schedule</span>{{ $schedule->departure_at->format('H:i') }} WIB</p></div>
                <div class="md:text-right"><p class="font-caption text-caption text-on-surface-variant">Kelas Bus</p><p class="font-label-form text-label-form text-on-surface">{{ ucfirst($schedule->bus->seat_type) }} ({{ $schedule->bus->seat_layout }})</p></div>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-md py-sm"><span class="inline-flex items-center gap-xs"><i class="w-4 h-4 bg-surface-container-lowest border border-primary rounded-sm"></i> Tersedia</span><span class="inline-flex items-center gap-xs"><i class="w-4 h-4 bg-primary rounded-sm"></i> Dipilih</span><span class="inline-flex items-center gap-xs"><i class="w-4 h-4 bg-surface-container-highest rounded-sm"></i> Terisi</span></div>
            <form id="seat-form" method="POST" action="{{ route('schedules.seats.store', $schedule) }}" class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col items-center">@csrf
                <div class="w-full max-w-[360px] flex justify-end mb-xl pb-sm border-b-2 border-dashed border-outline-variant"><div class="flex flex-col items-center"><span class="material-symbols-outlined text-outline">airline_seat_recline_extra</span><span class="font-caption text-caption text-outline">Supir</span></div></div>
                <div class="w-full max-w-[360px] grid grid-cols-5 gap-sm justify-items-center">
                    @foreach($seats->groupBy(fn($s) => $s['row']) as $row => $rowSeats)
                        @foreach($rowSeats as $seat)
                            @if($schedule->bus->seat_layout === '2-2' && $seat['column'] === 3)<div class="w-12 h-12"></div>@endif
                            @php $occupied = in_array($seat['seat_number'], $occupiedSeatNumbers, true); @endphp
                            <button type="button" data-seat-number="{{ $seat['seat_number'] }}" @disabled($occupied) class="seat-btn w-12 h-12 rounded-lg font-label-form flex items-center justify-center {{ $occupied ? 'bg-surface-container-highest text-outline cursor-not-allowed' : 'bg-surface-container-lowest border border-primary text-primary' }}">{{ $seat['seat_number'] }}</button>
                        @endforeach
                    @endforeach
                </div>
                <div id="seat-inputs"></div>
            </form>
        </div>
        <aside class="lg:col-span-4"><div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md sticky top-[88px]"><h2 class="font-h3 text-h3 text-on-surface mb-sm">Ringkasan Pemesanan</h2><div class="flex justify-between mb-xs"><span class="text-on-surface-variant">Kursi Dipilih</span><span id="seat-count" class="font-label-form text-label-form text-primary bg-surface-container-low px-2 py-1 rounded">0 dari {{ $pax }}</span></div><div class="flex flex-wrap gap-xs mb-md min-h-8" id="selected-seats-list"></div><div class="border-t border-outline-variant pt-sm mb-md"><div class="flex justify-between"><span class="text-on-surface-variant">Harga per kursi</span><span>Rp {{ number_format($schedule->price, 0, ',', '.') }}</span></div></div><div class="flex justify-between items-end mb-md"><span class="font-h3 text-h3">Total</span><span id="total-price" class="font-h2 text-h2 text-primary">Rp 0</span></div><button form="seat-form" id="continue-button" disabled class="w-full bg-primary-container text-on-primary disabled:opacity-50 font-label-form text-label-form py-sm rounded-lg flex justify-center items-center gap-xs">Lanjut ke Data Penumpang<span class="material-symbols-outlined text-[18px]">arrow_forward</span></button></div></aside>
    </div>
</main>
@push('scripts')
<script>
const maxSeats = {{ $pax }}; const price = {{ (int) $schedule->price }}; const selected = new Set();
function rupiah(value){ return new Intl.NumberFormat('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits:0}).format(value); }
function render(){ const list=document.getElementById('selected-seats-list'); const inputs=document.getElementById('seat-inputs'); list.innerHTML=''; inputs.innerHTML=''; selected.forEach(num=>{ list.insertAdjacentHTML('beforeend', `<span class="inline-flex items-center px-sm py-1 rounded-full bg-primary-fixed text-on-primary-fixed font-label-form text-label-form">${num}</span>`); inputs.insertAdjacentHTML('beforeend', `<input type="hidden" name="seat_numbers[]" value="${num}">`); }); document.getElementById('seat-count').textContent=`${selected.size} dari ${maxSeats}`; document.getElementById('total-price').textContent=rupiah(selected.size*price); document.getElementById('continue-button').disabled=selected.size!==maxSeats; }
document.querySelectorAll('.seat-btn:not(:disabled)').forEach(btn=>btn.addEventListener('click',()=>{ const num=btn.dataset.seatNumber; if(selected.has(num)){ selected.delete(num); btn.className='seat-btn w-12 h-12 rounded-lg font-label-form flex items-center justify-center bg-surface-container-lowest border border-primary text-primary'; btn.textContent=num; } else { if(selected.size>=maxSeats) return; selected.add(num); btn.className='seat-btn w-12 h-12 rounded-lg font-label-form flex items-center justify-center bg-primary text-on-primary'; btn.innerHTML='<span class="material-symbols-outlined text-[20px]">check</span>'; } render(); }));
render();
</script>
@endpush
</x-layouts.passenger>
