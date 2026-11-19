<section class="rounded-2xl border border-slate-200
                bg-white p-6 shadow-sm">

    {{-- HEADER --}}
    <div class="mb-5 flex items-center justify-between">

        <div class="flex items-center gap-2">

            <h2 class="font-bold text-slate-900">
                Notifikasi Terkini
            </h2>

            <span class="rounded-full bg-emerald-100
                         px-2 py-0.5 text-[10px]
                         font-bold text-emerald-700">

                {{ collect($notifikasi)->where('baru', true)->count() }}
                Baru

            </span>

        </div>

        <button type="button"
                class="text-slate-400 hover:text-slate-700"
                aria-label="Menu notifikasi">

            •••

        </button>

    </div>


    {{-- DAFTAR NOTIFIKASI --}}
    <div class="space-y-3">

        @foreach ($notifikasi as $item)

            <div class="rounded-xl border border-slate-200 p-3.5

                {{ $item['baru']
                    ? 'border-l-4 border-l-teal-500 bg-slate-50'
                    : 'bg-white' }}">

                <div class="flex items-start justify-between gap-2">

                    <div class="flex items-center gap-2">

                        <span class="h-2 w-2 shrink-0 rounded-full

                            {{ $item['baru']
                                ? 'bg-teal-500'
                                : 'bg-slate-300' }}">
                        </span>

                        <h3 class="text-xs font-bold text-slate-800">

                            {{ $item['judul'] }}

                        </h3>

                    </div>

                    <span class="shrink-0 text-[10px] text-slate-400">

                        {{ $item['waktu'] }}

                    </span>

                </div>


                <p class="ml-4 mt-2 text-xs leading-relaxed
                          text-slate-500">

                    {{ $item['pesan'] }}

                </p>

            </div>

        @endforeach

    </div>


    {{-- FOOTER --}}
    <button type="button"
            class="mt-5 w-full rounded-lg py-2
                   text-xs font-semibold text-slate-600
                   transition hover:bg-emerald-50
                   hover:text-emerald-700">

        Tandai Semua Sudah Dibaca

    </button>

</section>