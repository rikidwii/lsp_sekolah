{{-- resources/views/asesi/skema/index.blade.php  (ganti seluruh isi file skema/index.blade.php yang lama) --}}
{{--
  KONTRAK DATA (yang perlu dikirim controller saat backend jadi). Selama variabel ini belum dikirim,
  halaman memakai DATA DUMMY di bawah. Setelah controller mengirimnya, dummy otomatis tidak dipakai.

  $skemaAktif : 1 objek/model atau null
      kode, jenis (Klaster|Okupasi|KKNI, sesuai pilihan admin), nama, deskripsi, status_proses, no_registrasi,
      tanggal_daftar, waktu_mulai (mis. "08:00 WIB"), tanggal_pelaksanaan, asesor, tahap (1-4),
      pesan, url_silabus (file opsional dari admin; null = tombol nonaktif), nama_asesi (opsional; default nama user login)
  $riwayat    : koleksi (skema lulus)
      kode, nama, skor, diterbitkan, no_sertifikat, berlaku_sampai, sertifikat_terbit (bool; true bila admin sudah meluncurkan),
      url_transkrip, url_sertifikat (keduanya opsional; default memakai route)
  $tersedia   : koleksi (skema yang bisa didaftar)
      kode, nama, jenis (Klaster|Okupasi|KKNI), warna (amber|blue|emerald), ikon (image|server|phone|award), jumlah_unit,
      deskripsi, url_detail, url_daftar
  Tanggal boleh string ATAU Carbon (otomatis diformat "20 Okt 2024").
--}}
@php
    // ===================== DATA DUMMY (hapus blok ini bila backend sudah mengirim data) =====================
    $skemaAktif ??= (object) [
        'kode' => 'JWD-001', 'jenis' => 'Klaster',
        'nama' => 'Junior Web Developer',
        'deskripsi' => 'Skema uji sertifikasi kompetensi pengembangan front-end & back-end dasar sesuai Standar Kompetensi Kerja Nasional Indonesia (SKKNI Kemnaker).',
        'status_proses' => 'Asesmen Berlangsung', 'no_registrasi' => 'REG-BNSP-2024-8831',
        'tanggal_daftar' => '20 Okt 2024', 'waktu_mulai' => '08:00 WIB', 'tanggal_pelaksanaan' => '25 Okt 2024',
        'asesor' => 'Dr. Ir. Budi Santoso', 'tahap' => 2, 'url_silabus' => '#',
        'pesan' => 'Jadwal asesmen sedang berlangsung, silakan menuju ke ruangan yang dijadwalkan.',
    ];
    $riwayat ??= collect([(object) [
        'kode' => 'RPL-002', 'nama' => 'Pemrograman Berorientasi Objek (OOP Dasar)', 'skor' => '92.5',
        'diterbitkan' => '15 Mei 2024', 'no_sertifikat' => 'BNSP-LSP-2024-09812', 'berlaku_sampai' => 'Mei 2027', 'sertifikat_terbit' => true,
    ]]);
    $tersedia ??= collect([
        (object) ['kode' => 'DGM-002', 'nama' => 'Desain Grafis Muda', 'jenis' => 'Klaster', 'warna' => 'amber', 'ikon' => 'image', 'jumlah_unit' => 5,
                  'deskripsi' => 'Pembuatan aset visual, layout digital raster & vector berbasis Adobe Illustrator / CorelDraw.'],
        (object) ['kode' => 'NET-003', 'nama' => 'Network Administrator', 'jenis' => 'Okupasi', 'warna' => 'blue', 'ikon' => 'server', 'jumlah_unit' => 7,
                  'deskripsi' => 'Konfigurasi routing MikroTik, subnetting IPv4/IPv6, dan keamanan jaringan dasar.'],
        (object) ['kode' => 'MOB-004', 'nama' => 'Junior Mobile Programmer', 'jenis' => 'KKNI', 'warna' => 'emerald', 'ikon' => 'phone', 'jumlah_unit' => 6,
                  'deskripsi' => 'Pengembangan aplikasi mobile multiplatform Android & Flutter dengan integrasi RESTful API.'],
    ]);
    // =========================================================================================================

    $a = 'fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"';
    $ic = [
        'code'   => '<path d="m16 18 6-6-6-6M8 6l-6 6 6 6"/>',
        'award'  => '<circle cx="12" cy="8" r="6"/><path d="M15.5 12.9 17 22l-5-3-5 3 1.5-9.1"/>',
        'check'  => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
        'plus'   => '<path d="M12 5v14M5 12h14"/>',
        'doc'    => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z"/><path d="M14 2v4a2 2 0 0 0 2 2h4M8 13h8M8 17h5"/>',
        'down'   => '<path d="M12 3v12m0 0-4-4m4 4 4-4M4 21h16"/>',
        'edit'   => '<path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>',
        'list'   => '<path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>',
        'book'   => '<path d="M2 4h6a4 4 0 0 1 4 4v13a3 3 0 0 0-3-3H2zM22 4h-6a4 4 0 0 0-4 4v13a3 3 0 0 1 3-3h7z"/>',
        'image'  => '<rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.1-3.1a2 2 0 0 0-2.8 0L6 21"/>',
        'server' => '<rect width="20" height="8" x="2" y="2" rx="2"/><rect width="20" height="8" x="2" y="14" rx="2"/><path d="M6 6h.01M6 18h.01"/>',
        'phone'  => '<rect width="12" height="20" x="6" y="2" rx="2"/><path d="M12 18h.01"/>',
    ];
    $fmt  = fn ($d) => $d instanceof \Carbon\CarbonInterface ? $d->locale('id')->translatedFormat('d M Y') : $d;
    $card = 'bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,.04)]';
    $btnP = 'cursor-pointer inline-flex items-center justify-center gap-2 rounded-lg bg-[#0b5a49] hover:bg-[#074237] px-4 py-2 text-xs font-semibold text-white transition-colors';
    $btnO = 'cursor-pointer inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-800 transition-colors';
    $btnS = 'cursor-pointer inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-100 hover:bg-slate-200 px-5 py-2.5 text-[13px] font-semibold text-slate-800 transition-colors';
    $warna = [
        'amber'   => ['bg-amber-50 border-amber-200 text-amber-600',     'bg-amber-50 text-amber-700 border-amber-200'],
        'blue'    => ['bg-blue-50 border-blue-200 text-blue-600',         'bg-blue-50 text-blue-700 border-blue-200'],
        'emerald' => ['bg-emerald-50 border-emerald-200 text-emerald-600', 'bg-emerald-50 text-emerald-700 border-emerald-200'],
    ];
    $jenisWarna = [
        'Klaster' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        'Okupasi' => 'border-cyan-200 bg-cyan-50 text-cyan-700',
        'KKNI'    => 'border-indigo-200 bg-indigo-50 text-indigo-700',
    ];
    $labels = ['Pendaftaran', 'Jadwal Asesmen', 'Uji Penilaian', 'Sertifikat'];
    $stText = ['done' => 'text-emerald-600', 'current' => 'text-amber-600', 'pending' => 'text-slate-400'];
    $jumlahAktif = $skemaAktif ? 1 : 0;
    $total = $jumlahAktif + $riwayat->count() + $tersedia->count();
    $ringkasan = [
        ['Skema Aktif', $jumlahAktif . ' Skema'], ['Jumlah Skema', ($jumlahAktif + $riwayat->count()) . ' Skema'],
        ['Skema Tersedia', $tersedia->count() . ' Skema'], ['Sertifikat Terbit', $riwayat->count() . ' Lisensi'],
    ];
    $panduan = [
        ['Pilih Skema Sesuai Jurusan', 'Pilih klaster kompetensi sesuai program keahlian SMK.'],
        ['Lengkapi APL-01 & APL-02', 'Upload rapor, surat rekomendasi, dan bukti portofolio.'],
        ['Verifikasi Oleh Tim Asesor', 'Pemeriksaan kelayakan sebelum penetapan jadwal uji.'],
        ['Uji Asesmen Praktik di TUK', 'Pelaksanaan tes teori, demonstrasi lab, dan wawancara.'],
        ['Rapat Pleno & Cetak Sertifikat', 'Penetapan asesi kompeten dan penerbitan sertifikat BNSP.'],
    ];
@endphp

<x-layouts.app :title="$title ?? 'Daftar Skema'">
<style>
    @keyframes skIn { from { opacity: 0; transform: translateY(18px); } }
    .sk-in { animation: skIn .7s cubic-bezier(.22, 1, .36, 1) backwards; }
    @keyframes skFill { from { width: 0 !important; } }
    @keyframes skPing { 0% { transform: scale(1); opacity: .7; } 80%, 100% { transform: scale(1.9); opacity: 0; } }
    @keyframes skDot { 0%, 100% { transform: scale(1); } 50% { transform: scale(.7); } }
    @keyframes skShine { from { transform: translateX(-100%); } to { transform: translateX(250%); } }
    .sk-fill { animation: skFill 1.1s cubic-bezier(.22, 1, .36, 1) .3s backwards; }
    .sk-fill::after { content: ''; position: absolute; inset: 0; width: 40%; background: linear-gradient(90deg, transparent, rgba(255,255,255,.6), transparent); animation: skShine 2.2s ease-in-out 1.4s infinite; }
    .sk-ping { animation: skPing 1.8s cubic-bezier(0, 0, .2, 1) infinite; }
    .sk-dot { animation: skDot 1.8s ease-in-out infinite; }
    @media (prefers-reduced-motion: reduce) { .sk-in, .sk-fill, .sk-fill::after, .sk-ping, .sk-dot { animation: none; } }
</style>

<div id="skema-page" class="max-w-[1100px]">

    {{-- Header --}}
    <nav class="text-[11px] text-slate-400 flex items-center gap-2" aria-label="Breadcrumb">
        <span>Portal Asesi</span><span>›</span><span class="font-semibold text-[#0b5a49]">Daftar Skema Sertifikasi</span>
    </nav>
    <div class="mt-4 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <h1 class="text-2xl font-bold text-slate-900">Daftar Skema Sertifikasi</h1>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[11px] font-semibold">{{ $total }} Skema</span>
            </div>
            <p class="mt-1.5 text-sm text-slate-500 max-w-xl leading-relaxed">Kelola pendaftaran skema aktif, pantau progres tahapan sertifikasi, dan jelajahi skema kompetensi BNSP yang tersedia.</p>
        </div>
        <a href="{{ route('asesi.skema.daftar') }}" class="{{ $btnP }} !px-5 !py-2.5 shrink-0 self-start shadow-sm">
            <svg class="w-4 h-4" stroke-width="2.2" {!! $a !!}>{!! $ic['plus'] !!}</svg> Daftar Skema Baru
        </a>
    </div>

    {{-- Tab (memenuhi lebar hingga di sebelah "Status Sinkronisasi BNSP") --}}
    <div class="mt-6 flex items-center gap-4 border-b border-slate-200">
        <div class="flex flex-1 min-w-0 overflow-x-auto overflow-y-hidden [scrollbar-width:none] [&::-webkit-scrollbar]:hidden" role="tablist">
            @foreach ([['semua', 'Semua Skema', $total], ['aktif', 'Skema Berjalan', $jumlahAktif . ' Aktif'], ['riwayat', 'Riwayat Selesai', $riwayat->count() . ' Lulus'], ['tersedia', 'Skema Tersedia', $tersedia->count()]] as [$key, $lbl, $cnt])
                <button type="button" role="tab" data-tab="{{ $key }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                        class="cursor-pointer flex-1 shrink-0 whitespace-nowrap px-3 text-center -mb-px pb-3 border-b-2 border-transparent text-[13px] font-medium text-slate-500 hover:text-slate-800 transition-colors aria-selected:border-[#0b5a49] aria-selected:text-[#0b5a49] aria-selected:font-semibold">
                    {{ $lbl }} <span class="ml-1 px-1.5 py-0.5 rounded-md bg-slate-100 text-[10px] text-slate-500">{{ $cnt }}</span>
                </button>
            @endforeach
        </div>
        <span class="hidden md:block text-[11px] text-slate-400 whitespace-nowrap pb-3">Status Sinkronisasi BNSP: Real-Time</span>
    </div>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_260px] gap-6 items-start">

        {{-- ============ Kolom utama ============ --}}
        <div class="space-y-8 min-w-0">

            {{-- Skema aktif --}}
            @if ($skemaAktif)
                @php
                    $tahap = (int) $skemaAktif->tahap;
                    $steps = collect($labels)->map(function ($l, $i) use ($tahap) {
                        $n = $i + 1;
                        $st = $n < $tahap ? 'done' : ($n === $tahap ? 'current' : 'pending');
                        return ['label' => $l, 'st' => $st, 'text' => ['done' => 'Selesai', 'current' => 'Sedang Berlangsung', 'pending' => $n === 4 ? 'Final' : 'Menunggu'][$st]];
                    });
                    $jenisAktif = $skemaAktif->jenis ?? 'Klaster';
                    $tglPelaksanaan = $fmt($skemaAktif->tanggal_pelaksanaan ?? null) ?: 'Belum dijadwalkan';
                    $waktuMulai = $skemaAktif->waktu_mulai ?? null ?: '-';
                @endphp
                <section data-sec="aktif" class="sk-in">
                    <h2 class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-800"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Skema Sedang Berjalan (Aktif)</h2>
                    <div class="mt-3 {{ $card }} overflow-hidden">
                        <div class="flex flex-wrap items-center justify-between gap-2 px-5 py-3 border-b border-emerald-100" style="background:linear-gradient(90deg,#ecfdf5,#fff)">
                            <span class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wide text-[#0b5a49]"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>Proses Berjalan: {{ $skemaAktif->status_proses }}</span>
                            <span class="px-2 py-0.5 rounded border border-slate-200 bg-white font-mono text-[10px] font-semibold text-slate-700">{{ $skemaAktif->no_registrasi }}</span>
                        </div>
                        <div class="p-5">
                            <div class="flex gap-4">
                                <span class="shrink-0 w-12 h-12 rounded-xl border border-emerald-200 bg-emerald-50 text-[#0b5a49] grid place-items-center"><svg class="w-5 h-5" {!! $a !!}>{!! $ic['code'] !!}</svg></span>
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-1.5 text-[10px] font-semibold">
                                        <span class="px-2 py-0.5 rounded border border-slate-200 bg-slate-50 text-slate-600">KODE: {{ $skemaAktif->kode }}</span>
                                        <span class="px-2 py-0.5 rounded border {{ $jenisWarna[$jenisAktif] ?? $jenisWarna['Klaster'] }}">{{ $jenisAktif }}</span>
                                    </div>
                                    <h3 class="mt-2 text-lg font-bold text-slate-900">{{ $skemaAktif->nama }}</h3>
                                    <p class="mt-1 text-xs text-slate-500 leading-relaxed">{{ $skemaAktif->deskripsi }}</p>
                                </div>
                            </div>

                            <dl class="mt-5 grid grid-cols-2 sm:grid-cols-4 gap-4 rounded-xl border border-slate-100 bg-slate-50 p-4 text-xs">
                                @foreach ([['Tanggal Daftar', $fmt($skemaAktif->tanggal_daftar)], ['Waktu Mulai', $waktuMulai], ['Tanggal Pelaksanaan', $tglPelaksanaan], ['Asesor Verifikator', $skemaAktif->asesor]] as [$k, $v])
                                    <div><dt class="text-[10px] text-slate-400">{{ $k }}</dt><dd class="mt-0.5 font-semibold {{ $k === 'Waktu Mulai' ? 'text-emerald-700' : 'text-slate-800' }}">{{ $v }}</dd></div>
                                @endforeach
                            </dl>

                            <p class="mt-5 text-[10px] font-bold uppercase tracking-wider text-slate-500">Alur 4 Tahapan Sertifikasi BNSP</p>
                            <div class="mt-3 rounded-2xl border border-slate-200 p-4 sm:p-5">
                                <h4 class="text-sm font-bold text-slate-900">Progres Sertifikasi: {{ $skemaAktif->nama }}</h4>
                                <p class="text-[11px] text-slate-500 mt-0.5 pb-4 border-b border-slate-100">Alur tahapan resmi sertifikasi BNSP dari pendaftaran hingga lisensi kompetensi</p>
                                @php
                                    $jml   = max($steps->count(), 2);
                                    $sisi  = round(100 / ($jml * 2), 4);                      // jarak garis dari tepi = setengah kolom
                                    $isi   = min(100, max(0, (min($tahap, $jml) - 1) / ($jml - 1) * 100)); // garis terisi sampai step berjalan
                                @endphp
                                <div class="relative pt-6">
                                    {{-- Garis penghubung: hanya antar titik (tidak keluar dari kartu) --}}
                                    <div class="absolute top-[calc(1.5rem+1.125rem)] h-1 -translate-y-1/2 rounded-full bg-slate-200 overflow-hidden" style="left: {{ $sisi }}%; right: {{ $sisi }}%">
                                        <div class="sk-fill relative h-full rounded-full bg-gradient-to-r from-[#0b5a49] to-emerald-500" style="width: {{ $isi }}%"></div>
                                    </div>
                                    <ol class="relative grid" style="grid-template-columns: repeat({{ $jml }}, minmax(0, 1fr))">
                                        @foreach ($steps as $i => $s)
                                            <li class="flex flex-col items-center text-center px-1" @if ($s['st'] === 'current') aria-current="step" @endif>
                                                <div class="w-9 h-9 grid place-items-center">
                                                    @if ($s['st'] === 'done')
                                                        <span class="w-9 h-9 rounded-full bg-[#0b5a49] text-white grid place-items-center shadow-sm"><svg class="w-4 h-4" stroke-width="2.4" {!! $a !!}>{!! $ic['check'] !!}</svg></span>
                                                    @elseif ($s['st'] === 'current')
                                                        <span class="relative w-9 h-9 rounded-full bg-white border-2 border-[#0b5a49] grid place-items-center">
                                                            <span class="sk-ping absolute inset-0 rounded-full bg-emerald-400/50"></span>
                                                            <span class="sk-dot relative w-3 h-3 rounded-full bg-[#0b5a49]"></span>
                                                        </span>
                                                    @else
                                                        <span class="w-8 h-8 rounded-full bg-white border border-slate-200 text-slate-400 text-xs grid place-items-center">@if ($loop->last)<svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['award'] !!}</svg>@else{{ $i + 1 }}@endif</span>
                                                    @endif
                                                </div>
                                                <p class="mt-2.5 text-[11px] font-semibold leading-tight {{ $s['st'] === 'pending' ? 'text-slate-500' : 'text-slate-900' }}">{{ $s['label'] }}</p>
                                                <p class="mt-0.5 text-[10px] {{ $stText[$s['st']] }}">{{ $s['text'] }}</p>
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                            </div>

                            @if ($skemaAktif->pesan)
                                <div class="mt-4 flex items-center gap-2.5 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs font-semibold text-amber-800">
                                    <svg class="w-4 h-4 shrink-0 text-amber-600" {!! $a !!}>{!! $ic['edit'] !!}</svg>{{ $skemaAktif->pesan }}
                                </div>
                            @endif

                            <div class="mt-4 pt-4 border-t border-slate-100 flex flex-wrap gap-3">
                                <button type="button" data-unduh-kartu data-filename="Kartu-Registrasi-{{ $skemaAktif->no_registrasi }}" class="{{ $btnP }} !rounded-xl !px-5 !py-2.5 !text-[13px]"><svg class="w-4 h-4" {!! $a !!}>{!! $ic['down'] !!}</svg>Unduh Kartu Registrasi</button>
                                @if (!empty($skemaAktif->url_silabus))
                                    <a href="{{ $skemaAktif->url_silabus }}" target="_blank" rel="noopener" class="{{ $btnS }}"><svg class="w-4 h-4 text-slate-500" {!! $a !!}>{!! $ic['doc'] !!}</svg>Detail Silabus</a>
                                @else
                                    <span aria-disabled="true" title="Admin belum melampirkan silabus untuk skema ini" class="{{ $btnS }} opacity-50 !cursor-not-allowed hover:!bg-slate-100"><svg class="w-4 h-4 text-slate-500" {!! $a !!}>{!! $ic['doc'] !!}</svg>Detail Silabus</span>
                                @endif
                                <div id="kartu-registrasi-src" class="hidden" aria-hidden="true">
                                    @include('asesi.skema._kartu-registrasi', [
                                        'nama'  => $skemaAktif->nama_asesi ?? auth()->user()?->name ?? 'Nama Asesi',
                                        'skema' => $skemaAktif->nama,
                                        'noReg' => $skemaAktif->no_registrasi,
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif

            {{-- Riwayat --}}
            @if ($riwayat->isNotEmpty())
                <section data-sec="riwayat" class="sk-in" style="animation-delay:.1s">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <h2 class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-800"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Riwayat Skema Tersertifikasi (Lulus)</h2>
                        <span class="px-2.5 py-0.5 rounded-full border border-emerald-200 bg-emerald-50 text-[11px] font-semibold text-emerald-700">{{ $riwayat->count() }} Sertifikat Aktif BNSP</span>
                    </div>
                    <div class="mt-3 space-y-3">
                        @foreach ($riwayat as $r)
                            <div class="{{ $card }} p-5 flex flex-col sm:flex-row sm:items-center gap-4">
                                <span class="shrink-0 w-11 h-11 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-600 grid place-items-center"><svg class="w-5 h-5" {!! $a !!}>{!! $ic['award'] !!}</svg></span>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-1.5 text-[10px] font-semibold">
                                        <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 uppercase">Kompeten (Lulus) • Skor {{ $r->skor }}</span>
                                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600">Kode: {{ $r->kode }}</span>
                                        <span class="font-normal text-slate-400">Diterbitkan: {{ $fmt($r->diterbitkan) }}</span>
                                    </div>
                                    <h3 class="mt-1.5 text-base font-bold text-slate-900 leading-snug">{{ $r->nama }}</h3>
                                    @if ($r->sertifikat_terbit ?? false)
                                        <p class="mt-1 text-xs text-slate-500 break-words">Nomor Registrasi Sertifikat: <b class="font-mono text-slate-800">{{ $r->no_sertifikat }}</b> • Berlaku s/d {{ $r->berlaku_sampai }}</p>
                                    @else
                                        <p class="mt-1 text-xs text-amber-700">Sertifikat sedang diproses admin dan akan tersedia setelah diterbitkan.</p>
                                    @endif
                                </div>
                                <div class="flex flex-wrap gap-2 sm:shrink-0">
                                    <a href="{{ $r->url_transkrip ?? (Route::has('asesi.skema.transkrip') ? route('asesi.skema.transkrip', ['kode' => $r->kode]) : '#') }}" class="{{ $btnO }}">Transkrip Nilai</a>
                                    @if ($r->sertifikat_terbit ?? false)
                                        <a href="{{ $r->url_sertifikat ?? (Route::has('asesi.skema.sertifikat') ? route('asesi.skema.sertifikat', ['kode' => $r->kode]) : '#') }}" class="{{ $btnP }}">Unduh Sertifikat (PDF)</a>
                                    @else
                                        <span aria-disabled="true" title="Tersedia setelah admin menerbitkan sertifikat" class="{{ $btnO }} opacity-60 !cursor-not-allowed hover:!bg-white">Menunggu Penerbitan Sertifikat</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Tersedia --}}
            <section id="skema-tersedia" data-sec="tersedia" class="sk-in scroll-mt-24" style="animation-delay:.2s">
                <div class="flex flex-wrap items-start justify-between gap-2">
                    <h2 class="flex items-start gap-2 text-xs font-bold uppercase tracking-wider text-slate-800 max-w-md"><span class="mt-1 w-1.5 h-1.5 shrink-0 rounded-full bg-slate-400"></span> Daftar Skema Tersedia (Katalog Kompetensi Terbuka)</h2>
                    <span class="text-[11px] text-slate-400">{{ $tersedia->count() }} Skema Tersedia untuk Didaftarkan</span>
                </div>
                <div class="mt-3 space-y-3">
                    @forelse ($tersedia as $t)
                        @php [$tile, $badge] = $warna[$t->warna ?? 'emerald'] ?? $warna['emerald']; @endphp
                        <div class="{{ $card }} p-5 flex flex-col sm:flex-row sm:items-center gap-4 transition-shadow hover:shadow-md">
                            <span class="shrink-0 w-11 h-11 rounded-xl border grid place-items-center {{ $tile }}"><svg class="w-5 h-5" {!! $a !!}>{!! $ic[$t->ikon ?? 'award'] ?? $ic['award'] !!}</svg></span>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-1.5 text-[10px] font-semibold">
                                    <span class="px-2 py-0.5 rounded border {{ $badge }}">{{ $t->jenis ?? 'Klaster' }}</span>
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600">Kode: {{ $t->kode }}</span>
                                </div>
                                <p class="mt-1.5 text-[11px] text-slate-400">{{ $t->jumlah_unit }} Unit Kompetensi</p>
                                <h3 class="text-base font-bold text-slate-900">{{ $t->nama }}</h3>
                                <p class="mt-1 text-xs text-slate-500 leading-relaxed max-w-md">{{ $t->deskripsi }}</p>
                            </div>
                            <div class="flex flex-wrap gap-2 sm:shrink-0">
                                <a href="{{ $t->url_detail ?? route('asesi.skema.detail', ['skema' => $t->kode]) }}" class="{{ $btnO }}">Detail Silabus/Unit</a>
                                <a href="{{ $t->url_daftar ?? route('asesi.skema.daftar', ['skema' => $t->kode]) }}" class="{{ $btnP }}">Daftar Sekarang</a>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-300 px-4 py-8 text-center text-xs text-slate-500">Belum ada skema yang tersedia untuk didaftarkan.</div>
                    @endforelse
                </div>
            </section>
        </div>

        {{-- ============ Kolom kanan ============ --}}
        <aside class="space-y-5 lg:sticky lg:top-6">
            <div class="{{ $card }} p-5 sk-in" style="animation-delay:.15s">
                <h2 class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-slate-800"><svg class="w-4 h-4 text-slate-500" {!! $a !!}>{!! $ic['list'] !!}</svg>Ringkasan Status Asesi</h2>
                <div class="mt-4 grid grid-cols-2 gap-2.5">
                    @foreach ($ringkasan as [$k, $v])
                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-3"><p class="text-[10px] text-slate-400">{{ $k }}</p><p class="mt-1 text-sm font-bold text-[#0b5a49]">{{ $v }}</p></div>
                    @endforeach
                </div>
            </div>
            <div class="{{ $card }} p-5 sk-in" style="animation-delay:.25s">
                <h2 class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-slate-800"><svg class="w-4 h-4 text-slate-500" {!! $a !!}>{!! $ic['book'] !!}</svg>Panduan Alur Sertifikasi BNSP</h2>
                <ol class="mt-4 space-y-3.5">
                    @foreach ($panduan as $i => [$jd, $ds])
                        <li class="flex gap-3">
                            <span class="shrink-0 w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold grid place-items-center">{{ $i + 1 }}</span>
                            <div><p class="text-xs font-semibold text-slate-900">{{ $jd }}</p><p class="text-[11px] text-slate-500 leading-relaxed">{{ $ds }}</p></div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </aside>
    </div>
</div>

<script>
    // Tab: tampilkan section sesuai pilihan
    (() => {
        const root = document.getElementById('skema-page');
        if (!root) return;
        const tabs = [...root.querySelectorAll('[data-tab]')];
        const secs = [...root.querySelectorAll('[data-sec]')];
        const show = (t) => {
            tabs.forEach((b) => b.setAttribute('aria-selected', b.dataset.tab === t ? 'true' : 'false'));
            secs.forEach((s) => {
                const on = t === 'semua' || s.dataset.sec === t;
                s.classList.toggle('hidden', !on);
                if (on) { s.style.animationDelay = '0s'; s.classList.remove('sk-in'); void s.offsetWidth; s.classList.add('sk-in'); }
            });
        };
        tabs.forEach((b) => b.addEventListener('click', () => show(b.dataset.tab)));
    })();

    // Unduh Kartu Registrasi: kartu (SVG) diubah ke gambar PNG di browser lalu diunduh
    (() => {
        const src = document.querySelector('#kartu-registrasi-src svg');
        document.querySelectorAll('[data-unduh-kartu]').forEach((btn) => btn.addEventListener('click', () => {
            if (!src) return;
            const W = 1920, H = 1200;
            const svg = src.cloneNode(true);
            svg.setAttribute('width', W); svg.setAttribute('height', H);
            const url = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(new XMLSerializer().serializeToString(svg));
            const img = new Image();
            img.onload = () => {
                const c = document.createElement('canvas'); c.width = W; c.height = H;
                c.getContext('2d').drawImage(img, 0, 0, W, H);
                c.toBlob((blob) => {
                    const a = document.createElement('a');
                    a.href = URL.createObjectURL(blob);
                    a.download = (btn.dataset.filename || 'Kartu-Registrasi') + '.png';
                    document.body.appendChild(a); a.click(); a.remove();
                    setTimeout(() => URL.revokeObjectURL(a.href), 1000);
                }, 'image/png');
            };
            img.src = url;
        }));
    })();
</script>
</x-layouts.app>