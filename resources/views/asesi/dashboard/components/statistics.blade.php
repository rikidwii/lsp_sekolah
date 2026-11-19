@php
    $styles = [
        'emerald' => [
            'icon' => 'bg-emerald-50 text-emerald-700',
            'badge' => 'bg-amber-50 text-amber-700 border-amber-200',
        ],
        'blue' => [
            'icon' => 'bg-blue-50 text-blue-600',
            'badge' => 'bg-slate-100 text-slate-600 border-slate-100',
        ],
        'violet' => [
            'icon' => 'bg-violet-50 text-violet-600',
            'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        ],
    ];
@endphp

<section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

    @foreach ($statistik as $item)

        @php
            $style = $styles[$item['warna']];
        @endphp

        <div class="rounded-2xl border border-slate-200
                    bg-white p-5 shadow-sm transition
                    hover:-translate-y-1 hover:shadow-md">

            {{-- ICON DAN BADGE --}}
            <div class="mb-4 flex items-center justify-between">

                <div class="flex h-10 w-10 items-center
                            justify-center rounded-xl
                            {{ $style['icon'] }}">

                    @if ($item['icon'] === 'code')

                        <span class="text-lg font-bold">&lt;/&gt;</span>

                    @else

                        <x-icon :name="$item['icon']"
                                class="h-5 w-5" />

                    @endif

                </div>

                <span class="rounded-full border px-2.5 py-1
                             text-[10px] font-semibold
                             {{ $style['badge'] }}">

                    {{ $item['badge'] }}

                </span>

            </div>

            {{-- INFORMASI --}}
            <p class="text-[10px] font-semibold tracking-wide
                      text-slate-400">

                {{ $item['judul'] }}

            </p>

            <div class="mt-1 flex flex-wrap items-baseline gap-1">

                <h3 class="text-lg font-bold text-slate-900">

                    {{ $item['nilai'] }}

                </h3>

                @if ($item['keterangan'])

                    <span class="text-xs text-slate-400">

                        {{ $item['keterangan'] }}

                    </span>

                @endif

            </div>

        </div>

    @endforeach

</section>