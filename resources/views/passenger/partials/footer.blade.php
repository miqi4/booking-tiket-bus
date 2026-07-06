<footer class="bg-surface-container-lowest mt-auto border-t border-outline-variant">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-lg py-lg px-gutter w-full">
        <div><span class="text-h3 font-h3 text-primary">Bus Akas</span><p class="font-caption text-caption text-on-surface-variant mt-xs">Booking tiket bus online yang rapi, cepat, dan mudah dipantau.</p></div>
        <div class="md:col-span-3 flex flex-col md:flex-row gap-md md:justify-end">
            <a class="text-on-surface-variant hover:text-primary" href="{{ route('home') }}">Beranda</a>
            <a class="text-on-surface-variant hover:text-primary" href="{{ route('schedules.index') }}">Jadwal</a>
            <a class="text-on-surface-variant hover:text-primary" href="/admin">Admin</a>
        </div>
    </div>
</footer>
