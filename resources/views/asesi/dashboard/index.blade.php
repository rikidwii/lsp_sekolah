{{-- resources/views/dashboard/index.blade.php --}}
@php
    $a = 'fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"';
    $card = 'bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,.04)]';
    $ic = [
        'code'  => '<path d="m16 18 6-6-6-6M8 6l-6 6 6 6"/>',
        'cal'   => '<rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'award' => '<circle cx="12" cy="8" r="6"/><path d="M15.5 12.9 17 22l-5-3-5 3 1.5-9.1"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'pin'   => '<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'check' => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
        'info'  => '<circle cx="12" cy="12" r="9"/><path d="M12 8h.01M11 12h1v4h1"/>',
    ];
    $tone  = ['indigo' => 'bg-indigo-50 text-indigo-500', 'emerald' => 'bg-emerald-50 text-emerald-600'];
    $pill  = ['slate' => 'bg-slate-100 text-slate-500', 'emerald' => 'bg-emerald-50 text-emerald-700'];
    $stats = [
        ['code',  'indigo',  '+' . $stat['skema'] . ' Skema', 'slate',   'Jumlah Skema yang Diikuti', $stat['skema'], 'Skema'],
        ['cal',   'indigo',  $stat['badgeH'],                 'emerald', 'Jadwal Terdekat',          $stat['terdekat'] ?? '-', ''],
        ['award', 'emerald', 'Target ' . $stat['skema'],      'slate',   'Sertifikat Diperoleh',     $stat['sertifikat'], 'Sertifikat'],
    ];
    $stText = ['done' => 'text-emerald-600', 'current' => 'text-amber-600', 'pending' => 'text-slate-400'];
    $main = $jadwal->first();
@endphp

<x-layouts.app :title="$title">
<div class="space-y-5 max-w-[1100px]">

    {{-- Selamat datang --}}
    <section class="{{ $card }} border-l-4 border-l-[#0d9488] p-5 sm:p-6 flex flex-col sm:flex-row sm:items-start gap-4 sm:gap-5"
             style="background:linear-gradient(90deg,#fff 55%,#ecfdf5)">
        <div class="relative shrink-0 self-start">
            <div class="w-[72px] h-[72px] sm:w-20 sm:h-20 rounded-2xl grid place-items-center text-2xl sm:text-3xl font-bold text-white"
                 style="background:linear-gradient(135deg,#0f766e,#14b8a6)">{{ $initials }}</div>
            <span class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-emerald-500 ring-2 ring-white text-white grid place-items-center">
                <svg class="w-3.5 h-3.5" stroke-width="3" {!! $a !!}>{!! $ic['check'] !!}</svg>
            </span>
        </div>
        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-center gap-2">
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 break-words">Halo, {{ $user->name }}!</h1>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[11px] font-semibold">{{ $aktif ? 'Asesi Aktif' : 'Belum Mendaftar' }}</span>
            </div>
            <p class="mt-1 font-mono text-[11px] text-slate-400">No. Reg: {{ $noReg }}</p>
            <p class="mt-2 text-sm text-slate-600 leading-relaxed">Selamat datang di portal asesi LSP Sekolah. Pantau terus progres sertifikasi kompetensimu menuju pengakuan sertifikat nasional standar BNSP.</p>
        </div>
        {{-- Tanggal real time --}}
        <div class="sm:ml-auto shrink-0 inline-flex items-center gap-2 rounded-xl bg-[#0b5a49] text-white px-4 py-2 text-xs font-semibold shadow-sm self-start">
            <svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['clock'] !!}</svg>
            <span data-today>{{ now()->locale('id')->translatedFormat('l, d M Y') }}</span>
        </div>
    </section>

    {{-- Statistik --}}
    <section class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach ($stats as [$icon, $tn, $pl, $plTone, $label, $val, $unit])
            <div class="{{ $card }} p-5">
                <div class="flex items-start justify-between">
                    <span class="w-10 h-10 rounded-xl grid place-items-center {{ $tone[$tn] }}"><svg class="w-5 h-5" {!! $a !!}>{!! $ic[$icon] !!}</svg></span>
                    <span class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold {{ $pill[$plTone] }}">{{ $pl }}</span>
                </div>
                <p class="mt-4 text-[10px] font-semibold uppercase tracking-wide text-slate-500">{{ $label }}</p>
                <p class="mt-1 text-xl font-bold text-slate-900">{{ $val }} @if ($unit)<span class="text-xs font-normal text-slate-400">{{ $unit }}</span>@endif</p>
            </div>
        @endforeach
    </section>

    {{-- Progres sertifikasi --}}
    <section class="{{ $card }} p-5 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 pb-5 border-b border-slate-100">
            <div>
                <h2 class="text-base font-bold text-slate-900">Progres Sertifikasi{{ $skemaAktif ? ': ' . $skemaAktif : '' }}</h2>
                <p class="text-xs text-slate-500 mt-0.5">Alur tahapan resmi sertifikasi BNSP dari pendaftaran hingga lisensi kompetensi</p>
            </div>
            <span class="inline-flex items-center gap-1.5 self-start px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-semibold whitespace-nowrap">
                <svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['clock'] !!}</svg> Tahap {{ min($tahap, 4) }} dari 4 ({{ $persen }}% Selesai)
            </span>
        </div>

        <div class="flex items-start pt-8">
            @foreach ($steps as $i => $s)
                <div class="flex-1 min-w-0 flex flex-col items-center text-center">
                    <div class="flex items-center w-full">
                        <span class="h-0.5 flex-1 {{ $s['status'] !== 'pending' ? 'bg-[#0b5a49]' : 'bg-slate-200' }}"></span>
                        @if ($s['status'] === 'done')
                            <span class="w-10 h-10 shrink-0 rounded-full bg-[#0b5a49] text-white grid place-items-center"><svg class="w-5 h-5" stroke-width="2.4" {!! $a !!}>{!! $ic['check'] !!}</svg></span>
                        @elseif ($s['status'] === 'current')
                            <span class="w-10 h-10 shrink-0 rounded-full bg-white border-2 border-[#0b5a49] ring-4 ring-emerald-100 grid place-items-center"><span class="w-3 h-3 rounded-full bg-[#0b5a49]"></span></span>
                        @else
                            <span class="w-8 h-8 shrink-0 rounded-full bg-white border border-slate-200 text-slate-400 text-xs grid place-items-center">
                                @if ($loop->last)<svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['award'] !!}</svg>@else{{ $i + 1 }}@endif
                            </span>
                        @endif
                        <span class="h-0.5 flex-1 {{ $s['status'] === 'done' ? 'bg-[#0b5a49]' : 'bg-slate-200' }}"></span>
                    </div>
                    <p class="mt-3 text-xs font-semibold {{ $s['status'] === 'pending' ? 'text-slate-500' : 'text-slate-900' }}">{{ $s['label'] }}</p>
                    <p class="text-[11px] {{ $stText[$s['status']] }}">{{ $s['text'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs text-slate-600">
            <svg class="w-4 h-4 shrink-0 text-emerald-600" {!! $a !!}>{!! $ic['info'] !!}</svg> {{ $pesanTahap }}
        </div>
    </section>

    {{-- Jadwal + notifikasi --}}
    <section class="grid grid-cols-1 lg:grid-cols-5 gap-5">

        <div class="{{ $card }} p-5 sm:p-6 lg:col-span-3">
            <h2 class="flex items-center gap-2 text-base font-bold text-slate-900">Jadwal Asesmen Terdekat <span class="w-2 h-2 rounded-full bg-emerald-500"></span></h2>

            @if ($main)
                <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50/50 p-4 flex flex-col sm:flex-row gap-4">
                    <div class="shrink-0 w-14 h-14 rounded-xl bg-[#0b5a49] text-white flex flex-col items-center justify-center leading-none">
                        <span class="text-[10px] font-semibold tracking-wide">{{ strtoupper($main->tanggal->locale('id')->translatedFormat('M')) }}</span>
                        <span class="text-xl font-bold mt-0.5">{{ $main->tanggal->format('d') }}</span>
                    </div>
                    <div class="min-w-0 flex-1 text-xs text-slate-500 space-y-1">
                        <p class="text-sm font-bold text-slate-900">{{ $main->judul }}</p>
                        <p class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['clock'] !!}</svg>{{ \Carbon\Carbon::parse($main->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($main->jam_selesai)->format('H:i') }} WIB</p>
                        <p class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['pin'] !!}</svg>{{ $main->lokasi }}</p>
                        @if ($main->asesor)<p>Asesor Penguji: <span class="font-medium text-slate-700">{{ $main->asesor->name }}</span></p>@endif
                    </div>
                    <span class="self-start px-3 py-1.5 rounded-lg bg-teal-100 text-teal-800 text-xs font-semibold whitespace-nowrap">Wajib Hadir</span>
                </div>

                <div class="mt-3 flex gap-2 rounded-xl bg-slate-100 px-4 py-3 text-xs text-slate-600 leading-relaxed">
                    <svg class="w-4 h-4 shrink-0 mt-0.5 text-slate-500" {!! $a !!}>{!! $ic['info'] !!}</svg>
                    <p><b class="text-slate-800">Persiapan Ujian:</b> {{ $main->persiapan ?? 'Pastikan membawa kartu identitas siswa/KTP dan perlengkapan yang dibutuhkan untuk uji kompetensi.' }}</p>
                </div>
            @endif

            @foreach ($jadwal->skip(1) as $j)
                <div class="mt-3 flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 text-xs">
                    <span class="font-semibold text-slate-800">{{ $j->judul }}</span>
                    <span class="text-slate-500">{{ $j->tanggal->locale('id')->translatedFormat('d M Y') }}</span>
                </div>
            @endforeach

            @if ($jadwal->count() < 2)
                <div class="mt-3 rounded-xl border border-dashed border-slate-300 px-4 py-6 text-center text-xs text-slate-500">
                    <svg class="w-5 h-5 mx-auto mb-2 text-slate-400" {!! $a !!}>{!! $ic['cal'] !!}</svg>
                    {{ $main ? 'Belum ada jadwal asesmen lain yang dijadwalkan.' : 'Belum ada jadwal asesmen untukmu.' }}
                </div>
            @endif
        </div>

        <div class="{{ $card }} p-5 sm:p-6 lg:col-span-2 flex flex-col">
            <div class="flex items-center gap-2">
                <h2 class="text-base font-bold text-slate-900">Notifikasi Terkini</h2>
                @if ($unread > 0)<span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700 text-[10px] font-semibold">{{ $unread }} Baru</span>@endif
            </div>

            <div class="mt-4 space-y-2.5 flex-1">
                @forelse ($notifs as $n)
                    @php $new = $n->read_at === null; @endphp
                    <div class="rounded-xl border p-3 text-xs {{ $new ? 'border-slate-200 border-l-4 border-l-[#0d9488] bg-slate-50' : 'border-slate-200 bg-white' }}">
                        <div class="flex items-start justify-between gap-2">
                            <p class="font-semibold text-slate-900">{{ $n->data['judul'] ?? 'Notifikasi' }}</p>
                            <span class="shrink-0 text-[10px] text-slate-400">{{ $n->created_at->locale('id')->diffForHumans() }}</span>
                        </div>
                        <p class="mt-1 text-slate-500 leading-relaxed">{{ $n->data['pesan'] ?? '' }}</p>
                    </div>
                @empty
                    <p class="py-8 text-center text-xs text-slate-400">Belum ada notifikasi.</p>
                @endforelse
            </div>

            @if ($unread > 0)
                <form method="POST" action="{{ route('asesi.notifikasi.baca') }}" class="mt-4 text-center">
                    @csrf
                    <button class="text-xs font-semibold text-slate-700 hover:text-[#0b5a49] transition-colors">Tandai Semua Sudah Dibaca</button>
                </form>
            @endif
        </div>
    </section>
</div>

<script>
    // Tanggal di pojok kanan atas ikut berubah (misalnya saat lewat tengah malam)
    (() => {
        const el = document.querySelector('[data-today]');
        if (!el) return;
        const H = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const B = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const tick = () => {
            const d = new Date();
            el.textContent = `${H[d.getDay()]}, ${String(d.getDate()).padStart(2, '0')} ${B[d.getMonth()]} ${d.getFullYear()}`;
        };
        tick();
        setInterval(tick, 30000);
    })();
</script>
</x-layouts.app>