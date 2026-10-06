<header class="bg-white border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        <a href="{{ route('guest.index') }}" class="text-xl font-bold text-emerald-800">
            Lembaga Sertifikasi Profesi
        </a>

        <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
            <a href="{{ route('guest.index') }}" class="{{ request()->routeIs('guest.index') ? 'text-emerald-800 border-b-2 border-emerald-700 pb-1' : 'hover:text-emerald-700' }}">Beranda</a>
            <a href="{{ route('placeholder', ['title' => 'Skema']) }}" class="hover:text-emerald-700">Skema</a>
            <a href="{{ route('placeholder', ['title' => 'Info']) }}" class="hover:text-emerald-700">Info</a>
            <a href="{{ route('placeholder', ['title' => 'Tentang']) }}" class="hover:text-emerald-700">Tentang</a>
        </nav>

        <div class="hidden md:flex items-center gap-3">
            <a href="{{ route('login') }}" class="px-5 py-2 rounded-full border border-emerald-700 text-emerald-800 text-sm font-medium hover:bg-emerald-50">Login</a>
            <a href="{{ route('register') }}" class="px-5 py-2 rounded-full bg-emerald-800 text-white text-sm font-medium hover:bg-emerald-900">Daftar</a>
        </div>

        <button id="navbar-toggle" class="md:hidden text-slate-700" aria-label="Menu">
            <x-icon name="menu" />
        </button>
    </div>

    <div id="navbar-mobile" class="hidden md:hidden border-t border-slate-100 px-4 py-4 space-y-3">
        <a href="{{ route('guest.index') }}" class="block text-slate-700">Beranda</a>
        <a href="{{ route('placeholder', ['title' => 'Skema']) }}" class="block text-slate-700">Skema</a>
        <a href="{{ route('placeholder', ['title' => 'Info']) }}" class="block text-slate-700">Info</a>
        <a href="{{ route('placeholder', ['title' => 'Tentang']) }}" class="block text-slate-700">Tentang</a>
        <div class="flex gap-3 pt-2">
            <a href="{{ route('login') }}" class="flex-1 text-center px-4 py-2 rounded-full border border-emerald-700 text-emerald-800 text-sm font-medium">Login</a>
            <a href="{{ route('register') }}" class="flex-1 text-center px-4 py-2 rounded-full bg-emerald-800 text-white text-sm font-medium">Daftar</a>
        </div>
    </div>
</header>

<script>
    document.getElementById('navbar-toggle')?.addEventListener('click', () => {
        document.getElementById('navbar-mobile')?.classList.toggle('hidden');
    });
</script>