{{-- resources/views/asesi/jadwal/index.blade.php --}}
{{--
  KONTRAK DATA (dikirim controller saat backend jadi). Selama belum dikirim, halaman memakai DATA DUMMY di bawah.

  $jadwal : koleksi jadwal asesmen milik asesi yang login (hari ini dan seterusnya; jadwal lampau tidak ditampilkan,
            riwayat ada di menu "Daftar Skema")
      tanggal (Carbon/date), waktu_mulai ("08:00"; hanya jam mulai), jenis ('Klaster'|'Okupasi'|'KKNI', dari admin),
      judul (nama skema), bidang (kategori/bidang keahlian skema, opsional), asesor,
      catatan (opsional), catatan_tone ('warning'|'info'),
      no_registrasi (untuk Kartu Registrasi PNG), nama_asesi (opsional; default nama user login)
--}}
@php
    use Carbon\Carbon;

    // ===================== DATA DUMMY (hapus blok ini bila backend sudah mengirim data) =====================
    $jadwal ??= collect([
        (object) ['tanggal' => now(), 'waktu_mulai' => '08:00', 'jenis' => 'Klaster', 'judul' => 'Junior Web Developer',
                  'bidang' => 'Teknologi Informasi', 'asesor' => 'Bpk. Dr. Ir. Budi Santoso, M.Kom',
                  'catatan' => 'Wajib hadir 30 menit sebelum sesi dimulai', 'catatan_tone' => 'warning', 'no_registrasi' => 'REG-BNSP-2024-8831'],
        (object) ['tanggal' => now()->addDays(3), 'waktu_mulai' => '09:30', 'jenis' => 'Okupasi', 'judul' => 'Teknisi Jaringan Komputer Madya',
                  'bidang' => 'Teknologi Informasi', 'asesor' => 'Bpk. Hendra Gunawan, S.T.',
                  'catatan' => 'Bawa kartu registrasi dan kartu pelajar.', 'catatan_tone' => 'info', 'no_registrasi' => 'REG-BNSP-2024-8832'],
        (object) ['tanggal' => now()->addDays(10), 'waktu_mulai' => '13:00', 'jenis' => 'KKNI', 'judul' => 'Desain Grafis Level II',
                  'bidang' => 'Desain', 'asesor' => 'Ibu Siti Aminah, S.Ds',
                  'catatan' => 'Siapkan file portofolio karya asli format PDF.', 'catatan_tone' => 'info', 'no_registrasi' => 'REG-BNSP-2024-8833'],
    ]);
    // =========================================================================================================

    $a = 'fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"';
    $ic = [
        'monitor' => '<rect width="20" height="14" x="2" y="3" rx="2"/><path d="M8 21h8M12 17v4"/>',
        'clock'   => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'user'    => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
        'tag'     => '<path d="M12.6 2.6A2 2 0 0 0 11.2 2H4a2 2 0 0 0-2 2v7.2a2 2 0 0 0 .6 1.4l8.7 8.7a2.4 2.4 0 0 0 3.4 0l6.6-6.6a2.4 2.4 0 0 0 0-3.4z"/><circle cx="7.5" cy="7.5" r=".5"/>',
        'warn'    => '<path d="m21.7 18-8-14a2 2 0 0 0-3.4 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.7-3zM12 9v4M12 17h.01"/>',
        'down'    => '<path d="M12 3v12m0 0-4-4m4 4 4-4M4 21h16"/>',
        'check'   => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
        'left'    => '<path d="m15 18-6-6 6-6"/>',
        'right'   => '<path d="m9 18 6-6-6-6"/>',
        'x'       => '<path d="M18 6 6 18M6 6l12 12"/>',
        'cal'     => '<rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
    ];
    // Warna per jenis skema (dipakai di kartu, kalender, dan pop up)
    $warna = [
        'Klaster' => ['icon' => 'border-emerald-100 bg-emerald-50 text-emerald-700', 'badge' => 'border-emerald-200 bg-emerald-50 text-emerald-700', 'bar' => 'bg-emerald-500', 'tgl' => 'text-emerald-700', 'dot' => 'bg-emerald-600', 'fill' => 'bg-emerald-600 text-white hover:bg-emerald-700'],
        'Okupasi' => ['icon' => 'border-sky-100 bg-sky-50 text-sky-700',             'badge' => 'border-sky-200 bg-sky-50 text-sky-700',             'bar' => 'bg-sky-500',     'tgl' => 'text-sky-700',     'dot' => 'bg-sky-500',     'fill' => 'bg-sky-500 text-white hover:bg-sky-600'],
        'KKNI'    => ['icon' => 'border-violet-100 bg-violet-50 text-violet-700',    'badge' => 'border-violet-200 bg-violet-50 text-violet-700',    'bar' => 'bg-violet-500',  'tgl' => 'text-violet-700',  'dot' => 'bg-violet-600',  'fill' => 'bg-violet-600 text-white hover:bg-violet-700'],
    ];
    $normJenis = fn ($j) => match (strtolower(trim((string) $j))) { 'okupasi' => 'Okupasi', 'kkni' => 'KKNI', default => 'Klaster' };

    $card = 'bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,.04)]';
    $btnP = 'cursor-pointer inline-flex items-center justify-center gap-2 rounded-lg bg-[#0b5a49] hover:bg-[#074237] px-5 py-2.5 text-xs font-semibold text-white transition-colors';

    $jadwal   = collect($jadwal)->filter(fn ($j) => Carbon::parse($j->tanggal)->startOfDay()->gte(today()))->sortBy('tanggal')->values();
    $hariIni  = $jadwal->filter(fn ($j) => Carbon::parse($j->tanggal)->isToday());
    $mendatang = $jadwal->reject(fn ($j) => Carbon::parse($j->tanggal)->isToday());
    $grup     = $jadwal->groupBy(fn ($j) => Carbon::parse($j->tanggal)->format('Y-m'));
    $tgl      = fn ($d) => Carbon::parse($d)->locale('id');
    $events   = $jadwal->map(fn ($j) => [
        'd' => Carbon::parse($j->tanggal)->format('Y-m-d'), 'jenis' => $normJenis($j->jenis ?? null),
        'judul' => $j->judul, 'waktu' => $j->waktu_mulai ?? null, 'asesor' => $j->asesor ?? null, 'bidang' => $j->bidang ?? null,
    ])->values();
    $persiapan = [
        'Membawa Kartu Tanda Peserta Ujian fisik / PDF.',
        'Hadir tepat waktu 30 menit sebelum verifikasi data.',
        'Memakai seragam sekolah rapi / kemeja berkerah.',
        'Membawa portofolio tugas / demonstrasi proyek.',
    ];
    $n = 0;
@endphp

<x-layouts.app :title="$title ?? 'Jadwal Asesmen'">
<style>
    @keyframes jdIn { from { opacity: 0; transform: translateY(18px); } }
    @keyframes jdFlash { 0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); } 30% { box-shadow: 0 0 0 5px rgba(16, 185, 129, .35); } }
    @keyframes jdPop { from { opacity: 0; transform: translateY(12px) scale(.97); } }
    @keyframes jdFade { from { opacity: 0; } }
    .jd-in { animation: jdIn .7s cubic-bezier(.22, 1, .36, 1) backwards; }
    .jd-flash { animation: jdFlash 1.2s ease; }
    .jd-pop { animation: jdPop .28s cubic-bezier(.22, 1, .36, 1); }
    .jd-fade { animation: jdFade .2s ease; }
    @media (prefers-reduced-motion: reduce) { .jd-in, .jd-flash, .jd-pop, .jd-fade { animation: none; } }
</style>

<div id="jadwal-page" class="max-w-[1100px]">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <nav class="text-[11px] text-slate-400 flex items-center gap-2" aria-label="Breadcrumb"><span>Portal Asesi</span><span>›</span><span class="font-semibold text-[#0b5a49]">Jadwal Asesmen</span></nav>
            <h1 class="mt-1 text-3xl font-bold text-slate-900">Jadwal Asesmen</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola dan pantau jadwal sertifikasi Anda.</p>
        </div>
        <div class="shrink-0 self-start inline-flex items-center gap-2 rounded-xl bg-[#0b5a49] text-white px-4 py-2 text-xs font-semibold shadow-sm">
            <svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['clock'] !!}</svg>
            <span data-today>{{ now()->locale('id')->translatedFormat('l, d M Y') }}</span>
        </div>
    </div>

    {{-- Tab --}}
    <div class="mt-6 flex flex-wrap gap-2" role="tablist">
        @foreach ([['hari', 'Jadwal Hari Ini', $hariIni->count()], ['mendatang', 'Jadwal Mendatang', $mendatang->count()], ['semua', 'Semua Jadwal', $jadwal->count()]] as [$key, $lbl, $cnt])
            <button type="button" role="tab" data-tab="{{ $key }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                    class="cursor-pointer rounded-lg px-3.5 py-1.5 text-[11px] font-semibold bg-slate-200/70 text-slate-600 hover:bg-slate-200 transition-colors aria-selected:bg-[#0b5a49] aria-selected:text-white">{{ $lbl }} ({{ $cnt }})</button>
        @endforeach
    </div>
    <hr class="mt-5 border-slate-200">

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_280px] gap-6 items-start">

        {{-- ============ Kolom utama ============ --}}
        <div class="min-w-0 space-y-8">
            <section data-sec="daftar">
                @foreach ($grup as $bulan => $items)
                    @php $b = Carbon::createFromFormat('Y-m', $bulan)->locale('id'); @endphp
                    <div data-month class="{{ $loop->first ? '' : 'mt-8' }}">
                        <div class="flex items-center justify-between">
                            <h2 class="text-[11px] font-bold uppercase tracking-wider text-slate-400">{{ $b->isSameMonth(now()) ? 'Bulan Ini' : $b->translatedFormat('F') }}</h2>
                            <span class="text-xs font-semibold text-[#0b5a49]">{{ $b->translatedFormat('F Y') }}</span>
                        </div>
                        <div class="mt-3 space-y-4">
                            @foreach ($items as $j)
                                @php
                                    $d = $tgl($j->tanggal); $isToday = $d->isToday();
                                    $jn = $normJenis($j->jenis ?? null); $w = $warna[$jn];
                                    $warn = ($j->catatan_tone ?? 'info') === 'warning';
                                @endphp
                                <article data-item data-when="{{ $isToday ? 'hari' : 'mendatang' }}" data-date="{{ $d->format('Y-m-d') }}"
                                         class="jd-in {{ $card }} relative overflow-hidden p-5 sm:p-6 transition-shadow hover:shadow-md" style="animation-delay:{{ $n++ * .1 }}s">
                                    <span class="absolute inset-x-0 top-0 h-[3px] {{ $w['bar'] }}"></span>
                                    <div class="flex gap-4">
                                        <span class="shrink-0 w-11 h-11 rounded-xl border {{ $w['icon'] }} grid place-items-center"><svg class="w-5 h-5" {!! $a !!}>{!! $ic['monitor'] !!}</svg></span>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-wrap items-center gap-2 text-[10px] font-semibold">
                                                @if ($isToday)
                                                    <span class="px-2 py-0.5 rounded border border-amber-200 bg-amber-50 text-amber-700">Hari Ini</span>
                                                @else
                                                    <span class="px-2 py-0.5 rounded border border-slate-200 bg-slate-50 text-slate-600">Mendatang</span>
                                                @endif
                                                <span class="px-2 py-0.5 rounded border {{ $w['badge'] }}">{{ $jn }}</span>
                                            </div>
                                            <h3 class="mt-1.5 text-lg sm:text-xl font-bold text-slate-900 leading-snug">{{ $j->judul }}</h3>
                                            @if (! empty($j->bidang))<p class="text-xs text-slate-500">{{ $j->bidang }}</p>@endif
                                        </div>
                                        <div class="shrink-0 text-right leading-none">
                                            <p class="text-3xl font-bold {{ $w['tgl'] }}">{{ $d->format('d') }}</p>
                                            <p class="mt-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400">{{ strtoupper($d->translatedFormat('M')) }} {{ $d->format('Y') }}</p>
                                        </div>
                                    </div>

                                    <div class="mt-5 pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-xs text-slate-600">
                                        <p class="flex items-center gap-2 font-semibold text-slate-800"><svg class="w-4 h-4 shrink-0 text-slate-400" {!! $a !!}>{!! $ic['clock'] !!}</svg>Mulai {{ $j->waktu_mulai }} WIB</p>
                                        <p class="flex items-center gap-2"><svg class="w-4 h-4 shrink-0 text-slate-400" {!! $a !!}>{!! $ic['user'] !!}</svg>Asesor: {{ $j->asesor }}</p>
                                    </div>

                                    <div class="mt-4 pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                        @if (! empty($j->catatan))
                                            @if ($warn)
                                                <p class="flex items-center gap-2 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-[11px] font-semibold text-amber-800 sm:max-w-[16rem]"><svg class="w-4 h-4 shrink-0 text-amber-500" {!! $a !!}>{!! $ic['warn'] !!}</svg>{{ $j->catatan }}</p>
                                            @else
                                                <p class="text-[11px] text-slate-400 sm:max-w-[16rem]">{{ $j->catatan }}</p>
                                            @endif
                                        @else
                                            <span></span>
                                        @endif
                                        <button type="button" data-unduh-kartu data-filename="Kartu-Registrasi-{{ $j->no_registrasi ?? 'Asesi' }}" class="{{ $btnP }} sm:w-52 text-center"><svg class="w-3.5 h-3.5 shrink-0" {!! $a !!}>{!! $ic['down'] !!}</svg>Unduh Kartu Registrasi</button>
                                        <div data-kartu-src class="hidden" aria-hidden="true">
                                            @include('asesi.skema._kartu-registrasi', [
                                                'nama'  => $j->nama_asesi ?? auth()->user()?->name ?? 'Nama Asesi',
                                                'skema' => $j->judul,
                                                'noReg' => $j->no_registrasi ?? '-',
                                            ])
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                <div data-empty class="{{ $jadwal->isEmpty() ? '' : 'hidden' }} rounded-xl border border-dashed border-slate-300 px-4 py-10 text-center text-xs text-slate-500">Belum ada jadwal asesmen.</div>
            </section>
        </div>

        {{-- ============ Kolom kanan ============ --}}
        <aside class="space-y-5 lg:sticky lg:top-6">
            <div class="{{ $card }} p-5 jd-in" style="animation-delay:.15s">
                <div class="flex items-center justify-between">
                    <h2 data-cal-title class="text-sm font-bold text-slate-900"></h2>
                    <div class="flex gap-1">
                        <button type="button" data-cal-prev aria-label="Bulan sebelumnya" class="cursor-pointer w-7 h-7 rounded-lg grid place-items-center text-slate-500 hover:bg-slate-100"><svg class="w-4 h-4" {!! $a !!}>{!! $ic['left'] !!}</svg></button>
                        <button type="button" data-cal-next aria-label="Bulan berikutnya" class="cursor-pointer w-7 h-7 rounded-lg grid place-items-center text-slate-500 hover:bg-slate-100"><svg class="w-4 h-4" {!! $a !!}>{!! $ic['right'] !!}</svg></button>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-7 text-center text-[10px] font-semibold text-slate-400">
                    @foreach (['MIN', 'SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB'] as $h)<span>{{ $h }}</span>@endforeach
                </div>
                <div data-cal-grid class="mt-2 grid grid-cols-7 gap-y-1.5 text-center"></div>
                <div class="mt-4 pt-4 border-t border-slate-100 space-y-2 text-[11px] text-slate-600">
                    @foreach ($warna as $nama => $w)
                        <p class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full {{ $w['dot'] }}"></span>{{ $nama }}</p>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-blue-100 bg-blue-50/60 p-5 jd-in" style="animation-delay:.25s">
                <h2 class="flex items-center gap-2.5 text-sm font-bold text-slate-900"><span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 grid place-items-center"><svg class="w-4 h-4" {!! $a !!}>{!! $ic['clock'] !!}</svg></span>Persiapan Asesmen</h2>
                <p class="mt-3 text-xs text-slate-600 leading-relaxed">Pastikan Anda membawa identitas diri yang valid dan mematuhi tata tertib LSP:</p>
                <ul class="mt-3 space-y-2.5">
                    @foreach ($persiapan as $p)
                        <li class="flex gap-2.5 text-xs text-slate-700 leading-relaxed"><svg class="w-4 h-4 shrink-0 mt-0.5 text-emerald-600" stroke-width="2.4" {!! $a !!}>{!! $ic['check'] !!}</svg>{{ $p }}</li>
                    @endforeach
                </ul>
            </div>
        </aside>
    </div>

    {{-- ============ Pop up agenda per tanggal ============ --}}
    <div id="agenda-modal" class="hidden fixed inset-0 z-50 items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="agenda-title">
        <div data-close class="jd-fade absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
        <div class="jd-pop relative w-full max-w-md max-h-[85vh] overflow-y-auto rounded-2xl bg-white shadow-2xl border border-slate-200">
            <div class="flex items-start justify-between gap-3 px-5 pt-5">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Agenda Asesmen</p>
                    <h2 id="agenda-title" class="mt-0.5 text-base font-bold text-slate-900"></h2>
                </div>
                <button type="button" data-close aria-label="Tutup" class="cursor-pointer shrink-0 w-8 h-8 rounded-lg grid place-items-center text-slate-500 hover:bg-slate-100"><svg class="w-4 h-4" {!! $a !!}>{!! $ic['x'] !!}</svg></button>
            </div>
            <div id="agenda-body" class="px-5 py-4 space-y-3"></div>
            <div class="px-5 pb-5"><button type="button" data-close class="cursor-pointer w-full rounded-lg border border-slate-200 bg-white hover:bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700 transition-colors">Tutup</button></div>
        </div>
    </div>
</div>

<script>
    (() => {
        const root = document.getElementById('jadwal-page');
        if (!root) return;

        // Tanggal real time di pojok kanan atas
        const H = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const B = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const today = root.querySelector('[data-today]');
        const tick = () => { const d = new Date(); today.textContent = `${H[d.getDay()]}, ${String(d.getDate()).padStart(2, '0')} ${B[d.getMonth()]} ${d.getFullYear()}`; };
        tick(); setInterval(tick, 30000);

        // Tab: hari ini | mendatang | semua
        const tabs = [...root.querySelectorAll('[data-tab]')];
        const items = [...root.querySelectorAll('[data-item]')];
        const months = [...root.querySelectorAll('[data-month]')];
        const empty = root.querySelector('[data-empty]');
        const pesan = { hari: 'Tidak ada jadwal asesmen hari ini.', mendatang: 'Belum ada jadwal asesmen mendatang.', semua: 'Belum ada jadwal asesmen.' };
        const show = (t) => {
            tabs.forEach((b) => b.setAttribute('aria-selected', b.dataset.tab === t ? 'true' : 'false'));
            let total = 0;
            items.forEach((a) => {
                const on = t === 'semua' || a.dataset.when === t;
                a.classList.toggle('hidden', !on);
                if (on) { total++; a.classList.remove('jd-in'); void a.offsetWidth; a.classList.add('jd-in'); }
            });
            months.forEach((m) => m.classList.toggle('hidden', !m.querySelector('[data-item]:not(.hidden)')));
            empty.textContent = pesan[t];
            empty.classList.toggle('hidden', total > 0);
        };
        tabs.forEach((b) => b.addEventListener('click', () => show(b.dataset.tab)));
        show('hari');


        // Unduh Kartu Registrasi: kartu (SVG) diubah ke gambar PNG di browser lalu diunduh (sama seperti halaman skema)
        root.querySelectorAll('[data-unduh-kartu]').forEach((btn) => btn.addEventListener('click', () => {
            const src = btn.closest('article')?.querySelector('[data-kartu-src] svg');
            if (!src) return;
            const W = 1920, H = 1200;
            const svg = src.cloneNode(true);
            svg.setAttribute('width', W); svg.setAttribute('height', H);
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
            img.src = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(new XMLSerializer().serializeToString(svg));
        }));
        // Kalender
        const EV = @json($events);
        const W  = @json($warna);
        const byDay = {};
        EV.forEach((e) => { (byDay[e.d] = byDay[e.d] || []).push(e); });
        const BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const title = root.querySelector('[data-cal-title]');
        const grid = root.querySelector('[data-cal-grid]');
        const pad = (n) => String(n).padStart(2, '0');
        const key = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
        let cur = new Date(); cur = new Date(cur.getFullYear(), cur.getMonth(), 1);

        // Pop up
        const modal = document.getElementById('agenda-modal');
        const mTitle = document.getElementById('agenda-title');
        const mBody = document.getElementById('agenda-body');
        let lastFocus = null;
        const el = (tag, cls, text) => { const n = document.createElement(tag); if (cls) n.className = cls; if (text != null) n.textContent = text; return n; };
        const open = (d) => {
            const list = (byDay[key(d)] || []).slice().sort((x, y) => String(x.waktu).localeCompare(String(y.waktu)));
            mTitle.textContent = d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            mBody.innerHTML = '';
            if (!list.length) {
                mBody.appendChild(el('div', 'rounded-xl border border-dashed border-slate-300 px-4 py-8 text-center text-xs text-slate-500', 'Tidak ada agenda asesmen pada tanggal ini.'));
            }
            list.forEach((e) => {
                const w = W[e.jenis] || W.Klaster;
                const row = el('div', 'flex gap-3 rounded-xl border border-slate-200 p-3.5');
                row.appendChild(el('span', 'shrink-0 self-start rounded-lg px-2.5 py-1.5 text-xs font-bold text-white ' + w.dot, e.waktu || '-'));
                const info = el('div', 'min-w-0 flex-1');
                info.appendChild(el('span', 'inline-block px-2 py-0.5 rounded border text-[10px] font-semibold ' + w.badge, e.jenis));
                info.appendChild(el('p', 'mt-1.5 text-sm font-bold text-slate-900 leading-snug', e.judul));
                if (e.bidang) info.appendChild(el('p', 'text-[11px] text-slate-500', e.bidang));
                if (e.asesor) info.appendChild(el('p', 'mt-1.5 text-[11px] text-slate-600', 'Asesor: ' + e.asesor));
                row.appendChild(info);
                mBody.appendChild(row);
            });
            lastFocus = document.activeElement;
            modal.classList.remove('hidden'); modal.classList.add('flex');
            modal.querySelector('button[data-close]').focus();
        };
        const close = () => {
            modal.classList.add('hidden'); modal.classList.remove('flex');
            if (lastFocus && lastFocus.focus) lastFocus.focus();
        };
        modal.querySelectorAll('[data-close]').forEach((x) => x.addEventListener('click', close));
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !modal.classList.contains('hidden')) close(); });

        function draw() {
            title.textContent = `${BULAN[cur.getMonth()]} ${cur.getFullYear()}`;
            grid.innerHTML = '';
            const start = new Date(cur); start.setDate(1 - cur.getDay());
            const todayKey = key(new Date());
            for (let i = 0; i < 42; i++) {
                const d = new Date(start); d.setDate(start.getDate() + i);
                if (i === 35 && d.getMonth() !== cur.getMonth()) break;
                const k = key(d), evs = byDay[k], out = d.getMonth() !== cur.getMonth();
                const b = document.createElement('button');
                b.type = 'button';
                b.textContent = d.getDate();
                b.setAttribute('aria-label', d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) + (evs ? `, ${evs.length} agenda` : ', tidak ada agenda'));
                let cls = 'cursor-pointer relative w-9 h-9 mx-auto grid place-items-center rounded-lg text-xs transition-colors ';
                if (evs) cls += (W[evs[0].jenis] || W.Klaster).fill + ' font-semibold';
                else if (k === todayKey) cls += 'font-bold text-emerald-700 ring-2 ring-emerald-200 hover:bg-emerald-50';
                else cls += (out ? 'text-slate-300' : 'text-slate-600') + ' hover:bg-slate-100';
                b.className = cls;
                if (evs && evs.length > 1) b.appendChild(el('span', 'absolute -top-1 -right-1 min-w-[14px] h-[14px] px-0.5 rounded-full bg-white border border-slate-300 text-slate-700 text-[9px] font-bold leading-[12px]', evs.length));
                b.addEventListener('click', () => open(new Date(d)));
                grid.appendChild(b);
            }
        }
        root.querySelector('[data-cal-prev]').addEventListener('click', () => { cur = new Date(cur.getFullYear(), cur.getMonth() - 1, 1); draw(); });
        root.querySelector('[data-cal-next]').addEventListener('click', () => { cur = new Date(cur.getFullYear(), cur.getMonth() + 1, 1); draw(); });
        draw();
    })();
</script>
</x-layouts.app>