{{-- resources/views/asesi/skema/detail.blade.php  (ganti seluruh isi file detail.blade.php yang lama) --}}
{{--
  KONTRAK DATA (dikirim controller nanti; selama belum ada dipakai DATA DUMMY):
  $skema : kode, nama, tipe (Klaster|Okupasi), status (Aktif|...),
           deskripsi (array paragraf, tampil di "Tentang Skema Ini"), peluang_karir (array),
           waktu_pelaksanaan (string|Carbon), waktu_mulai (mis. "08:00 WIB"), kuota, batas_daftar (string|Carbon),
           url_daftar, url_silabus, url_kembali
  $unit  : koleksi -> kode, judul
--}}
@php
    // ===================== DATA DUMMY =====================
    $skema ??= (object) [
        'kode' => 'JWD-001', 'nama' => 'Junior Web Developer', 'tipe' => 'Klaster', 'status' => 'Aktif',
        'deskripsi' => [
            'Skema Sertifikasi Klaster Junior Web Developer merupakan skema sertifikasi yang dikembangkan oleh komite skema sertifikasi LSP. Kemasan kompetensi yang digunakan mengacu pada SKKNI yang ditetapkan berdasarkan Keputusan Menteri Ketenagakerjaan Republik Indonesia.',
            'Skema ini digunakan untuk memastikan kompetensi lulusan Sekolah Menengah Kejuruan dan sebagai acuan bagi LSP dan asesor kompetensi dalam pelaksanaan sertifikasi kompetensi Junior Web Developer.',
        ],
        'peluang_karir' => ['Frontend Developer', 'Backend Developer', 'Web Programmer', 'IT Support'],
        'waktu_pelaksanaan' => '10/25/2024', 'waktu_mulai' => '08:00 WIB', 'kuota' => 30, 'batas_daftar' => '24 Okt 2024',
    ];
    $unit ??= collect([
        (object) ['kode' => 'J.620100.004.02', 'judul' => 'Menggunakan Struktur Data'],
        (object) ['kode' => 'J.620100.015.01', 'judul' => 'Menyusun Spesifikasi Program'],
        (object) ['kode' => 'J.620100.017.02', 'judul' => 'Mengimplementasikan Pemrograman Terstruktur'],
        (object) ['kode' => 'J.620100.022.02', 'judul' => 'Mengimplementasikan Algoritma Pemrograman'],
        (object) ['kode' => 'J.620100.023.02', 'judul' => 'Membuat Dokumen Kode Program'],
    ]);
    // =======================================================

    $a = 'fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"';
    $ic = [
        'code'     => '<path d="m16 18 6-6-6-6M8 6l-6 6 6 6"/>',
        'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'clock'    => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
        'users'    => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/>',
        'right'    => '<path d="M5 12h14m-6-6 6 6-6 6"/>',
        'left'     => '<path d="M19 12H5m6-6-6 6 6 6"/>',
        'down'     => '<path d="M12 3v12m0 0-4-4m4 4 4-4M4 21h16"/>',
        'ok'       => '<circle cx="12" cy="12" r="9"/><path d="m8.5 12.5 2.5 2.5 4.5-5"/>',
    ];
    $fmt = fn ($d) => $d instanceof \Carbon\CarbonInterface ? $d->locale('id')->translatedFormat('d M Y') : $d;
    $tgl = $skema->waktu_pelaksanaan instanceof \Carbon\CarbonInterface ? $skema->waktu_pelaksanaan->format('m/d/Y') : $skema->waktu_pelaksanaan;
    $card = 'bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,.04)]';
    $chip = [
        'bg-indigo-50 border-indigo-200 text-indigo-700',
        'bg-teal-50 border-teal-200 text-teal-700',
        'bg-amber-50 border-amber-200 text-amber-700',
        'bg-rose-50 border-rose-200 text-rose-700',
    ];
    $badgeNo = ['bg-teal-100 text-teal-700', 'bg-indigo-100 text-indigo-700', 'bg-amber-100 text-amber-700', 'bg-rose-100 text-rose-700', 'bg-emerald-100 text-emerald-700'];
    $urlKembali = $skema->url_kembali ?? route('asesi.skema.index');
@endphp

<x-layouts.app :title="$title ?? 'Detail Skema'">
<style>
    @keyframes dtIn { from { opacity: 0; transform: translateY(14px); } }
    .dt-in { animation: dtIn .6s cubic-bezier(.22, 1, .36, 1) backwards; }
    @media (prefers-reduced-motion: reduce) { .dt-in { animation: none; } }
</style>

<div id="detail-skema" class="max-w-[1000px] mx-auto space-y-5">

    {{-- Hero (hanya badge + judul) --}}
    <div class="{{ $card }} dt-in relative overflow-hidden px-6 sm:px-8 py-8 sm:py-10" style="background:linear-gradient(120deg,#ffffff 35%,#ecfeff 75%,#e0f2fe 100%)">
        <span aria-hidden="true" class="pointer-events-none select-none absolute -left-2 top-6 text-5xl font-bold tracking-tight text-slate-900/[.04] whitespace-nowrap">Detail Skema -</span>
        <span aria-hidden="true" class="pointer-events-none absolute -right-10 -top-14 w-52 h-52 rounded-full bg-cyan-200/40 blur-2xl"></span>
        <span aria-hidden="true" class="pointer-events-none absolute right-24 -bottom-16 w-40 h-40 rounded-full bg-emerald-200/40 blur-2xl"></span>
        <div class="relative flex items-center justify-between gap-6">
            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <span class="rounded-full bg-cyan-200 px-3 py-1 text-[10px] font-bold uppercase tracking-wide text-cyan-900">{{ $skema->tipe }}</span>
                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-[10px] font-semibold text-emerald-800"><svg class="w-3 h-3" {!! $a !!}>{!! $ic['ok'] !!}</svg>{{ $skema->status }}</span>
                </div>
                <h1 class="mt-3 text-3xl sm:text-4xl font-bold text-slate-900">{{ $skema->nama }}</h1>
            </div>
            <span class="shrink-0 w-16 h-16 sm:w-[76px] sm:h-[76px] rounded-full bg-indigo-100 text-[#0b6b5e] grid place-items-center shadow-[0_6px_20px_rgba(99,102,241,.18)] ring-4 ring-white"><svg class="w-7 h-7" {!! $a !!}>{!! $ic['code'] !!}</svg></span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_260px] gap-5 items-start">

        {{-- ============ Kolom kiri ============ --}}
        <div class="space-y-5 min-w-0">
            {{-- Tab --}}
            <div class="grid grid-cols-2 border-b-2 border-slate-200 dt-in" role="tablist" style="animation-delay:.05s">
                <button type="button" role="tab" data-tab="deskripsi" aria-selected="true"
                        class="py-3 text-sm font-medium text-slate-600 border-b-2 -mb-0.5 border-transparent transition-colors hover:text-slate-900 aria-selected:bg-[#e8eff6] aria-selected:border-[#0b6b5e] aria-selected:font-semibold aria-selected:text-[#0b6b5e]">Deskripsi</button>
                <button type="button" role="tab" data-tab="unit" aria-selected="false"
                        class="py-3 text-sm font-medium text-slate-600 border-b-2 -mb-0.5 border-transparent transition-colors hover:text-slate-900 aria-selected:bg-[#e8eff6] aria-selected:border-[#0b6b5e] aria-selected:font-semibold aria-selected:text-[#0b6b5e]">Unit Kompetensi</button>
            </div>

            {{-- Panel: Deskripsi --}}
            <section data-panel="deskripsi" role="tabpanel" class="{{ $card }} border-t-4 border-t-teal-500 p-6 dt-in" style="animation-delay:.1s">
                <h2 class="text-base font-bold text-slate-900">Tentang Skema Ini</h2>
                <div class="mt-3 space-y-3 text-[13px] leading-relaxed text-slate-600">
                    @foreach ((array) $skema->deskripsi as $p)<p>{{ $p }}</p>@endforeach
                </div>
                @if (!empty($skema->peluang_karir))
                    <p class="mt-5 text-[10px] font-bold uppercase tracking-wider text-slate-700">Peluang Karir</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach ($skema->peluang_karir as $pk)
                            <span class="rounded-md border px-3 py-1.5 text-xs font-medium {{ $chip[$loop->index % count($chip)] }}">{{ $pk }}</span>
                        @endforeach
                    </div>
                @endif
            </section>

            {{-- Panel: Unit Kompetensi --}}
            <section data-panel="unit" role="tabpanel" class="hidden {{ $card }} border-t-4 border-t-indigo-400 overflow-hidden">
                <div class="px-6 pt-6 pb-4">
                    <h2 class="text-base font-bold text-slate-900">Daftar Unit Kompetensi</h2>
                    <p class="mt-0.5 text-[11px] text-slate-500">Total {{ $unit->count() }} Unit Kompetensi yang akan diujikan.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-indigo-50 text-[10px] font-bold uppercase tracking-wider text-slate-700">
                                <th class="px-6 py-3 w-16">No</th>
                                <th class="px-4 py-3 w-48">Kode Unit</th>
                                <th class="px-4 py-3">Judul Unit Kompetensi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($unit as $u)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-6 py-4"><span class="w-6 h-6 rounded-full text-[11px] font-bold grid place-items-center {{ $badgeNo[$loop->index % count($badgeNo)] }}">{{ $loop->iteration }}</span></td>
                                    <td class="px-4 py-4"><span class="rounded bg-slate-100 px-2 py-1 font-mono text-[11px] text-slate-600">{{ $u->kode }}</span></td>
                                    <td class="px-4 py-4 text-[13px]">{{ $u->judul }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-6 py-8 text-center text-slate-500">Belum ada unit kompetensi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Kembali --}}
            <a href="{{ $urlKembali }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-800 transition-colors">
                <svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['left'] !!}</svg> Kembali
            </a>
        </div>

        {{-- ============ Kolom kanan ============ --}}
        <aside class="{{ $card }} overflow-hidden dt-in lg:sticky lg:top-6" style="animation-delay:.2s">
            <div class="h-1.5" style="background:linear-gradient(90deg,#0b6b5e,#22d3ee,#818cf8)"></div>
            <div class="p-5">
                <h2 class="text-base font-bold text-slate-900">Informasi Pendaftaran</h2>
                <div class="mt-4 flex items-center gap-3 rounded-xl bg-emerald-50/70 border border-emerald-100 p-3">
                    <span class="w-10 h-10 shrink-0 rounded-full bg-emerald-100 text-[#0b5a49] grid place-items-center"><svg class="w-4 h-4" {!! $a !!}>{!! $ic['calendar'] !!}</svg></span>
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-wide text-slate-600">Tanggal Pelaksanaan</p>
                        <p class="text-xl font-bold text-slate-900 leading-tight">{{ $tgl ?: '-' }}</p>
                        <p class="text-[10px] font-semibold text-emerald-700">*Tanggal tidak dapat diubah</p>
                    </div>
                </div>
                <dl class="mt-4 pt-4 border-t border-slate-100 space-y-3.5 text-xs">
                    <div class="flex items-center justify-between gap-2">
                        <dt class="flex items-center gap-2.5 text-slate-600"><span class="w-7 h-7 rounded-lg bg-amber-100 text-amber-600 grid place-items-center"><svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['clock'] !!}</svg></span>Waktu Mulai</dt>
                        <dd class="font-bold text-slate-900">{{ $skema->waktu_mulai ?? '-' }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <dt class="flex items-center gap-2.5 text-slate-600"><span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-600 grid place-items-center"><svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['users'] !!}</svg></span>Kuota Gelombang</dt>
                        <dd class="font-bold text-slate-900">{{ $skema->kuota ?? '-' }} Peserta</dd>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <dt class="flex items-center gap-2.5 text-slate-600"><span class="w-7 h-7 rounded-lg bg-rose-100 text-rose-600 grid place-items-center"><svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['calendar'] !!}</svg></span>Batas Daftar</dt>
                        <dd class="font-bold text-red-700">{{ $fmt($skema->batas_daftar) ?: '-' }}</dd>
                    </div>
                </dl>
                <div class="mt-5 pt-5 border-t border-slate-100 space-y-2.5">
                    <a href="{{ $skema->url_daftar ?? route('asesi.skema.daftar', ['skema' => $skema->kode]) }}" class="flex items-center justify-center gap-2 rounded-lg bg-[#0b6b5e] hover:bg-[#074237] py-2.5 text-xs font-semibold text-white shadow-sm transition-colors">Daftar Sekarang <svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['right'] !!}</svg></a>
                    <a href="{{ $skema->url_silabus ?? '#' }}" class="flex items-center justify-center gap-2 rounded-lg border border-[#0b6b5e] bg-white hover:bg-emerald-50 py-2.5 text-xs font-semibold text-[#0b6b5e] transition-colors">Unduh Silabus <svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['down'] !!}</svg></a>
                </div>
            </div>
        </aside>
    </div>
</div>

@verbatim
<script>
    // Tab: tampilkan panel sesuai pilihan (mendukung #unit di URL)
    (() => {
        const root = document.getElementById('detail-skema');
        if (!root) return;
        const tabs = [...root.querySelectorAll('[data-tab]')];
        const panels = [...root.querySelectorAll('[data-panel]')];
        const show = (t) => {
            tabs.forEach((b) => b.setAttribute('aria-selected', b.dataset.tab === t ? 'true' : 'false'));
            panels.forEach((p) => {
                const on = p.dataset.panel === t;
                p.classList.toggle('hidden', !on);
                if (on) { p.classList.remove('dt-in'); void p.offsetWidth; p.classList.add('dt-in'); }
            });
        };
        tabs.forEach((b) => b.addEventListener('click', () => show(b.dataset.tab)));
        if (location.hash === '#unit') show('unit');
    })();
</script>
@endverbatim
</x-layouts.app>