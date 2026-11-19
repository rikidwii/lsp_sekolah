<section class="relative overflow-hidden rounded-2xl border
                border-slate-200 bg-white shadow-sm">

    {{-- Garis hijau di sebelah kiri --}}
    <div class="absolute inset-y-0 left-0 w-1.5
                bg-gradient-to-b from-emerald-700 to-teal-400">
    </div>

    {{-- Background dekorasi --}}
    <div class="absolute -right-10 -top-20 h-64 w-64
                rounded-full bg-emerald-50/70 blur-3xl">
    </div>

    <div class="relative flex flex-col justify-between gap-5
                p-6 md:flex-row md:items-center">

        {{-- PROFIL ASESІ --}}
        <div class="flex items-center gap-4">

            {{-- Foto Profil --}}
            <div class="relative shrink-0">

                @if ($asesi['foto'])

                    <img
                        src="{{ asset($asesi['foto']) }}"
                        alt="Foto profil asesi"
                        class="h-[76px] w-[76px] rounded-2xl
                               border-4 border-slate-100 object-cover"
                    >

                @else

                    <div class="flex h-[76px] w-[76px] items-center
                                justify-center rounded-2xl
                                border-4 border-slate-100
                                bg-emerald-100 text-2xl
                                font-bold text-emerald-800">

                        {{ strtoupper(substr($asesi['nama'], 0, 1)) }}

                    </div>

                @endif

                {{-- Status aktif --}}
                <span class="absolute -bottom-1 -right-1 flex h-6 w-6
                             items-center justify-center rounded-full
                             border-2 border-white bg-emerald-700
                             text-xs font-bold text-white">

                    ✓

                </span>

            </div>

            {{-- Informasi Asesi --}}
            <div class="min-w-0">

                <div class="flex flex-wrap items-center gap-3">

                    <h2 class="text-xl font-bold tracking-tight
                               text-slate-900 md:text-2xl">

                        Halo, {{ $asesi['nama'] }}!

                    </h2>

                    <span class="rounded-full border border-emerald-200
                                 bg-emerald-50 px-3 py-1
                                 text-[10px] font-semibold
                                 text-emerald-700">

                        {{ $asesi['status'] }}

                    </span>

                </div>

                <p class="mt-2 text-xs text-slate-400">

                    No. Reg: {{ $asesi['no_registrasi'] }}

                </p>

                <p class="mt-2 max-w-xl text-sm leading-relaxed
                          text-slate-500">

                    Selamat datang di portal asesi LSP Sekolah.
                    Pantau terus progres sertifikasi kompetensimu
                    menuju pengakuan sertifikat nasional standar BNSP.

                </p>

            </div>

        </div>

        {{-- TANGGAL --}}
        <div class="flex shrink-0 items-center gap-2 self-start
                    rounded-xl bg-emerald-800 px-4 py-3
                    text-xs font-medium text-white shadow-sm
                    md:self-center">

            <x-icon name="calendar" class="h-4 w-4" />

            {{ now()->locale('id')->translatedFormat('l, d M Y') }}

        </div>

    </div>

</section>