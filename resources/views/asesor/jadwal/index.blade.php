@php
    $qsBulan = ['bulan' => $bulan->format('Y-m')];

    $kelasBadge = [
        'emerald' => 'border-emerald-200/90 bg-[#eaf7f2] text-[#0d7a5f]',
        'sky'     => 'border-sky-200 bg-sky-50 text-sky-700',
        'indigo'  => 'border-indigo-200 bg-indigo-50 text-indigo-700',
    ];
    $kelasTombol = [
        'primary' => 'bg-[#0b6e56] text-white hover:bg-[#08523f] shadow-[0_6px_14px_-6px_rgba(11,110,86,.6)]',
        'muted'   => 'bg-slate-200 text-slate-600 hover:bg-slate-300',
        'dark'    => 'bg-slate-900 text-white hover:bg-slate-700',
        'ghost'   => 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 hover:border-slate-300',
    ];
    $kelasInisial = [
        'bg-emerald-100 text-emerald-700',
        'bg-indigo-100 text-indigo-700',
        'bg-rose-100 text-rose-600',
        'bg-slate-200 text-slate-600',
    ];
    $kelasChipKalender = [
        'berlangsung' => 'bg-[#0b6e56] text-white',
        'selesai'     => 'bg-emerald-100 text-emerald-800 border border-emerald-200/80',
        'terjadwal'   => 'bg-sky-100 text-sky-700 border border-sky-200/80',
    ];
    $kelasWaktuPanel = [
        'berlangsung' => 'bg-emerald-100 text-emerald-700',
        'selesai'     => 'bg-slate-200 text-slate-600',
        'terjadwal'   => 'bg-sky-100 text-sky-700',
    ];
    $labelStatusPanel = ['selesai' => 'Selesai', 'berlangsung' => 'Berlangsung', 'terjadwal' => 'Mendatang'];
@endphp

<x-layouts.app :title="'Jadwal Asesmen'">
<style>
    #asesor-jadwal [hidden] { display: none !important; }

    /* Animasi muncul */
    #asesor-jadwal .anim-in  { animation: jd-rise .6s cubic-bezier(.22, 1, .36, 1) backwards; animation-delay: var(--d, 0ms); }
    #asesor-jadwal .anim-pop { animation: jd-pop .5s cubic-bezier(.34, 1.56, .64, 1) backwards; animation-delay: var(--d, 0ms); }
    @keyframes jd-rise { from { opacity: 0; transform: translateY(16px); } }
    @keyframes jd-pop  { from { opacity: 0; transform: scale(.9); } }
    @keyframes jd-fade { from { opacity: 0; } }
    @media (prefers-reduced-motion: reduce) {
        #asesor-jadwal .anim-in, #asesor-jadwal .anim-pop { animation: none; }
    }

    /* Pop up penilaian */
    #asesor-jadwal .jd-modal-backdrop { animation: jd-fade .2s ease backwards; }
    #asesor-jadwal .jd-modal-panel    { animation: jd-pop .25s cubic-bezier(.34, 1.3, .64, 1) backwards; }

    /* Kalender */
    #asesor-jadwal .kal-cell { transition: background-color .15s ease, box-shadow .15s ease; }
    #asesor-jadwal button.kal-cell:hover { background: #f4fbf8; }
    #asesor-jadwal .kal-cell.is-selected { background: #eefaf5; box-shadow: inset 0 0 0 2px #0b6e56; }
    #asesor-jadwal .grad-emerald { background: linear-gradient(135deg, #10b981, #0d9488); box-shadow: 0 8px 16px -6px rgba(16,185,129,.35); }
    #asesor-jadwal .hari-ini-head { background: linear-gradient(120deg, #ecfdf5 0%, #f7fffb 60%, #ffffff 100%); }
</style>

<div id="asesor-jadwal" class="max-w-[1160px] space-y-6 pb-8">

    {{-- Breadcrumb --}}
    <nav class="anim-in text-sm flex items-center gap-2.5" aria-label="Breadcrumb" style="--d:0ms">
        <span class="text-slate-400 font-medium">Portal Asesor</span>
        <span class="text-slate-300">›</span>
        <span class="font-semibold text-[#0b6e56]">Jadwal Asesmen</span>
    </nav>

    {{-- Judul + Toggle Tampilan --}}
    <header class="anim-in flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4" style="--d:60ms">
        <div>
            <h1 class="text-3xl sm:text-[34px] font-extrabold text-slate-900 tracking-tight">Jadwal Asesmen</h1>
            <p class="mt-1 text-sm text-slate-400">Kelola dan pantau agenda uji kompetensi asesi secara terintegrasi.</p>
        </div>

        <div class="self-start inline-flex items-center rounded-xl bg-slate-100 border border-slate-200/80 p-1 gap-1">
            <a href="{{ route('asesor.jadwal.index', $qsBulan + ['tampilan' => 'kalender']) }}"
               class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition {{ $tampilan === 'kalender' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9"><rect width="18" height="18" x="3" y="4" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
                Kalender
            </a>
            <a href="{{ route('asesor.jadwal.index', $qsBulan + ['tampilan' => 'daftar']) }}"
               class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition {{ $tampilan === 'daftar' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                Daftar
            </a>
        </div>
    </header>

@if ($tampilan === 'daftar')
    {{-- ======================= MODE DAFTAR ======================= --}}

    {{-- Filter --}}
    <form method="GET" action="{{ route('asesor.jadwal.index') }}"
          class="anim-in rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-5 shadow-[0_1px_3px_rgba(15,23,42,.03)]" style="--d:120ms">
        <input type="hidden" name="tampilan" value="daftar">

        <div class="flex flex-wrap items-center gap-3">
            <label class="relative flex-1 min-w-[220px] max-w-xs">
                <span class="sr-only">Cari</span>
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="m20 20-3.5-3.5"/></svg>
                <input type="search" name="q" value="{{ $filter['q'] }}" placeholder="Cari nama skema, bidang, atau asesi..."
                       class="w-full rounded-xl border border-slate-200 bg-slate-50/70 py-2.5 pl-10 pr-3 text-sm text-slate-700 placeholder:text-slate-400 focus:border-[#0d7a5f] focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-100">
            </label>

            <label class="relative">
                <span class="sr-only">Skema Sertifikasi</span>
                <select name="skema" onchange="this.form.submit()"
                        class="appearance-none rounded-xl border border-slate-200 bg-white py-2.5 pl-4 pr-10 text-sm font-medium text-slate-700 focus:border-[#0d7a5f] focus:outline-none focus:ring-2 focus:ring-emerald-100 cursor-pointer">
                    <option value="">Semua Skema Sertifikasi</option>
                    @foreach ($daftarSkema as $sk)
                        <option value="{{ $sk }}" @selected($filter['skema'] === $sk)>{{ $sk }}</option>
                    @endforeach
                </select>
                <svg class="w-4 h-4 text-slate-400 pointer-events-none absolute right-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
            </label>

            <label class="relative">
                <span class="sr-only">Status Sesi</span>
                <select name="status" onchange="this.form.submit()"
                        class="appearance-none rounded-xl border border-slate-200 bg-white py-2.5 pl-4 pr-10 text-sm font-medium text-slate-700 focus:border-[#0d7a5f] focus:outline-none focus:ring-2 focus:ring-emerald-100 cursor-pointer">
                    <option value="">Status Sesi (Semua)</option>
                    @foreach ($opsiStatus as $val => $lbl)
                        <option value="{{ $val }}" @selected($filter['status'] === $val)>{{ $lbl }}</option>
                    @endforeach
                </select>
                <svg class="w-4 h-4 text-slate-400 pointer-events-none absolute right-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
            </label>

            <div class="ml-auto flex items-center gap-3">
                <a href="{{ route('asesor.jadwal.index', ['tampilan' => 'daftar']) }}" class="text-sm font-medium text-slate-500 hover:text-slate-800">Reset</a>
                <a href="{{ route('asesor.jadwal.export', array_filter(['bulan' => $bulan->format('Y-m'), 'q' => $filter['q'], 'skema' => $filter['skema'], 'status' => $filter['status']])) }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-[#f2fbf7] px-4 py-2.5 text-sm font-semibold text-[#0d7a5f] hover:bg-emerald-50 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/></svg>
                    Ekspor Jadwal
                </a>
            </div>
        </div>

        <div class="mt-3">
            <label class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-1.5 text-sm font-semibold text-slate-700">
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9"><rect width="18" height="18" x="3" y="4" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
                <span class="sr-only">Pilih bulan</span>
                <input type="month" name="bulan" value="{{ $bulan->format('Y-m') }}" onchange="this.form.submit()"
                       class="bg-transparent text-sm font-semibold text-slate-700 focus:outline-none cursor-pointer">
            </label>
        </div>
    </form>

    {{-- Agenda Bulan Ini --}}
    <section class="anim-in rounded-2xl border border-slate-200/80 bg-white px-5 py-4 sm:px-6 sm:py-5 shadow-[0_1px_3px_rgba(15,23,42,.03)] flex flex-col md:flex-row md:items-center md:justify-between gap-4" style="--d:180ms">
        <div class="flex items-center gap-4">
            <span class="grad-emerald h-12 w-12 shrink-0 rounded-xl text-white flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect width="18" height="18" x="3" y="4" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
            </span>
            <div>
                <div class="flex flex-wrap items-center gap-2.5">
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Agenda Asesmen Bulan Ini</h2>
                    <span class="inline-flex items-center rounded-full border border-emerald-200/80 bg-[#eaf7f2] px-2.5 py-0.5 text-[11px] font-semibold text-[#0d7a5f]">{{ $bulanLabel }}</span>
                </div>
                <p class="mt-0.5 text-xs sm:text-[13px] text-slate-400">Menampilkan daftar agenda uji kompetensi aktif terverifikasi oleh Master Asesor LSP</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-[#eaf7f2] px-3.5 py-1.5 text-xs font-bold text-[#0d7a5f]">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>{{ $ringkasan['total_asesmen'] }} Asesmen Terjadwal
            </span>
            <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3.5 py-1.5 text-xs font-bold text-slate-700">
                {{ $ringkasan['asesi_menunggu'] }} Asesi Menunggu Uji
            </span>
        </div>
    </section>

    {{-- Kelompok per Hari --}}
    @forelse ($kelompok as $tglKey => $sesiHari)
        @php
            $tgl     = $sesiHari->first()['tanggal'];
            $isToday = $tgl->isSameDay($hariIni);
            $isPast  = $tgl->lt($hariIni);

            $info = $infoHari[$tglKey] ?? [
                'sesi'    => $sesiHari->pluck('mulai')->unique()->count(),
                'asesmen' => $sesiHari->count(),
                'asesi'   => (int) $sesiHari->sum('jumlah_asesi'),
                'skema'   => $sesiHari->pluck('skema')->unique()->count(),
            ];

            $adaBerjalan = $sesiHari->contains('status', 'berlangsung');
            $semuaSelesai = $sesiHari->every(fn ($x) => $x['status'] === 'selesai');
        @endphp

        <section class="anim-in overflow-hidden rounded-2xl bg-white shadow-[0_1px_3px_rgba(15,23,42,.03)] {{ $isToday ? 'border-2 border-emerald-400/70 shadow-[0_14px_34px_-18px_rgba(16,185,129,.55)]' : 'border border-slate-200/80' }}"
                 style="--d:{{ 240 + $loop->index * 90 }}ms">

            {{-- Header Hari --}}
            <div class="px-5 sm:px-6 py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b {{ $isToday ? 'hari-ini-head border-emerald-100' : 'bg-slate-50/70 border-slate-100' }}">
                <div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        @if ($isToday)
                            <span class="inline-flex items-center rounded-md bg-[#0b6e56] px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-white">Hari Ini</span>
                        @endif
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">{{ $tgl->locale('id')->translatedFormat('l, j F Y') }}</h3>
                        <span class="text-xs font-medium text-slate-500">({{ $info['sesi'] }} Sesi • {{ $info['asesmen'] }} Asesmen)</span>
                    </div>
                    @if ($isToday)
                        <p class="mt-1 inline-flex items-center gap-1.5 text-xs text-slate-500">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                            Total {{ $info['asesi'] }} Asesi • {{ $info['skema'] }} Skema Sertifikasi
                        </p>
                    @endif
                </div>

                @if ($isToday && $adaBerjalan)
                    <span class="self-start sm:self-center inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-[#e3f7ee] px-3.5 py-1.5 text-xs font-bold text-[#0d7a5f]">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>Uji Kompetensi Sedang Berjalan
                    </span>
                @elseif ($semuaSelesai)
                    <span class="self-start sm:self-center inline-flex items-center rounded-lg bg-slate-100 border border-slate-200/70 px-3 py-1 text-xs font-bold text-slate-600">{{ $info['asesmen'] }} Asesmen Selesai</span>
                @else
                    <span class="self-start sm:self-center inline-flex items-center rounded-lg bg-sky-50 border border-sky-200/70 px-3 py-1 text-xs font-bold text-sky-700">{{ $info['asesmen'] }} Asesmen Terjadwal</span>
                @endif
            </div>

            {{-- Daftar Asesmen --}}
            <div class="divide-y divide-slate-100">
                @foreach ($sesiHari as $s)
                    <div class="px-5 sm:px-6 py-4 sm:py-5 flex flex-col lg:flex-row lg:items-center gap-4 hover:bg-slate-50/40 transition-colors">
                        {{-- Kotak Sesi (hanya waktu mulai) --}}
                        <div class="shrink-0 w-[78px] rounded-xl border px-2 py-2.5 text-center {{ $s['status'] === 'berlangsung' ? 'border-emerald-200 bg-[#eff9f5]' : 'border-slate-200 bg-slate-50' }}">
                            <div class="text-[10px] font-bold font-mono uppercase tracking-wider {{ $s['status'] === 'berlangsung' ? 'text-[#0d7a5f]' : 'text-slate-500' }}">Sesi {{ $s['sesi_ke'] }}</div>
                            <div class="mt-0.5 text-base font-extrabold font-mono {{ $s['status'] === 'berlangsung' ? 'text-[#0b6e56]' : 'text-slate-800' }}">{{ $s['mulai'] }}</div>
                            <div class="text-[10px] font-mono text-slate-400">WIB</div>
                        </div>

                        {{-- Detail --}}
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h4 class="text-[15px] sm:text-base font-bold text-slate-900">{{ $s['skema'] }}</h4>
                                <span class="inline-flex items-center rounded-md bg-slate-100 border border-slate-200/70 px-2 py-0.5 text-[10px] font-bold font-mono text-slate-600">{{ $s['kode'] }}</span>
                                <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[11px] font-semibold {{ $kelasBadge[$s['nada']] }}">
                                    @if ($s['status'] === 'selesai')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                                    @elseif ($s['status'] === 'berlangsung')
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    @endif
                                    {{ $s['badge'] }}
                                </span>
                            </div>

                            {{-- Kategori / bidang keahlian skema (dibuat admin) --}}
                            <p class="mt-1.5 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-500">
                                <span class="inline-flex items-center gap-1.5 font-medium">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8z"/><circle cx="7.5" cy="7.5" r="1.2"/></svg>
                                    Bidang Keahlian: {{ $s['bidang'] }}
                                </span>
                                @if ($s['status'] !== 'selesai' && $s['sudah_dinilai'] > 0)
                                    <span class="inline-flex items-center gap-1 text-[#0d7a5f] font-semibold">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                                        {{ $s['sudah_dinilai'] }} dari {{ $s['jumlah_asesi'] }} asesi sudah dinilai
                                    </span>
                                @endif
                            </p>

                            @if (count($s['peserta']))
                                <div class="mt-3 flex flex-wrap items-center gap-2">
                                    <span class="text-xs font-bold text-slate-700 mr-1">{{ count($s['peserta']) }} Asesi:</span>
                                    @foreach (array_slice($s['peserta'], 0, 4) as $i => $a)
                                        <span class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50/80 px-2 py-1.5 text-xs font-medium text-slate-700">
                                            <span class="h-5 w-5 rounded-md text-[9px] font-extrabold inline-flex items-center justify-center {{ $kelasInisial[$i % 4] }}">{{ $a['inisial'] }}</span>
                                            {{ $a['nama'] }}
                                            <span class="text-[9px] font-mono text-slate-400">({{ $a['sekolah'] }})</span>
                                        </span>
                                    @endforeach
                                    @if (count($s['peserta']) > 4)
                                        <span class="inline-flex items-center rounded-lg border border-dashed border-slate-300 px-2 py-1.5 text-xs font-semibold text-slate-500">+{{ count($s['peserta']) - 4 }} lainnya</span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- Aksi --}}
                        <div class="shrink-0 flex items-center gap-2.5 self-end lg:self-center">
                            @foreach ($s['aksi'] as $ak)
                                @if ($ak['modal'])
                                    <button type="button" data-buka="modal-{{ $s['id'] }}"
                                            class="inline-flex items-center justify-center text-center rounded-xl px-4 py-2.5 text-xs font-bold leading-tight transition {{ $kelasTombol[$ak['gaya']] }}">
                                        {{ $ak['label'] }}
                                    </button>
                                @else
                                    <a href="{{ $ak['url'] }}" class="inline-flex items-center justify-center text-center rounded-xl px-4 py-2.5 text-xs font-bold leading-tight transition {{ $kelasTombol[$ak['gaya']] }}">
                                        {{ $ak['label'] }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @empty
        <section class="anim-in rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center" style="--d:240ms">
            <span class="mx-auto h-12 w-12 rounded-xl bg-slate-100 text-slate-400 inline-flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect width="18" height="18" x="3" y="4" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
            </span>
            <h3 class="mt-4 text-base font-bold text-slate-800">Tidak ada asesmen ditemukan</h3>
            <p class="mt-1 text-sm text-slate-400">Coba ubah kata kunci, filter, atau pilih bulan lain.</p>
        </section>
    @endforelse

    {{-- Pagination --}}
    @if ($paginator->total() > 0)
        <div class="anim-in rounded-2xl border border-slate-200/80 bg-white px-5 py-3.5 shadow-[0_1px_3px_rgba(15,23,42,.03)] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3" style="--d:500ms">
            <p class="text-xs text-slate-500">
                Menampilkan <strong class="text-slate-800">{{ $paginator->count() }}</strong> asesmen dari total
                <strong class="text-slate-800">{{ $paginator->total() }}</strong> asesmen bulan ini
            </p>

            @if ($paginator->lastPage() > 1)
                <nav class="flex items-center gap-1.5" aria-label="Paginasi">
                    @if ($paginator->onFirstPage())
                        <span class="rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-1.5 text-xs font-medium text-slate-300">Sebelumnya</span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" class="rounded-lg border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Sebelumnya</a>
                    @endif

                    @foreach (range(1, $paginator->lastPage()) as $p)
                        <a href="{{ $paginator->url($p) }}"
                           class="h-8 min-w-8 px-2 inline-flex items-center justify-center rounded-lg text-xs font-bold transition {{ $p === $paginator->currentPage() ? 'bg-[#0b6e56] text-white' : 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50' }}">{{ $p }}</a>
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" class="rounded-lg border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Selanjutnya</a>
                    @else
                        <span class="rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-1.5 text-xs font-medium text-slate-300">Selanjutnya</span>
                    @endif
                </nav>
            @endif
        </div>
    @endif


@else
    {{-- ======================= MODE KALENDER ======================= --}}

    <div class="anim-in flex flex-wrap items-center gap-2.5" style="--d:120ms">
        <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-[#eaf7f2] px-3.5 py-1.5 text-xs font-bold text-[#0d7a5f]">
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>{{ $ringkasan['total_sesi'] }} Sesi Bulan Ini
        </span>
        <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3.5 py-1.5 text-xs font-bold text-slate-700">
            {{ $ringkasan['asesi_menunggu'] }} Asesi Menunggu Uji
        </span>
    </div>

    <div id="kalender-root" data-awal="{{ $tanggalTerpilih }}" class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_360px] gap-6 items-start">

        {{-- Kalender --}}
        <section class="anim-in rounded-2xl border border-slate-200/80 bg-white shadow-[0_1px_3px_rgba(15,23,42,.03)] overflow-hidden" style="--d:180ms">
            <div class="px-5 sm:px-6 py-4 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">{{ $bulanLabel }}</h2>
                    @if ($adalahBulanIni)
                        <span class="inline-flex items-center rounded-full border border-emerald-200/80 bg-[#eaf7f2] px-2.5 py-0.5 text-[11px] font-semibold text-[#0d7a5f]">Hari Ini</span>
                    @else
                        <a href="{{ route('asesor.jadwal.index', ['tampilan' => 'kalender']) }}" class="text-xs font-semibold text-[#0d7a5f] hover:underline">Ke bulan ini</a>
                    @endif
                </div>
                <div class="flex items-center gap-1">
                    <a href="{{ route('asesor.jadwal.index', ['tampilan' => 'kalender', 'bulan' => $bulanSebelum]) }}" class="h-9 w-9 rounded-lg text-slate-500 hover:bg-slate-100 inline-flex items-center justify-center" aria-label="Bulan sebelumnya">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6"/></svg>
                    </a>
                    <a href="{{ route('asesor.jadwal.index', ['tampilan' => 'kalender', 'bulan' => $bulanSesudah]) }}" class="h-9 w-9 rounded-lg text-slate-500 hover:bg-slate-100 inline-flex items-center justify-center" aria-label="Bulan berikutnya">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
                    </a>
                </div>
            </div>

            {{-- Nama Hari --}}
            <div class="grid grid-cols-7 border-y border-slate-100 bg-slate-50/70">
                @foreach (['SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB', 'MIN'] as $i => $h)
                    <div class="py-2.5 text-center text-[11px] font-bold tracking-wider {{ $i === 6 ? 'text-rose-500' : 'text-slate-400' }}">{{ $h }}</div>
                @endforeach
            </div>

            {{-- Sel Tanggal --}}
            <div class="grid grid-cols-7">
                @foreach ($minggu as $pekan)
                    @foreach ($pekan as $hari)
                        @php
                            $sesiTgl = $sesiPerTanggal->get($hari['key'], collect());
                            $ada = $sesiTgl->isNotEmpty() && $hari['dalam_bulan'];
                            $tag = $ada ? 'button' : 'div';
                        @endphp
                        <{{ $tag }} @if ($ada) type="button" data-tanggal="{{ $hari['key'] }}" @endif
                            class="kal-cell relative min-h-[96px] sm:min-h-[104px] border-b border-r border-slate-100 p-1.5 sm:p-2 text-left align-top flex flex-col gap-1 {{ $hari['dalam_bulan'] ? '' : 'bg-slate-50/50' }} {{ $ada ? 'cursor-pointer' : '' }}">

                            <div class="flex items-start justify-between gap-1">
                                @if ($hari['hari_ini'])
                                    <span class="inline-flex items-center gap-1.5">
                                        <span class="h-6 min-w-6 px-1 rounded-full bg-[#0b6e56] text-white text-xs font-bold inline-flex items-center justify-center">{{ $hari['tanggal']->day }}</span>
                                    </span>
                                @else
                                    <span class="text-xs sm:text-sm font-semibold {{ ! $hari['dalam_bulan'] ? 'text-slate-300' : ($hari['minggu'] ? 'text-rose-500' : 'text-slate-700') }}">{{ $hari['tanggal']->day }}</span>
                                @endif

                                @if ($ada && $sesiTgl->count() > 1)
                                    <span class="text-[9px] sm:text-[10px] font-semibold {{ $hari['hari_ini'] ? 'rounded-full bg-[#0b6e56]/10 text-[#0b6e56] px-1.5 py-0.5' : 'text-slate-400' }}">{{ $sesiTgl->count() }} Sesi</span>
                                @endif
                            </div>

                            @if ($ada)
                                @foreach ($sesiTgl->take(3) as $s)
                                    <span class="block w-full truncate rounded-md px-1.5 py-0.5 text-[9px] sm:text-[10px] font-semibold leading-snug {{ $kelasChipKalender[$s['status']] }}">
                                        {{ $s['mulai'] }} • {{ $s['skema'] }}
                                    </span>
                                @endforeach
                                @if ($sesiTgl->count() > 3)
                                    <span class="text-[10px] font-semibold text-slate-400">+{{ $sesiTgl->count() - 3 }} lagi</span>
                                @endif
                            @endif
                        </{{ $tag }}>
                    @endforeach
                @endforeach
            </div>

            {{-- Legenda --}}
            <div class="px-5 sm:px-6 py-3.5 flex flex-wrap items-center justify-between gap-3 text-xs text-slate-500 bg-slate-50/40">
                <div class="flex flex-wrap items-center gap-x-5 gap-y-1.5">
                    <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-[#0b6e56]"></span>Hari Ini</span>
                    <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-emerald-200"></span>Selesai</span>
                    <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-sky-400"></span>Terjadwal</span>
                </div>
                <span class="text-slate-400">Klik tanggal untuk melihat rincian asesmen</span>
            </div>
        </section>

        {{-- Panel Rincian --}}
        <aside class="anim-in rounded-2xl border border-slate-200/80 bg-white shadow-[0_1px_3px_rgba(15,23,42,.03)] overflow-hidden xl:sticky xl:top-4" style="--d:260ms">

            {{-- Kosong --}}
            <div data-panel-kosong hidden class="px-6 py-16 text-center">
                <span class="mx-auto h-12 w-12 rounded-xl bg-slate-100 text-slate-400 inline-flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect width="18" height="18" x="3" y="4" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
                </span>
                <h3 class="mt-4 text-base font-bold text-slate-800">Belum ada tanggal dipilih</h3>
                <p class="mt-1 text-sm text-slate-400">Klik tanggal yang memiliki agenda pada kalender untuk melihat rinciannya.</p>
            </div>

            @foreach ($sesiPerTanggal as $key => $sesiHari)
                @php
                    $tglPanel = $sesiHari->first()['tanggal'];
                    $totalAsesiPanel = $sesiHari->where('tipe', 'uji')->sum('jumlah_asesi');
                    $nSkemaPanel = $sesiHari->pluck('skema')->unique()->count();
                    $sorot = $sesiHari->search(fn ($x) => $x['status'] === 'berlangsung');
                    $sorot = $sorot === false ? 0 : $sorot;
                @endphp

                <div data-panel="{{ $key }}" hidden class="flex flex-col">
                    <div class="px-5 pt-5 pb-4 border-b border-slate-100">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2.5">
                                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-[#eaf7f2] px-2.5 py-0.5 text-[10px] font-extrabold tracking-wider text-[#0d7a5f]">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>TERPILIH
                                </span>
                                <span class="text-xs font-medium text-slate-500">{{ $sesiHari->count() }} Sesi Agenda</span>
                            </div>
                            <button type="button" data-tutup class="h-7 w-7 rounded-md text-slate-400 hover:bg-slate-100 hover:text-slate-600 inline-flex items-center justify-center" aria-label="Tutup rincian">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M6 6l12 12M18 6 6 18"/></svg>
                            </button>
                        </div>
                        <h3 class="mt-3 text-xl font-extrabold text-slate-900 tracking-tight">{{ $tglPanel->locale('id')->translatedFormat('l, j F Y') }}</h3>
                        <p class="mt-1 inline-flex items-center gap-1.5 text-xs text-slate-500">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9"><rect width="18" height="18" x="3" y="4" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
                            Total {{ $totalAsesiPanel }} Asesi • {{ $nSkemaPanel }} Skema Sertifikasi
                        </p>
                    </div>

                    <div class="p-4 space-y-3 max-h-[560px] overflow-y-auto">
                        @foreach ($sesiHari as $idx => $s)
                            <div class="rounded-2xl border p-4 {{ $idx === $sorot ? 'border-emerald-400 ring-1 ring-emerald-200 bg-emerald-50/40' : 'border-slate-200 bg-slate-50/60' }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-bold text-slate-900 leading-snug">{{ $s['skema'] }}</h4>
                                        <p class="mt-1 inline-flex items-center gap-1.5 text-xs text-slate-500">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                                            @if ($s['jumlah_asesi'] > 0 && $s['tipe'] === 'uji')
                                                {{ $s['jumlah_asesi'] }} Peserta
                                            @else
                                                {{ $s['tuk'] }}
                                            @endif
                                        </p>
                                    </div>
                                    <span class="shrink-0 rounded-md px-2 py-1 text-[11px] font-bold font-mono {{ $kelasWaktuPanel[$s['status']] }}">{{ $s['mulai'] }}</span>
                                </div>

                                @if (count($s['asesi']))
                                    <div class="mt-3 space-y-2">
                                        @foreach ($s['asesi'] as $i => $a)
                                            <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2">
                                                <span class="h-8 w-8 shrink-0 rounded-lg text-[11px] font-extrabold inline-flex items-center justify-center {{ $kelasInisial[$i % 4] }}">{{ $a['inisial'] }}</span>
                                                <div class="min-w-0 flex-1">
                                                    <p class="truncate text-xs font-bold text-slate-800">{{ $a['nama'] }}</p>
                                                    <p class="truncate text-[10px] font-mono text-slate-400">NIS: {{ $a['nis'] }} • {{ $a['sekolah'] }}</p>
                                                </div>
                                                <a href="#" class="shrink-0 rounded-lg bg-[#0b6e56] px-3 py-1.5 text-[11px] font-bold text-white hover:bg-[#08523f]">{{ $s['status'] === 'selesai' ? 'Lihat' : 'Nilai' }}</a>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="mt-3 pt-3 border-t border-slate-200/80 flex items-center justify-between gap-2 text-xs">
                                        <span class="text-slate-500">Status: <span class="font-semibold text-slate-700">{{ $labelStatusPanel[$s['status']] }}</span></span>
                                        @if ($s['jumlah_asesi'] > 0)
                                            <button type="button" data-buka="modal-{{ $s['id'] }}" class="font-bold text-[#0d7a5f] hover:underline">Lihat {{ $s['jumlah_asesi'] }} Asesi →</button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="px-4 py-4 border-t border-slate-100 bg-slate-50/50 space-y-2.5">
                        <a href="#" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-[#0b6e56] px-4 py-3 text-sm font-bold text-white hover:bg-[#08523f] shadow-[0_8px_18px_-8px_rgba(11,110,86,.65)] transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/></svg>
                            Buka Penilaian Asesi
                        </a>
                        <a href="#" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                            Lihat Riwayat Penilaian
                        </a>
                    </div>
                </div>
            @endforeach
        </aside>
    </div>

    <script>
        (function () {
            var root = document.getElementById('kalender-root');
            if (!root) return;

            var sel    = root.querySelectorAll('[data-tanggal]');
            var panels = root.querySelectorAll('[data-panel]');
            var kosong = root.querySelector('[data-panel-kosong]');

            function pilih(tgl) {
                var ada = false;
                panels.forEach(function (p) {
                    var cocok = tgl && p.getAttribute('data-panel') === tgl;
                    p.hidden = !cocok;
                    if (cocok) ada = true;
                });
                if (kosong) kosong.hidden = ada;
                sel.forEach(function (c) {
                    c.classList.toggle('is-selected', !!tgl && c.getAttribute('data-tanggal') === tgl);
                });
            }

            sel.forEach(function (c) {
                c.addEventListener('click', function () { pilih(c.getAttribute('data-tanggal')); });
            });
            root.querySelectorAll('[data-tutup]').forEach(function (b) {
                b.addEventListener('click', function () { pilih(null); });
            });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') pilih(null); });

            pilih(root.getAttribute('data-awal') || null);
        })();
    </script>
@endif

    {{-- ===== Pop up daftar asesi (dipakai mode Daftar & Kalender) ===== --}}
    @php $sesiUntukModal = $tampilan === 'kalender' ? $sesiPerTanggal->collapse() : $paginator->getCollection(); @endphp
    @foreach ($sesiUntukModal as $s)
        @if (true)
            @php $bisaDinilai = $tampilan === 'daftar' && $s['status'] === 'berlangsung'; @endphp

            <div id="modal-{{ $s['id'] }}" data-modal hidden class="fixed inset-0 z-[100] flex items-center justify-center p-4"
                 role="dialog" aria-modal="true" aria-labelledby="judul-{{ $s['id'] }}">
                <div data-tutup-modal class="jd-modal-backdrop absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>

                <div class="jd-modal-panel relative w-full max-w-2xl max-h-[90vh] flex flex-col rounded-2xl bg-white shadow-2xl overflow-hidden">
                    {{-- Header --}}
                    <div class="px-5 sm:px-6 pt-5 pb-4 border-b border-slate-100 hari-ini-head">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="text-[11px] font-extrabold uppercase tracking-wider text-[#0d7a5f]">{{ $tampilan === 'kalender' ? 'Daftar Asesi' : 'Mulai Penilaian' }}</p>
                                <h3 id="judul-{{ $s['id'] }}" class="mt-1 text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">{{ $s['skema'] }}</h3>
                                <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-500">
                                    <span class="inline-flex items-center rounded-md bg-white border border-slate-200 px-2 py-0.5 text-[10px] font-bold font-mono text-slate-600">{{ $s['kode'] }}</span>
                                    <span>{{ $s['tanggal']->locale('id')->translatedFormat('l, j F Y') }} • {{ $s['mulai'] }} WIB</span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8z"/><circle cx="7.5" cy="7.5" r="1.2"/></svg>
                                        {{ $s['bidang'] }}
                                    </span>
                                </div>
                            </div>
                            <button type="button" data-tutup-modal data-fokus class="h-8 w-8 shrink-0 rounded-lg text-slate-400 hover:bg-white hover:text-slate-600 inline-flex items-center justify-center" aria-label="Tutup">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M6 6l12 12M18 6 6 18"/></svg>
                            </button>
                        </div>
                    </div>

                    @unless ($bisaDinilai)
                        <div class="mx-5 sm:mx-6 mt-4 flex items-start gap-2.5 rounded-xl border border-amber-200 bg-amber-50 px-3.5 py-2.5 text-xs text-amber-700">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>
                            <span>Asesmen ini terjadwal pada <strong>{{ $s['tanggal']->locale('id')->translatedFormat('l, j F Y') }}</strong> pukul <strong>{{ $s['mulai'] }} WIB</strong>. {{ $tampilan === 'kalender' ? 'Penilaian dilakukan dari halaman Daftar.' : 'Penilaian baru dapat dilakukan saat sesi sedang berjalan.' }}</span>
                        </div>
                    @endunless

                    {{-- Daftar asesi --}}
                    <div class="px-5 sm:px-6 py-4 overflow-y-auto">
                        <div class="overflow-x-auto rounded-xl border border-slate-200">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <tr>
                                        <th class="px-3.5 py-2.5 w-10">No</th>
                                        <th class="px-3.5 py-2.5">Nama</th>
                                        <th class="px-3.5 py-2.5">NIS/NISN</th>
                                        <th class="px-3.5 py-2.5">Asal Sekolah</th>
                                        <th class="px-3.5 py-2.5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($s['peserta'] as $i => $p)
                                        <tr class="hover:bg-slate-50/60">
                                            <td class="px-3.5 py-3 text-xs font-semibold text-slate-400">{{ $i + 1 }}</td>
                                            <td class="px-3.5 py-3">
                                                <div class="flex items-center gap-2.5">
                                                    <span class="h-8 w-8 shrink-0 rounded-lg text-[11px] font-extrabold inline-flex items-center justify-center {{ $kelasInisial[$i % 4] }}">{{ $p['inisial'] }}</span>
                                                    <span class="font-semibold text-slate-800">{{ $p['nama'] }}</span>
                                                </div>
                                            </td>
                                            <td class="px-3.5 py-3 font-mono text-xs text-slate-500">{{ $p['nis'] }}</td>
                                            <td class="px-3.5 py-3 text-xs text-slate-600">{{ $p['sekolah'] }}</td>
                                            <td class="px-3.5 py-3 text-right">
                                                @if ($p['dinilai'])
                                                    <span class="inline-flex items-center gap-1 rounded-lg border border-emerald-200 bg-[#eaf7f2] px-2.5 py-1.5 text-[11px] font-bold text-[#0d7a5f]">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                                                        Sudah Dinilai
                                                    </span>
                                                @elseif ($bisaDinilai)
                                                    <a href="{{ $p['url_nilai'] }}" class="inline-flex items-center rounded-lg bg-[#0b6e56] px-3.5 py-1.5 text-xs font-bold text-white hover:bg-[#08523f] transition">Nilai</a>
                                                @else
                                                    <button type="button" disabled title="Penilaian belum dapat dilakukan"
                                                            class="inline-flex items-center rounded-lg bg-slate-200 px-3.5 py-1.5 text-xs font-bold text-slate-400 cursor-not-allowed">Nilai</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-5 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between gap-3">
                        <p class="text-xs text-slate-500">
                            <strong class="text-slate-800">{{ $s['sudah_dinilai'] }}</strong> dari <strong class="text-slate-800">{{ $s['jumlah_asesi'] }}</strong> asesi sudah dinilai
                        </p>
                        <button type="button" data-tutup-modal class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">Tutup</button>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <script>
        (function () {
            var root = document.getElementById('asesor-jadwal');
            if (!root) return;

            function buka(id) {
                var m = document.getElementById(id);
                if (!m) return;
                m.hidden = false;
                document.body.style.overflow = 'hidden';
                var f = m.querySelector('[data-fokus]');
                if (f) f.focus();
            }

            function tutup(m) {
                if (!m) return;
                m.hidden = true;
                if (!document.querySelector('[data-modal]:not([hidden])')) {
                    document.body.style.overflow = '';
                }
            }

            root.addEventListener('click', function (e) {
                var b = e.target.closest('[data-buka]');
                if (b) { buka(b.getAttribute('data-buka')); return; }

                var c = e.target.closest('[data-tutup-modal]');
                if (c) { tutup(c.closest('[data-modal]')); }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('[data-modal]:not([hidden])').forEach(tutup);
                }
            });
        })();
    </script>

</div>
</x-layouts.app>