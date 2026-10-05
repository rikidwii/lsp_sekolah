<x-layouts.app :title="$title">
    <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                <tr>
                    <th class="text-left px-5 py-3">Nama</th>
                    <th class="text-left px-5 py-3">Email</th>
                    <th class="text-left px-5 py-3">NIS/NISN</th>
                    <th class="text-left px-5 py-3">Asal Sekolah</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($asesi as $item)
                    <tr>
                        <td class="px-5 py-3 font-medium text-slate-800">{{ $item->name }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ $item->email }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ $item->asesiProfile?->nis_nisn ?? '-' }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ $item->asesiProfile?->asal_sekolah ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center text-slate-400">Belum ada asesi terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>