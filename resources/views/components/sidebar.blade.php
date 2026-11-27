@php
    $user = auth()->user();
    $items = config("navigation.{$user->role}", []);

    $pendingAsesor = $user->role === 'admin'
        ? \App\Models\AsesorProfile::where('status_verifikasi', 'menunggu')->count()
        : null;

    $totalAsesi = $user->role === 'asesor'
        ? \App\Models\User::where('role', 'asesi')->count()
        : null;
@endphp

<aside id="app-sidebar" class="fixed inset-y-0 left-0 z-40 w-72 bg-white border-r border-slate-100 -translate-x-full lg:translate-x-0 transition-transform duration-200 flex flex-col">
    <div class="px-5 py-6 flex items-center gap-3 border-b border-slate-100">
        <div class="w-11 h-11 rounded-xl bg-emerald-700 flex items-center justify-center text-white">
            <x-icon name="certificate" class="w-6 h-6" />
        </div>
        <div>
            <p class="font-semibold text-slate-800 leading-tight">Lembaga Sertifikasi</p>
            <p class="text-xs text-emerald-600 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Portal Asesi Resmi
            </p>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-5 space-y-1">
        <p class="px-2 pb-2 text-xs font-semibold text-slate-400 tracking-wide">NAVIGASI UTAMA</p>

        @foreach ($items as $item)
            @php
                $url = route($item['route'], $item['params'] ?? []);
                $active = request()->url() === $url;
                $badge = $item['badge'] ?? null;
                if (($item['dynamic_badge'] ?? null) === 'pending_asesor') {
                    $badge = $pendingAsesor.' Baru';
                }
                if (($item['dynamic_badge'] ?? null) === 'total_asesi') {
                    $badge = (string) $totalAsesi;
                }
            @endphp
            <a href="{{ $url }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                      {{ $active ? 'bg-emerald-800 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                <x-icon :name="$item['icon']" class="w-5 h-5 {{ $active ? 'text-white' : 'text-slate-400' }}" />
                <span class="flex-1">{{ $item['label'] }}</span>
                @if ($badge)
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $active ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $badge }}</span>
                @endif
                @if (! empty($item['dot']))
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                @endif
            </a>
        @endforeach
    </nav>

    <div class="px-4 pb-4 space-y-3">
        @if ($user->isAdmin())
            <p class="px-2 pb-1 text-xs font-semibold text-slate-400 tracking-wide">SISTEM & AKUN</p>
            <a href="{{ route('placeholder', ['title' => 'Pengaturan Sistem']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-emerald-50 text-emerald-800 font-medium text-sm">
                <span class="w-8 h-8 rounded-lg bg-emerald-700 text-white flex items-center justify-center">
                    <x-icon name="gear" class="w-4 h-4" />
                </span>
                Pengaturan sistem
            </a>
            <div class="px-3 py-3 rounded-xl border border-slate-100 text-sm">
                <p class="font-medium text-slate-800">{{ $user->name }}</p>
                <p class="text-slate-400 text-xs">Status: {{ ucfirst($user->status) }}</p>
            </div>
        @else
            <div class="px-3 py-3 rounded-xl bg-emerald-50 text-sm space-y-1">
                <p class="font-semibold text-slate-800 flex items-center gap-2">
                    <x-icon name="headset" class="w-4 h-4 text-emerald-700" /> Pusat Bantuan
                </p>
                <p class="text-slate-500 text-xs">Butuh panduan pengisian dokumen asesmen?</p>
                <a href="mailto:admin@lsp-sekolah.sch.id" class="text-emerald-700 text-xs font-medium">Hubungi Admin →</a>
            </div>

            @if ($user->isAsesor())
                <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl border border-slate-100 text-sm">
                    <span class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-semibold text-xs">
                        {{ collect(explode(' ', $user->name))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('') }}
                    </span>
                    <div class="flex-1">
                        <p class="font-medium text-slate-800 leading-tight">{{ $user->name }}</p>
                        <p class="text-slate-400 text-xs">{{ $user->asesorProfile?->no_registrasi_bnsp ?? '-' }}</p>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </div>
            @endif
        @endif
@if (auth()->check() && auth()->user()->role === 'asesi')
    @php
        $u = auth()->user();
        $parts = preg_split('/\s+/', trim($u->name ?? 'A'));
        $inisial = strtoupper(mb_substr($parts[0] ?? 'A', 0, 1) . (isset($parts[1]) ? mb_substr($parts[1], 0, 1) : ''));
        $nisn = $u->asesiProfile?->nis_nisn ?: '-';
        $isProfilActive = request()->routeIs('asesi.profil.*');
    @endphp
    <a href="{{ route('asesi.profil.index') }}"
       class="mb-3 flex items-center gap-3 rounded-xl border-l-4 border-l-[#0b5a49] bg-gradient-to-r from-emerald-50/90 to-slate-50 p-3 transition-all hover:shadow-sm {{ $isProfilActive ? 'ring-1 ring-[#0b5a49]/40 bg-emerald-50' : '' }}">
        @if ($u->foto_profil)
            <img src="{{ asset('storage/' . $u->foto_profil) }}" alt="{{ $u->name }}"
                 class="h-10 w-10 shrink-0 rounded-full object-cover ring-1 ring-emerald-200">
        @else
            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full border border-emerald-300 bg-white text-xs font-bold text-[#0b5a49]">
                {{ $inisial }}
            </span>
        @endif
        <div class="min-w-0 flex-1">
            <p class="truncate text-xs font-bold text-[#0b5a49]">{{ $u->name }}</p>
            <p class="truncate font-mono text-[10px] text-slate-500">NISN: {{ $nisn }}</p>
        </div>
        <span class="h-2 w-2 shrink-0 rounded-full bg-emerald-500"></span>
    </a>
@endif
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2.5 text-sm font-medium text-rose-500 hover:bg-rose-50 rounded-xl">
                <x-icon name="logout" class="w-5 h-5" /> Log Out
            </button>
        </form>
    </div>
</aside>

<div id="sidebar-backdrop" class="fixed inset-0 bg-black/30 z-30 hidden lg:hidden"></div>