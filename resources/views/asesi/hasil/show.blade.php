{{-- resources/views/hasil/show.blade.php --}}
@php
    $ok = $h->status === 'K';
    $a = 'fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"';
    $ic = [
        'down'  => '<path d="M12 3v12m0 0-4-4m4 4 4-4M4 21h16"/>',
        'arrow' => '<path d="M5 12h14M12 5l7 7-7 7"/>',
        'check' => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
        'x'     => '<path d="M6 6l12 12M18 6 6 18"/>',
        'book'  => '<rect width="8" height="4" x="8" y="2" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>',
        'code'  => '<path d="m16 18 6-6-6-6M8 6l-6 6 6 6"/>',
        'chat'  => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22z"/>',
        'left'  => '<path d="m15 18-6-6 6-6"/>',
    ];
    $bar = ['green' => 'bg-emerald-700', 'amber' => 'bg-amber-500', 'red' => 'bg-rose-500'];
    $txt = ['green' => 'text-emerald-600', 'amber' => 'text-amber-600', 'red' => 'text-rose-600'];
    $card = 'bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,.04)]';
    $circ = 2 * pi() * 64;
    $dash = round($h->skor / 100 * $circ, 1);
    $bkUnits = $h->units->where('hasil', 'BK');
    $d = $h->tanggal_uji->copy()->locale('id');
@endphp

<x-layouts.app :title="$title ?? 'Hasil & Kelulusan'">
<style>
    @keyframes hdIn { from { opacity: 0; transform: translateY(18px); } }
    @keyframes hdBar { from { width: 0; } }
    @keyframes hdRing { from { stroke-dasharray: 0 {{ $circ }}; } }
    .hd-in { animation: hdIn .7s cubic-bezier(.22, 1, .36, 1) backwards; }
    .hd-bar { animation: hdBar 1.1s cubic-bezier(.22, 1, .36, 1) .3s backwards; }
    .hd-ring { animation: hdRing 1.4s cubic-bezier(.22, 1, .36, 1) .2s backwards; }
    @media (prefers-reduced-motion: reduce) { .hd-in, .hd-bar, .hd-ring { animation: none; } }
</style>

<div class="max-w-[1100px]">
    <nav class="text-[11px] text-slate-400 flex items-center gap-2" aria-label="Breadcrumb">
        <a href="{{ route('asesi.hasil.index') }}" class="hover:text-[#0b5a49]">Portal Asesi</a><span>›</span><span class="font-semibold text-[#0b5a49]">Hasil & Status Kelulusan</span>
    </nav>

    <div class="mt-2 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Hasil & Status Kelulusan</h1>
            <p class="mt-1 text-sm text-slate-500">Skema Sertifikasi: <b class="{{ $ok ? 'text-[#0b5a49]' : 'text-rose-700' }}">{{ $h->nama }}</b> <span class="text-xs text-slate-400">• {{ $h->kode }} • Standar {{ ucwords(strtolower($h->level)) }}</span></p>
        </div>
        <div class="flex flex-wrap gap-2 shrink-0">
            <a href="{{ route('asesi.hasil.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 px-3.5 py-2 text-xs font-semibold text-slate-700"><svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['left'] !!}</svg>Kembali</a>
            @if ($ok)<a href="{{ $h->url_sertifikat ?? '#' }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 px-3.5 py-2 text-xs font-semibold text-slate-800"><svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['down'] !!}</svg>Unduh Sertifikat (PDF)</a>@endif
            <a href="#transkrip" class="inline-flex items-center rounded-lg px-4 py-2 text-xs font-semibold text-white {{ $ok ? 'bg-[#0b5a49] hover:bg-[#074237]' : 'bg-rose-700 hover:bg-rose-800' }} transition-colors">Lihat Detail Transkrip</a>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-[320px_minmax(0,1fr)] gap-5">

        {{-- Status kelulusan --}}
        <section class="hd-in {{ $card }} p-6 text-center lg:row-span-2 flex flex-col" style="background:linear-gradient(160deg,{{ $ok ? '#ecfdf5' : '#fff1f2' }} 0%,#fff 40%)">
            <h2 class="text-sm font-bold uppercase tracking-wide text-slate-700">Status Kelulusan</h2>
            <p class="text-xs text-slate-400">Akumulasi Nilai Teori & Praktik</p>
            <div class="relative mx-auto my-8 w-40 h-40">
                <svg viewBox="0 0 160 160" class="w-full h-full -rotate-90">
                    <circle cx="80" cy="80" r="64" fill="none" stroke="#e2e8f0" stroke-width="16"/>
                    <circle cx="80" cy="80" r="64" fill="none" stroke="{{ $ok ? '#0b5a49' : '#e11d48' }}" stroke-width="16" stroke-linecap="round" stroke-dasharray="{{ $dash }} {{ $circ }}" class="hd-ring"/>
                </svg>
                <div class="absolute inset-0 grid place-items-center"><div><p class="text-4xl font-bold text-slate-900">{{ round($h->skor) }}%</p><p class="text-[10px] font-semibold tracking-wider text-slate-400">TOTAL SKOR</p></div></div>
            </div>
            <span class="mx-auto inline-flex items-center gap-1.5 rounded-full border px-4 py-1.5 text-xs font-bold {{ $ok ? 'border-emerald-200 bg-emerald-100 text-emerald-800' : 'border-rose-200 bg-rose-100 text-rose-700' }}">
                <svg class="w-4 h-4" stroke-width="2.4" {!! $a !!}>{!! $ic[$ok ? 'check' : 'x'] !!}</svg>{{ $ok ? 'KOMPETEN (K)' : 'BELUM KOMPETEN (BK)' }}</span>
            <p class="mt-3 text-[11px] text-slate-500 leading-relaxed">Nilai {{ $ok ? 'melampaui' : 'belum mencapai' }} standar ambang batas kelulusan LSP ({{ number_format($pass, 2) }}).</p>
            <p class="mt-auto pt-6 text-[11px] text-slate-400">Tanggal uji: {{ $d->translatedFormat('d F Y') }} • TUK: {{ $h->tuk }}</p>
        </section>

        {{-- Skor teori & praktik --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            @foreach ([['UJIAN TEORI', 'book', 30, $h->teori, $h->teori_info, $h->ket_teori ?? 'Ujian Tertulis CBT'], ['UJIAN PRAKTIK', 'code', 70, $h->praktik, $h->praktik_info, $h->ket_praktik ?? 'Demonstrasi Langsung Lab']] as [$lbl, $icon, $bobot, $v, $info, $ket])
                <section class="hd-in {{ $card }} p-5" style="animation-delay:{{ .1 + $loop->index * .1 }}s">
                    <div class="flex items-center justify-between">
                        <h3 class="flex items-center gap-2 text-xs font-bold tracking-wide text-slate-600"><svg class="w-4 h-4" {!! $a !!}>{!! $ic[$icon] !!}</svg>{{ $lbl }}</h3>
                        <span class="px-2 py-0.5 rounded bg-emerald-50 text-[10px] font-semibold text-emerald-700">Bobot {{ $bobot }}%</span>
                    </div>
                    <p class="mt-3 text-4xl font-bold text-slate-900">{{ $v }} <span class="text-sm font-normal text-slate-400">/ 100</span></p>
                    <div class="mt-3 h-2 rounded-full bg-slate-100 overflow-hidden"><div class="hd-bar h-full rounded-full {{ $bar[$info->tone] }}" style="width:{{ $v }}%"></div></div>
                    <div class="mt-2 flex items-center justify-between gap-2 text-[11px]"><span class="text-slate-500">{{ $ket }}</span><span class="font-semibold {{ $txt[$info->tone] }}">{{ $v >= $pass ? 'Tercapai' : 'Belum Tercapai' }}</span></div>
                </section>
            @endforeach
        </div>

        {{-- Catatan asesor --}}
        <section class="hd-in rounded-2xl border-l-4 p-5 {{ $ok ? 'border-l-[#0b5a49] bg-slate-100/80' : 'border-l-rose-600 bg-rose-50' }}" style="animation-delay:.3s">
            <h3 class="flex items-center gap-2 text-lg font-bold {{ $ok ? 'text-[#0b5a49]' : 'text-rose-700' }}"><svg class="w-5 h-5" {!! $a !!}>{!! $ic['chat'] !!}</svg>Catatan Asesor</h3>
            <p class="mt-3 rounded-xl bg-white/80 px-5 py-4 text-sm sm:text-base leading-relaxed text-slate-700">"{{ $h->catatan }}"</p>
            <p class="mt-3 text-right text-xs font-semibold text-slate-500">- Asesor: {{ $h->asesor }}</p>
        </section>
    </div>

    {{-- Detail unit kompetensi --}}
    <section id="transkrip" class="hd-in mt-5 {{ $card }} overflow-hidden scroll-mt-24" style="animation-delay:.35s">
        <div class="p-5 flex flex-wrap items-start justify-between gap-3">
            <div><h2 class="text-base font-bold text-slate-900">Detail Unit Kompetensi</h2><p class="text-xs text-slate-500">Penilaian spesifik setiap standar unit yang diujikan dalam skema ini</p></div>
            <span class="px-3 py-1 rounded-full bg-slate-100 text-[11px] font-semibold text-slate-600">{{ $h->units->count() }} Unit Diujikan</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] text-left text-xs">
                <thead class="bg-blue-50/70 text-[10px] font-bold uppercase tracking-wider text-teal-800">
                    <tr><th class="px-5 py-3 w-12">No</th><th class="px-3 py-3">Kode Unit</th><th class="px-3 py-3">Judul Unit Kompetensi</th><th class="px-3 py-3">Kriteria Penilaian</th><th class="px-5 py-3 text-center">Hasil</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($h->units as $u)
                        <tr class="{{ $u->hasil === 'BK' ? 'bg-rose-50/60' : '' }}">
                            <td class="px-5 py-4 text-slate-500">{{ $u->no }}</td>
                            <td class="px-3 py-4 font-mono text-slate-600">{{ $u->kode }}</td>
                            <td class="px-3 py-4 font-semibold text-slate-900 max-w-[16rem]">{{ $u->judul }}</td>
                            <td class="px-3 py-4 text-slate-500 max-w-[16rem]">{{ $u->kriteria }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center gap-1 rounded-md border px-2.5 py-1 font-bold {{ $u->hasil === 'K' ? 'border-cyan-200 bg-cyan-50 text-cyan-700' : 'border-rose-200 bg-rose-50 text-rose-600' }}"><svg class="w-3 h-3" stroke-width="2.6" {!! $a !!}>{!! $ic[$u->hasil === 'K' ? 'check' : 'x'] !!}</svg>{{ $u->hasil }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-500">
            <span class="flex items-center gap-4">Keterangan: <span class="flex items-center gap-1.5"><i class="w-2.5 h-2.5 rounded-full bg-cyan-400"></i>K = Kompeten</span><span class="flex items-center gap-1.5"><i class="w-2.5 h-2.5 rounded-full bg-rose-400"></i>BK = Belum Kompeten</span></span>
            <span class="text-slate-400">Disahkan pada Sidang Pleno Komite Sertifikasi LSP Sekolah No: {{ $h->pleno }}</span>
        </div>
    </section>

    {{-- Tahap selanjutnya --}}
    <section class="hd-in mt-5 relative overflow-hidden rounded-2xl p-6 sm:p-7 text-white flex flex-col md:flex-row md:items-center md:justify-between gap-5"
             style="animation-delay:.4s;background:linear-gradient(120deg,{{ $ok ? '#0b5a49,#0f766e' : '#9f1239,#be123c' }})">
        <span class="absolute -right-12 -bottom-16 w-56 h-56 rounded-full bg-white/10"></span>
        <div class="relative max-w-xl">
            <span class="inline-flex items-center gap-1.5 rounded-full border border-white/25 bg-white/10 px-3 py-1 text-[11px] font-semibold">
                <svg class="w-3 h-3" stroke-width="2.4" {!! $a !!}>{!! $ic['check'] !!}</svg>{{ $ok ? ($h->no_sertifikat ? 'Sertifikat BNSP Telah Terbit' : 'Tahapan Selanjutnya: Penerbitan Sertifikat BNSP') : 'Tahapan Selanjutnya: Asesmen Ulang (Remedial)' }}</span>
            <h2 class="mt-3 text-xl font-bold">{{ $ok ? 'Selamat! Anda Telah Memenuhi Syarat Kelulusan' : 'Anda Belum Memenuhi Syarat Kelulusan' }}</h2>
            @if ($ok)
                <p class="mt-2 text-sm text-white/80 leading-relaxed">{{ $h->no_sertifikat ? 'Sertifikat Kompetensi berlogo Garuda Emas BNSP Anda telah terbit dengan nomor ' . $h->no_sertifikat . '.' : 'Hasil asesmen telah disahkan oleh Master Asesor. Sertifikat Kompetensi berlogo Garuda Emas BNSP Anda sedang dalam proses pencetakan fisik dan pengarsipan nomor registrasi nasional.' }}</p>
            @else
                <p class="mt-2 text-sm text-white/85 leading-relaxed">Skor akhir {{ round($h->skor) }}% belum mencapai ambang batas {{ $pass }}. Anda dapat mengajukan asesmen ulang untuk {{ $bkUnits->count() }} unit kompetensi yang belum tercapai.</p>
                <div class="mt-3 flex flex-wrap gap-1.5">@foreach ($bkUnits as $u)<span class="rounded-md bg-white/15 px-2 py-1 font-mono text-[10px]">{{ $u->kode }}</span>@endforeach</div>
            @endif
        </div>
        <a href="{{ $ok ? route('placeholder', ['title' => 'Sertifikat Saya']) : route('asesi.skema.index') }}" class="relative shrink-0 inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold {{ $ok ? 'text-[#0b5a49]' : 'text-rose-700' }} hover:bg-slate-50 transition-colors">
            {{ $ok ? 'Lihat di Menu Sertifikat Saya' : 'Ajukan Asesmen Ulang' }} <svg class="w-4 h-4" {!! $a !!}>{!! $ic['arrow'] !!}</svg></a>
    </section>
</div>
</x-layouts.app>