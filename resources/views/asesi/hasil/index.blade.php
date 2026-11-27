{{-- resources/views/hasil/index.blade.php --}}
@php
    $a = 'fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"';
    $ic = [
        'bag'   => '<rect width="18" height="12" x="3" y="8" rx="2"/><path d="M8 8V6a4 4 0 0 1 8 0v2"/>',
        'ok'    => '<circle cx="12" cy="12" r="9"/><path d="m8.5 12.5 2.5 2.5 4.5-5"/>',
        'chart' => '<path d="M5 21V10M12 21V4M19 21v-8"/>',
        'award' => '<circle cx="12" cy="8" r="6"/><path d="M15.5 12.9 17 22l-5-3-5 3 1.5-9.1"/>',
        'pin'   => '<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'down'  => '<path d="M12 3v12m0 0-4-4m4 4 4-4M4 21h16"/>',
        'arrow' => '<path d="M5 12h14M12 5l7 7-7 7"/>',
        'check' => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
        'x'     => '<path d="M6 6l12 12M18 6 6 18"/>',
        'cal'   => '<rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
    ];
    $cards = [
        ['TOTAL SKEMA', $stat['total'] . ' Skema', $stat['lulus'] . ' Kompeten • ' . $stat['belum'] . ' Belum Kompeten', 'bag', '#eef2ff', 'bg-indigo-100 text-indigo-600', 'text-slate-900'],
        ['TINGKAT KELULUSAN', $stat['persen'] . '%', 'Tingkat Kompetensi Asesi', 'ok', '#ecfdf5', 'bg-emerald-100 text-emerald-600', 'text-emerald-700'],
        ['RATA-RATA SKOR', $stat['rata'], 'Nilai Gabungan Teori & Praktik', 'chart', '#eff6ff', 'bg-blue-100 text-blue-600', 'text-slate-900'],
        ['SERTIFIKAT TERBIT', $stat['sertifikat'] . ' Lisensi', $stat['menunggu'] > 0 ? $stat['menunggu'] . ' Hasil Menunggu Penerbitan' : 'Semua sertifikat sudah terbit', 'award', '#fffbeb', 'bg-amber-100 text-amber-600', 'text-slate-900'],
    ];
    $bar = ['green' => 'bg-emerald-700', 'amber' => 'bg-amber-500', 'red' => 'bg-rose-500'];
    $txt = ['green' => 'text-emerald-600', 'amber' => 'text-amber-600', 'red' => 'text-rose-600'];
    $btnO = 'cursor-pointer inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-800 transition-colors';
    $n = 0;
@endphp

<x-layouts.app :title="$title ?? 'Hasil & Kelulusan'">
<style>
    @keyframes hsIn { from { opacity: 0; transform: translateY(18px); } }
    @keyframes hsBar { from { width: 0; } }
    .hs-in { animation: hsIn .7s cubic-bezier(.22, 1, .36, 1) backwards; }
    .hs-bar { animation: hsBar 1.1s cubic-bezier(.22, 1, .36, 1) .3s backwards; }
    @media (prefers-reduced-motion: reduce) { .hs-in, .hs-bar { animation: none; } }
</style>

<div id="hasil-page" class="max-w-[1100px]">
    <nav class="text-[11px] text-slate-400 flex items-center gap-2" aria-label="Breadcrumb"><span>Portal Asesi</span><span>›</span><span class="font-semibold text-[#0b5a49]">Hasil & Status Kelulusan</span></nav>
    <h1 class="mt-2 text-3xl font-bold text-slate-900">Hasil & Kelulusan Asesmen</h1>
    <p class="mt-1.5 text-sm text-slate-500 max-w-xl leading-relaxed">Pantau daftar riwayat hasil uji kompetensi, status kelulusan BNSP, dan transkrip penilaian dari seluruh skema sertifikasi yang telah Anda ikuti.</p>

    {{-- Statistik --}}
    <section class="mt-6 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        @foreach ($cards as [$lbl, $val, $sub, $icon, $tint, $tile, $vc])
            <div class="hs-in rounded-2xl border border-slate-200/80 p-5 flex items-center justify-between gap-3 shadow-[0_1px_2px_rgba(15,23,42,.04)]" style="background:linear-gradient(135deg,#fff 40%,{{ $tint }});animation-delay:{{ $loop->index * .08 }}s">
                <div class="min-w-0">
                    <p class="text-[10px] font-semibold tracking-wide text-slate-500">{{ $lbl }}</p>
                    <p class="mt-1 text-2xl font-bold {{ $vc }}">{{ $val }}@if ($loop->index === 2) <span class="text-sm font-normal text-slate-400">/ 100</span>@endif</p>
                    <p class="text-[11px] text-slate-500 leading-snug">{{ $sub }}</p>
                </div>
                <span class="shrink-0 w-11 h-11 rounded-xl grid place-items-center {{ $tile }}"><svg class="w-5 h-5" {!! $a !!}>{!! $ic[$icon] !!}</svg></span>
            </div>
        @endforeach
    </section>

    {{-- Tab + filter --}}
    <div class="mt-7 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 border-b border-slate-200">
        <div class="flex gap-5 overflow-x-auto overflow-y-hidden [scrollbar-width:none] [&::-webkit-scrollbar]:hidden" role="tablist">
            @foreach ([['semua', 'Semua Hasil', $stat['total']], ['K', 'Kompeten (K)', $stat['lulus']], ['BK', 'Belum Kompeten (BK)', $stat['belum']]] as [$key, $lbl, $cnt])
                <button type="button" role="tab" data-tab="{{ $key }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                        class="cursor-pointer shrink-0 -mb-px pb-3 border-b-2 border-transparent text-[13px] font-medium text-slate-500 hover:text-slate-800 transition-colors aria-selected:border-[#0b5a49] aria-selected:text-[#0b5a49] aria-selected:font-semibold">
                    {{ $lbl }} @if (! is_null($cnt))<span class="ml-1 px-1.5 py-0.5 rounded-md bg-slate-100 text-[10px] text-slate-500">{{ $cnt }}</span>@endif
                </button>
            @endforeach
        </div>
        <div class="flex flex-wrap items-center gap-2 pb-3">
            {{-- Filter rentang tanggal uji: ikon kalender, klik untuk memilih tanggal mulai s/d selesai --}}
            <div class="relative" data-range>
                <input type="hidden" data-from><input type="hidden" data-to>
                <button type="button" data-range-btn aria-haspopup="dialog" aria-expanded="false" title="Filter rentang tanggal uji"
                        class="relative cursor-pointer w-9 h-9 grid place-items-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 hover:text-[#0b5a49] transition-colors data-[active=true]:border-[#0b5a49] data-[active=true]:bg-emerald-50 data-[active=true]:text-[#0b5a49]">
                    <svg class="w-4 h-4" {!! $a !!}>{!! $ic['cal'] !!}</svg>
                    <span data-range-dot class="hidden absolute -top-1 -right-1 w-2.5 h-2.5 rounded-full bg-[#0b5a49] ring-2 ring-white"></span>
                </button>
                <div data-range-pop role="dialog" aria-label="Pilih rentang tanggal uji" class="hidden absolute right-0 lg:right-auto lg:left-0 top-full mt-2 z-30 w-[288px] rounded-xl border border-slate-200 bg-white p-4 shadow-[0_12px_32px_rgba(15,23,42,.16)]">
                    <div class="flex items-center justify-between">
                        <button type="button" data-rp-prev aria-label="Bulan sebelumnya" class="cursor-pointer w-7 h-7 rounded-lg grid place-items-center text-slate-500 hover:bg-slate-100"><svg class="w-4 h-4" {!! $a !!}><path d="m15 18-6-6 6-6"/></svg></button>
                        <p data-rp-title class="text-sm font-bold text-slate-900"></p>
                        <button type="button" data-rp-next aria-label="Bulan berikutnya" class="cursor-pointer w-7 h-7 rounded-lg grid place-items-center text-slate-500 hover:bg-slate-100"><svg class="w-4 h-4" {!! $a !!}><path d="m9 18 6-6-6-6"/></svg></button>
                    </div>
                    <div class="mt-3 grid grid-cols-7 text-center text-[10px] font-semibold text-slate-400">@foreach (['MIN', 'SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB'] as $hh)<span>{{ $hh }}</span>@endforeach</div>
                    <div data-rp-grid class="mt-1.5 grid grid-cols-7 gap-y-1"></div>
                    <p data-rp-label class="mt-3 pt-3 border-t border-slate-100 text-[11px] font-semibold text-slate-600 text-center">Pilih tanggal mulai</p>
                    <div class="mt-3 flex gap-2">
                        <button type="button" data-rp-reset class="cursor-pointer flex-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-700 transition-colors">Reset</button>
                        <button type="button" data-rp-apply class="cursor-pointer flex-1 rounded-lg bg-[#0b5a49] hover:bg-[#074237] px-3 py-2 text-xs font-semibold text-white transition-colors">Terapkan</button>
                    </div>
                </div>
            </div>
            <select data-sort class="cursor-pointer rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-700">
                <option value="terbaru">Tanggal Uji Terkini</option>
                <option value="terlama">Tanggal Uji Terlama</option>
                <option value="skor">Skor Tertinggi</option>
            </select>
            <select data-jur class="cursor-pointer rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-700">
                <option value="">Semua Jurusan</option>
                @foreach ($jurusan as $j)<option value="{{ $j }}">{{ $j }}</option>@endforeach
            </select>
        </div>
    </div>

    {{-- Daftar hasil --}}
    <div data-list class="mt-6 space-y-5">
        @foreach ($hasil as $h)
            @php $ok = $h->status === 'K'; $d = $h->tanggal_uji->copy()->locale('id'); @endphp
            <article data-item data-status="{{ $h->status }}" data-date="{{ $d->format('Y-m-d') }}" data-ts="{{ $d->timestamp }}" data-skor="{{ $h->skor }}" data-jur="{{ $h->jurusan }}"
                     class="hs-in group rounded-2xl border overflow-hidden bg-white shadow-[0_1px_2px_rgba(15,23,42,.04)] transition-all duration-300 ease-out hover:-translate-y-1 {{ $ok ? 'border-emerald-300 hover:border-emerald-500 hover:shadow-[0_14px_30px_rgba(16,185,129,.18)]' : 'border-rose-300 hover:border-rose-500 hover:shadow-[0_14px_30px_rgba(244,63,94,.16)]' }}" style="animation-delay:{{ .3 + $n++ * .1 }}s">

                <div class="flex flex-wrap items-center justify-between gap-2 px-5 py-3 text-xs border-b {{ $ok ? 'border-slate-100' : 'border-rose-100 bg-rose-50/70' }}">
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                        <span class="px-2.5 py-1 rounded text-[10px] font-bold {{ $ok ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-700' }}">{{ $h->level }}</span>
                        <span class="text-slate-600">Kode: <b class="font-mono text-slate-900">{{ $h->kode }}</b></span>
                        <span class="text-slate-400">• Tanggal Uji: {{ $d->translatedFormat('d F Y') }}</span>
                    </div>
                    <span class="flex items-center gap-1.5 text-slate-500"><svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['pin'] !!}</svg>TUK: {{ $h->tuk }}</span>
                </div>

                <div class="p-5 grid grid-cols-1 lg:grid-cols-2 gap-5">
                    <div class="min-w-0">
                        <p class="text-[11px] font-bold uppercase tracking-wide {{ $ok ? 'text-slate-500' : 'text-rose-700' }}">{{ $h->jurusan }}</p>
                        <h2 class="mt-1 flex flex-wrap items-center gap-2 text-xl font-bold text-slate-900 leading-snug">{{ $h->nama }} @if ($h->terkini)<span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-600 text-[10px] font-semibold">Terkini</span>@endif</h2>
                        <div class="mt-4 flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 p-3">
                            <span class="shrink-0 w-10 h-10 rounded-full bg-slate-200 text-slate-500 text-xs font-bold grid place-items-center">{{ $h->inisial }}</span>
                            <div class="min-w-0"><p class="text-[10px] text-slate-400">Asesor Penguji</p><p class="text-sm font-bold text-slate-900">{{ $h->asesor }}</p><p class="font-mono text-[10px] text-slate-400">No. Reg: {{ $h->asesor_reg }}</p></div>
                        </div>
                        <div class="mt-3 rounded-xl border p-4 text-xs leading-relaxed {{ $ok ? ($h->terkini ? 'border-amber-200 bg-amber-50 text-amber-900' : 'border-slate-100 bg-slate-50 text-slate-600') : 'border-rose-200 bg-rose-50 text-rose-800' }}">
                            <p class="font-bold">Catatan Evaluasi Asesor:</p>
                            <p class="mt-1">"{{ \Illuminate\Support\Str::limit($h->catatan, 170) }}"</p>
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-4 flex flex-col sm:flex-row sm:items-start gap-4">
                        <div class="shrink-0 w-full sm:w-44 sm:self-start rounded-xl border bg-white px-3 py-4 text-center {{ $ok ? 'border-slate-200' : 'border-rose-200' }}">
                            <span class="inline-flex items-center justify-center gap-1 whitespace-nowrap rounded-full px-3 py-1 text-[10px] font-bold {{ $ok ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-600' }}"><svg class="w-3 h-3" stroke-width="2.6" {!! $a !!}>{!! $ic[$ok ? 'check' : 'x'] !!}</svg>{{ $ok ? 'KOMPETEN (K)' : 'BELUM KOMPETEN' }}</span>
                            <p class="mt-2 text-4xl font-bold {{ $ok ? 'text-emerald-800' : 'text-rose-600' }}">{{ round($h->skor) }}%</p>
                            <p class="text-[11px] text-slate-400">Total Skor Akhir</p>
                            <p class="text-[10px] {{ $ok ? 'text-slate-400' : 'text-rose-500' }}">{{ $ok ? 'Standar Lulus' : 'Ambang Batas' }}: 75.00</p>
                        </div>
                        <div class="flex-1 min-w-0 space-y-3 text-xs">
                            @foreach ([['Ujian Teori CBT (Bobot 30%)', $h->teori, $h->teori_info], [$ok ? 'Ujian Praktik / Demo (Bobot 70%)' : 'Ujian Praktik / Lab (Bobot 70%)', $h->praktik, $h->praktik_info]] as [$lbl, $v, $info])
                                <div>
                                    <div class="flex justify-between gap-2 font-semibold text-slate-800"><span>{{ $lbl }}</span><span><b>{{ $v }}</b> <span class="font-normal text-slate-400">/ 100</span></span></div>
                                    <div class="mt-1 h-2 rounded-full bg-slate-200 overflow-hidden"><div class="hs-bar h-full rounded-full {{ $bar[$info->tone] }}" style="width:{{ $v }}%"></div></div>
                                    <p class="mt-1 text-[10px] {{ $txt[$info->tone] }}">{{ $info->label }}</p>
                                </div>
                            @endforeach
                            <div class="pt-2 border-t border-slate-200 flex items-center justify-between gap-2">
                                <span class="text-slate-600">Status Unit Kompetensi:</span>
                                <span class="rounded-md px-2.5 py-1.5 text-[11px] font-semibold text-right {{ ! $ok ? 'bg-rose-50 text-rose-600' : ($h->unit_bk === 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-200/70 text-slate-700') }}">{{ $h->unit_k }} K / {{ $h->unit_bk }} BK ({{ $h->units->count() }} Unit {{ ! $ok ? 'Perlu Asesmen Ulang' : ($h->unit_bk === 0 ? 'Lengkap' : 'Diujikan') }})</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-5 py-3.5 border-t flex flex-col sm:flex-row sm:items-center gap-3 {{ $ok ? 'border-slate-100 bg-slate-50/60 sm:justify-between' : 'border-rose-100 sm:justify-end' }}">
                    @if ($ok)
                        <p class="flex items-center gap-2 text-xs font-semibold text-emerald-800"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            @if ($h->no_sertifikat)Sertifikat Resmi Terbit • No: <b class="font-mono">{{ $h->no_sertifikat }}</b> (Aktif s/d {{ $h->aktif_sampai }})@else Disahkan Pleno LSP • Sertifikat BNSP Dalam Proses Penerbitan @endif</p>
                    @endif
                    <div class="flex flex-wrap gap-2">
                        @if ($ok)<a href="{{ $h->url_sertifikat ?? '#' }}" class="{{ $btnO }}"><svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['down'] !!}</svg>Unduh Sertifikat (PDF)</a>@endif
                        <a href="{{ route('asesi.hasil.show', $h->id) }}" class="inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-xs font-semibold text-white transition-colors {{ $ok ? 'bg-[#0b5a49] hover:bg-[#074237]' : 'bg-rose-700 hover:bg-rose-800' }}">Lihat Detail Hasil & Transkrip <svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['arrow'] !!}</svg></a>
                    </div>
                </div>
            </article>
        @endforeach
        <div data-empty class="hidden rounded-xl border border-dashed border-slate-300 px-4 py-10 text-center text-xs text-slate-500">Tidak ada hasil yang cocok dengan filter.</div>
    </div>
</div>

<script>
    (() => {
        const root = document.getElementById('hasil-page');
        if (!root) return;
        const list = root.querySelector('[data-list]');
        const items = [...list.querySelectorAll('[data-item]')];
        const empty = list.querySelector('[data-empty]');
        const tabs = [...root.querySelectorAll('[data-tab]')];
        const sortEl = root.querySelector('[data-sort]'), jurEl = root.querySelector('[data-jur]');
        const from = root.querySelector('[data-from]'), to = root.querySelector('[data-to]');
        let tab = 'semua';

        function apply() {
            const jur = jurEl.value, s = sortEl.value;
            const sorted = [...items].sort((x, y) => s === 'skor' ? y.dataset.skor - x.dataset.skor : s === 'terlama' ? x.dataset.ts - y.dataset.ts : y.dataset.ts - x.dataset.ts);
            let shown = 0;
            sorted.forEach((el) => {
                const okTab = tab === 'semua' || tab === el.dataset.status;
                const okTgl = (!from.value || el.dataset.date >= from.value) && (!to.value || el.dataset.date <= to.value);
                const on = okTab && okTgl && (!jur || el.dataset.jur === jur);
                el.classList.toggle('hidden', !on);
                if (on) shown++;
                list.insertBefore(el, empty);
            });
            empty.classList.toggle('hidden', shown > 0);
        }
        tabs.forEach((b) => b.addEventListener('click', () => {
            tab = b.dataset.tab;
            tabs.forEach((x) => x.setAttribute('aria-selected', x === b ? 'true' : 'false'));
            apply();
        }));
        // ---- Kalender rentang tanggal ----
        const rng = root.querySelector('[data-range]'), btn = rng.querySelector('[data-range-btn]'), pop = rng.querySelector('[data-range-pop]');
        const rpTitle = pop.querySelector('[data-rp-title]'), rpGrid = pop.querySelector('[data-rp-grid]'), rpLabel = pop.querySelector('[data-rp-label]');
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
            btn.querySelector('[data-range-dot]').classList.toggle('hidden', !st);
            btn.title = st ? `Tanggal uji: ${fmt(from.value)}${from.value !== to.value ? ' – ' + fmt(to.value) : ''}` : 'Filter rentang tanggal uji';
            apply();
        };
        const tutup = () => { pop.classList.add('hidden'); btn.setAttribute('aria-expanded', 'false'); };
        btn.addEventListener('click', () => {
            const buka = pop.classList.contains('hidden');
            pop.classList.toggle('hidden', !buka); btn.setAttribute('aria-expanded', String(buka));
            if (buka) { st = from.value; en = to.value; if (st) { const [y, m] = st.split('-'); view = new Date(+y, m - 1, 1); } label(); gambar(); }
        });
        pop.querySelector('[data-rp-prev]').addEventListener('click', () => { view = new Date(view.getFullYear(), view.getMonth() - 1, 1); gambar(); });
        pop.querySelector('[data-rp-next]').addEventListener('click', () => { view = new Date(view.getFullYear(), view.getMonth() + 1, 1); gambar(); });
        pop.querySelector('[data-rp-reset]').addEventListener('click', () => { st = ''; en = ''; terapkan(); label(); gambar(); tutup(); });
        pop.querySelector('[data-rp-apply]').addEventListener('click', () => { terapkan(); tutup(); });
        pop.addEventListener('click', (e) => e.stopPropagation());   // grid digambar ulang saat klik; jangan dianggap klik di luar
        document.addEventListener('click', (e) => { if (!rng.contains(e.target)) tutup(); });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') tutup(); });
        sortEl.addEventListener('change', apply);
        jurEl.addEventListener('change', apply);
        apply();
    })();
</script>
</x-layouts.app>