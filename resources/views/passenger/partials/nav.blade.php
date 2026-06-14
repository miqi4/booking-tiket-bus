<nav class="sticky top-0 z-50 bg-surface shadow-sm border-b border-outline-variant">
    <div class="flex justify-between items-center w-full px-gutter max-w-container-max mx-auto h-xl">
        <div class="flex items-center gap-md">
            <a class="text-h2 font-h2 text-primary font-extrabold tracking-tight" href="{{ route('home') }}">PO. Akas</a>
            <div class="hidden md:flex items-center gap-sm h-full">
                <a class="h-xl flex items-center px-sm {{ request()->routeIs('home') ? 'text-primary font-bold border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-low' }}" href="{{ route('home') }}">Beranda</a>
                <a class="h-xl flex items-center px-sm {{ request()->routeIs('schedules.*') ? 'text-primary font-bold border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-low' }}" href="{{ route('schedules.index') }}">Jadwal</a>
                @auth
                    <a class="h-xl flex items-center px-sm {{ request()->routeIs('dashboard.*') ? 'text-primary font-bold border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-low' }}" href="{{ route('dashboard.bookings') }}">Pemesanan</a>
                    @if(in_array(auth()->user()->role, ['admin', 'operator']))
                        <a class="h-xl flex items-center px-sm gap-xs {{ request()->routeIs('operator.boarding.*') ? 'text-secondary font-bold border-b-2 border-secondary' : 'text-on-surface-variant hover:text-secondary hover:bg-surface-container-low' }}" href="{{ route('operator.boarding.scan') }}" aria-label="Buka Pemindai Boarding">
                            <span class="material-symbols-outlined text-[20px]" aria-hidden="true">qr_code_scanner</span>
                            Scan Boarding
                        </a>
                    @endif
                @endauth
            </div>
        </div>
        <div class="flex items-center gap-sm">
            @guest
                <a class="inline-flex items-center justify-center px-4 py-2 font-label-form text-label-form text-primary border border-primary rounded-lg hover:bg-primary hover:text-on-primary" href="{{ route('login') }}">Masuk</a>
                <a class="inline-flex items-center justify-center px-4 py-2 font-label-form text-label-form bg-primary-container text-on-primary rounded-lg shadow-sm hover:opacity-90" href="{{ route('register') }}">Daftar</a>
            @else
                @if(in_array(auth()->user()->role, ['admin', 'operator']))
                    <a class="md:hidden flex items-center justify-center w-10 h-10 text-secondary border border-secondary rounded-lg" href="{{ route('operator.boarding.scan') }}" aria-label="Buka Pemindai Boarding">
                        <span class="material-symbols-outlined" aria-hidden="true">qr_code_scanner</span>
                    </a>
                @endif
                <a class="hidden md:inline-flex text-primary font-label-form text-label-form" href="{{ route('dashboard.profile') }}">{{ auth()->user()->name }}</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="inline-flex items-center justify-center px-4 py-2 font-label-form text-label-form border border-outline-variant rounded-lg hover:bg-surface-container-low">Keluar</button></form>
            @endguest
        </div>
    </div>
</nav>
