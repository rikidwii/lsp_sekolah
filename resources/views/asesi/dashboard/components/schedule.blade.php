<section class="rounded-2xl border border-slate-200
                bg-white p-6 shadow-sm">

    {{-- HEADER --}}
    <div class="mb-5 flex items-center gap-2">

        <h2 class="font-bold text-slate-900">
            Jadwal Asesmen Terdekat
        </h2>

        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

    </div>


    {{-- JADWAL --}}
    <div class="rounded-xl border border-emerald-200
                bg-emerald-50/40 p-4">

        <div class="flex flex-col gap-4 sm:flex-row
                    sm:items-start">

            {{-- TANGGAL --}}
            <div class="flex h-14 w-14 shrink-0 flex-col
                        items-center justify-center rounded-xl
                        bg-emerald-800 text-white">

                <span class="text-[10px] font-semibold">
                    {{ $jadwal['bulan'] }}
                </span>

                <span class="text-xl font-bold leading-none">
                    {{ $jadwal['tanggal'] }}
                </span>

            </div>


            {{-- DETAIL JADWAL --}}
            <div class="min-w-0 flex-1">

                <h3 class="text-sm font-bold text-slate-800">
                    {{ $jadwal['nama'] }}
                </h3>

                <p class="mt-2 flex items-center gap-2
                          text-xs text-slate-500">

                    <x-icon name="calendar" class="h-4 w-4" />

                    {{ $jadwal['waktu'] }}

                </p>

                <p class="mt-1 flex items-center gap-2
                          text-xs text-slate-500">

                    <x-icon name="grid" class="h-4 w-4" />

                    {{ $jadwal['tempat'] }}

                </p>

                <p class="mt-2 text-[11px] text-slate-500">

                    Asesor Penguji: {{ $jadwal['asesor'] }}

                </p>

            </div>


            {{-- STATUS --}}
            <span class="h-fit shrink-0 rounded-xl
                         border border-slate-300 bg-teal-100
                         px-3 py-2 text-center text-[11px]
                         font-bold text-slate-700">

                {{ $jadwal['status'] }}

            </span>

        </div>

    </div>


    {{-- PERSIAPAN UJIAN --}}
    <div class="mt-4 flex items-start gap-3 rounded-xl
                border border-slate-200 bg-slate-50 p-4">

        <span class="mt-0.5 text-emerald-700">
            ⊕
        </span>

        <div>

            <p class="text-xs font-bold text-slate-700">
                Persiapan Ujian:
            </p>

            <p class="mt-1 text-xs leading-relaxed text-slate-600">
                {{ $jadwal['persiapan'] }}
            </p>

        </div>

    </div>


    {{-- EMPTY STATE --}}
    <div class="mt-4 flex flex-col items-center
                justify-center rounded-xl border
                border-dashed border-slate-300 py-6 text-center">

        <x-icon name="calendar"
                class="h-7 w-7 text-slate-400" />

        <p class="mt-2 text-xs text-slate-500">

            Belum ada jadwal asesmen lain yang dijadwalkan.

        </p>

    </div>

</section>