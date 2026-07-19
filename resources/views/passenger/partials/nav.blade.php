<nav class="sticky top-0 z-50 bg-surface shadow-sm border-b border-outline-variant">
    <div class="flex justify-between items-center w-full px-gutter h-xl">
        <div class="flex items-center gap-md">
            <a class="inline-flex items-center" href="{{ route('home') }}" aria-label="Bus Akas">
                <img src="{{ asset('images/akas-logo.png') }}" alt="Bus Akas" class="h-10 w-auto">
            </a>
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
                <a class="hidden md:inline-flex items-center justify-center px-4 py-2 font-label-form text-label-form text-primary border border-primary rounded-lg hover:bg-primary hover:text-on-primary" href="{{ route('login') }}">Masuk</a>
                <a class="hidden md:inline-flex items-center justify-center px-4 py-2 font-label-form text-label-form bg-primary-container text-on-primary rounded-lg shadow-sm hover:opacity-90" href="{{ route('register') }}">Daftar</a>
            @else
                @if(in_array(auth()->user()->role, ['admin', 'operator']))
                    <a class="md:hidden flex items-center justify-center w-10 h-10 text-secondary border border-secondary rounded-lg" href="{{ route('operator.boarding.scan') }}" aria-label="Buka Pemindai Boarding">
                        <span class="material-symbols-outlined" aria-hidden="true">qr_code_scanner</span>
                    </a>
                @endif
                <span class="hidden md:inline text-primary font-label-form text-label-form">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="hidden md:block">@csrf<button class="inline-flex items-center justify-center px-4 py-2 font-label-form text-label-form border border-outline-variant rounded-lg hover:bg-surface-container-low">Keluar</button></form>
            @endguest
            {{-- Hamburger button for mobile --}}
            <button id="mobile-menu-btn" class="md:hidden flex items-center justify-center w-10 h-10 rounded-lg border border-outline-variant text-on-surface-variant hover:bg-surface-container-low" aria-label="Buka menu" aria-expanded="false" aria-controls="mobile-menu">
                <span class="material-symbols-outlined" id="mobile-menu-icon">menu</span>
            </button>
        </div>
    </div>
    {{-- Mobile Dropdown Menu --}}
    <div id="mobile-menu" class="hidden md:hidden border-t border-outline-variant bg-surface">
        <div class="flex flex-col py-sm px-gutter gap-xs">
            <a class="flex items-center gap-sm px-sm py-sm rounded-lg {{ request()->routeIs('home') ? 'bg-primary/10 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container-low' }}" href="{{ route('home') }}">
                <span class="material-symbols-outlined text-[20px]">home</span>Beranda
            </a>
            <a class="flex items-center gap-sm px-sm py-sm rounded-lg {{ request()->routeIs('schedules.*') ? 'bg-primary/10 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container-low' }}" href="{{ route('schedules.index') }}">
                <span class="material-symbols-outlined text-[20px]">directions_bus</span>Jadwal
            </a>
            @auth
                <a class="flex items-center gap-sm px-sm py-sm rounded-lg {{ request()->routeIs('dashboard.*') ? 'bg-primary/10 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container-low' }}" href="{{ route('dashboard.bookings') }}">
                    <span class="material-symbols-outlined text-[20px]">confirmation_number</span>Pemesanan
                </a>
                @if(in_array(auth()->user()->role, ['admin', 'operator']))
                    <a class="flex items-center gap-sm px-sm py-sm rounded-lg {{ request()->routeIs('operator.boarding.*') ? 'bg-secondary/10 text-secondary font-bold' : 'text-on-surface-variant hover:bg-surface-container-low' }}" href="{{ route('operator.boarding.scan') }}">
                        <span class="material-symbols-outlined text-[20px]">qr_code_scanner</span>Scan Boarding
                    </a>
                @endif
                <div class="border-t border-outline-variant mt-xs pt-xs">
                    <p class="px-sm py-xs text-caption text-on-surface-variant">Masuk sebagai <strong>{{ auth()->user()->name }}</strong></p>
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button class="flex items-center gap-sm px-sm py-sm rounded-lg text-on-surface-variant hover:bg-surface-container-low w-full text-left">
                            <span class="material-symbols-outlined text-[20px]">logout</span>Keluar
                        </button>
                    </form>
                </div>
            @else
                <div class="border-t border-outline-variant mt-xs pt-xs flex flex-col gap-xs">
                    <a class="flex items-center justify-center px-4 py-2 font-label-form text-label-form text-primary border border-primary rounded-lg hover:bg-primary hover:text-on-primary" href="{{ route('login') }}">Masuk</a>
                    <a class="flex items-center justify-center px-4 py-2 font-label-form text-label-form bg-primary-container text-on-primary rounded-lg shadow-sm hover:opacity-90" href="{{ route('register') }}">Daftar</a>
                </div>
            @endguest
        </div>
    </div>
</nav>
<script>
    (function() {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const icon = document.getElementById('mobile-menu-icon');
        if (btn && menu) {
            btn.addEventListener('click', function () {
                const isOpen = !menu.classList.contains('hidden');
                menu.classList.toggle('hidden');
                icon.textContent = isOpen ? 'menu' : 'close';
                btn.setAttribute('aria-expanded', String(!isOpen));
            });
        }
    })();
</script>
