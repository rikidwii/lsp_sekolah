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
    ];
    $cards = [
        ['TOTAL SKEMA DIUJI', $stat['total'] . ' Skema', $stat['lulus'] . ' Lulus • ' . $stat['belum'] . ' Perlu Asesmen Ulang', 'bag', '#eef2ff', 'bg-indigo-100 text-indigo-600', 'text-slate-900'],
        ['TINGKAT KELULUSAN', $stat['persen'] . '%', 'Tingkat Kompetensi Asesi', 'ok', '#ecfdf5', 'bg-emerald-100 text-emerald-600', 'text-emerald-700'],
        ['RATA-RATA SKOR', $stat['rata'], 'Nilai Gabungan Teori & Praktik', 'chart', '#eff6ff', 'bg-blue-100 text-blue-600', 'text-slate-900'],
        ['SERTIFIKAT TERBIT', $stat['sertifikat'] . ' Lisensi', $stat['berlaku'] ? 'Berlaku Aktif s/d ' . $stat['berlaku'] : 'Menunggu penerbitan', 'award', '#fffbeb', 'bg-amber-100 text-amber-600', 'text-slate-900'],
    ];
    $bar = ['green' => 'bg-emerald-700', 'amber' => 'bg-amber-500', 'red' => 'bg-rose-500'];
    $txt = ['green' => 'text-emerald-600', 'amber' => 'text-amber-600', 'red' => 'text-rose-600'];
    $btnO = 'inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-800 transition-colors';
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
        <div class="flex gap-5 overflow-x-auto" role="tablist">
            @foreach ([['semua', 'Semua Hasil', $stat['total']], ['K', 'Kompeten (K)', $stat['lulus']], ['BK', 'Belum Kompeten (BK)', $stat['belum']], ['tahun', 'Tahun ' . $tahun, null]] as [$key, $lbl, $cnt])
                <button type="button" role="tab" data-tab="{{ $key }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                        class="shrink-0 -mb-px pb-3 border-b-2 border-transparent text-[13px] font-medium text-slate-500 hover:text-slate-800 transition-colors aria-selected:border-[#0b5a49] aria-selected:text-[#0b5a49] aria-selected:font-semibold">
                    {{ $lbl }} @if (! is_null($cnt))<span class="ml-1 px-1.5 py-0.5 rounded-md bg-slate-100 text-[10px] text-slate-500">{{ $cnt }}</span>@endif
                </button>
            @endforeach
        </div>
        <div class="flex gap-2 pb-3">
            <select data-sort class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-700">
                <option value="terbaru">Urutkan: Tanggal Uji Terkini</option>
                <option value="terlama">Urutkan: Tanggal Uji Terlama</option>
                <option value="skor">Urutkan: Skor Tertinggi</option>
            </select>
            <select data-jur class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-700">
                <option value="">Semua Jurusan</option>
                @foreach ($jurusan as $j)<option value="{{ $j }}">{{ $j }}</option>@endforeach
            </select>
        </div>
    </div>

    {{-- Daftar hasil --}}
    <div data-list class="mt-6 space-y-5">
        @foreach ($hasil as $h)
            @php $ok = $h->status === 'K'; $d = $h->tanggal_uji->copy()->locale('id'); @endphp
            <article data-item data-status="{{ $h->status }}" data-year="{{ $d->year }}" data-ts="{{ $d->timestamp }}" data-skor="{{ $h->skor }}" data-jur="{{ $h->jurusan }}"
                     class="hs-in rounded-2xl border overflow-hidden bg-white shadow-[0_1px_2px_rgba(15,23,42,.04)] {{ $ok ? ($h->terkini ? 'border-emerald-300 ring-2 ring-emerald-100' : 'border-slate-200/80') : 'border-rose-200' }}" style="animation-delay:{{ .3 + $n++ * .1 }}s">

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

                    <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-4 flex flex-col sm:flex-row gap-4">
                        <div class="shrink-0 sm:w-40 rounded-xl border bg-white p-4 text-center {{ $ok ? 'border-slate-200' : 'border-rose-200' }}">
                            <span class="inline-flex items-center justify-center gap-1 rounded-full px-3 py-1 text-[10px] font-bold {{ $ok ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-600' }}"><svg class="w-3 h-3" stroke-width="2.6" {!! $a !!}>{!! $ic[$ok ? 'check' : 'x'] !!}</svg>{{ $ok ? 'KOMPETEN (K)' : 'BELUM KOMPETEN' }}</span>
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
        const tahun = {{ (int) $tahun }};
        let tab = 'semua';

        function apply() {
            const jur = jurEl.value, s = sortEl.value;
            const sorted = [...items].sort((x, y) => s === 'skor' ? y.dataset.skor - x.dataset.skor : s === 'terlama' ? x.dataset.ts - y.dataset.ts : y.dataset.ts - x.dataset.ts);
            let shown = 0;
            sorted.forEach((el) => {
                const okTab = tab === 'semua' || tab === el.dataset.status || (tab === 'tahun' && +el.dataset.year === tahun);
                const on = okTab && (!jur || el.dataset.jur === jur);
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
        sortEl.addEventListener('change', apply);
        jurEl.addEventListener('change', apply);
        apply();
    })();
</script>
</x-layouts.app>