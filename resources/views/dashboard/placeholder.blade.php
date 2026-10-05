<x-layouts.app :title="$title">
    <div class="bg-white border border-slate-100 rounded-2xl p-10 text-center">
        <p class="text-emerald-700 font-semibold">{{ $title }}</p>
        <p class="text-slate-400 text-sm mt-2">
            {{ ($isDashboard ?? true) ? 'Konten dashboard lengkap belum masuk scope hari ini.' : 'Fitur ini akan segera hadir. Halaman ini hanya placeholder agar menu sidebar tidak mengarah ke link mati.' }}
        </p>
    </div>
</x-layouts.app>