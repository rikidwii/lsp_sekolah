{{-- resources/views/.../skema/daftar.blade.php  (FILE BARU, wizard pendaftaran 4 langkah) --}}
{{--
  KONTRAK DATA (dikirim controller nanti; selama belum ada, dipakai DATA DUMMY):
  $daftarSkema : koleksi/paginator
      kode, nama, tipe (Klaster|Okupasi), durasi (menit), jumlah_unit, kategori, ikon (code|network|tools|store|database),
      tone (teal|cyan|emerald|blue|slate), url_detail
      (kirim HANYA skema yang aktif; skema yang belum hadir tidak ditampilkan)
  $kategori    : array nama kategori untuk filter chip
  $kelasList   : array pilihan Kelas/Jurusan
  $urlSimpan   : URL POST submit (opsional)  | $urlBatal : URL tombol Batal (opsional)
--}}
@php
    // ===================== DATA DUMMY =====================
    $daftarSkema ??= collect([
        (object) ['kode' => 'TIK.PBB.01', 'nama' => 'Pemrograman Perangkat Lunak Berbasis Objek', 'tipe' => 'Klaster', 'durasi' => 120, 'jumlah_unit' => 15, 'kategori' => 'Teknologi Informasi', 'ikon' => 'code', 'tone' => 'teal', 'status' => 'tersedia'],
        (object) ['kode' => 'TIK.TJK.02', 'nama' => 'Teknisi Jaringan Komputer Madya', 'tipe' => 'Okupasi', 'durasi' => 180, 'jumlah_unit' => 8, 'kategori' => 'Teknologi Informasi', 'ikon' => 'network', 'tone' => 'cyan', 'status' => 'tersedia'],
        (object) ['kode' => 'DSN.DKV.03', 'nama' => 'Desain Grafis Komunikasi Visual Utama', 'tipe' => 'Klaster', 'durasi' => 150, 'jumlah_unit' => 12, 'kategori' => 'Desain', 'ikon' => 'tools', 'tone' => 'emerald', 'status' => 'tersedia'],
        (object) ['kode' => 'BSN.RTL.01', 'nama' => 'Pengelolaan Bisnis Ritel Modern', 'tipe' => 'Okupasi', 'durasi' => 90, 'jumlah_unit' => 6, 'kategori' => 'Bisnis', 'ikon' => 'store', 'tone' => 'blue', 'status' => 'tersedia'],
    ]);
    // Pengaman: bila controller mengirim koleksi biasa, buang skema non-aktif (paginator tidak diubah)
    if ($daftarSkema instanceof \Illuminate\Support\Collection) {
        $daftarSkema = $daftarSkema->reject(fn ($x) => in_array($x->status ?? 'tersedia', ['segera', 'nonaktif', 'draft'], true))->values();
    }
    $kategori ??= collect($daftarSkema)->pluck('kategori')->unique()->values()->all();
    $kelasList ??= ['X RPL', 'XI RPL', 'XII RPL 1', 'XII RPL 2', 'X TKJ', 'XI TKJ', 'XII TKJ', 'X DKV', 'XI DKV', 'XII DKV', 'XII Bisnis Digital'];
    // =======================================================

    $a = 'fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"';
    $ic = [
        'code'     => '<path d="m16 18 6-6-6-6M8 6l-6 6 6 6"/>',
        'network'  => '<rect x="16" y="16" width="6" height="6" rx="1"/><rect x="2" y="16" width="6" height="6" rx="1"/><rect x="9" y="2" width="6" height="6" rx="1"/><path d="M5 16v-3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3M12 12V8"/>',
        'tools'    => '<path d="m15 5 4 4M2 22l1.5-5.5L17 3l4 4L7.5 20.5zM5 15l4 4"/>',
        'store'    => '<path d="m2 7 2-4h16l2 4M2 7v1a3 3 0 0 0 6 0 3 3 0 0 0 6 0 3 3 0 0 0 6 0V7M2 7h20M4 12v9h16v-9M9 21v-5h6v5"/>',
        'database' => '<ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14a9 3 0 0 0 18 0V5M3 12a9 3 0 0 0 18 0"/>',
        'clock'    => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
        'doc'      => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z"/><path d="M14 2v4a2 2 0 0 0 2 2h4M8 13h8M8 17h5"/>',
        'search'   => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
        'right'    => '<path d="M5 12h14m-6-6 6 6-6 6"/>',
        'left'     => '<path d="M19 12H5m6-6-6 6 6 6"/>',
        'check'    => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
        'x'        => '<path d="M18 6 6 18M6 6l12 12"/>',
        'cloud'    => '<path d="M12 13v8m0-8-3 3m3-3 3 3M20 17.6A5 5 0 0 0 18 8h-1.3A8 8 0 1 0 4 16.3"/>',
        'idcard'   => '<rect x="2" y="5" width="20" height="14" rx="2"/><circle cx="9" cy="11" r="2"/><path d="M5.5 16c.5-1.5 1.7-2 3.5-2s3 .5 3.5 2M15 10h4M15 14h3"/>',
        'grad'     => '<path d="M22 10 12 5 2 10l10 5zM6 12v5c3 2.5 9 2.5 12 0v-5"/>',
        'photo'    => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="12" cy="10" r="3"/><path d="M6.5 19c.7-2.5 2.7-3.5 5.5-3.5s4.8 1 5.5 3.5"/>',
        'letter'   => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h8M14 2v4a2 2 0 0 0 2 2h4M20 7v6M16 21l5-5M16 16h5v5"/>',
        'user'     => '<circle cx="12" cy="8" r="4"/><path d="M4 21c1-4 4-6 8-6s7 2 8 6"/>',
        'folder'   => '<path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.7-.9l-.8-1.2A2 2 0 0 0 7.9 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2z"/>',
        'edit'     => '<path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>',
        'send'     => '<path d="m22 2-7 20-4-9-9-4zM22 2 11 13"/>',
        'pdf'      => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/>',
        'chevron'  => '<path d="m6 9 6 6 6-6"/>',
        'chevl'    => '<path d="m15 18-6-6 6-6"/>',
        'chevr'    => '<path d="m9 18 6-6-6-6"/>',
        'sliders'  => '<path d="M21 4h-7M10 4H3M21 12h-9M8 12H3M21 20h-5M12 20H3M14 2v4M8 10v4M16 18v4"/>',
    ];
    $tones = [
        'teal'    => 'bg-teal-100 text-teal-700',
        'cyan'    => 'bg-cyan-100 text-cyan-700',
        'emerald' => 'bg-emerald-100 text-emerald-800',
        'blue'    => 'bg-indigo-100 text-indigo-700',
        'slate'   => 'bg-slate-100 text-slate-400',
    ];
    $stepLabels = ['Pilih Skema', 'Data Diri', 'Upload Persyaratan', 'Review & Submit'];
    $dokumen = [
        ['ktp',       '1. KTP / Kartu Pelajar', 'Identitas resmi yang masih berlaku.',   'idcard', 'Maks 5MB (PDF/JPG/PNG)', 5, '.pdf,.jpg,.jpeg,.png', 'Kartu Tanda Penduduk / Kartu Pelajar'],
        ['transkrip', '2. Transkrip Nilai',     'Transkrip semester terakhir dilegalisir.', 'grad',   'Maks 5MB (PDF/JPG/PNG)', 5, '.pdf,.jpg,.jpeg,.png', 'Fotokopi Rapor Semester Terakhir'],
        ['foto',      '3. Pas Foto',            'Ukuran 3×4, latar belakang merah.',       'photo',  'Maks 2MB (JPG/PNG)',     2, '.jpg,.jpeg,.png',      'Pas Foto 3×4 Background Merah'],
        ['rekomendasi', '4. Surat Rekomendasi', 'Dari pihak sekolah / kepala jurusan.',    'letter', 'Maks 5MB (PDF)',         5, '.pdf',                 'Surat Rekomendasi Sekolah'],
    ];
    $inp = 'w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-[#0b5a49] focus:ring-2 focus:ring-emerald-100';
    $btnP = 'inline-flex items-center justify-center gap-2 rounded-lg bg-[#0b5a49] hover:bg-[#074237] px-5 py-2.5 text-sm font-semibold text-white transition-colors disabled:opacity-50 disabled:cursor-not-allowed';
    $btnO = 'inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 px-5 py-2.5 text-sm font-semibold text-slate-800 transition-colors';
@endphp

<x-layouts.app :title="$title ?? 'Pendaftaran Sertifikasi'">
<style>
    @keyframes dfIn { from { opacity: 0; transform: translateY(14px); } }
    @keyframes dfPop { 0% { transform: scale(.975); } 55% { transform: scale(1.018); } 100% { transform: scale(1); } }
    @keyframes dfCheck { 0% { transform: scale(0) rotate(-45deg); opacity: 0; } 70% { transform: scale(1.2) rotate(0); opacity: 1; } 100% { transform: scale(1); opacity: 1; } }
    .df-in { animation: dfIn .5s cubic-bezier(.22, 1, .36, 1) backwards; }
    [data-card][data-selected="true"] { animation: dfPop .4s cubic-bezier(.22, 1, .36, 1); }
    [data-card][data-selected="true"] [data-check] { animation: dfCheck .4s cubic-bezier(.22, 1, .36, 1) forwards; }
    @media (prefers-reduced-motion: reduce) { .df-in, [data-card][data-selected="true"], [data-card][data-selected="true"] [data-check] { animation: none; } [data-card] { transition: none !important; } }
</style>

<form id="daftar-page" class="max-w-[1000px] mx-auto" method="POST" enctype="multipart/form-data" novalidate
      action="{{ $urlSimpan ?? '#' }}" data-batal="{{ $urlBatal ?? url()->previous() }}">
    @csrf
    <input type="hidden" name="skema_kode" id="skema_kode" value="">

    {{-- ============ Header (teks diganti JS per langkah) ============ --}}
    <header id="df-head" class="text-center">
        <a id="df-back" href="#" class="hidden items-center gap-1.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500 hover:text-slate-800">
            <svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['left'] !!}</svg> Pendaftaran Sertifikasi
        </a>
        <h1 id="df-title" class="text-3xl font-bold text-[#0b6b5e]">Pendaftaran Sertifikasi</h1>
        <p id="df-sub" class="mt-1.5 text-sm text-slate-600">Lengkapi data untuk mendaftar ujian kompetensi</p>
    </header>

    {{-- ============ Stepper ============ --}}
    <div class="mt-5 {{ 'bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,.04)]' }} px-4 py-4 overflow-hidden">
        <ol id="df-stepper" class="flex items-start">
            @foreach ($stepLabels as $i => $lbl)
                <li class="group flex-1 min-w-0 flex flex-col items-center text-center" data-stp="{{ $i + 1 }}" data-state="pending">
                    <div class="flex items-center w-full">
                        <span class="h-0.5 flex-1 {{ $loop->first ? 'invisible' : '' }} bg-slate-200 group-data-[state=done]:bg-[#0b5a49] group-data-[state=current]:bg-[#0b5a49]"></span>
                        <span class="w-9 h-9 shrink-0 rounded-full grid place-items-center text-sm font-semibold transition-all
                                     bg-indigo-100 text-slate-700
                                     group-data-[state=done]:bg-[#0b5a49] group-data-[state=done]:text-white
                                     group-data-[state=current]:bg-[#0b5a49] group-data-[state=current]:text-white group-data-[state=current]:ring-4 group-data-[state=current]:ring-emerald-100">
                            <span class="group-data-[state=done]:hidden">{{ $i + 1 }}</span>
                            <svg class="w-4 h-4 hidden group-data-[state=done]:block" stroke-width="2.4" {!! $a !!}>{!! $ic['check'] !!}</svg>
                        </span>
                        <span class="h-0.5 flex-1 {{ $loop->last ? 'invisible' : '' }} bg-slate-200 group-data-[state=done]:bg-[#0b5a49]"></span>
                    </div>
                    <span class="mt-2 text-[11px] font-semibold text-slate-600 group-data-[state=current]:text-[#0b5a49] group-data-[state=done]:text-[#0b5a49]">{{ $lbl }}</span>
                </li>
            @endforeach
        </ol>
    </div>

    {{-- ============================================================ --}}
    {{-- LANGKAH 1 : PILIH SKEMA                                       --}}
    {{-- ============================================================ --}}
    <section data-step="1" class="df-in mt-5">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,.04)] p-5 sm:p-7">
            <h2 class="text-xl font-bold text-slate-900">Pilih Skema Sertifikasi</h2>
            <p class="mt-3 text-xs text-slate-600">Silakan pilih skema sertifikasi yang ingin Anda ikuti sesuai dengan kompetensi keahlian Anda.</p>

            <div class="mt-4 flex flex-col lg:flex-row lg:items-center gap-3 w-full rounded-xl bg-white shadow-[0_2px_10px_rgba(15,23,42,.08)] p-2">
                <label class="relative w-full lg:w-[355px] lg:shrink-0">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" {!! $a !!}>{!! $ic['search'] !!}</svg>
                    <input id="df-cari" type="search" placeholder="Cari skema sertifikasi..." class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-xs placeholder:text-slate-400 focus:outline-none focus:border-[#0b5a49]">
                </label>
                <div class="flex flex-1 min-w-0 items-center gap-2 overflow-x-auto">
                    <span class="text-[9px] font-bold uppercase tracking-wide text-slate-700">Filter:</span>
                    @foreach (array_merge(['Semua'], $kategori) as $k)
                        <button type="button" data-filter="{{ $loop->first ? '' : $k }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                                class="shrink-0 rounded-full border border-slate-200 bg-white px-3 py-1 text-[11px] font-semibold text-slate-700 aria-pressed:bg-[#0b6b5e] aria-pressed:border-[#0b6b5e] aria-pressed:text-white">{{ $k }}</button>
                    @endforeach
                </div>
                <button type="button" aria-label="Filter lainnya" class="hidden lg:grid shrink-0 w-9 h-9 mr-1 place-items-center rounded-lg text-slate-700 hover:bg-slate-100 transition-colors"><svg class="w-5 h-5" {!! $a !!}>{!! $ic['sliders'] !!}</svg></button>
            </div>

            <div id="df-grid" class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($daftarSkema as $s)
                    <div data-card data-kode="{{ $s->kode }}" data-nama="{{ $s->nama }}" data-tipe="{{ $s->tipe }}" data-kategori="{{ $s->kategori ?? '' }}"
                         data-selected="false" tabindex="0" role="radio" aria-checked="false"
                         class="group relative rounded-xl border border-slate-200 bg-white p-4 shadow-[0_1px_2px_rgba(15,23,42,.05)] transition-all duration-300 ease-out
                                cursor-pointer hover:-translate-y-1 hover:shadow-[0_10px_24px_rgba(11,90,73,.14)] hover:border-emerald-300 active:translate-y-0 active:scale-[.99] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300
                                data-[selected=true]:border-[#0b5a49] data-[selected=true]:ring-1 data-[selected=true]:ring-[#0b5a49] data-[selected=true]:bg-emerald-50 data-[selected=true]:shadow-[0_10px_24px_rgba(11,90,73,.16)]">
                        <span data-check aria-hidden="true" class="pointer-events-none absolute -top-2 -left-2 z-10 w-6 h-6 rounded-full bg-[#0b5a49] text-white grid place-items-center shadow opacity-0 scale-0 group-data-[selected=true]:opacity-100 group-data-[selected=true]:scale-100"><svg class="w-3.5 h-3.5" stroke-width="3" {!! $a !!}>{!! $ic['check'] !!}</svg></span>
                        <div class="flex items-start justify-between gap-3">
                            <span class="w-10 h-10 rounded-lg grid place-items-center {{ $tones[$s->tone ?? 'teal'] ?? $tones['teal'] }}"><svg class="w-5 h-5" {!! $a !!}>{!! $ic[$s->ikon ?? 'code'] ?? $ic['code'] !!}</svg></span>
                            @if ($s->tipe === 'Okupasi')
                                <span class="rounded-full bg-cyan-100 border border-cyan-200 px-2.5 py-0.5 text-[10px] font-semibold text-cyan-800">Okupasi</span>
                            @else
                                <span class="rounded-full bg-slate-200 px-2.5 py-0.5 text-[10px] font-semibold text-slate-600">Klaster</span>
                            @endif
                        </div>
                        <h3 class="mt-3 text-[15px] font-semibold leading-snug max-w-[220px] text-slate-900">{{ $s->nama }}</h3>
                        <div class="mt-3 flex flex-wrap gap-2 text-[11px] text-slate-600">
                            <span class="inline-flex items-center gap-1 rounded-md bg-slate-100 px-2 py-1"><svg class="w-3 h-3" {!! $a !!}>{!! $ic['clock'] !!}</svg>{{ $s->durasi }} Menit</span>
                            <span class="inline-flex items-center gap-1 rounded-md bg-slate-100 px-2 py-1"><svg class="w-3 h-3" {!! $a !!}>{!! $ic['doc'] !!}</svg>{{ $s->jumlah_unit }} Unit</span>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="font-mono text-[10px] font-bold text-[#0b5a49]">{{ $s->kode }}</span>
                            <a href="{{ $s->url_detail ?? route('asesi.skema.detail', ['skema' => $s->kode]) }}" data-detail class="group/d inline-flex items-center gap-1.5 rounded-md bg-indigo-100 hover:bg-[#0b5a49] hover:text-white px-3 py-1.5 text-[11px] font-semibold text-slate-800 transition-colors duration-200">Detail <svg class="w-3 h-3 transition-transform duration-200 group-hover/d:translate-x-0.5" {!! $a !!}>{!! $ic['right'] !!}</svg></a>
                        </div>
                    </div>
                @endforeach
            </div>
            <p id="df-kosong" class="hidden mt-5 rounded-xl border border-dashed border-slate-300 px-4 py-8 text-center text-xs text-slate-500">Skema tidak ditemukan.</p>
            <p id="df-err1" class="hidden mt-4 text-xs font-semibold text-red-600">Pilih satu skema terlebih dahulu untuk melanjutkan.</p>

            {{-- Pagination: pakai paginator Laravel bila ada, kalau tidak tampil dummy --}}
            <div class="mt-8 flex justify-center">
                @if (is_object($daftarSkema) && method_exists($daftarSkema, 'links'))
                    {{ $daftarSkema->links() }}
                @else
                    <nav class="flex items-center gap-2 text-xs" aria-label="Pagination">
                        <span class="w-8 h-8 grid place-items-center rounded-md border border-slate-200 text-slate-300"><svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['chevl'] !!}</svg></span>
                        <span class="w-8 h-8 grid place-items-center rounded-md bg-[#0b5a49] text-white font-semibold">1</span>
                        <a href="#" class="w-8 h-8 grid place-items-center rounded-md text-slate-700 hover:bg-slate-100">2</a>
                        <a href="#" class="w-8 h-8 grid place-items-center rounded-md text-slate-700 hover:bg-slate-100">3</a>
                        <span class="text-slate-400">...</span>
                        <a href="#" class="w-8 h-8 grid place-items-center rounded-md text-slate-700 hover:bg-slate-100">8</a>
                        <a href="#" class="w-8 h-8 grid place-items-center rounded-md border border-slate-200 text-slate-700 hover:bg-slate-50"><svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['chevr'] !!}</svg></a>
                    </nav>
                @endif
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- LANGKAH 2 : DATA DIRI                                         --}}
    {{-- ============================================================ --}}
    <section data-step="2" class="df-in mt-5 hidden">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,.04)] p-5 sm:p-7">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-5">
                <label class="block"><span class="block mb-2 text-sm font-semibold text-slate-900">Nama Lengkap</span>
                    <input name="nama_lengkap" data-f="nama" required placeholder="Masukkan nama lengkap sesuai KTP/KK" class="{{ $inp }}"></label>
                <label class="block"><span class="block mb-2 text-sm font-semibold text-slate-900">NIS/NISN</span>
                    <input name="nisn" data-f="nisn" required inputmode="numeric" pattern="[0-9]{8,12}" title="Isi 8-12 digit angka" placeholder="Nomor Induk Siswa Nasional" class="{{ $inp }}"></label>
                <label class="block"><span class="block mb-2 text-sm font-semibold text-slate-900">Kelas/Jurusan</span>
                    <span class="relative block">
                        <select name="kelas_jurusan" data-f="kelas" required class="{{ $inp }} appearance-none pr-10 text-slate-800 invalid:text-slate-400">
                            <option value="" disabled selected>Pilih Kelas &amp; Jurusan</option>
                            @foreach ($kelasList as $kl)<option value="{{ $kl }}" class="text-slate-800">{{ $kl }}</option>@endforeach
                        </select>
                        <svg class="w-4 h-4 text-slate-500 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none" {!! $a !!}>{!! $ic['chevron'] !!}</svg>
                    </span></label>
                <label class="block"><span class="block mb-2 text-sm font-semibold text-slate-900">Asal Sekolah</span>
                    <input name="asal_sekolah" data-f="sekolah" required placeholder="Nama sekolah asal" class="{{ $inp }}"></label>
                <label class="block"><span class="block mb-2 text-sm font-semibold text-slate-900">Email Aktif</span>
                    <input type="email" name="email" data-f="email" required placeholder="contoh@email.com" class="{{ $inp }}"></label>
                <label class="block"><span class="block mb-2 text-sm font-semibold text-slate-900">Nomor HP (WhatsApp)</span>
                    <input type="tel" name="no_hp" data-f="hp" required pattern="(\+62|62|0)8[0-9]{8,12}" title="Contoh: 08123456789" placeholder="08xxxxxxxxxx" class="{{ $inp }}"></label>
            </div>
            <label class="block mt-5"><span class="block mb-2 text-sm font-semibold text-slate-900">Alamat Lengkap</span>
                <textarea name="alamat" data-f="alamat" required rows="4" placeholder="Masukkan alamat lengkap" class="{{ $inp }} resize-none"></textarea></label>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- LANGKAH 3 : UPLOAD PERSYARATAN                                --}}
    {{-- ============================================================ --}}
    <section data-step="3" class="df-in mt-5 hidden">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,.04)] p-5 sm:p-8">
            <h2 class="text-xl font-bold text-[#0b6b5e]">Dokumen Wajib</h2>
            <p class="mt-2 text-xs text-slate-700">Silakan unggah dokumen persyaratan di bawah ini. Format yang didukung: PDF, JPG, PNG (Maks 5MB per file).</p>

            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($dokumen as [$key, $judul, $ket, $ikon, $hint, $maxMb, $accept, $label])
                    <div data-slot="{{ $key }}" data-max="{{ $maxMb }}" data-accept="{{ $accept }}" data-label="{{ $label }}" data-filled="false"
                         class="rounded-lg bg-slate-50 p-4 border border-dashed border-slate-300 data-[filled=true]:border-solid data-[filled=true]:border-slate-200 data-[filled=true]:border-l-4 data-[filled=true]:border-l-emerald-500">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">{{ $judul }}</h3>
                                <p class="mt-1 text-xs text-slate-600">{{ $ket }}</p>
                            </div>
                            <span class="w-8 h-8 shrink-0 rounded-full bg-indigo-100 text-slate-700 grid place-items-center"><svg class="w-4 h-4" {!! $a !!}>{!! $ic[$ikon] !!}</svg></span>
                        </div>

                        {{-- dropzone --}}
                        <label data-drop class="mt-3 flex flex-col items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white py-5 px-3 text-center cursor-pointer hover:border-[#0b5a49] transition-colors">
                            <svg class="w-7 h-7 text-slate-500" {!! $a !!}>{!! $ic['cloud'] !!}</svg>
                            <span class="text-[11px] font-bold text-[#0b5a49]">Klik atau seret file ke sini</span>
                            <span class="text-[11px] font-semibold text-slate-500">{{ $hint }}</span>
                            <input type="file" name="dokumen_{{ $key }}" accept="{{ $accept }}" class="sr-only">
                        </label>

                        {{-- file terpilih --}}
                        <div data-file class="mt-3 hidden items-center gap-3 rounded-lg bg-[#d4e1f9] px-3 py-3">
                            <span class="w-5 h-5 shrink-0 rounded-full bg-emerald-500 text-white grid place-items-center"><svg class="w-3 h-3" stroke-width="3" {!! $a !!}>{!! $ic['check'] !!}</svg></span>
                            <div class="min-w-0 flex-1"><p data-fname class="truncate text-[11px] font-bold text-slate-900"></p><p data-fsize class="text-[10px] font-semibold text-slate-600"></p></div>
                            <button type="button" data-remove class="shrink-0 text-slate-500 hover:text-red-600" aria-label="Hapus file"><svg class="w-4 h-4" {!! $a !!}>{!! $ic['x'] !!}</svg></button>
                        </div>
                        <p data-err class="hidden mt-2 text-[11px] font-semibold text-red-600"></p>
                    </div>
                @endforeach
            </div>
            <p id="df-err3" class="hidden mt-4 text-xs font-semibold text-red-600">Lengkapi semua dokumen wajib sebelum melanjutkan.</p>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- LANGKAH 4 : REVIEW & SUBMIT                                   --}}
    {{-- ============================================================ --}}
    <section data-step="4" class="df-in mt-5 hidden space-y-4">
        {{-- Skema --}}
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,.04)] overflow-hidden border-t-2 border-t-teal-400">
            <div class="p-5">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-lg bg-indigo-100 text-slate-700 grid place-items-center"><svg class="w-4 h-4" {!! $a !!}>{!! $ic['code'] !!}</svg></span>
                        <div><h3 class="text-base font-bold text-slate-900">Skema Sertifikasi</h3><p class="text-[11px] text-slate-500">Pilihan kompetensi yang akan diujikan</p></div>
                    </div>
                    <button type="button" data-goto="1" class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#0b5a49] hover:underline"><svg class="w-3 h-3" {!! $a !!}>{!! $ic['edit'] !!}</svg>Ubah</button>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <div class="rounded-lg bg-slate-50 p-4 flex flex-wrap items-center justify-between gap-3">
                        <div><p class="text-[9px] font-bold uppercase tracking-wide text-slate-500">Nama Skema</p><p data-r="skema_nama" class="mt-1 text-base font-bold text-[#0b5a49]">-</p></div>
                        <div class="flex gap-2">
                            <div class="rounded-md border border-slate-200 bg-white px-3 py-1.5"><p class="text-[9px] text-slate-500">Kode Skema</p><p data-r="skema_kode" class="text-xs font-bold text-slate-900">-</p></div>
                            <div class="rounded-md border border-slate-200 bg-white px-3 py-1.5"><p class="text-[9px] text-slate-500">Tipe</p><p data-r="skema_tipe" class="text-xs font-bold text-slate-900">-</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data diri --}}
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,.04)] p-5">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-lg bg-indigo-100 text-slate-700 grid place-items-center"><svg class="w-4 h-4" {!! $a !!}>{!! $ic['user'] !!}</svg></span>
                    <div><h3 class="text-base font-bold text-slate-900">Data Diri (Asesi)</h3><p class="text-[11px] text-slate-500">Informasi profil peserta ujian</p></div>
                </div>
                <button type="button" data-goto="2" class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#0b5a49] hover:underline"><svg class="w-3 h-3" {!! $a !!}>{!! $ic['edit'] !!}</svg>Ubah</button>
            </div>
            <dl class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 text-xs">
                <div><dt class="text-[10px] text-slate-500">Nama Lengkap</dt><dd data-r="nama" class="mt-1 text-sm font-semibold text-slate-900">-</dd></div>
                <div><dt class="text-[10px] text-slate-500">NIS / NISN</dt><dd data-r="nisn" class="mt-1 text-sm font-semibold text-slate-900">-</dd></div>
                <div><dt class="text-[10px] text-slate-500">Email</dt><dd data-r="email" class="mt-1 text-sm font-semibold text-slate-900 break-all">-</dd></div>
                <div><dt class="text-[10px] text-slate-500">Nomor Telepon / WhatsApp</dt><dd data-r="hp" class="mt-1 text-sm font-semibold text-slate-900">-</dd></div>
                <div><dt class="text-[10px] text-slate-500">Jurusan / Kelas</dt><dd data-r="kelas" class="mt-1 text-sm font-semibold text-slate-900">-</dd></div>
                <div><dt class="text-[10px] text-slate-500">Asal Sekolah</dt><dd data-r="sekolah" class="mt-1 text-sm font-semibold text-slate-900">-</dd></div>
                <div class="sm:col-span-2"><dt class="text-[10px] text-slate-500">Alamat Lengkap</dt><dd data-r="alamat" class="mt-1 text-sm font-semibold text-slate-900">-</dd></div>
            </dl>
        </div>

        {{-- Dokumen --}}
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,.04)] p-5">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-lg bg-indigo-100 text-slate-700 grid place-items-center"><svg class="w-4 h-4" {!! $a !!}>{!! $ic['folder'] !!}</svg></span>
                    <div><h3 class="text-base font-bold text-slate-900">Dokumen Persyaratan</h3><p class="text-[11px] text-slate-500">Berkas yang telah diunggah untuk verifikasi</p></div>
                </div>
                <button type="button" data-goto="3" class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#0b5a49] hover:underline"><svg class="w-3 h-3" {!! $a !!}>{!! $ic['edit'] !!}</svg>Ubah</button>
            </div>
            <div id="df-rdok" class="mt-4 pt-4 border-t border-slate-100 space-y-2.5"></div>
        </div>

        {{-- Pernyataan + aksi --}}
        <div class="rounded-xl border border-slate-200 bg-indigo-50/60 p-5">
            <label class="flex items-start gap-3 cursor-pointer">
                <input id="df-setuju" type="checkbox" class="mt-0.5 w-4 h-4 rounded border-slate-300 text-[#0b5a49] focus:ring-emerald-200">
                <span>
                    <span class="block text-xs font-bold text-slate-900">Saya menyatakan data yang diisi benar</span>
                    <span class="block mt-1 text-[11px] leading-relaxed text-slate-600 max-w-md">Dengan mencentang kotak ini, saya bertanggung jawab penuh atas kebenaran dan keabsahan dokumen serta data diri yang telah saya lampirkan untuk keperluan sertifikasi kompetensi.</span>
                </span>
            </label>
            <div class="mt-4 pt-4 border-t border-slate-200 flex justify-end gap-2.5">
                <button type="button" data-prev class="{{ $btnO }} !py-2 !text-xs">Kembali</button>
                <button type="submit" id="df-submit" disabled class="{{ $btnP }} !py-2 !text-xs">Submit Pendaftaran <svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['send'] !!}</svg></button>
            </div>
        </div>
    </section>

    {{-- ============ Navigasi bawah (langkah 1-3) ============ --}}
    <div id="df-nav" class="mt-5 flex items-center justify-between">
        <button type="button" id="df-batal" class="{{ $btnO }} !py-2 !text-xs">Batal</button>
        <button type="button" id="df-next" class="{{ $btnP }}">Selanjutnya <svg class="w-4 h-4" {!! $a !!}>{!! $ic['right'] !!}</svg></button>
    </div>

    <div id="df-toast" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-50 rounded-lg bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white shadow-lg"></div>
</form>

@verbatim
<script>
(() => {
    const root = document.getElementById('daftar-page');
    if (!root) return;
    const $  = (s, c = root) => c.querySelector(s);
    const $$ = (s, c = root) => [...c.querySelectorAll(s)];

    const heads = {
        1: ['Pendaftaran Sertifikasi', 'Lengkapi data untuk mendaftar ujian kompetensi'],
        2: ['Data Diri', 'Lengkapi informasi pribadi Anda untuk melanjutkan pendaftaran.'],
        3: ['Upload Persyaratan', 'Langkah 3 dari 4 - Pendaftaran Sertifikasi Kompetensi'],
        4: ['Review & Submit', 'Pastikan semua data Anda sudah benar sebelum mengirimkan permohonan sertifikasi.'],
    };
    let cur = 1;
    let skema = null;
    const files = {};

    const toast = (t) => {
        const el = $('#df-toast'); el.textContent = t; el.classList.remove('hidden');
        clearTimeout(toast.t); toast.t = setTimeout(() => el.classList.add('hidden'), 2800);
    };
    const fmtSize = (b) => b >= 1048576 ? (b / 1048576).toFixed(1) + ' MB' : Math.max(1, Math.round(b / 1024)) + ' KB';

    /* ---------- Navigasi langkah ---------- */
    const go = (n) => {
        cur = n;
        $$('[data-step]').forEach((s) => {
            const on = +s.dataset.step === n;
            s.classList.toggle('hidden', !on);
            if (on) { s.classList.remove('df-in'); void s.offsetWidth; s.classList.add('df-in'); }
        });
        $$('[data-stp]').forEach((li) => {
            const i = +li.dataset.stp;
            li.dataset.state = i < n ? 'done' : (i === n ? 'current' : 'pending');
        });
        $('#df-title').textContent = heads[n][0];
        $('#df-sub').textContent = heads[n][1];
        $('#df-head').classList.toggle('text-center', n !== 4);
        $('#df-head').classList.toggle('text-left', n === 4);
        $('#df-back').classList.toggle('hidden', n !== 4);
        $('#df-back').classList.toggle('inline-flex', n === 4);
        $('#df-title').classList.toggle('text-slate-900', n === 4);
        $('#df-title').classList.toggle('text-[#0b6b5e]', n !== 4);
        $('#df-nav').classList.toggle('hidden', n === 4);
        $('#df-nav').classList.toggle('flex', n !== 4);
        $('#df-batal').textContent = n === 1 ? 'Batal' : 'Kembali';
        if (n === 4) renderReview();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    /* ---------- Langkah 1 : pilih skema, cari, filter ---------- */
    const cards = $$('[data-card]');
    const select = (c) => {
        cards.forEach((x) => { x.dataset.selected = 'false'; x.setAttribute('aria-checked', 'false'); });
        c.dataset.selected = 'true'; c.setAttribute('aria-checked', 'true');
        skema = { kode: c.dataset.kode, nama: c.dataset.nama, tipe: c.dataset.tipe };
        $('#skema_kode').value = skema.kode;
        $('#df-err1').classList.add('hidden');
    };
    cards.forEach((c) => {
        c.addEventListener('click', () => select(c));
        c.addEventListener('keydown', (e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); select(c); } });
        const d = $('[data-detail]', c);
        if (d) d.addEventListener('click', (e) => e.stopPropagation());
    });
    let kat = '';
    const applyFilter = () => {
        const q = $('#df-cari').value.trim().toLowerCase();
        let n = 0;
        cards.forEach((c) => {
            const ok = (!kat || c.dataset.kategori === kat) && (!q || (c.dataset.nama + ' ' + c.dataset.kode).toLowerCase().includes(q));
            c.classList.toggle('hidden', !ok); if (ok) n++;
        });
        $('#df-kosong').classList.toggle('hidden', n > 0);
    };
    $('#df-cari').addEventListener('input', applyFilter);
    $$('[data-filter]').forEach((b) => b.addEventListener('click', () => {
        kat = b.dataset.filter;
        $$('[data-filter]').forEach((x) => x.setAttribute('aria-pressed', x === b ? 'true' : 'false'));
        applyFilter();
    }));

    /* ---------- Langkah 3 : upload ---------- */
    $$('[data-slot]').forEach((slot) => {
        const key = slot.dataset.slot, max = +slot.dataset.max * 1048576;
        const input = $('input[type=file]', slot), drop = $('[data-drop]', slot), box = $('[data-file]', slot), err = $('[data-err]', slot);
        const exts = slot.dataset.accept.split(',');
        const setErr = (t) => { err.textContent = t; err.classList.toggle('hidden', !t); };
        const clear = () => {
            delete files[key]; input.value = ''; slot.dataset.filled = 'false';
            box.classList.add('hidden'); box.classList.remove('flex'); drop.classList.remove('hidden');
        };
        const accept = (f) => {
            if (!f) return;
            const ext = '.' + f.name.split('.').pop().toLowerCase();
            if (!exts.includes(ext)) { clear(); return setErr('Format tidak didukung. Gunakan ' + exts.join(', ').toUpperCase().replaceAll('.', '')); }
            if (f.size > max) { clear(); return setErr('Ukuran file melebihi ' + slot.dataset.max + ' MB.'); }
            setErr('');
            files[key] = { name: f.name, size: f.size, label: slot.dataset.label };
            $('[data-fname]', slot).textContent = f.name;
            $('[data-fsize]', slot).textContent = fmtSize(f.size);
            slot.dataset.filled = 'true';
            drop.classList.add('hidden'); box.classList.remove('hidden'); box.classList.add('flex');
            $('#df-err3').classList.add('hidden');
        };
        input.addEventListener('change', () => accept(input.files[0]));
        $('[data-remove]', slot).addEventListener('click', () => { clear(); setErr(''); });
        ['dragenter', 'dragover'].forEach((ev) => drop.addEventListener(ev, (e) => { e.preventDefault(); drop.classList.add('border-[#0b5a49]'); }));
        ['dragleave', 'drop'].forEach((ev) => drop.addEventListener(ev, (e) => { e.preventDefault(); drop.classList.remove('border-[#0b5a49]'); }));
        drop.addEventListener('drop', (e) => {
            const f = e.dataTransfer.files[0]; if (!f) return;
            const dt = new DataTransfer(); dt.items.add(f); input.files = dt.files; accept(f);
        });
    });

    /* ---------- Langkah 4 : review ---------- */
    const esc = (s) => s.replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));
    const renderReview = () => {
        const set = (k, v) => { const el = $('[data-r="' + k + '"]'); if (el) el.textContent = v || '-'; };
        set('skema_nama', skema && skema.nama); set('skema_kode', skema && skema.kode); set('skema_tipe', skema && skema.tipe);
        $$('[data-f]').forEach((f) => set(f.dataset.f, f.tagName === 'SELECT' ? f.value : f.value.trim()));
        const wrap = $('#df-rdok'); wrap.innerHTML = '';
        Object.values(files).forEach((f) => {
            const pdf = f.name.toLowerCase().endsWith('.pdf');
            wrap.insertAdjacentHTML('beforeend',
                '<div class="flex items-center justify-between gap-3 rounded-lg border border-slate-100 bg-slate-50 px-3 py-2.5">' +
                '<div class="flex items-center gap-3 min-w-0"><span class="shrink-0 text-[8px] font-bold rounded px-1 py-0.5 ' + (pdf ? 'bg-red-100 text-red-600' : 'bg-slate-200 text-slate-600') + '">' + (pdf ? 'PDF' : 'IMG') + '</span>' +
                '<div class="min-w-0"><p class="truncate text-xs font-semibold text-slate-900">' + esc(f.name) + '</p><p class="text-[10px] text-slate-500">' + esc(f.label) + '</p></div></div>' +
                '<span class="shrink-0 inline-flex items-center gap-1 rounded-md bg-teal-100 px-2 py-1 text-[10px] font-semibold text-teal-800">&#10003; Terunggah</span></div>');
        });
    };
    $$('[data-goto]').forEach((b) => b.addEventListener('click', () => go(+b.dataset.goto)));
    $('[data-prev]').addEventListener('click', () => go(3));
    $('#df-back').addEventListener('click', (e) => { e.preventDefault(); go(1); });
    $('#df-setuju').addEventListener('change', (e) => { $('#df-submit').disabled = !e.target.checked; });

    /* ---------- Tombol bawah ---------- */
    $('#df-batal').addEventListener('click', () => { cur === 1 ? (location.href = root.dataset.batal) : go(cur - 1); });
    $('#df-next').addEventListener('click', () => {
        if (cur === 1) {
            if (!skema) return $('#df-err1').classList.remove('hidden');
        } else if (cur === 2) {
            const bad = $$('[data-step="2"] input, [data-step="2"] select, [data-step="2"] textarea').find((f) => !f.checkValidity());
            if (bad) return bad.reportValidity();
        } else if (cur === 3) {
            if (Object.keys(files).length < 4) return $('#df-err3').classList.remove('hidden');
        }
        go(cur + 1);
    });

    /* ---------- Submit ---------- */
    root.addEventListener('submit', (e) => {
        if (cur !== 4 || !$('#df-setuju').checked) return e.preventDefault();
        if (root.getAttribute('action') === '#') {   // belum ada backend
            e.preventDefault();
            toast('Mode demo: pendaftaran belum dikirim (backend belum terhubung).');
        }
    });

    go(1);

    /* ---------- Pra-pilih skema dari ?skema=KODE (mis. dari tombol "Daftar Sekarang") ---------- */
    const pre = new URLSearchParams(location.search).get('skema');
    if (pre) {
        const c = cards.find((x) => x.dataset.kode === pre);
        if (c) {
            select(c);
            requestAnimationFrame(() => setTimeout(() => c.scrollIntoView({ behavior: 'smooth', block: 'center' }), 150));
        }
    }
})();
</script>
@endverbatim
</x-layouts.app>