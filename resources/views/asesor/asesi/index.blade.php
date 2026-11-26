{{-- View "Daftar Asesi" (role asesor). Ganti isi view yang dipakai Asesor\AsesiController@index dengan file ini.
  Contoh lokasi: resources/views/asesor/asesi/index.blade.php

  KONTRAK DATA (dikirim controller saat backend jadi). Selama $daftarAsesi belum dikirim, halaman memakai DATA DUMMY
  di bawah (filter lewat query string tetap berfungsi pada data dummy).

  $daftarAsesi : koleksi ATAU paginator (LengthAwarePaginator) -> tiap item:
        id, nama, nis, sekolah, foto (url|null), skema, tanggal (Carbon|string), jam ("08:00 - 12:00"), ruang,
        status (menunggu|proses|kompeten|belum_kompeten), url_nilai, url_lihat
  $ringkasan   : ['total' => 142, 'persen' => 12, 'periode' => 'Semester Ganjil 2024', 'menunggu' => 18, 'proses' => 35,
                  'selesai' => 89 (= kompeten + belum_kompeten), 'kompeten' => 74, 'belum_kompeten' => 15]   (kompeten & belum_kompeten opsional)
  $skemaList   : array nama skema untuk dropdown filter
  $urlEkspor   : (opsional) URL ekspor dari server. Bila kosong, tombol mengekspor baris yang tampil ke CSV.
  Filter via query string (GET): ?status=semua|menunggu|proses|selesai &hasil=kompeten|belum_kompeten (hanya untuk status selesai)
                                  &q= &skema= &tanggal=YYYY-MM-DD &page=
--}}
@php
    $c = fn ($d) => $d instanceof \Carbon\CarbonInterface ? $d : \Carbon\Carbon::parse($d);

    $status  = in_array(request('status'), ['menunggu', 'proses', 'selesai'], true) ? request('status') : 'semua';
    $hasil   = in_array(request('hasil'), ['kompeten', 'belum_kompeten'], true) ? request('hasil') : null;
    if ($hasil) { $status = 'selesai'; }
    $q        = trim((string) request('q', ''));
    $skemaF   = (string) request('skema', '');
    $tanggalF = (string) request('tanggal', '');
    $difilter = $status !== 'semua' || $q !== '' || $skemaF !== '' || $tanggalF !== '';

    // ===================== DATA DUMMY (dipakai bila controller belum mengirim $daftarAsesi) =====================
    $dummy = ! isset($daftarAsesi);
    if ($dummy) {
        $d = fn ($y, $m, $t) => \Carbon\Carbon::create($y, $m, $t);
        $semua = collect([
            ['id' => 1, 'nama' => 'Budi Santoso',  'nis' => '21221001', 'sekolah' => 'SMK Negeri 1 Surabaya', 'skema' => 'Teknik Komputer Jaringan',   'tanggal' => $d(2024, 10, 15), 'jam' => '08:00 - 12:00', 'ruang' => 'TUK Lab Jaringan 1',   'status' => 'menunggu'],
            ['id' => 2, 'nama' => 'Siti Rahma',    'nis' => '21221002', 'sekolah' => 'SMK Negeri 1 Surabaya', 'skema' => 'Rekayasa Perangkat Lunak', 'tanggal' => $d(2024, 10, 14), 'jam' => '13:00 - 16:00', 'ruang' => 'TUK Lab Software RPL', 'status' => 'kompeten'],
            ['id' => 3, 'nama' => 'Ahmad Faisal',  'nis' => '21221003', 'sekolah' => 'SMK Negeri 4 Surabaya', 'skema' => 'Multimedia / DKV',         'tanggal' => $d(2024, 10, 12), 'jam' => '09:00 - 11:30', 'ruang' => 'TUK Multimedia Room',  'status' => 'kompeten'],
            ['id' => 4, 'nama' => 'Dwi Lestari',   'nis' => '21221004', 'sekolah' => 'SMK Negeri 5 Malang',   'skema' => 'Rekayasa Perangkat Lunak', 'tanggal' => $d(2024, 10, 16), 'jam' => '08:00 - 12:00', 'ruang' => 'TUK Lab RPL 2',        'status' => 'menunggu'],
            ['id' => 5, 'nama' => 'Rian Hidayat',  'nis' => '21221005', 'sekolah' => 'SMK Negeri 12 Malang',  'skema' => 'Teknik Jaringan Madya',    'tanggal' => $d(2024, 10, 10), 'jam' => '08:30 - 11:30', 'ruang' => 'TUK Jaringan Lab 2',    'status' => 'belum_kompeten'],
        ])->map(fn ($r) => (object) ($r + ['foto' => null, 'url_nilai' => '#', 'url_lihat' => '#']));

        $daftarAsesi = $semua->filter(function ($r) use ($status, $hasil, $q, $skemaF, $tanggalF, $c) {
            if ($status === 'menunggu' && $r->status !== 'menunggu') return false;
            if ($status === 'proses' && $r->status !== 'proses') return false;
            if ($status === 'selesai' && ! in_array($r->status, ['kompeten', 'belum_kompeten'], true)) return false;
            if ($hasil && $r->status !== $hasil) return false;
            if ($q !== '' && stripos($r->nama . ' ' . $r->nis, $q) === false) return false;
            if ($skemaF !== '' && $r->skema !== $skemaF) return false;
            if ($tanggalF !== '' && $c($r->tanggal)->format('Y-m-d') !== $tanggalF) return false;
            return true;
        })->values();

        $ringkasan = ['total' => 142, 'persen' => 12, 'periode' => 'Semester Ganjil 2024', 'menunggu' => 18, 'proses' => 35,
                      'selesai' => 89, 'kompeten' => 74, 'belum_kompeten' => 15];
        $skemaList = $semua->pluck('skema')->unique()->sort()->values()->all();
    }
    $skemaList ??= [];
    // ============================================================================================================

    // Paginasi: paginator Laravel bila ada; kalau tidak, nilai dummy sesuai desain
    if ($daftarAsesi instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
        $pg = ['cur' => $daftarAsesi->currentPage(), 'last' => $daftarAsesi->lastPage(), 'total' => $daftarAsesi->total(),
               'from' => $daftarAsesi->firstItem() ?? 0, 'to' => $daftarAsesi->lastItem() ?? 0];
        $pageUrl = fn ($n) => $daftarAsesi->url($n);
    } else {
        $n = $daftarAsesi->count();
        $pg = $difilter
            ? ['cur' => 1, 'last' => 1, 'total' => $n, 'from' => $n ? 1 : 0, 'to' => $n]
            : ['cur' => max(1, (int) request('page', 1)), 'last' => 29, 'total' => $ringkasan['total'], 'from' => 1, 'to' => $n];
        $pageUrl = fn ($p) => request()->fullUrlWithQuery(['page' => $p]);
    }
    $halaman = collect([1, 2, 3, $pg['last'], $pg['cur'] - 1, $pg['cur'], $pg['cur'] + 1])
        ->filter(fn ($p) => $p >= 1 && $p <= $pg['last'])->unique()->sort()->values();

    $tabUrl = fn ($st, $h = null) => request()->fullUrlWithQuery(['status' => $st === 'semua' ? null : $st, 'hasil' => $h, 'page' => null]);

    $a = 'fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"';
    $ic = [
        'download' => '<path d="M12 3v12m0 0-4-4m4 4 4-4M4 21h16"/>',
        'user-cog' => '<circle cx="9" cy="7" r="4"/><path d="M10 15H6a4 4 0 0 0-4 4v2"/><circle cx="18" cy="17" r="3"/><path d="M18 13v1M18 20v1M14.5 15l.9.5M20.6 18.5l.9.5M14.5 19l.9-.5M20.6 15.5l.9-.5"/>',
        'clock'    => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
        'pen'      => '<path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.4 2.6a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4z"/>',
        'gauge'    => '<path d="m12 14 4-4"/><path d="M3.34 19a10 10 0 1 1 17.32 0"/>',
        'refresh'  => '<path d="M3 12a9 9 0 0 1 15-6.7L21 8M21 3v5h-5M21 12a9 9 0 0 1-15 6.7L3 16M3 21v-5h5"/>',
        'search'   => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
        'chevron'  => '<path d="m6 9 6 6 6-6"/>',
        'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01"/>',
        'cal-sm'   => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'pin'      => '<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'eye'      => '<path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>',
        'check'    => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
        'x'        => '<path d="M18 6 6 18M6 6l12 12"/>',
        'chevl'    => '<path d="m15 18-6-6 6-6"/>',
        'chevr'    => '<path d="m9 18 6-6-6-6"/>',
        'arrow-up' => '<path d="M12 19V5M5 12l7-7 7 7"/>',
        'inbox'    => '<path d="M22 12h-6l-2 3h-4l-2-3H2"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>',
    ];
    $card = 'bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_3px_rgba(15,23,42,.05),0_10px_28px_-14px_rgba(15,23,42,.10)]';

    $stat = [
        ['lbl' => 'TOTAL ASESI DITUGASKAN', 'num' => $ringkasan['total'],    'numCls' => 'text-slate-900',   'ikon' => 'user-cog',
         'tile' => 'bg-gradient-to-br from-slate-100 to-slate-200 text-slate-700', 'bar' => 'from-slate-600 to-slate-300', 'glow' => 'bg-slate-200/70',
         'badge' => null, 'flag' => 'persen', 'sub' => ! empty($ringkasan['periode']) ? 'Periode ' . $ringkasan['periode'] : 'Asesi terdaftar'],
        ['lbl' => 'MENUNGGU PENILAIAN',     'num' => $ringkasan['menunggu'], 'numCls' => 'text-amber-600',   'ikon' => 'clock',
         'tile' => 'bg-gradient-to-br from-amber-50 to-amber-100 text-amber-600', 'bar' => 'from-amber-500 to-amber-300', 'glow' => 'bg-amber-200/60',
         'badge' => ['Perlu Diuji', 'bg-amber-50 text-amber-700 ring-1 ring-amber-100'], 'flag' => null, 'sub' => 'Menunggu form FR.IA diisi'],
        ['lbl' => 'SEDANG PROSES UJI',      'num' => $ringkasan['proses'],   'numCls' => 'text-teal-600',    'ikon' => 'pen',
         'tile' => 'bg-gradient-to-br from-teal-50 to-teal-100 text-teal-600', 'bar' => 'from-teal-600 to-emerald-300', 'glow' => 'bg-teal-200/60',
         'badge' => ['Berlangsung', 'bg-teal-50 text-teal-700 ring-1 ring-teal-100'], 'flag' => null, 'sub' => 'Observasi & Portofolio berjalan'],
        ['lbl' => 'SELESAI DINILAI',        'num' => $ringkasan['selesai'],  'numCls' => 'text-emerald-800', 'ikon' => 'gauge',
         'tile' => 'bg-gradient-to-br from-emerald-50 to-emerald-100 text-emerald-700', 'bar' => 'from-emerald-700 to-emerald-400', 'glow' => 'bg-emerald-200/60',
         'badge' => null, 'flag' => null, 'sub' => 'Rekomendasi FR.AK diterbitkan'],
    ];
    $tabs = [
        ['semua',    'Semua Asesi',     $ringkasan['total'],    'bg-emerald-100 text-emerald-800'],
        ['menunggu', 'Menunggu',        $ringkasan['menunggu'], 'bg-amber-100 text-amber-700'],
        ['proses',   'Dalam Proses',    $ringkasan['proses'],   'bg-teal-100 text-teal-700'],
        ['selesai',  'Selesai Dinilai', $ringkasan['selesai'],  'bg-emerald-100 text-emerald-700'],
    ];
    $hasilMenu = [
        [null,             'Semua Dinilai',  $ringkasan['selesai'] ?? null,        null],
        ['kompeten',       'Kompeten',       $ringkasan['kompeten'] ?? null,       'bg-emerald-100 text-emerald-700'],
        ['belum_kompeten', 'Belum Kompeten', $ringkasan['belum_kompeten'] ?? null, 'bg-red-100 text-red-600'],
    ];
    $stLabel = ['menunggu' => 'Menunggu', 'proses' => 'Dalam Proses', 'kompeten' => 'Kompeten', 'belum_kompeten' => 'Belum Kompeten'];
    $avatar  = ['bg-emerald-100 text-emerald-700', 'bg-amber-100 text-amber-700', 'bg-sky-100 text-sky-700', 'bg-rose-100 text-rose-700', 'bg-indigo-100 text-indigo-700'];
@endphp

<x-layouts.app :title="$title ?? 'Daftar Asesi'">
<style>
    @keyframes adIn  { from { opacity: 0; transform: translateY(14px); } }
    @keyframes adPop { from { opacity: 0; transform: translateY(-6px) scale(.97); } }
    .ad-in { animation: adIn .55s cubic-bezier(.22, 1, .36, 1) backwards; }
    #ad-selesai-menu:not(.hidden) { animation: adPop .16s ease-out; }
    @media (prefers-reduced-motion: reduce) { .ad-in, #ad-selesai-menu:not(.hidden) { animation: none; } }
</style>

<div id="asesi-page" class="max-w-[1100px]">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-3 text-sm text-slate-500" aria-label="Breadcrumb">
        <span>Portal Asesor</span><span class="text-slate-300">›</span><span class="font-semibold text-[#0b5a49]">Daftar Asesi</span>
    </nav>

    {{-- Judul + ekspor --}}
    <div class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Daftar Asesi</h1>
            <p class="mt-2 max-w-xl text-[17px] leading-relaxed text-slate-500">Daftar kandidat asesi beserta status proses dan hasil penilaian asesmen.</p>
        </div>
        @php $btnEkspor = 'inline-flex shrink-0 items-center gap-2.5 self-start rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-800 shadow-sm transition-all hover:-translate-y-0.5 hover:border-emerald-300 hover:text-[#0b5a49] hover:shadow-md'; @endphp
        @if (!empty($urlEkspor))
            <a href="{{ $urlEkspor }}" class="{{ $btnEkspor }}"><svg class="h-4 w-4" {!! $a !!}>{!! $ic['download'] !!}</svg>Ekspor Data</a>
        @else
            <button type="button" id="ad-ekspor" class="{{ $btnEkspor }}"><svg class="h-4 w-4" {!! $a !!}>{!! $ic['download'] !!}</svg>Ekspor Data</button>
        @endif
    </div>

    {{-- Ringkasan --}}
    <div class="mt-7 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($stat as $s)
            <div class="ad-in group relative flex items-center justify-between gap-3 overflow-hidden {{ $card }} p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_14px_30px_-12px_rgba(15,23,42,.18)]" style="animation-delay:{{ $loop->index * 0.07 }}s">
                <span aria-hidden="true" class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r {{ $s['bar'] }}"></span>
                <span aria-hidden="true" class="pointer-events-none absolute -right-10 -top-10 h-32 w-32 rounded-full blur-2xl {{ $s['glow'] }} opacity-60 transition-opacity group-hover:opacity-100"></span>
                <div class="relative min-w-0">
                    <p class="text-[13px] font-medium uppercase leading-7 tracking-[.1em] text-slate-400">{{ $s['lbl'] }}</p>
                    <p class="mt-1 flex items-center gap-2.5">
                        <span class="text-[34px] font-bold leading-none tabular-nums {{ $s['numCls'] }}">{{ $s['num'] }}</span>
                        @if ($s['flag'] === 'persen' && ! empty($ringkasan['persen']))
                            <span class="inline-flex items-center gap-1 rounded-md bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100"><svg class="h-3 w-3" stroke-width="2.4" {!! $a !!}>{!! $ic['arrow-up'] !!}</svg>{{ $ringkasan['persen'] }}%</span>
                        @elseif ($s['badge'])
                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $s['badge'][1] }}">{{ $s['badge'][0] }}</span>
                        @endif
                    </p>
                    <p class="mt-2 text-xs leading-relaxed text-slate-400">{{ $s['sub'] }}</p>
                </div>
                <span class="relative grid h-[52px] w-[52px] shrink-0 place-items-center rounded-2xl shadow-sm ring-1 ring-black/5 transition-transform duration-300 group-hover:scale-105 group-hover:-rotate-3 {{ $s['tile'] }}"><svg class="h-6 w-6" {!! $a !!}>{!! $ic[$s['ikon']] !!}</svg></span>
            </div>
        @endforeach
    </div>

    {{-- Tabel --}}
    <div class="ad-in mt-6 {{ $card }} overflow-hidden" style="animation-delay:.3s">

        {{-- Tab status (tanpa scroller; "Selesai Dinilai" punya dropdown hasil) --}}
        <div class="relative z-20 flex flex-wrap items-center justify-between gap-x-6 border-b border-slate-200 px-6">
            <nav class="flex flex-wrap items-center gap-x-8" aria-label="Filter status asesmen">
                @foreach ($tabs as [$key, $lbl, $cnt, $badge])
                    @if ($key !== 'selesai')
                        <a href="{{ $tabUrl($key) }}" data-active="{{ $status === $key ? 'true' : 'false' }}"
                           class="-mb-px flex items-center gap-2.5 border-b-2 border-transparent py-5 text-[15px] font-medium text-slate-500 transition-colors hover:text-slate-800 data-[active=true]:border-[#0b5a49] data-[active=true]:font-semibold data-[active=true]:text-[#0b5a49]">
                            {{ $lbl }}<span class="rounded-full px-2.5 py-0.5 text-[13px] font-semibold tabular-nums {{ $badge }}">{{ $cnt }}</span>
                        </a>
                    @else
                        <div class="group relative -mb-px flex items-center border-b-2 border-transparent data-[active=true]:border-[#0b5a49]" data-active="{{ $status === 'selesai' ? 'true' : 'false' }}">
                            <a href="{{ $tabUrl('selesai') }}" class="flex items-center gap-2.5 py-5 text-[15px] font-medium text-slate-500 transition-colors hover:text-slate-800 group-data-[active=true]:font-semibold group-data-[active=true]:text-[#0b5a49]">
                                {{ $lbl }}<span class="rounded-full px-2.5 py-0.5 text-[13px] font-semibold tabular-nums {{ $badge }}">{{ $cnt }}</span>
                                @if ($hasil)
                                    <span class="rounded-md px-2 py-0.5 text-[11px] font-semibold {{ $hasil === 'kompeten' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">{{ $hasil === 'kompeten' ? 'Kompeten' : 'Belum Kompeten' }}</span>
                                @endif
                            </a>
                            <button type="button" id="ad-selesai-btn" aria-haspopup="menu" aria-expanded="false" aria-controls="ad-selesai-menu" aria-label="Pilih hasil penilaian"
                                    class="ml-1 grid h-7 w-7 place-items-center rounded-md text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-700 group-data-[active=true]:text-[#0b5a49]">
                                <svg id="ad-selesai-ikon" class="h-4 w-4 transition-transform duration-200" {!! $a !!}>{!! $ic['chevron'] !!}</svg>
                            </button>
                            <div id="ad-selesai-menu" role="menu" class="hidden absolute left-0 top-full z-30 mt-1 w-60 rounded-xl border border-slate-200 bg-white p-1.5 shadow-[0_16px_40px_-12px_rgba(15,23,42,.25)]">
                                @foreach ($hasilMenu as [$hKey, $hLbl, $hCnt, $hCls])
                                    @php $aktifMenu = $status === 'selesai' && $hasil === $hKey; @endphp
                                    <a href="{{ $tabUrl('selesai', $hKey) }}" role="menuitem" @if ($aktifMenu) aria-current="true" @endif
                                       class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-700 transition-colors hover:bg-slate-50 {{ $aktifMenu ? 'bg-slate-50 font-semibold' : '' }}">
                                        @if ($hKey === 'kompeten')
                                            <span class="grid h-6 w-6 place-items-center rounded-full {{ $hCls }}"><svg class="h-3.5 w-3.5" stroke-width="2.6" {!! $a !!}>{!! $ic['check'] !!}</svg></span>
                                        @elseif ($hKey === 'belum_kompeten')
                                            <span class="grid h-6 w-6 place-items-center rounded-full {{ $hCls }}"><svg class="h-3.5 w-3.5" stroke-width="2.6" {!! $a !!}>{!! $ic['x'] !!}</svg></span>
                                        @else
                                            <span class="grid h-6 w-6 place-items-center rounded-full bg-slate-100 text-slate-500"><svg class="h-3.5 w-3.5" {!! $a !!}>{!! $ic['gauge'] !!}</svg></span>
                                        @endif
                                        <span class="flex-1">{{ $hLbl }}</span>
                                        @if (! is_null($hCnt))<span class="text-xs tabular-nums text-slate-400">{{ $hCnt }}</span>@endif
                                        @if ($aktifMenu)<svg class="h-4 w-4 text-[#0b5a49]" stroke-width="2.4" {!! $a !!}>{!! $ic['check'] !!}</svg>@endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </nav>
            <a href="{{ url()->current() }}" class="my-3 inline-flex shrink-0 items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition-colors {{ $difilter ? 'bg-emerald-50 text-[#0b5a49] hover:bg-emerald-100' : 'text-slate-500 hover:bg-slate-50 hover:text-[#0b5a49]' }}">
                <svg class="h-4 w-4" {!! $a !!}>{!! $ic['refresh'] !!}</svg>Reset Filter
                @if ($difilter)<span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>@endif
            </a>
        </div>

        {{-- Filter (GET, diproses server) --}}
        <form id="ad-filter" method="GET" action="{{ url()->current() }}" class="flex flex-col gap-3 border-b border-slate-200 bg-slate-50/50 px-6 py-5 lg:flex-row lg:items-center">
            @if ($status !== 'semua')<input type="hidden" name="status" value="{{ $status }}">@endif
            @if ($hasil)<input type="hidden" name="hasil" value="{{ $hasil }}">@endif
            <label class="relative flex-1">
                <span class="sr-only">Cari asesi</span>
                <svg class="absolute left-4 top-1/2 h-[18px] w-[18px] -translate-y-1/2 text-slate-400" {!! $a !!}>{!! $ic['search'] !!}</svg>
                <input id="ad-cari" type="search" name="q" value="{{ $q }}" placeholder="Cari nama atau NISN asesi..." class="h-11 w-full rounded-xl border border-slate-200 bg-white pl-11 pr-10 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-[#0b5a49] focus:outline-none focus:ring-4 focus:ring-emerald-100">
                <kbd class="pointer-events-none absolute right-3 top-1/2 hidden -translate-y-1/2 rounded border border-slate-200 bg-slate-50 px-1.5 py-0.5 font-sans text-[11px] text-slate-400 sm:inline">/</kbd>
            </label>
            <label class="relative lg:w-[257px]">
                <span class="sr-only">Skema sertifikasi</span>
                <select name="skema" data-auto class="h-11 w-full cursor-pointer appearance-none rounded-xl border border-slate-200 bg-white pl-4 pr-10 text-sm text-slate-800 shadow-sm transition focus:border-[#0b5a49] focus:outline-none focus:ring-4 focus:ring-emerald-100">
                    <option value="">Semua Skema Sertifikasi</option>
                    @foreach ($skemaList as $sk)<option value="{{ $sk }}" @selected($skemaF === $sk)>{{ $sk }}</option>@endforeach
                </select>
                <svg class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" {!! $a !!}>{!! $ic['chevron'] !!}</svg>
            </label>
            <label class="relative flex h-11 cursor-pointer items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white pl-4 pr-3.5 text-sm shadow-sm transition focus-within:border-[#0b5a49] focus-within:ring-4 focus-within:ring-emerald-100 lg:w-[160px]">
                <span id="ad-tgl-teks" class="{{ $tanggalF ? 'text-slate-700' : 'text-slate-400' }}">{{ $tanggalF ? \Carbon\Carbon::parse($tanggalF)->format('d / m / Y') : 'dd / mm / yyyy' }}</span>
                <svg class="h-[18px] w-[18px] shrink-0 text-slate-500" {!! $a !!}>{!! $ic['calendar'] !!}</svg>
                <input type="date" id="ad-tgl" name="tanggal" value="{{ $tanggalF }}" aria-label="Tanggal asesmen" class="absolute inset-0 h-full w-full cursor-pointer opacity-0">
            </label>
        </form>

        {{-- Tabel asesi --}}
        <div class="overflow-x-auto">
            <table class="w-full min-w-[880px] table-fixed text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-center text-[12px] font-semibold uppercase tracking-[.1em] text-slate-500">
                        <th scope="col" class="w-[27%] px-6 py-5">Kandidat Asesi</th>
                        <th scope="col" class="w-[18%] px-4 py-5">Skema Sertifikasi</th>
                        <th scope="col" class="w-[19%] px-4 py-5 leading-relaxed">Jadwal Asesmen</th>
                        <th scope="col" class="w-[20%] px-4 py-5">Status Asesmen</th>
                        <th scope="col" class="w-[16%] px-4 py-5">Aksi Penilaian</th>
                    </tr>
                </thead>
                <tbody id="ad-body" class="divide-y divide-slate-100">
                    @forelse ($daftarAsesi as $r)
                        @php
                            $tg = ! empty($r->tanggal) ? $c($r->tanggal) : null;
                            $ini = collect(preg_split('/\s+/', trim($r->nama)))->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->join('');
                            $selesai = in_array($r->status, ['kompeten', 'belum_kompeten'], true);
                        @endphp
                        <tr class="ad-in group transition-colors hover:bg-emerald-50/40" style="animation-delay:{{ min($loop->index, 8) * 0.05 }}s"
                            data-row data-nama="{{ $r->nama }}" data-nis="{{ $r->nis }}" data-sekolah="{{ $r->sekolah }}" data-skema="{{ $r->skema ?? '' }}"
                            data-tanggal="{{ $tg?->format('Y-m-d') }}" data-jam="{{ $r->jam ?? '' }}" data-ruang="{{ $r->ruang ?? '' }}" data-status="{{ $stLabel[$r->status ?? ''] ?? '' }}">
                            <td class="px-6 py-9 transition-shadow group-hover:shadow-[inset_3px_0_0_0_#0b5a49]">
                                <div class="flex items-center gap-4">
                                    @if (!empty($r->foto))
                                        <img src="{{ $r->foto }}" alt="Foto {{ $r->nama }}" loading="lazy" class="h-[52px] w-[52px] shrink-0 rounded-full border-2 border-white object-cover shadow-md ring-1 ring-slate-200">
                                    @else
                                        <span class="grid h-[52px] w-[52px] shrink-0 place-items-center rounded-full border-2 border-white text-sm font-bold shadow-md ring-1 ring-slate-200 {{ $avatar[$loop->index % count($avatar)] }}">{{ $ini }}</span>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="truncate text-[17px] font-semibold leading-tight text-slate-900">{{ $r->nama }}</p>
                                        <p class="mt-1 font-serif text-[13px] text-slate-700">NIS: {{ $r->nis }}</p>
                                        <p class="truncate text-sm text-slate-400">{{ $r->sekolah }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-9 text-center">
                                @if (!empty($r->skema))
                                    <span class="inline-block rounded-lg bg-emerald-50 px-3 py-1.5 text-[13px] font-semibold leading-snug text-emerald-700 ring-1 ring-emerald-100">{{ $r->skema }}</span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-9">
                                @if ($tg)
                                    <div class="mx-auto w-[158px] space-y-1.5 text-left">
                                        <p class="flex items-center gap-2 text-[14px] font-bold text-slate-900"><svg class="h-3.5 w-3.5 shrink-0 text-slate-400" {!! $a !!}>{!! $ic['cal-sm'] !!}</svg>{{ $tg->locale('id')->translatedFormat('d M Y') }}</p>
                                        <p class="flex items-center gap-2 text-[13px] text-slate-500"><svg class="h-3.5 w-3.5 shrink-0 text-slate-400" {!! $a !!}>{!! $ic['clock'] !!}</svg>{{ ! empty($r->jam) ? $r->jam . ' WIB' : '-' }}</p>
                                        <p class="flex items-start gap-2 text-[13px] leading-snug text-slate-700"><svg class="mt-0.5 h-3.5 w-3.5 shrink-0 text-slate-400" {!! $a !!}>{!! $ic['pin'] !!}</svg>{{ $r->ruang ?? '-' }}</p>
                                    </div>
                                @else
                                    <p class="text-center text-[13px] text-slate-400">Belum dijadwalkan</p>
                                @endif
                            </td>
                            <td class="px-4 py-9 text-center">
                                @if ($r->status === 'menunggu')
                                    <span class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-4 py-1.5 text-sm font-medium text-amber-800"><span class="h-2 w-2 rounded-full bg-amber-500"></span>Menunggu</span>
                                @elseif ($r->status === 'proses')
                                    <span class="inline-flex items-center gap-2 rounded-full border border-teal-200 bg-teal-50 px-4 py-1.5 text-sm font-medium text-teal-800"><span class="h-2 w-2 animate-pulse rounded-full bg-teal-500"></span>Dalam Proses</span>
                                @elseif ($r->status === 'kompeten')
                                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-1.5 text-sm font-medium text-emerald-700"><svg class="h-4 w-4" stroke-width="2.2" {!! $a !!}>{!! $ic['check'] !!}</svg>Kompeten</span>
                                @elseif ($r->status === 'belum_kompeten')
                                    <span class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-4 py-1.5 text-sm font-medium text-red-700"><svg class="h-4 w-4" stroke-width="2.6" {!! $a !!}>{!! $ic['x'] !!}</svg>Belum Kompeten</span>
                                @else
                                    <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-1.5 text-sm font-medium text-slate-500"><span class="h-2 w-2 rounded-full bg-slate-400"></span>Belum Dinilai</span>
                                @endif
                            </td>
                            <td class="px-4 py-9 text-center">
                                @if ($selesai)
                                    <a href="{{ $r->url_lihat ?? '#' }}" title="Lihat hasil penilaian" class="inline-flex items-center gap-2 rounded-lg bg-[#0b5a49] px-5 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:bg-[#074237] hover:shadow-md"><svg class="h-4 w-4" {!! $a !!}>{!! $ic['eye'] !!}</svg>Lihat</a>
                                @else
                                    <a href="{{ $r->url_nilai ?? '#' }}" title="Mulai penilaian asesmen" class="inline-flex items-center gap-2 rounded-lg border border-[#0b5a49] bg-white px-5 py-2 text-sm font-semibold text-[#0b5a49] shadow-sm transition-all hover:-translate-y-0.5 hover:bg-[#0b5a49] hover:text-white hover:shadow-md"><svg class="h-4 w-4" {!! $a !!}>{!! $ic['pen'] !!}</svg>Nilai</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <span class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-slate-400"><svg class="h-7 w-7" {!! $a !!}>{!! $ic['inbox'] !!}</svg></span>
                                <p class="mt-4 text-sm font-semibold text-slate-700">Tidak ada asesi yang cocok</p>
                                <p class="mt-1 text-sm text-slate-400">Coba ubah kata kunci atau reset filter yang dipilih.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer: info + paginasi --}}
        <div class="flex flex-col items-start justify-between gap-4 border-t border-slate-200 bg-slate-50/50 px-6 py-5 sm:flex-row sm:items-center">
            <p class="text-sm text-slate-500">Menampilkan <b class="font-semibold text-slate-800">{{ $pg['from'] }}</b> sampai <b class="font-semibold text-slate-800">{{ $pg['to'] }}</b> dari <b class="font-semibold text-slate-800">{{ $pg['total'] }}</b> data asesi</p>
            <nav class="flex items-center gap-2 text-sm" aria-label="Paginasi">
                @if ($pg['cur'] > 1)
                    <a href="{{ $pageUrl($pg['cur'] - 1) }}" aria-label="Sebelumnya" class="grid h-10 w-10 place-items-center rounded-lg border border-slate-200 bg-white text-slate-700 shadow-sm transition-colors hover:border-emerald-300 hover:text-[#0b5a49]"><svg class="h-4 w-4" {!! $a !!}>{!! $ic['chevl'] !!}</svg></a>
                @else
                    <span aria-disabled="true" class="grid h-10 w-10 place-items-center rounded-lg border border-slate-100 bg-white text-slate-300"><svg class="h-4 w-4" {!! $a !!}>{!! $ic['chevl'] !!}</svg></span>
                @endif
                @foreach ($halaman as $p)
                    @if (! $loop->first && $p - $halaman[$loop->index - 1] > 1)<span class="px-1 text-slate-400">...</span>@endif
                    @if ($p === $pg['cur'])
                        <span aria-current="page" class="grid h-10 min-w-10 place-items-center rounded-lg bg-[#0b5a49] px-2 font-semibold text-white shadow-sm">{{ $p }}</span>
                    @else
                        <a href="{{ $pageUrl($p) }}" class="grid h-10 min-w-10 place-items-center rounded-lg border border-slate-200 bg-white px-2 text-slate-700 shadow-sm transition-colors hover:border-emerald-300 hover:text-[#0b5a49]">{{ $p }}</a>
                    @endif
                @endforeach
                @if ($pg['cur'] < $pg['last'])
                    <a href="{{ $pageUrl($pg['cur'] + 1) }}" aria-label="Berikutnya" class="grid h-10 w-10 place-items-center rounded-lg border border-slate-200 bg-white text-slate-700 shadow-sm transition-colors hover:border-emerald-300 hover:text-[#0b5a49]"><svg class="h-4 w-4" {!! $a !!}>{!! $ic['chevr'] !!}</svg></a>
                @else
                    <span aria-disabled="true" class="grid h-10 w-10 place-items-center rounded-lg border border-slate-100 bg-white text-slate-300"><svg class="h-4 w-4" {!! $a !!}>{!! $ic['chevr'] !!}</svg></span>
                @endif
            </nav>
        </div>
    </div>
</div>

@verbatim
<script>
(() => {
    const root = document.getElementById('asesi-page');
    if (!root) return;
    const form = document.getElementById('ad-filter');

    // Dropdown skema & tanggal: filter langsung dikirim ke server (query string)
    form.querySelectorAll('[data-auto]').forEach((el) => el.addEventListener('change', () => form.submit()));
    const tgl = document.getElementById('ad-tgl');
    tgl.addEventListener('click', () => { try { tgl.showPicker && tgl.showPicker(); } catch (e) { /* abaikan */ } });
    tgl.addEventListener('change', () => {
        const teks = document.getElementById('ad-tgl-teks');
        if (tgl.value) { const [y, m, d] = tgl.value.split('-'); teks.textContent = d + ' / ' + m + ' / ' + y; }
        form.submit();
    });

    // Pintasan "/" untuk fokus ke kolom cari
    const cari = document.getElementById('ad-cari');
    document.addEventListener('keydown', (e) => {
        const t = e.target, mengetik = t && (t.tagName === 'INPUT' || t.tagName === 'TEXTAREA' || t.tagName === 'SELECT' || t.isContentEditable);
        if (e.key === '/' && !mengetik && !e.ctrlKey && !e.metaKey) { e.preventDefault(); cari.focus(); }
    });

    // Dropdown hasil pada tab "Selesai Dinilai"
    const btn = document.getElementById('ad-selesai-btn'), menu = document.getElementById('ad-selesai-menu'), ikon = document.getElementById('ad-selesai-ikon');
    if (btn && menu) {
        const set = (buka) => {
            menu.classList.toggle('hidden', !buka);
            btn.setAttribute('aria-expanded', buka ? 'true' : 'false');
            ikon.classList.toggle('rotate-180', buka);
        };
        btn.addEventListener('click', (e) => { e.stopPropagation(); set(menu.classList.contains('hidden')); });
        document.addEventListener('click', (e) => { if (!menu.contains(e.target)) set(false); });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !menu.classList.contains('hidden')) { set(false); btn.focus(); } });
    }

    // Ekspor Data (CSV dari baris yang sedang tampil)
    const ekspor = document.getElementById('ad-ekspor');
    if (ekspor) ekspor.addEventListener('click', () => {
        const rows = [...root.querySelectorAll('[data-row]')];
        const esc = (v) => '"' + String(v ?? '').replace(/"/g, '""') + '"';
        const lines = [['Nama', 'NIS', 'Asal Sekolah', 'Skema Sertifikasi', 'Tanggal', 'Waktu', 'Ruang TUK', 'Status'].map(esc).join(',')];
        rows.forEach((r) => {
            const d = r.dataset;
            lines.push([d.nama, d.nis, d.sekolah, d.skema, d.tanggal, d.jam ? d.jam + ' WIB' : '', d.ruang, d.status].map(esc).join(','));
        });
        const blob = new Blob(['\ufeff' + lines.join('\r\n')], { type: 'text/csv;charset=utf-8;' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'Daftar-Asesi-' + new Date().toISOString().slice(0, 10) + '.csv';
        document.body.appendChild(a); a.click(); a.remove();
        setTimeout(() => URL.revokeObjectURL(a.href), 1000);
    });
})();
</script>
@endverbatim
</x-layouts.app>