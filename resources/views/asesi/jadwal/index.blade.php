{{-- resources/views/jadwal/index.blade.php --}}
{{--
  KONTRAK DATA (dikirim controller saat backend jadi). Selama belum dikirim, halaman memakai DATA DUMMY di bawah.

  $mendatang : koleksi jadwal yang akan datang
      tanggal (Carbon/date), jenis ('offline'|'online'), ikon (monitor|image), skema, judul, subjudul, jam,
      lokasi (offline), link_online (online), asesor, penempatan (opsional),
      catatan, catatan_tone ('warning'|'info'), url_kartu
  $riwayat   : koleksi jadwal selesai
      tanggal, hasil, skema, judul, asesor, nilai, no_reg, url_hasil, url_sertifikat
--}}
@php
    use Carbon\Carbon;

    // ===================== DATA DUMMY (hapus blok ini bila backend sudah mengirim data) =====================
    $mendatang ??= collect([
        (object) ['tanggal' => now()->addDays(3), 'jenis' => 'offline', 'ikon' => 'monitor', 'skema' => 'Skema KKNI Level II',
                  'judul' => 'Rekayasa Perangkat Lunak', 'subjudul' => 'Uji Praktik Demonstrasi - Junior Web Developer',
                  'jam' => '08:00 - 12:00 WIB (Sesi Pagi)', 'lokasi' => 'Lab Komputer 1 (Gedung A, Lt. 2)',
                  'asesor' => 'Bpk. Dr. Ir. Budi Santoso, M.Kom', 'penempatan' => 'Meja PX-14 (TUK Mandiri)',
                  'catatan' => 'Wajib hadir 30 menit sebelum sesi dimulai', 'catatan_tone' => 'warning'],
        (object) ['tanggal' => now()->addDays(10), 'jenis' => 'online', 'ikon' => 'image', 'skema' => 'Klaster Multimedia',
                  'judul' => 'Desain Grafis', 'subjudul' => 'Asesmen Portofolio & Wawancara Daring',
                  'jam' => '13:00 - 15:00 WIB (Sesi Siang)', 'link_online' => '#',
                  'asesor' => 'Ibu Siti Aminah, S.Ds (Lisensi Asesor BNSP)',
                  'catatan' => 'Siapkan file portofolio karya asli format PDF/Behance.', 'catatan_tone' => 'info'],
    ]);
    $riwayat ??= collect([
        (object) ['tanggal' => now()->subDays(75), 'hasil' => 'Kompeten (Lulus)', 'skema' => 'Skema KKNI Level II',
                  'judul' => 'Teknik Komputer Jaringan', 'asesor' => 'Bpk. Hendra Gunawan, S.T.', 'nilai' => '92.5',
                  'no_reg' => 'BNSP-LSP-2024-09812'],
    ]);
    // =========================================================================================================

    $a = 'fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"';
    $ic = [
        'monitor' => '<rect width="20" height="14" x="2" y="3" rx="2"/><path d="M8 21h8M12 17v4"/>',
        'image'   => '<rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.1-3.1a2 2 0 0 0-2.8 0L6 21"/>',
        'clock'   => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'pin'     => '<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'user'    => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
        'hash'    => '<path d="M4 9h16M4 15h16M10 3 8 21M16 3l-2 18"/>',
        'video'   => '<path d="m16 10 5-3v10l-5-3z"/><rect width="14" height="12" x="2" y="6" rx="2"/>',
        'ext'     => '<path d="M15 3h6v6M10 14 21 3M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',
        'warn'    => '<path d="m21.7 18-8-14a2 2 0 0 0-3.4 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.7-3zM12 9v4M12 17h.01"/>',
        'down'    => '<path d="M12 3v12m0 0-4-4m4 4 4-4M4 21h16"/>',
        'checkc'  => '<circle cx="12" cy="12" r="9"/><path d="m8.5 12.5 2.5 2.5 4.5-5"/>',
        'check'   => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
        'left'    => '<path d="m15 18-6-6 6-6"/>',
        'right'   => '<path d="m9 18 6-6-6-6"/>',
    ];
    $card  = 'bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,.04)]';
    $btnP  = 'inline-flex items-center justify-center gap-2 rounded-lg bg-[#0b5a49] hover:bg-[#074237] px-5 py-2.5 text-xs font-semibold text-white transition-colors';
    $tgl   = fn ($d) => Carbon::parse($d)->locale('id');
    $grup  = $mendatang->sortBy('tanggal')->groupBy(fn ($j) => Carbon::parse($j->tanggal)->format('Y-m'));
    $events = $mendatang->map(fn ($j) => ['d' => Carbon::parse($j->tanggal)->format('Y-m-d'), 't' => $j->jenis ?? 'offline'])->values();
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
    .jd-in { animation: jdIn .7s cubic-bezier(.22, 1, .36, 1) backwards; }
    .jd-flash { animation: jdFlash 1.2s ease; }
    @media (prefers-reduced-motion: reduce) { .jd-in, .jd-flash { animation: none; } }
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
        @foreach ([['mendatang', 'Jadwal Mendatang', $mendatang->count()], ['semua', 'Semua Jadwal', $mendatang->count() + $riwayat->count()], ['riwayat', 'Riwayat Selesai', $riwayat->count()]] as [$key, $lbl, $cnt])
            <button type="button" role="tab" data-tab="{{ $key }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                    class="rounded-lg px-3.5 py-1.5 text-[11px] font-semibold bg-slate-200/70 text-slate-600 hover:bg-slate-200 transition-colors aria-selected:bg-[#0b5a49] aria-selected:text-white">{{ $lbl }} ({{ $cnt }})</button>
        @endforeach
    </div>
    <hr class="mt-5 border-slate-200">

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_280px] gap-6 items-start">

        {{-- ============ Kolom utama ============ --}}
        <div class="min-w-0 space-y-8">

            {{-- Mendatang --}}
            <section data-sec="mendatang">
                @forelse ($grup as $bulan => $items)
                    @php $b = Carbon::createFromFormat('Y-m', $bulan)->locale('id'); @endphp
                    <div class="flex items-center justify-between {{ $loop->first ? '' : 'mt-8' }}">
                        <h2 class="text-[11px] font-bold uppercase tracking-wider text-slate-400">{{ $b->isSameMonth(now()) ? 'Bulan Ini' : $b->translatedFormat('F') }}</h2>
                        <span class="text-xs font-semibold text-[#0b5a49]">{{ $b->translatedFormat('F Y') }}</span>
                    </div>
                    <div class="mt-3 space-y-4">
                        @foreach ($items as $j)
                            @php $d = $tgl($j->tanggal); $online = ($j->jenis ?? 'offline') === 'online'; $warn = ($j->catatan_tone ?? 'info') === 'warning'; @endphp
                            <article data-date="{{ $d->format('Y-m-d') }}" class="jd-in {{ $card }} relative overflow-hidden p-5 sm:p-6 transition-shadow hover:shadow-md" style="animation-delay:{{ $n * .1 }}s">
                                @if ($n === 0)<span class="absolute inset-x-0 top-0 h-[3px] bg-emerald-500"></span>@endif
                                @php $n++; @endphp
                                <div class="flex gap-4">
                                    <span class="shrink-0 w-11 h-11 rounded-xl border border-emerald-100 bg-emerald-50 text-[#0b5a49] grid place-items-center"><svg class="w-5 h-5" {!! $a !!}>{!! $ic[$j->ikon ?? 'monitor'] ?? $ic['monitor'] !!}</svg></span>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2 text-[10px] font-semibold">
                                            <span class="px-2 py-0.5 rounded border border-emerald-200 bg-emerald-50 text-emerald-700">Mendatang</span>
                                            <span class="font-medium text-slate-400">{{ $j->skema }}</span>
                                        </div>
                                        <h3 class="mt-1.5 text-lg sm:text-xl font-bold text-slate-900 leading-snug">{{ $j->judul }}</h3>
                                        <p class="text-xs text-slate-500">{{ $j->subjudul }}</p>
                                    </div>
                                    <div class="shrink-0 text-right leading-none">
                                        <p class="text-3xl font-bold text-[#0b5a49]">{{ $d->format('d') }}</p>
                                        <p class="mt-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400">{{ strtoupper($d->translatedFormat('M')) }} {{ $d->format('Y') }}</p>
                                    </div>
                                </div>

                                <div class="mt-5 pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-xs text-slate-600">
                                    <p class="flex items-center gap-2 font-semibold text-slate-800"><svg class="w-4 h-4 shrink-0 text-slate-400" {!! $a !!}>{!! $ic['clock'] !!}</svg>{{ $j->jam }}</p>
                                    @if ($online)
                                        <a href="{{ $j->link_online ?? '#' }}" target="_blank" rel="noopener" class="flex items-center gap-2 font-semibold text-[#0b5a49] hover:underline"><svg class="w-4 h-4 shrink-0" {!! $a !!}>{!! $ic['video'] !!}</svg>Zoom Meeting Link <svg class="w-3 h-3" {!! $a !!}>{!! $ic['ext'] !!}</svg></a>
                                    @else
                                        <p class="flex items-center gap-2"><svg class="w-4 h-4 shrink-0 text-slate-400" {!! $a !!}>{!! $ic['pin'] !!}</svg>{{ $j->lokasi }}</p>
                                    @endif
                                    <p class="flex items-center gap-2"><svg class="w-4 h-4 shrink-0 text-slate-400" {!! $a !!}>{!! $ic['user'] !!}</svg>{{ $online ? 'Asesor Penguji' : 'Asesor' }}: {{ $j->asesor }}</p>
                                    @if (! empty($j->penempatan))
                                        <p class="flex items-center gap-2"><svg class="w-4 h-4 shrink-0 text-slate-400" {!! $a !!}>{!! $ic['hash'] !!}</svg>Penempatan: {{ $j->penempatan }}</p>
                                    @endif
                                </div>

                                <div class="mt-4 pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    @if ($warn)
                                        <p class="flex items-center gap-2 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-[11px] font-semibold text-amber-800 sm:max-w-[16rem]"><svg class="w-4 h-4 shrink-0 text-amber-500" {!! $a !!}>{!! $ic['warn'] !!}</svg>{{ $j->catatan }}</p>
                                    @else
                                        <p class="text-[11px] text-slate-400 sm:max-w-[16rem]">{{ $j->catatan }}</p>
                                    @endif
                                    <a href="{{ $j->url_kartu ?? '#' }}" class="{{ $btnP }} sm:w-36 text-center"><svg class="w-3.5 h-3.5 shrink-0" {!! $a !!}>{!! $ic['down'] !!}</svg>Unduh Kartu Ujian</a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-slate-300 px-4 py-10 text-center text-xs text-slate-500">Belum ada jadwal asesmen mendatang.</div>
                @endforelse
            </section>

            {{-- Riwayat --}}
            <section data-sec="riwayat" class="jd-in" style="animation-delay:.25s">
                <div class="flex items-center justify-between">
                    <h2 class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Riwayat Asesmen</h2>
                    <span class="text-[11px] text-slate-400">{{ $riwayat->count() }} Sesi Selesai</span>
                </div>
                <div class="mt-3 space-y-4">
                    @forelse ($riwayat as $r)
                        @php $d = $tgl($r->tanggal); @endphp
                        <article class="{{ $card }} p-5 sm:p-6">
                            <div class="flex gap-4">
                                <span class="shrink-0 w-11 h-11 rounded-xl bg-blue-50 text-blue-600 grid place-items-center"><svg class="w-5 h-5" {!! $a !!}>{!! $ic['checkc'] !!}</svg></span>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2 text-[10px] font-semibold">
                                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600">Selesai</span>
                                        <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">{{ $r->hasil }}</span>
                                        <span class="font-medium text-slate-400">{{ $r->skema }}</span>
                                    </div>
                                    <h3 class="mt-1.5 text-lg font-bold text-slate-900">{{ $r->judul }}</h3>
                                    <p class="text-xs text-slate-500">Asesor: {{ $r->asesor }} • Nilai: {{ $r->nilai }}</p>
                                </div>
                                <div class="shrink-0 text-right leading-none">
                                    <p class="text-2xl font-bold text-slate-400">{{ $d->format('d') }}</p>
                                    <p class="mt-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400">{{ strtoupper($d->translatedFormat('M')) }} {{ $d->format('Y') }}</p>
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs">
                                <span class="font-mono text-[10px] text-slate-400">No. Reg: {{ $r->no_reg }}</span>
                                <span class="flex items-center gap-4 font-semibold text-[#0b5a49]">
                                    <a href="{{ $r->url_hasil ?? '#' }}" class="hover:underline">Lihat Hasil Penilaian</a>
                                    <a href="{{ $r->url_sertifikat ?? '#' }}" class="hover:underline">Unduh Sertifikat →</a>
                                </span>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-300 px-4 py-8 text-center text-xs text-slate-500">Belum ada riwayat asesmen.</div>
                    @endforelse
                </div>
            </section>
        </div>

        {{-- ============ Kolom kanan ============ --}}
        <aside class="space-y-5 lg:sticky lg:top-6">
            <div class="{{ $card }} p-5 jd-in" style="animation-delay:.15s">
                <div class="flex items-center justify-between">
                    <h2 data-cal-title class="text-sm font-bold text-slate-900"></h2>
                    <div class="flex gap-1">
                        <button type="button" data-cal-prev aria-label="Bulan sebelumnya" class="w-7 h-7 rounded-lg grid place-items-center text-slate-500 hover:bg-slate-100"><svg class="w-4 h-4" {!! $a !!}>{!! $ic['left'] !!}</svg></button>
                        <button type="button" data-cal-next aria-label="Bulan berikutnya" class="w-7 h-7 rounded-lg grid place-items-center text-slate-500 hover:bg-slate-100"><svg class="w-4 h-4" {!! $a !!}>{!! $ic['right'] !!}</svg></button>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-7 text-center text-[10px] font-semibold text-slate-400">
                    @foreach (['MIN', 'SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB'] as $h)<span>{{ $h }}</span>@endforeach
                </div>
                <div data-cal-grid class="mt-2 grid grid-cols-7 gap-y-1.5 text-center"></div>
                <div class="mt-4 pt-4 border-t border-slate-100 space-y-2 text-[11px] text-slate-600">
                    <p class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-[#0b5a49]"></span>Jadwal Anda (TUK Lab Fisik)</p>
                    <p class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full border-2 border-emerald-500"></span>Asesmen Online / Wawancara Zoom</p>
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
                <a href="{{ route('placeholder', ['title' => 'Panduan BNSP']) }}" class="mt-4 inline-block text-xs font-semibold text-[#0b5a49] hover:underline">Pelajari Panduan Lengkap BNSP ›</a>
            </div>
        </aside>
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

        // Tab
        const tabs = [...root.querySelectorAll('[data-tab]')];
        const secs = [...root.querySelectorAll('[data-sec]')];
        const show = (t) => {
            tabs.forEach((b) => b.setAttribute('aria-selected', b.dataset.tab === t ? 'true' : 'false'));
            secs.forEach((s) => {
                const on = t === 'semua' || s.dataset.sec === t;
                s.classList.toggle('hidden', !on);
                if (on) { s.classList.remove('jd-in'); void s.offsetWidth; s.classList.add('jd-in'); }
            });
        };
        tabs.forEach((b) => b.addEventListener('click', () => show(b.dataset.tab)));

        // Kalender (bulan berjalan, bisa dinavigasi)
        const EV = @json($events);
        const map = Object.fromEntries(EV.map((e) => [e.d, e.t]));
        const BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const title = root.querySelector('[data-cal-title]');
        const grid = root.querySelector('[data-cal-grid]');
        const pad = (n) => String(n).padStart(2, '0');
        const key = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
        let cur = new Date(); cur = new Date(cur.getFullYear(), cur.getMonth(), 1);

        function draw() {
            title.textContent = `${BULAN[cur.getMonth()]} ${cur.getFullYear()}`;
            grid.innerHTML = '';
            const start = new Date(cur); start.setDate(1 - cur.getDay());
            const todayKey = key(new Date());
            for (let i = 0; i < 42; i++) {
                const d = new Date(start); d.setDate(start.getDate() + i);
                if (i === 35 && d.getMonth() !== cur.getMonth()) break;
                const k = key(d), t = map[k], out = d.getMonth() !== cur.getMonth();
                const el = document.createElement(t ? 'button' : 'span');
                el.textContent = d.getDate();
                let cls = 'w-9 h-9 mx-auto grid place-items-center rounded-lg text-xs ';
                if (t === 'online') cls += 'border-2 border-emerald-500 text-emerald-700 font-semibold';
                else if (t) cls += 'bg-[#0b5a49] text-white font-semibold';
                else if (k === todayKey) cls += 'font-bold text-emerald-700';
                else cls += out ? 'text-slate-300' : 'text-slate-600';
                el.className = cls;
                if (t) {
                    el.type = 'button';
                    el.addEventListener('click', () => {
                        const card = root.querySelector(`[data-date="${k}"]`);
                        if (!card || card.offsetParent === null) return;
                        card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        card.classList.remove('jd-flash'); void card.offsetWidth; card.classList.add('jd-flash');
                    });
                }
                grid.appendChild(el);
            }
        }
        root.querySelector('[data-cal-prev]').addEventListener('click', () => { cur = new Date(cur.getFullYear(), cur.getMonth() - 1, 1); draw(); });
        root.querySelector('[data-cal-next]').addEventListener('click', () => { cur = new Date(cur.getFullYear(), cur.getMonth() + 1, 1); draw(); });
        draw();
    })();
</script>
</x-layouts.app>