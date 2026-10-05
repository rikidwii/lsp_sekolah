<x-layouts.app :title="$title">
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($skema as $item)
            <div class="bg-white border border-slate-100 rounded-2xl p-5">
                <span class="text-xs text-emerald-600 font-semibold">{{ $item->kode_skema }}</span>
                <h3 class="font-semibold text-slate-800 mt-1">{{ $item->nama_skema }}</h3>
                <p class="text-xs text-slate-400 mt-1">{{ $item->kategori }}</p>
                <p class="text-sm text-slate-500 mt-3 line-clamp-2">{{ $item->deskripsi }}</p>
                <div class="mt-4 flex items-center justify-between text-sm">
                    <span class="text-slate-500">{{ $item->durasi_ujian }}</span>
                    <span class="font-semibold text-slate-800">Rp {{ number_format($item->biaya, 0, ',', '.') }}</span>
                </div>
            </div>
        @empty
            <p class="text-slate-400 col-span-full text-center py-8">Belum ada skema aktif.</p>
        @endforelse
    </div>
</x-layouts.app>