<section class="rounded-2xl border border-slate-200
                bg-white p-6 shadow-sm">

    {{-- HEADER --}}
    <div class="flex flex-col justify-between gap-3
                border-b border-slate-100 pb-5 md:flex-row">

        <div>

            <h2 class="font-bold text-slate-900">

                Progres Sertifikasi: {{ $progress['nama_skema'] }}

            </h2>

            <p class="mt-1 text-xs text-slate-500">

                Alur tahapan resmi sertifikasi BNSP dari
                pendaftaran hingga lisensi kompetensi

            </p>

        </div>

        <span class="h-fit rounded-full border border-emerald-200
                     bg-emerald-50 px-3 py-1.5 text-xs
                     font-semibold text-emerald-700">

            ◉ Tahap {{ $progress['tahap_aktif'] }} dari
            {{ $progress['total_tahap'] }}
            ({{ $progress['persentase'] }}% Selesai)

        </span>

    </div>


    {{-- TIMELINE --}}
    <div class="relative mt-10 grid grid-cols-4 gap-2">

        {{-- GARIS BACKGROUND --}}
        <div class="absolute left-[12.5%] right-[12.5%]
                    top-5 h-1 rounded-full bg-slate-100">
        </div>

        {{-- GARIS PROGRESS --}}
        <div class="absolute left-[12.5%] top-5 h-1
                    rounded-full bg-emerald-600"
             style="width: {{ ($progress['tahap_aktif'] - 1) * 25 }}%">
        </div>


        @foreach ($progress['tahapan'] as $tahap)

            @php
                $selesai = $loop->iteration < $progress['tahap_aktif'];
                $aktif = $loop->iteration === $progress['tahap_aktif'];
            @endphp

            <div class="relative z-10 flex flex-col
                        items-center text-center">

                {{-- CIRCLE --}}
                <div class="flex h-11 w-11 items-center
                            justify-center rounded-full border-2
                            bg-white font-bold transition

                    {{ $selesai
                        ? 'border-emerald-700 bg-emerald-700 text-white'
                        : ($aktif
                            ? 'border-teal-500 text-teal-600 ring-4 ring-teal-50'
                            : 'border-slate-200 text-slate-400') }}">

                    @if ($selesai)

                        ✓

                    @elseif ($aktif)

                        <span class="h-3.5 w-3.5 rounded-full
                                     bg-teal-500"></span>

                    @else

                        {{ $loop->iteration }}

                    @endif

                </div>


                {{-- LABEL --}}
                <p class="mt-3 text-xs font-semibold
                          {{ $aktif || $selesai
                            ? 'text-slate-800'
                            : 'text-slate-500' }}">

                    {{ $tahap['nama'] }}

                </p>

                <p class="mt-1 text-[10px] sm:text-xs

                    {{ $selesai
                        ? 'text-emerald-600'
                        : ($aktif
                            ? 'text-amber-600'
                            : 'text-slate-400') }}">

                    {{ $tahap['status'] }}

                </p>

            </div>

        @endforeach

    </div>


    {{-- INFORMASI --}}
    <div class="mt-8 flex items-start gap-3 rounded-xl
                border border-slate-200 bg-slate-50 p-4">

        <x-icon name="clipboard"
                class="mt-0.5 h-4 w-4 shrink-0 text-emerald-700" />

        <p class="text-xs leading-relaxed text-slate-500">

            Jadwal asesmen sedang berlangsung, silakan menuju
            ke ruangan yang dijadwalkan.

        </p>

    </div>

</section>