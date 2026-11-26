@php
    $statAktif = $statistikTahunan[$tahunAktif] ?? reset($statistikTahunan);
    $bulanData = $statAktif['bulan'];
    $bulanAktifIdx = (int) ($statAktif['bulan_aktif_index'] ?? 5);

    $namaBulanPenuh = [
        'Jan' => 'Januari', 'Feb' => 'Februari', 'Mar' => 'Maret', 'Apr' => 'April',
        'Mei' => 'Mei', 'Jun' => 'Juni', 'Jul' => 'Juli', 'Agu' => 'Agustus',
        'Sep' => 'September', 'Okt' => 'Oktober', 'Nov' => 'November', 'Des' => 'Desember',
    ];

    // Skala sumbu Y dinamis: selalu cukup untuk nilai tertinggi (kelipatan 10, minimal 40)
    $maxTotal = (int) (collect($bulanData)->max('total') ?? 0);
    $axisMax  = max(40, (int) (ceil($maxTotal / 10) * 10));
    $ticks    = range($axisMax, 0, -10);

    $totalKompeten = (int) ($statusKelulusan['kompeten'] ?? 58);
    $totalProses   = (int) ($statusKelulusan['sedang_proses'] ?? 18);
    $totalBelum    = (int) ($statusKelulusan['belum_kompeten'] ?? 13);
    $totalPeriode  = max(1, $totalKompeten + $totalProses + $totalBelum);

    $persenKompeten = (int) ($statusKelulusan['persentase'] ?? round(($totalKompeten / $totalPeriode) * 100));

    // Donut: 3 segmen saja (tanpa ring abu-abu), pathLength=100 sehingga segmen = persentase langsung
    $warnaDonut = ['lulus' => '#007066', 'proses' => '#a5c8fa', 'belum' => '#fda4af'];
    $donutSegmen = [];
    $mulai = 0;
    foreach ([
        ['p' => ($totalKompeten / $totalPeriode) * 100, 'warna' => $warnaDonut['lulus'],  'delay' => 750],
        ['p' => ($totalProses / $totalPeriode) * 100,   'warna' => $warnaDonut['proses'], 'delay' => 1050],
        ['p' => ($totalBelum / $totalPeriode) * 100,    'warna' => $warnaDonut['belum'],  'delay' => 1250],
    ] as $seg) {
        $donutSegmen[] = [
            'panjang' => number_format($seg['p'], 3, '.', ''),
            'sisa'    => number_format(100 - $seg['p'], 3, '.', ''),
            'offset'  => number_format(-$mulai, 3, '.', ''),
            'warna'   => $seg['warna'],
            'delay'   => $seg['delay'],
        ];
        $mulai += $seg['p'];
    }

    $jadwalUrl = \Illuminate\Support\Facades\Route::has('asesor.jadwal.index')
        ? route('asesor.jadwal.index')
        : (\Illuminate\Support\Facades\Route::has('asesor.daftar-asesi.index') ? route('asesor.daftar-asesi.index') : '#');
@endphp

<x-layouts.app :title="$title ?? 'Dashboard Asesor'">
<style>
    /* ===== Animasi muncul saat halaman pertama kali dirender ===== */
    #asesor-dashboard .anim-in {
        animation: dash-rise .65s cubic-bezier(.22, 1, .36, 1) backwards;
        animation-delay: var(--d, 0ms);
    }
    #asesor-dashboard .anim-bar {
        transform-origin: bottom center;
        animation: dash-bar-grow .8s cubic-bezier(.22, 1, .36, 1) backwards;
        animation-delay: var(--d, 0ms);
    }
    #asesor-dashboard .anim-pop {
        animation: dash-pop .6s cubic-bezier(.34, 1.56, .64, 1) backwards;
        animation-delay: var(--d, 0ms);
    }
    #asesor-dashboard .anim-donut {
        animation: dash-donut-draw .9s cubic-bezier(.4, 0, .2, 1) backwards;
        animation-delay: var(--d, 0ms);
    }
    @keyframes dash-rise     { from { opacity: 0; transform: translateY(18px); } }
    @keyframes dash-bar-grow { from { opacity: 0; transform: scaleY(0); } }
    @keyframes dash-pop      { from { opacity: 0; transform: scale(.85); } }
    @keyframes dash-donut-draw { from { stroke-dasharray: 0 100; } }

    @media (prefers-reduced-motion: reduce) {
        #asesor-dashboard .anim-in,
        #asesor-dashboard .anim-bar,
        #asesor-dashboard .anim-pop,
        #asesor-dashboard .anim-donut { animation: none; }
    }

    /* ===== Tema warna ===== */
    #asesor-dashboard .tone-emerald { --t-from:#10b981; --t-to:#0d9488; --t-soft:#ecfdf5; --t-ring:#a7f3d0; --t-text:#047857; --t-glow:rgba(16,185,129,.30); }
    #asesor-dashboard .tone-sky     { --t-from:#0ea5e9; --t-to:#2563eb; --t-soft:#f0f9ff; --t-ring:#bae6fd; --t-text:#0369a1; --t-glow:rgba(14,165,233,.30); }
    #asesor-dashboard .tone-violet  { --t-from:#8b5cf6; --t-to:#4f46e5; --t-soft:#f5f3ff; --t-ring:#ddd6fe; --t-text:#6d28d9; --t-glow:rgba(139,92,246,.30); }
    #asesor-dashboard .tone-rose    { --t-from:#f43f5e; --t-to:#f97316; --t-soft:#fff1f2; --t-ring:#fecdd3; --t-text:#e11d48; --t-glow:rgba(244,63,94,.30); }
    #asesor-dashboard .tone-amber   { --t-from:#f59e0b; --t-to:#f97316; --t-soft:#fffbeb; --t-ring:#fde68a; --t-text:#b45309; --t-glow:rgba(245,158,11,.30); }

    /* Kartu ringkasan */
    #asesor-dashboard .stat-card {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--t-ring);
        background: linear-gradient(135deg, var(--t-soft) 0%, #ffffff 65%);
        box-shadow: 0 1px 3px rgba(15, 23, 42, .04);
        transition: transform .25s ease, box-shadow .25s ease;
    }
    #asesor-dashboard .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 32px -14px var(--t-glow);
    }
    #asesor-dashboard .stat-card::before {
        content: "";
        position: absolute; inset: 0 0 auto 0; height: 4px;
        background: linear-gradient(90deg, var(--t-from), var(--t-to));
    }
    #asesor-dashboard .stat-card::after {
        content: "";
        position: absolute; right: -34px; top: -34px; width: 140px; height: 140px;
        border-radius: 9999px; pointer-events: none;
        background: radial-gradient(circle, var(--t-glow) 0%, transparent 70%);
        opacity: .55;
    }
    #asesor-dashboard .stat-card > * { position: relative; z-index: 1; }

    #asesor-dashboard .tone-icon {
        color: #fff;
        background: linear-gradient(135deg, var(--t-from), var(--t-to));
        box-shadow: 0 8px 16px -6px var(--t-glow);
    }
    #asesor-dashboard .tone-chip {
        color: var(--t-text);
        background: color-mix(in srgb, var(--t-from) 12%, #ffffff);
    }
    #asesor-dashboard .tone-dategrad {
        color: #fff;
        background: linear-gradient(135deg, var(--t-from), var(--t-to));
        box-shadow: 0 6px 14px -6px var(--t-glow);
    }

    /* Banner profil */
    #asesor-dashboard .banner {
        background: linear-gradient(120deg, #ecfdf5 0%, #ffffff 52%, #f0f9ff 100%);
    }
    #asesor-dashboard .banner::after {
        content: "";
        position: absolute; right: -60px; top: -80px; width: 260px; height: 260px;
        border-radius: 9999px; pointer-events: none;
        background: radial-gradient(circle, rgba(16,185,129,.16) 0%, transparent 70%);
    }
    #asesor-dashboard .banner > * { position: relative; z-index: 1; }
    #asesor-dashboard .avatar-grad { background: linear-gradient(135deg, #10b981 0%, #0f766e 100%); box-shadow: 0 10px 20px -8px rgba(16,185,129,.55); }
    #asesor-dashboard .pill-grad   { background: linear-gradient(135deg, #0d9488 0%, #0b6e56 100%); box-shadow: 0 8px 18px -8px rgba(13,148,136,.65); }

    /* Batang chart */
    #asesor-dashboard .bar-total   { background: linear-gradient(180deg, #f1f5f9 0%, #eaf0f6 100%); }
    #asesor-dashboard .bar-total-active { background: linear-gradient(180deg, #f0fdf8 0%, #e3f7ee 100%); }
    #asesor-dashboard .bar-kompeten { background: linear-gradient(180deg, #5fc4ad 0%, #b9e6da 100%); }
    #asesor-dashboard .bar-kompeten-active { background: linear-gradient(180deg, #0b6e56 0%, #2f967c 58%, #e9f6f1 100%); }
</style>

<div id="asesor-dashboard" class="max-w-[1160px] space-y-6 pb-8">

    {{-- Breadcrumb --}}
    <nav class="anim-in text-sm flex items-center gap-2.5" aria-label="Breadcrumb" style="--d:0ms">
        <span class="text-slate-400 font-medium">Portal Asesor</span>
        <span class="text-slate-300">›</span>
        <span class="font-semibold text-[#0b6e56]">Dashboard</span>
    </nav>

    {{-- Banner Profil Asesor --}}
    <section class="anim-in banner relative overflow-hidden rounded-2xl border border-emerald-100 px-6 py-5 sm:px-7 sm:py-6 shadow-[0_1px_3px_rgba(15,23,42,.04)]" style="--d:80ms">
        {{-- Garis Hijau Kiri (dipaksa muncul di atas layer .banner) --}}
        <span aria-hidden="true"
            style="position: absolute; top: 0; left: 0; bottom: 0; width: 6px; background: linear-gradient(180deg, #0b6e56 0%, #10b981 100%); z-index: 20; border-top-left-radius: 16px; border-bottom-left-radius: 16px;"></span>

        <div class="relative z-[10] flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div class="flex items-start sm:items-center gap-4 sm:gap-5 min-w-0">
                {{-- Avatar Inisial + Badge Centang --}}
                <div class="anim-pop relative shrink-0" style="--d:250ms">
                    <div class="avatar-grad h-16 w-16 sm:h-[68px] sm:w-[68px] rounded-2xl ring-4 ring-white flex items-center justify-center text-lg sm:text-xl font-bold text-white tracking-wide">
                        {{ str($namaAsesor)->explode(' ')->filter()->take(2)->map(fn($w) => mb_substr($w, 0, 1))->implode('') ?: 'BS' }}
                    </div>
                    <span class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-sky-500 text-white ring-2 ring-white flex items-center justify-center" title="Terverifikasi">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7" />
                        </svg>
                    </span>
                </div>

                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                            Halo, {{ $namaAsesor }}!
                        </h1>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200/90 bg-white/80 px-3 py-0.5 text-xs font-semibold text-[#0d7a5f]">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Asesor Aktif
                        </span>
                    </div>

                    <p class="mt-1 text-xs sm:text-[13px] font-mono text-slate-400 tracking-tight">
                        No. Reg: {{ $noReg }} <span class="mx-1.5 text-slate-300">•</span> Bidang: {{ $bidang }}
                    </p>

                    <p class="mt-2 max-w-2xl text-xs sm:text-[13px] leading-relaxed text-slate-500">
                        Selamat datang di Portal Asesor LSP Sekolah. Kelola jadwal asesmen, dan lakukan penilaian uji kompetensi secara akurat dan objektif.
                    </p>
                </div>
            </div>

            {{-- Pill Tanggal Kanan (real-time, diperbarui oleh JS) --}}
            <div class="anim-pop self-start lg:self-center shrink-0" style="--d:350ms">
                <div class="pill-grad inline-flex items-center gap-2 rounded-full px-4 py-2 text-xs sm:text-[13px] font-semibold text-white">
                    <svg class="w-4 h-4 text-emerald-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2" />
                    </svg>
                    <span id="tanggal-realtime">{{ $tanggalHeader }}</span>
                </div>
            </div>
        </div>
    </section>

    {{-- 4 Summary Cards --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5">
        {{-- Card 1: Asesi Aktif --}}
        <div class="anim-in stat-card tone-emerald rounded-2xl p-5 pt-6 flex flex-col justify-between" style="--d:180ms">
            <div class="flex items-start justify-between gap-3">
                <span class="text-[11px] font-bold uppercase tracking-[.08em] text-slate-500 pt-1">Asesi Aktif</span>
                <span class="tone-icon h-11 w-11 shrink-0 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </span>
            </div>
            <div class="mt-3 text-4xl font-extrabold text-slate-900 tracking-tight">
                {{ number_format($ringkasan['asesi_aktif']) }}
            </div>
            <p class="tone-chip mt-3 self-start inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m22 7-8.5 8.5-5-5L2 17" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7h6v6" />
                </svg>
                <span>{{ $ringkasan['kenaikan_asesi'] }}</span>
            </p>
        </div>

        {{-- Card 2: Jadwal Hari Ini --}}
        <div class="anim-in stat-card tone-sky rounded-2xl p-5 pt-6 flex flex-col justify-between" style="--d:260ms">
            <div class="flex items-start justify-between gap-3">
                <span class="text-[11px] font-bold uppercase tracking-[.08em] text-slate-500 pt-1">Jadwal Hari Ini</span>
                <span class="tone-icon h-11 w-11 shrink-0 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9">
                        <rect width="18" height="18" x="3" y="4" rx="2" />
                        <path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18" />
                    </svg>
                </span>
            </div>
            <div class="mt-3 text-4xl font-extrabold text-slate-900 tracking-tight">
                {{ $ringkasan['jadwal_hari_ini'] }}
            </div>
            <p class="tone-chip mt-3 self-start inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold">
                {{ $ringkasan['rincian_jadwal'] }}
            </p>
        </div>

        {{-- Card 3: Penilaian Selesai --}}
        <div class="anim-in stat-card tone-violet rounded-2xl p-5 pt-6 flex flex-col justify-between" style="--d:340ms">
            <div class="flex items-start justify-between gap-3">
                <span class="text-[11px] font-bold uppercase tracking-[.08em] text-slate-500 pt-1">Penilaian Selesai</span>
                <span class="tone-icon h-11 w-11 shrink-0 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4" />
                    </svg>
                </span>
            </div>
            <div class="mt-3 text-4xl font-extrabold text-slate-900 tracking-tight">
                {{ $ringkasan['penilaian_selesai'] }}
            </div>
            <p class="tone-chip mt-3 self-start inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-5 5-4-4-3 3" />
                </svg>
                <span>{{ $ringkasan['catatan_selesai'] }}</span>
            </p>
        </div>

        {{-- Card 4: Belum Dinilai --}}
        <div class="anim-in stat-card tone-rose rounded-2xl p-5 pt-6 flex flex-col justify-between" style="--d:420ms">
            <div class="flex items-start justify-between gap-3">
                <span class="text-[11px] font-bold uppercase tracking-[.08em] text-slate-500 pt-1">Belum Dinilai</span>
                <span class="tone-icon h-11 w-11 shrink-0 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9">
                        <rect width="14" height="18" x="5" y="3" rx="2" />
                        <path stroke-linecap="round" d="M9 3h6M9 9h6M9 13h4" />
                    </svg>
                </span>
            </div>
            <div class="mt-3 text-4xl font-extrabold text-slate-900 tracking-tight">
                {{ $ringkasan['belum_dinilai'] }}
            </div>
            <p class="tone-chip mt-3 self-start inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                </svg>
                <span>{{ $ringkasan['catatan_belum'] }}</span>
            </p>
        </div>
    </section>

    {{-- Overview Performa & Beban Asesmen --}}
    <section class="anim-in rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-[0_1px_3px_rgba(15,23,42,.03)]" style="--d:500ms">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <span class="tone-emerald tone-icon h-10 w-10 shrink-0 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 20V10M10 20V4M16 20v-7M22 20H2" />
                    </svg>
                </span>
                <div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">
                            Overview Performa &amp; Beban Asesmen
                        </h2>
                        <span class="inline-flex items-center rounded-full border border-emerald-200/80 bg-[#eaf7f2] px-2.5 py-0.5 text-[11px] font-semibold text-[#0d7a5f]">
                            Terverifikasi
                        </span>
                    </div>
                    <p class="mt-1 text-xs sm:text-sm text-slate-400">
                        Statistik jumlah asesi yang telah diuji dan perolehan tingkat kompetensi bulanan
                    </p>
                </div>
            </div>

            <form method="GET" action="{{ url()->current() }}" class="self-start">
                <label class="relative inline-block">
                    <span class="sr-only">Pilih Tahun Statistik</span>
                    <select name="tahun" onchange="this.form.submit()"
                            class="appearance-none rounded-xl border border-slate-200 bg-white py-2 pl-4 pr-9 text-xs sm:text-sm font-semibold text-slate-700 shadow-2xs focus:border-[#0d7a5f] focus:outline-none focus:ring-2 focus:ring-emerald-100 cursor-pointer">
                        @foreach ($tahunTersedia as $th)
                            <option value="{{ $th }}" @selected($tahunAktif === $th)>{{ $th }}</option>
                        @endforeach
                    </select>
                    <svg class="w-4 h-4 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                    </svg>
                </label>
            </form>
        </div>

        {{-- Area Chart Batang --}}
        <div class="mt-14 relative">
            {{-- Area plot: tinggi tetap 240px, skala 0 .. {{ $axisMax }} --}}
            <div class="relative h-[240px] ml-9 sm:ml-11">
                {{-- Garis grid + label sumbu Y (posisi proporsional terhadap skala) --}}
                @foreach ($ticks as $tick)
                    @php $pos = ($tick / $axisMax) * 100; @endphp
                    <div class="absolute inset-x-0 border-b {{ $tick === 0 ? 'border-slate-200' : 'border-dashed border-slate-100' }}"
                         style="bottom: {{ $pos }}%"></div>
                    <span class="absolute -left-9 sm:-left-11 w-6 translate-y-1/2 text-right text-xs font-medium text-slate-400 select-none"
                          style="bottom: {{ $pos }}%">{{ $tick }}</span>
                @endforeach

                {{-- Kolom Batang Bulanan --}}
                <div class="absolute inset-0 grid grid-cols-8 gap-2 sm:gap-5 items-end px-1 sm:px-3">
                    @foreach ($bulanData as $idx => $item)
                        @php
                            $isAktif = ($idx === $bulanAktifIdx);

                            // Tinggi batang = nilai nyata / skala sumbu Y
                            $tinggiTotalPct = $item['total'] > 0 ? max(3, round(($item['total'] / $axisMax) * 100, 2)) : 0;
                            // Batang kompeten = proporsi kompeten terhadap total (di dalam batang total)
                            $tinggiKompetenPct = $item['total'] > 0
                                ? min(100, max(6, round(($item['kompeten'] / $item['total']) * 100, 2)))
                                : 0;

                            // Data tooltip (muncul saat hover)
                            $tipBulan = $isAktif ? ($statAktif['tooltip_bulan'] ?? null) : null;
                            $tipBulan = $tipBulan ?? (($item['nama'] ?? ($namaBulanPenuh[$item['label']] ?? $item['label'])) . ' ' . $tahunAktif);

                            $tipTotal = $isAktif ? ($statAktif['tooltip_total'] ?? $item['total']) : $item['total'];

                            $tipLulus = $isAktif ? ($statAktif['tooltip_kelulusan'] ?? null) : null;
                            if ($tipLulus === null) {
                                $tipLulus = $item['total'] > 0 ? round(($item['kompeten'] / $item['total']) * 100, 1) : 0;
                            }
                            if (is_numeric($tipLulus)) {
                                $tipLulus .= '%';
                            }
                        @endphp

                        <div class="anim-in group relative z-0 hover:z-30 focus-within:z-30 flex flex-col items-center h-full justify-end outline-none"
                             tabindex="0"
                             style="--d:{{ 650 + $idx * 70 }}ms">

                            {{-- Batang Latar (Total Terdaftar) --}}
                            <div class="anim-bar w-full max-w-[66px] rounded-t-xl relative flex items-end justify-center transition-all duration-300 {{ $isAktif ? 'bar-total-active ring-1 ring-emerald-200/90' : 'bar-total group-hover:brightness-95' }}"
                                 style="height: {{ $tinggiTotalPct }}%; --d:{{ 650 + $idx * 70 }}ms">

                                {{-- Tooltip Hover (semi transparan) --}}
                                <div class="pointer-events-none absolute bottom-full left-1/2 mb-3 w-44 -translate-x-1/2 translate-y-1.5 opacity-0 invisible
                                            group-hover:visible group-hover:translate-y-0 group-hover:opacity-100
                                            group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:opacity-100
                                            transition-all duration-200 ease-out
                                            rounded-xl border border-slate-200/70 bg-white/75 backdrop-blur-md px-3.5 py-2.5 text-left
                                            shadow-[0_10px_25px_-5px_rgba(15,23,42,.14)]">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-[11px] font-bold text-slate-800">{{ $tipBulan }}</span>
                                        <span class="h-1.5 w-1.5 rounded-full bg-[#0d7a5f]"></span>
                                    </div>
                                    <div class="mt-1.5 flex items-center justify-between text-[11px]">
                                        <span class="inline-flex items-center gap-1.5 text-slate-500">
                                            <span class="h-1.5 w-1.5 rounded-full bg-slate-300"></span>
                                            Total Asesi:
                                        </span>
                                        <span class="font-bold text-slate-800">{{ $tipTotal }}</span>
                                    </div>
                                    <div class="mt-1 flex items-center justify-between text-[11px]">
                                        <span class="inline-flex items-center gap-1.5 text-[#0d7a5f] font-medium">
                                            <span class="h-1.5 w-1.5 rounded-full bg-[#0d7a5f]"></span>
                                            Kelulusan:
                                        </span>
                                        <span class="font-bold text-[#0d7a5f]">{{ $tipLulus }}</span>
                                    </div>
                                </div>

                                {{-- Batang Depan (Asesi Kompeten) --}}
                                <div class="w-[74%] rounded-t-lg flex items-start justify-center pt-2 transition-all duration-300 {{ $isAktif ? 'bar-kompeten-active' : 'bar-kompeten' }}"
                                     style="height: {{ $tinggiKompetenPct }}%">
                                    <span class="text-xs font-bold {{ $isAktif ? 'text-white drop-shadow-2xs' : 'text-emerald-900' }} opacity-0 transition-opacity duration-200 group-hover:opacity-100 group-focus-within:opacity-100">{{ $item['total'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Label Sumbu X --}}
            <div class="ml-9 sm:ml-11 mt-2.5 grid grid-cols-8 gap-2 sm:gap-5 px-1 sm:px-3 text-center">
                @foreach ($bulanData as $idx => $item)
                    @php $isAktif = ($idx === $bulanAktifIdx); @endphp
                    <span class="text-xs {{ $isAktif ? 'font-bold text-[#0d7a5f]' : 'font-medium text-slate-400' }}">
                        @if ($isAktif)<span class="mr-0.5">•</span>@endif{{ $item['label'] }}
                    </span>
                @endforeach
            </div>
        </div>

        {{-- Legend Bawah Chart --}}
        <div class="mt-6 pt-5 border-t border-slate-100 flex flex-wrap items-center justify-center gap-x-7 gap-y-2 text-xs text-slate-500">
            <span class="inline-flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-xs" style="background: linear-gradient(135deg,#0b6e56,#5fc4ad)"></span>
                <span>Asesi Kompeten</span>
            </span>
            <span class="inline-flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-xs bg-[#dfe6ec]"></span>
                <span>Total Terdaftar</span>
            </span>
            <span class="inline-flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-[#0d7a5f]"></span>
                <span>Bulan Aktif: <strong class="font-semibold text-slate-700">{{ $statAktif['bulan_aktif_label'] }}</strong></span>
            </span>
        </div>
    </section>

    {{-- Baris Bawah: Jadwal Asesmen Terdekat & Status Kelulusan Periode Ini --}}
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">
        {{-- Kolom Kiri (7/12): Jadwal Asesmen Terdekat --}}
        <div class="anim-in lg:col-span-7 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-[0_1px_3px_rgba(15,23,42,.03)] flex flex-col justify-between" style="--d:600ms">
            <div>
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3.5">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 tracking-tight">Jadwal Asesmen Terdekat</h2>
                            <p class="mt-0.5 text-xs sm:text-sm text-slate-400">Daftar agenda uji kompetensi yang ditugaskan kepada Anda</p>
                        </div>
                    </div>
                    <a href="{{ $jadwalUrl }}" class="shrink-0 inline-flex items-center gap-1 text-xs sm:text-sm font-semibold text-[#0d7a5f] hover:text-[#08523f] transition-colors">
                        <span>Lihat Semua</span>
                        <span aria-hidden="true">→</span>
                    </a>
                </div>

                <div class="mt-5 space-y-3.5">
                    @foreach ($jadwalTerdekat as $jd)
                        @php $isWarning = ($jd['badge_type'] ?? '') === 'warning'; @endphp
                        <div class="anim-in rounded-2xl border border-slate-200/80 bg-white p-3.5 sm:p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:border-emerald-300 hover:shadow-[0_10px_24px_-14px_rgba(16,185,129,.45)] transition-all"
                             style="--d:{{ 750 + $loop->index * 110 }}ms">
                            <div class="flex items-center gap-3.5 min-w-0">
                                {{-- Kotak Tanggal --}}
                                <div class="tone-dategrad {{ $isWarning ? 'tone-amber' : 'tone-emerald' }} h-14 w-14 shrink-0 rounded-xl flex flex-col items-center justify-center">
                                    <span class="text-base font-extrabold leading-none">{{ $jd['tanggal'] }}</span>
                                    <span class="mt-1 text-[10px] font-bold uppercase tracking-wider text-white/85">{{ $jd['bulan'] }}</span>
                                </div>

                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="text-sm sm:text-[15px] font-bold text-slate-900 truncate">{{ $jd['skema'] }}</h3>
                                        @if ($isWarning)
                                            <span class="inline-flex items-center rounded-full bg-amber-50 border border-amber-200/80 px-2.5 py-0.5 text-[11px] font-semibold text-amber-600">
                                                {{ $jd['badge'] }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-[#eaf7f2] border border-emerald-200/80 px-2.5 py-0.5 text-[11px] font-semibold text-[#0d7a5f]">
                                                {{ $jd['badge'] }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-1.5 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-400">
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="9" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2" />
                                            </svg>
                                            <span>{{ $jd['waktu'] }}</span>
                                        </span>

                                        @if (($jd['metode'] ?? 'offline') === 'online')
                                            <span class="inline-flex items-center gap-1 font-medium text-sky-600">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <rect width="14" height="10" x="2" y="7" rx="2" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16 11 6-3v8l-6-3" />
                                                </svg>
                                                <span>{{ $jd['lokasi'] }}</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <rect width="16" height="12" x="4" y="4" rx="2" />
                                                    <path stroke-linecap="round" d="M8 20h8M12 16v4" />
                                                </svg>
                                                <span>{{ $jd['lokasi'] }}</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Badge Jumlah Asesi --}}
                            <div class="self-end sm:self-center shrink-0">
                                <span class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-200/90 bg-[#f2fbf7] px-3 py-2 text-xs font-bold text-[#0d7a5f]">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                    <span>{{ $jd['jumlah_asesi'] }} Asesi</span>
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Footer Catatan 3 Jadwal --}}
            <div class="mt-5 pt-4 border-t border-slate-100 flex items-center gap-2 text-xs text-slate-400">
                <span class="h-4 w-4 rounded-full bg-emerald-50 text-[#0d7a5f] inline-flex items-center justify-center font-bold text-[10px] shrink-0">i</span>
                <p>
                    Daftar di atas hanya memuat 3 jadwal asesmen terdekat. Silakan kunjungi halaman
                    <a href="{{ $jadwalUrl }}" class="font-semibold text-[#0d7a5f] hover:underline">Jadwal</a>
                    untuk melihat keseluruhan jadwal.
                </p>
            </div>
        </div>

        {{-- Kolom Kanan (5/12): Status Kelulusan Periode Ini --}}
        <div class="anim-in lg:col-span-5 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-[0_1px_3px_rgba(15,23,42,.03)] flex flex-col justify-between" style="--d:680ms">
            <div class="flex items-center gap-3.5">
                <h2 class="text-lg font-bold text-slate-900 tracking-tight leading-snug">
                    Status Kelulusan Periode Ini
                </h2>
            </div>

            {{-- Donut Ring (3 warna saja, tanpa ring abu-abu) --}}
            <div class="my-5 relative mx-auto w-full max-w-[250px] aspect-square flex items-center justify-center">
                <svg class="w-full h-full -rotate-90" viewBox="0 0 200 200" aria-hidden="true">
                    @foreach ($donutSegmen as $seg)
                        @if ((float) $seg['panjang'] > 0)
                            <circle class="anim-donut" cx="100" cy="100" r="84" fill="none"
                                    stroke="{{ $seg['warna'] }}" stroke-width="28"
                                    pathLength="100"
                                    stroke-dasharray="{{ $seg['panjang'] }} {{ $seg['sisa'] }}"
                                    stroke-dashoffset="{{ $seg['offset'] }}"
                                    style="--d:{{ $seg['delay'] }}ms" />
                        @endif
                    @endforeach
                </svg>

                <div class="anim-pop absolute inset-0 flex flex-col items-center justify-center text-center" style="--d:1100ms">
                    <span class="text-4xl font-extrabold text-slate-900 tracking-tight leading-none">{{ $persenKompeten }}%</span>
                    <span class="mt-1.5 text-sm font-medium text-slate-500">{{ $statusKelulusan['label_tengah'] ?? 'Lulus' }}</span>
                </div>
            </div>

            {{-- Rincian Status --}}
            <div class="space-y-3 pt-2 text-xs sm:text-sm">
                <div class="anim-in flex items-center justify-between gap-3" style="--d:900ms">
                    <span class="inline-flex items-center gap-2.5 text-slate-600 font-medium">
                        <span class="h-2.5 w-2.5 rounded-full" style="background: {{ $warnaDonut['lulus'] }}"></span>
                        <span>Kompeten (Lulus)</span>
                    </span>
                    <span class="font-bold text-slate-900">{{ $totalKompeten }} Asesi</span>
                </div>

                <div class="anim-in flex items-center justify-between gap-3" style="--d:980ms">
                    <span class="inline-flex items-center gap-2.5 text-slate-600 font-medium">
                        <span class="h-2.5 w-2.5 rounded-full" style="background: {{ $warnaDonut['proses'] }}"></span>
                        <span>Sedang Proses</span>
                    </span>
                    <span class="font-bold text-slate-900">{{ $totalProses }} Asesi</span>
                </div>

                <div class="anim-in flex items-center justify-between gap-3" style="--d:1060ms">
                    <span class="inline-flex items-center gap-2.5 text-slate-600 font-medium">
                        <span class="h-2.5 w-2.5 rounded-full" style="background: {{ $warnaDonut['belum'] }}"></span>
                        <span>Belum Kompeten</span>
                    </span>
                    <span class="font-bold text-slate-900">{{ $totalBelum }} Asesi</span>
                </div>
            </div>
        </div>
    </section>

</div>

{{-- Tanggal real-time: memakai waktu perangkat saat halaman dibuka, diperbarui tiap menit (ganti hari otomatis) --}}
<script>
    (function () {
        var el = document.getElementById('tanggal-realtime');
        if (!el) return;

        var hari  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        var bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        function render() {
            var d = new Date();
            el.textContent = hari[d.getDay()] + ', ' + d.getDate() + ' ' + bulan[d.getMonth()] + ' ' + d.getFullYear();
        }

        render();
        setInterval(render, 60000);
    })();
</script>
</x-layouts.app>