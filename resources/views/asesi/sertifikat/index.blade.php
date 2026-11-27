{{-- resources/views/asesi/sertifikat/index.blade.php  (FILE BARU, halaman "Sertifikat Saya")
  DATA dari SertifikatController@index:
  $sertifikat : koleksi objek -> id, kode, nama, jenis, nomor, nama_pemilik, tanggal_terbit, berlaku_sampai (Carbon|string),
                aktif (bool), tipe_berkas (pdf|image|null), lihat_url, download_url, url_verifikasi (opsional), demo (bool)
  $namaLsp, $sinkronisasi : string
--}}
@php
    $c   = fn ($d) => $d instanceof \Carbon\CarbonInterface ? $d : \Carbon\Carbon::parse($d);
    $tgl = fn ($d) => $d ? $c($d)->locale('id')->translatedFormat('d F Y') : '-';

    $aktif    = $sertifikat->where('aktif', true);
    $nonaktif = $sertifikat->where('aktif', false);
    $thnAktif    = $aktif->whereNotNull('berlaku_sampai')->isNotEmpty() ? $c($aktif->max('berlaku_sampai'))->year : null;
    $thnNonaktif = $nonaktif->whereNotNull('berlaku_sampai')->isNotEmpty() ? $c($nonaktif->max('berlaku_sampai'))->year : null;

    $a = 'fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"';
    $ic = [
        'badge-check' => '<path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m9 12 2 2 4-4"/>',
        'badge-x'     => '<path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m14.5 9.5-5 5M9.5 9.5l5 5"/>',
        'check'   => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
        'x'       => '<path d="M18 6 6 18M6 6l12 12"/>',
        'eye'     => '<path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>',
        'down'    => '<path d="M12 3v12m0 0-4-4m4 4 4-4M4 21h16"/>',
        'share'   => '<circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.59 13.51 6.83 3.98M15.41 6.51l-6.82 3.98"/>',
        'chevron' => '<path d="m6 9 6 6 6-6"/>',
        'award'   => '<circle cx="12" cy="8" r="6"/><path d="M15.5 12.9 17 22l-5-3-5 3 1.5-9.1"/>',
        'qr'      => '<rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3M21 21v.01M12 7v3a2 2 0 0 1-2 2H7M3 12h.01M12 3h.01M12 16v.01M16 12h1M21 12v.01M12 21v-3"/>',
        'sync'    => '<path d="M3 12a9 9 0 0 1 15-6.7L21 8M21 3v5h-5M21 12a9 9 0 0 1-15 6.7L3 16M3 21v-5h5"/>',
        'clock'   => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
        'cal'     => '<rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
    ];
    $tones = [
        ['bg' => 'from-emerald-100/90 via-teal-50 to-amber-100/80',   'outer' => 'border-emerald-300/90', 'inner' => 'border-amber-300/90', 'line' => 'border-emerald-200', 'ring' => 'border-amber-300 bg-gradient-to-br from-amber-100 to-yellow-50 text-amber-800',   'seal' => 'bnsp'],
        ['bg' => 'from-cyan-100/90 via-sky-50 to-teal-100/80',        'outer' => 'border-cyan-300/90',    'inner' => 'border-teal-300/90',  'line' => 'border-cyan-200',    'ring' => 'border-cyan-300 bg-gradient-to-br from-cyan-100 to-sky-50 text-cyan-800',         'seal' => 'qr'],
        ['bg' => 'from-indigo-100/85 via-violet-50/70 to-teal-100/75','outer' => 'border-indigo-300/90',  'inner' => 'border-indigo-300/90','line' => 'border-indigo-200',  'ring' => 'border-indigo-300 bg-gradient-to-br from-indigo-100 to-violet-50 text-indigo-800', 'seal' => 'bnsp'],
    ];
    $jenisChip = [
        'Klaster' => 'bg-gradient-to-r from-emerald-50 to-teal-50 text-emerald-800 border-emerald-200',
        'Okupasi' => 'bg-gradient-to-r from-cyan-50 to-sky-50 text-cyan-800 border-cyan-200',
    ];
@endphp

<x-layouts.app :title="$title ?? 'Sertifikat Saya'">
<style>
    @keyframes skIn { from { opacity: 0; transform: translateY(16px); } }
    .sk-in { animation: skIn .6s cubic-bezier(.22, 1, .36, 1) backwards; }
    @media (prefers-reduced-motion: reduce) { .sk-in { animation: none; } }
</style>

<div id="sertifikat-page" class="max-w-[1100px]">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-slate-500 flex items-center gap-2.5" aria-label="Breadcrumb">
        <span>Portal Asesi</span><span class="text-slate-400">›</span><span class="font-semibold text-slate-900">Sertifikat Saya</span>
    </nav>

    {{-- Judul + filter tahun --}}
    <div class="mt-5 rounded-3xl border border-emerald-200/60 bg-gradient-to-r from-white via-[#f3fbf8] to-cyan-50/60 p-5 sm:p-6 shadow-[0_2px_12px_rgba(9,84,70,.05)] flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#e6f3ef] px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-[#095446] border border-[#bfe3d9]">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                E-Sertifikat Resmi BNSP
            </span>
            <h1 class="mt-2.5 text-3xl sm:text-4xl font-bold bg-gradient-to-r from-slate-900 via-[#095446] to-teal-800 bg-clip-text text-transparent tracking-tight">Sertifikat Saya</h1>
            <p class="mt-2 max-w-lg text-[15px] leading-relaxed text-slate-600">Kelola, verifikasi keaslian, dan unduh sertifikat kompetensi berlisensi BNSP yang telah Anda raih.</p>
        </div>
        {{-- Filter rentang tanggal terbit: ikon kalender, klik untuk memilih tanggal mulai s/d selesai --}}
        <div class="relative shrink-0 self-start" data-range>
            <input type="hidden" data-from><input type="hidden" data-to>
            <button type="button" data-range-btn aria-haspopup="dialog" aria-expanded="false" title="Filter rentang tanggal terbit"
                    class="relative grid h-12 w-12 cursor-pointer place-items-center rounded-2xl border border-emerald-200/90 bg-white text-[#095446] shadow-sm transition-colors hover:border-[#095446] hover:bg-emerald-50/50 focus:outline-none focus:ring-2 focus:ring-emerald-100 data-[active=true]:border-[#0b5a49] data-[active=true]:bg-emerald-50">
                <svg class="w-5 h-5" {!! $a !!}>{!! $ic['cal'] !!}</svg>
                <span data-range-dot class="hidden absolute -top-1 -right-1 h-3 w-3 rounded-full bg-[#0b5a49] ring-2 ring-white"></span>
            </button>
            <div data-range-pop role="dialog" aria-label="Pilih rentang tanggal terbit" class="hidden absolute right-0 top-full z-30 mt-2 w-[288px] rounded-2xl border border-slate-200 bg-white p-4 shadow-[0_12px_32px_rgba(15,23,42,.16)]">
                <div class="flex items-center justify-between">
                    <button type="button" data-rp-prev aria-label="Bulan sebelumnya" class="grid h-7 w-7 cursor-pointer place-items-center rounded-lg text-slate-500 hover:bg-slate-100"><svg class="w-4 h-4" {!! $a !!}><path d="m15 18-6-6 6-6"/></svg></button>
                    <p data-rp-title class="text-sm font-bold text-slate-900"></p>
                    <button type="button" data-rp-next aria-label="Bulan berikutnya" class="grid h-7 w-7 cursor-pointer place-items-center rounded-lg text-slate-500 hover:bg-slate-100"><svg class="w-4 h-4" {!! $a !!}><path d="m9 18 6-6-6-6"/></svg></button>
                </div>
                <div class="mt-3 grid grid-cols-7 text-center text-[10px] font-semibold text-slate-400">@foreach (['MIN', 'SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB'] as $hh)<span>{{ $hh }}</span>@endforeach</div>
                <div data-rp-grid class="mt-1.5 grid grid-cols-7 gap-y-1"></div>
                <p data-rp-label class="mt-3 border-t border-slate-100 pt-3 text-center text-[11px] font-semibold text-slate-600">Pilih tanggal mulai</p>
                <div class="mt-3 flex gap-2">
                    <button type="button" data-rp-reset class="flex-1 cursor-pointer rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition-colors hover:bg-slate-50">Reset</button>
                    <button type="button" data-rp-apply class="flex-1 cursor-pointer rounded-lg bg-[#0b5a49] px-3 py-2 text-xs font-semibold text-white transition-colors hover:bg-[#074237]">Terapkan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="mt-7 grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="sk-in flex items-center justify-between gap-4 rounded-3xl border border-emerald-200/80 bg-gradient-to-br from-white via-white to-emerald-50/70 p-6 sm:p-7 shadow-[0_4px_16px_rgba(9,84,70,.05)]">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-700/80">Sertifikat Aktif</p>
                <p class="mt-3 flex items-baseline gap-2.5"><span class="text-3xl font-bold text-slate-900">{{ $aktif->count() }}</span><span class="rounded-full bg-emerald-100/80 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">{{ $thnAktif ? 'Berlaku s/d ' . $thnAktif : 'Belum ada' }}</span></p>
            </div>
            <span class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-600/20 grid place-items-center"><svg class="w-7 h-7" {!! $a !!}>{!! $ic['badge-check'] !!}</svg></span>
        </div>
        <div class="sk-in flex items-center justify-between gap-4 rounded-3xl border border-rose-200/70 bg-gradient-to-br from-white via-white to-rose-50/60 p-6 sm:p-7 shadow-[0_4px_16px_rgba(15,23,42,.04)]" style="animation-delay:.08s">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[.16em] text-rose-700/80">Sertifikat Tidak Aktif</p>
                <p class="mt-3 flex items-baseline gap-2.5"><span class="text-3xl font-bold text-slate-900">{{ $nonaktif->count() }}</span><span class="rounded-full bg-rose-100/90 px-2.5 py-0.5 text-xs font-semibold text-rose-700">{{ $thnNonaktif ? 'Berakhir s/d ' . $thnNonaktif : 'Belum ada yang kedaluwarsa' }}</span></p>
            </div>
            <span class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 text-white shadow-md shadow-rose-600/20 grid place-items-center"><svg class="w-7 h-7" {!! $a !!}>{!! $ic['badge-x'] !!}</svg></span>
        </div>
    </div>

    {{-- Tab --}}
    <div class="mt-8 flex gap-8 border-b border-slate-200" role="tablist">
        @foreach ([['aktif', 'Tersedia', $aktif->count()], ['kedaluwarsa', 'Kedaluwarsa', $nonaktif->count()]] as [$key, $lbl, $cnt])
            <button type="button" role="tab" data-tab="{{ $key }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                    class="group -mb-px flex items-center gap-2.5 border-b-2 border-transparent px-1 pb-4 text-[17px] font-medium text-slate-500 transition-colors hover:text-slate-800 aria-selected:border-[#0b5a49] aria-selected:font-semibold aria-selected:text-[#0b5a49] cursor-pointer">
                {{ $lbl }}
                <span data-count="{{ $key }}" class="rounded-full border border-slate-200 bg-slate-100 px-2.5 py-0.5 text-sm text-slate-500 group-aria-selected:border-emerald-200 group-aria-selected:bg-gradient-to-r group-aria-selected:from-emerald-100 group-aria-selected:to-teal-100 group-aria-selected:text-emerald-800 group-aria-selected:font-semibold">{{ $cnt }}</span>
            </button>
        @endforeach
    </div>

    {{-- Daftar sertifikat --}}
    <div id="sk-grid" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ($sertifikat as $s)
            @php
                $t = $tones[$loop->index % count($tones)];
                $terbit  = $c($s->tanggal_terbit);
                $berlaku = $s->berlaku_sampai ? $c($s->berlaku_sampai) : null;
                $demo = (bool) ($s->demo ?? false);
                $tipe = $s->tipe_berkas ?? null;
            @endphp
            <article data-card data-status="{{ $s->aktif ? 'aktif' : 'kedaluwarsa' }}" data-date="{{ $terbit->format('Y-m-d') }}"
                     data-judul="{{ $s->nama }}" data-tipe="{{ $tipe }}" data-lihat="{{ $s->lihat_url }}" data-unduh="{{ $s->download_url }}" data-demo="{{ $demo ? '1' : '' }}"
                     class="sk-in overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-[0_2px_10px_rgba(15,23,42,.04)] transition-all duration-300 hover:-translate-y-1 hover:border-emerald-300/80 hover:shadow-[0_16px_32px_rgba(9,84,70,.12)]"
                     style="animation-delay:{{ min($loop->index, 5) * 0.08 }}s">

                {{-- Pratinjau (berkas yang diunggah admin; bila belum ada tampil pratinjau bawaan) --}}
                <div data-thumb class="relative bg-gradient-to-br {{ $t['bg'] }} p-4 sm:p-6">
                    @if ($s->aktif)
                        <span class="absolute right-3.5 top-3.5 z-10 inline-flex items-center gap-1.5 rounded-full border border-emerald-300 bg-gradient-to-r from-emerald-500 to-teal-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-sm"><svg class="w-3.5 h-3.5" stroke-width="2.4" {!! $a !!}>{!! $ic['check'] !!}</svg>Aktif</span>
                    @else
                        <span class="absolute right-3.5 top-3.5 z-10 inline-flex items-center gap-1.5 rounded-full border border-rose-300 bg-gradient-to-r from-rose-500 to-red-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-sm"><svg class="w-3.5 h-3.5" stroke-width="2.4" {!! $a !!}>{!! $ic['x'] !!}</svg>Kedaluwarsa</span>
                    @endif

                    @if ($tipe === 'image')
                        <img src="{{ $s->lihat_url }}" alt="Sertifikat {{ $s->nama }}" loading="lazy" class="w-full aspect-[1.3/1] rounded-xl bg-white object-contain shadow-md">
                    @elseif ($tipe === 'pdf')
                        <div class="relative w-full aspect-[1.3/1] overflow-hidden rounded-xl bg-white shadow-md">
                            <object data="{{ $s->lihat_url }}#toolbar=0&navpanes=0&scrollbar=0&view=Fit" type="application/pdf" aria-label="Pratinjau sertifikat {{ $s->nama }}" class="pointer-events-none absolute inset-0 h-full w-full"></object>
                        </div>
                    @else
                        <div class="rounded-xl border-2 {{ $t['outer'] }} bg-white/80 p-2.5 shadow-sm">
                            <div class="flex aspect-[1.3/1] flex-col rounded-lg border {{ $t['inner'] }} bg-gradient-to-b from-white to-slate-50/70 px-5 py-4 text-center">
                                <span class="mx-auto grid h-8 w-8 place-items-center rounded-full border {{ $t['ring'] }}"><svg class="w-4 h-4" {!! $a !!}>{!! $ic['award'] !!}</svg></span>
                                <p class="mt-2 font-serif text-[8px] font-bold tracking-[.2em] text-slate-500">REPUBLIK INDONESIA</p>
                                <p class="font-serif text-[15px] font-bold tracking-wide text-slate-800">SERTIFIKAT KOMPETENSI</p>
                                <p class="text-[8px] text-slate-400">Certificate of Competency</p>
                                <div class="my-2 border-t {{ $t['line'] }}"></div>
                                <p class="text-[7px] tracking-[.15em] text-slate-400">DIBERIKAN KEPADA:</p>
                                <p class="truncate font-serif text-[14px] font-bold uppercase text-slate-900">{{ $s->nama_pemilik }}</p>
                                <p class="truncate text-[8px] text-slate-600">{{ $s->nama }}</p>
                                <div class="mt-2 border-t {{ $t['line'] }}"></div>
                                <div class="mt-auto grid grid-cols-[1fr_auto_1fr] items-end gap-2 pt-2">
                                    <div class="text-left font-mono text-[6.5px] leading-snug text-slate-400"><p class="truncate">ID: {{ $s->nomor }}</p><p>Tgl Terbit: {{ $terbit->locale('id')->translatedFormat('d M Y') }}</p></div>
                                    @if ($t['seal'] === 'qr')
                                        <span class="grid h-9 w-9 place-items-center rounded-md border {{ $t['ring'] }}"><svg class="w-5 h-5" {!! $a !!}>{!! $ic['qr'] !!}</svg></span>
                                    @else
                                        <span class="grid h-9 w-9 place-items-center rounded-full border {{ $t['ring'] }} font-serif text-[7px] font-bold shadow">BNSP</span>
                                    @endif
                                    <div class="text-right text-[7px] leading-snug text-slate-400"><p>Tanda Tangan Direktur</p><p class="text-slate-700 font-medium">{{ $namaLsp }}</p></div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Informasi --}}
                <div class="p-5 sm:p-6">
                    <div class="flex flex-wrap items-center gap-2 text-[11px] font-semibold">
                        <span class="rounded-md bg-slate-100 border border-slate-200/70 px-2.5 py-1 font-mono text-slate-700">{{ $s->kode }}</span>
                        <span class="rounded-md border px-2.5 py-1 {{ $jenisChip[$s->jenis] ?? 'bg-emerald-50 text-emerald-700 border-emerald-200' }}">{{ $s->jenis }}</span>
                    </div>
                    <h3 class="mt-3 text-xl font-semibold leading-snug text-slate-900">{{ $s->nama }}</h3>

                    <dl class="mt-4 space-y-2.5 border-t border-slate-100 pt-4 text-sm">
                        <div class="flex items-baseline justify-between gap-3"><dt class="text-slate-500">Nomor Registrasi:</dt><dd class="font-mono text-[13px] font-bold text-[#095446]">{{ $s->nomor }}</dd></div>
                        <div class="flex items-baseline justify-between gap-3"><dt class="text-slate-500">Tanggal Terbit:</dt><dd class="font-medium text-slate-900">{{ $tgl($terbit) }}</dd></div>
                        <div class="flex items-baseline justify-between gap-3"><dt class="text-slate-500">Berlaku Hingga:</dt><dd class="font-semibold {{ $s->aktif ? 'text-emerald-700' : 'text-rose-700' }}">{{ $tgl($berlaku) }}</dd></div>
                    </dl>
                </div>

                {{-- Aksi --}}
                <div class="grid grid-cols-[1fr_1fr_auto] gap-2.5 border-t border-slate-100 bg-slate-50/50 p-4 sm:px-5">
                    <button type="button" data-lihat-btn class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[13px] font-semibold text-slate-800 transition-colors hover:border-emerald-300 hover:bg-emerald-50/50 cursor-pointer"><svg class="w-4 h-4 text-[#095446]" {!! $a !!}>{!! $ic['eye'] !!}</svg>Lihat Detail</button>
                    <a data-dl @if ($demo) data-demo="1" href="#" @else href="{{ $s->download_url }}" download @endif class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#0b5a49] to-teal-700 px-3 py-2.5 text-[13px] font-semibold text-white shadow-xs transition-all hover:from-[#074237] hover:to-teal-800"><svg class="w-4 h-4" {!! $a !!}>{!! $ic['down'] !!}</svg>{{ $tipe === 'image' ? 'Unduh Gambar' : 'Unduh PDF' }}</a>
                    <button type="button" data-share data-title="Sertifikat {{ $s->nama }}" data-text="Sertifikat kompetensi {{ $s->nama }} (No. {{ $s->nomor }}) atas nama {{ $s->nama_pemilik }}" data-url="{{ $s->url_verifikasi ?? '' }}" aria-label="Bagikan sertifikat" class="grid h-[42px] w-[42px] place-items-center rounded-xl border border-slate-200 bg-white text-slate-700 transition-colors hover:border-cyan-300 hover:bg-cyan-50/60 hover:text-cyan-800 cursor-pointer"><svg class="w-4 h-4" {!! $a !!}>{!! $ic['share'] !!}</svg></button>
                </div>
            </article>
        @endforeach
    </div>

    <div id="sk-kosong" class="hidden mt-6 rounded-2xl border border-dashed border-slate-300 bg-white px-4 py-12 text-center text-sm text-slate-500">Tidak ada sertifikat pada kategori atau rentang tanggal yang dipilih.</div>

    {{-- Info bawah --}}
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_330px] gap-6 items-stretch">
        <section class="sk-in rounded-3xl p-7 text-white shadow-lg shadow-emerald-950/15" style="background:linear-gradient(135deg,#032e25 0%,#064a3b 55%,#0d6e5a 100%)">
            <p class="flex items-center gap-2.5 text-xs font-bold uppercase tracking-[.14em] text-emerald-300"><span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>Otentikasi &amp; Keabsahan Sertifikat</p>
            <h2 class="mt-4 text-2xl font-semibold leading-snug">Verifikasi Langsung ke Database BNSP Nasional</h2>
            <p class="mt-4 text-sm leading-relaxed text-emerald-50/85">Setiap sertifikat kompetensi yang diterbitkan oleh {{ $namaLsp }} dilengkapi enkripsi QR Code unik yang terdaftar secara sah di sistem registrasi Badan Nasional Sertifikasi Profesi (BNSP). Dokumen ini sah digunakan untuk portofolio industri, melamar pekerjaan, dan melanjutkan pendidikan vokasi.</p>
            <p class="mt-5 inline-flex items-center gap-2.5 rounded-xl bg-gradient-to-r from-emerald-400 to-teal-300 px-4 py-2.5 text-[13px] font-bold text-emerald-950 shadow-xs"><svg class="w-4 h-4" {!! $a !!}>{!! $ic['sync'] !!}</svg>Terakhir sinkronisasi: {{ $sinkronisasi }}</p>
        </section>
        <aside class="sk-in rounded-3xl border border-amber-200/90 bg-gradient-to-br from-amber-50 via-yellow-50/70 to-orange-50/60 p-6 shadow-xs" style="animation-delay:.1s">
            <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 text-white shadow-xs"><svg class="w-5 h-5" {!! $a !!}>{!! $ic['clock'] !!}</svg></span>
            <h2 class="mt-4 text-lg font-semibold text-slate-900">Panduan Perpanjangan (RCC)</h2>
            <p class="mt-3 text-sm leading-relaxed text-slate-600">Masa berlaku sertifikat adalah <b class="text-slate-900">3 (tiga) tahun</b>. Anda dapat mengajukan perpanjangan sertifikasi melalui skema Recognition of Current Competency (RCC) minimal 3 bulan sebelum masa berlaku berakhir.</p>
        </aside>
    </div>

    {{-- Modal: tampilan berkas sertifikat dari admin --}}
    <div id="sk-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm sm:p-8" role="dialog" aria-modal="true" aria-labelledby="sk-modal-title">
        <div class="flex h-[85vh] w-full max-w-4xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="flex items-center justify-between gap-3 border-b border-slate-200 bg-gradient-to-r from-white to-emerald-50/40 px-5 py-3">
                <h2 id="sk-modal-title" class="min-w-0 truncate text-sm font-semibold text-slate-900"></h2>
                <div class="flex shrink-0 items-center gap-2">
                    <a id="sk-modal-unduh" data-dl href="#" download class="inline-flex items-center gap-2 rounded-lg bg-[#0b5a49] px-3.5 py-2 text-xs font-semibold text-white transition-colors hover:bg-[#074237]"><svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['down'] !!}</svg>Unduh</a>
                    <button type="button" id="sk-modal-tutup" aria-label="Tutup" class="grid h-8 w-8 place-items-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100 cursor-pointer"><svg class="w-4 h-4" {!! $a !!}>{!! $ic['x'] !!}</svg></button>
                </div>
            </div>
            <div id="sk-modal-body" class="relative flex-1 overflow-auto bg-slate-100"></div>
        </div>
    </div>

    <div id="sk-toast" class="fixed bottom-6 left-1/2 z-[60] hidden -translate-x-1/2 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white shadow-lg"></div>
</div>

@verbatim
<script>
(() => {
    const root = document.getElementById('sertifikat-page');
    if (!root) return;
    const $  = (s, c = root) => c.querySelector(s);
    const $$ = (s, c = root) => [...c.querySelectorAll(s)];
    const cards = $$('[data-card]'), tabs = $$('[data-tab]');
    const from = $('[data-from]'), to = $('[data-to]');
    let tab = 'aktif';

    const toast = (t) => {
        const el = $('#sk-toast'); el.textContent = t; el.classList.remove('hidden');
        clearTimeout(toast.t); toast.t = setTimeout(() => el.classList.add('hidden'), 2800);
    };

    /* ---------- Tab + filter tahun ---------- */
    const apply = () => {
        const cnt = { aktif: 0, kedaluwarsa: 0 };
        let shown = 0;
        cards.forEach((c) => {
            const okTahun = (!from.value || c.dataset.date >= from.value) && (!to.value || c.dataset.date <= to.value);
            if (okTahun) cnt[c.dataset.status]++;
            const on = okTahun && c.dataset.status === tab;
            c.classList.toggle('hidden', !on);
            if (on) shown++;
        });
        $$('[data-count]').forEach((e) => { e.textContent = cnt[e.dataset.count]; });
        $('#sk-kosong').classList.toggle('hidden', shown > 0);
    };
    tabs.forEach((b) => b.addEventListener('click', () => {
        tab = b.dataset.tab;
        tabs.forEach((x) => x.setAttribute('aria-selected', x === b ? 'true' : 'false'));
        apply();
    }));
    /* ---------- Kalender rentang tanggal terbit ---------- */
    const rng = $('[data-range]'), btn = $('[data-range-btn]', rng), pop = $('[data-range-pop]', rng);
    const rpTitle = $('[data-rp-title]', pop), rpGrid = $('[data-rp-grid]', pop), rpLabel = $('[data-rp-label]', pop);
    const BLN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const pad = (n) => String(n).padStart(2, '0');
    const kunci = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
    const fmt = (k) => { const [y, m, d] = k.split('-'); return `${+d} ${BLN[m - 1].slice(0, 3)} ${y}`; };
    let st = '', en = '', view = new Date(); view = new Date(view.getFullYear(), view.getMonth(), 1);

    const label = () => { rpLabel.textContent = !st ? 'Pilih tanggal mulai' : !en ? `${fmt(st)} – pilih tanggal akhir` : (st === en ? fmt(st) : `${fmt(st)} – ${fmt(en)}`); };
    const gambar = () => {
        rpTitle.textContent = `${BLN[view.getMonth()]} ${view.getFullYear()}`;
        rpGrid.innerHTML = '';
        const start = new Date(view); start.setDate(1 - view.getDay());
        for (let i = 0; i < 42; i++) {
            const d = new Date(start); d.setDate(start.getDate() + i);
            if (i === 35 && d.getMonth() !== view.getMonth()) break;
            const k = kunci(d), luar = d.getMonth() !== view.getMonth();
            const ujung = k === st || k === en, tengah = st && en && k > st && k < en;
            const b = document.createElement('button');
            b.type = 'button'; b.textContent = d.getDate();
            b.className = 'cursor-pointer h-8 text-xs transition-colors ' + (ujung ? 'bg-[#0b5a49] text-white font-bold rounded-lg' : tengah ? 'bg-emerald-100 text-emerald-900 font-semibold' : (luar ? 'text-slate-300 hover:bg-slate-100 rounded-lg' : 'text-slate-700 hover:bg-slate-100 rounded-lg'));
            if (k === st && en && st !== en) b.classList.add('rounded-r-none');
            if (k === en && st !== en) b.classList.add('rounded-l-none');
            b.addEventListener('click', () => {
                if (!st || (st && en)) { st = k; en = ''; }
                else if (k < st) { st = k; }
                else { en = k; }
                label(); gambar();
            });
            rpGrid.appendChild(b);
        }
    };
    const terapkan = () => {
        from.value = st; to.value = st ? (en || st) : '';
        btn.dataset.active = st ? 'true' : 'false';
        $('[data-range-dot]', btn).classList.toggle('hidden', !st);
        btn.title = st ? `Tanggal terbit: ${fmt(from.value)}${from.value !== to.value ? ' – ' + fmt(to.value) : ''}` : 'Filter rentang tanggal terbit';
        apply();
    };
    const tutupRange = () => { pop.classList.add('hidden'); btn.setAttribute('aria-expanded', 'false'); };
    btn.addEventListener('click', () => {
        const buka = pop.classList.contains('hidden');
        pop.classList.toggle('hidden', !buka); btn.setAttribute('aria-expanded', String(buka));
        if (buka) { st = from.value; en = to.value; if (st) { const [y, m] = st.split('-'); view = new Date(+y, m - 1, 1); } label(); gambar(); }
    });
    $('[data-rp-prev]', pop).addEventListener('click', () => { view = new Date(view.getFullYear(), view.getMonth() - 1, 1); gambar(); });
    $('[data-rp-next]', pop).addEventListener('click', () => { view = new Date(view.getFullYear(), view.getMonth() + 1, 1); gambar(); });
    $('[data-rp-reset]', pop).addEventListener('click', () => { st = ''; en = ''; terapkan(); label(); gambar(); tutupRange(); });
    $('[data-rp-apply]', pop).addEventListener('click', () => { terapkan(); tutupRange(); });
    pop.addEventListener('click', (e) => e.stopPropagation());   // grid digambar ulang saat klik; jangan dianggap klik di luar
    document.addEventListener('click', (e) => { if (!rng.contains(e.target)) tutupRange(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') tutupRange(); });

    /* ---------- Unduh: otomatis mengunduh (atribut download); mode demo hanya notifikasi ---------- */
    $$('[data-dl]').forEach((a) => a.addEventListener('click', (e) => {
        if (a.dataset.demo) { e.preventDefault(); toast('Mode demo: berkas sertifikat dari admin belum tersedia.'); }
    }));

    /* ---------- Bagikan ---------- */
    $$('[data-share]').forEach((b) => b.addEventListener('click', async () => {
        const d = b.dataset, p = { title: d.title, text: d.text };
        if (d.url) p.url = d.url;
        try {
            if (navigator.share) { await navigator.share(p); }
            else { await navigator.clipboard.writeText(d.url ? d.text + ' - ' + d.url : d.text); toast('Informasi sertifikat disalin.'); }
        } catch (e) { /* dibatalkan pengguna */ }
    }));

    /* ---------- Modal: hanya menampilkan berkas yang diunggah admin ---------- */
    const modal = $('#sk-modal'), body = $('#sk-modal-body'), dl = $('#sk-modal-unduh');
    const buka = (card) => {
        body.innerHTML = '';
        $('#sk-modal-title').textContent = card.dataset.judul;
        const demo = !!card.dataset.demo;
        dl.href = demo ? '#' : card.dataset.unduh;
        if (demo) dl.dataset.demo = '1'; else delete dl.dataset.demo;
        const tipe = card.dataset.tipe, url = card.dataset.lihat;
        if (tipe === 'pdf') {
            const f = document.createElement('iframe');
            f.src = url + '#view=FitH'; f.title = card.dataset.judul; f.className = 'absolute inset-0 h-full w-full bg-white';
            body.appendChild(f);
        } else if (tipe === 'image') {
            const i = document.createElement('img');
            i.src = url; i.alt = card.dataset.judul; i.className = 'mx-auto my-4 max-w-full bg-white shadow';
            body.appendChild(i);
        } else {
            const w = document.createElement('div');
            w.className = 'mx-auto my-6 max-w-2xl px-4';
            w.appendChild($('[data-thumb]', card).cloneNode(true));
            body.appendChild(w);
        }
        modal.classList.remove('hidden'); modal.classList.add('flex');
        document.documentElement.classList.add('overflow-hidden');
        $('#sk-modal-tutup').focus();
    };
    const tutup = () => {
        modal.classList.add('hidden'); modal.classList.remove('flex');
        document.documentElement.classList.remove('overflow-hidden');
        body.innerHTML = '';
    };
    cards.forEach((c) => $('[data-lihat-btn]', c).addEventListener('click', () => buka(c)));
    $('#sk-modal-tutup').addEventListener('click', tutup);
    modal.addEventListener('click', (e) => { if (e.target === modal) tutup(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !modal.classList.contains('hidden')) tutup(); });

    apply();
})();
</script>
@endverbatim
</x-layouts.app>