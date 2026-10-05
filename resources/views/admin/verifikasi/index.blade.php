<x-layouts.app :title="$title">
    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-700 text-sm">{{ session('success') }}</div>
    @endif

    <div class="flex gap-2 mb-5">
        @foreach (['menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak', 'semua' => 'Semua'] as $key => $label)
            <a href="{{ route('admin.verifikasi.index', ['status' => $key]) }}"
               class="px-4 py-2 rounded-full text-sm font-medium {{ $statusFilter === $key ? 'bg-emerald-800 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                <tr>
                    <th class="text-left px-5 py-3">Nama</th>
                    <th class="text-left px-5 py-3">Email</th>
                    <th class="text-left px-5 py-3">No. Registrasi BNSP</th>
                    <th class="text-left px-5 py-3">Instansi</th>
                    <th class="text-left px-5 py-3">Status</th>
                    <th class="text-left px-5 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($asesor as $item)
                    <tr>
                        <td class="px-5 py-3 font-medium text-slate-800">{{ $item->user->name }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ $item->user->email }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ $item->no_registrasi_bnsp ?? '-' }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ $item->instansi_asal ?? '-' }}</td>
                        <td class="px-5 py-3">
                            <span @class([
                                'text-xs px-2 py-1 rounded-full',
                                'bg-amber-50 text-amber-600' => $item->status_verifikasi === 'menunggu',
                                'bg-emerald-50 text-emerald-600' => $item->status_verifikasi === 'disetujui',
                                'bg-rose-50 text-rose-600' => $item->status_verifikasi === 'ditolak',
                            ])>{{ ucfirst($item->status_verifikasi) }}</span>
                        </td>
                        <td class="px-5 py-3">
                            @if ($item->status_verifikasi === 'menunggu')
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('admin.verifikasi.approve', $item) }}">
                                        @csrf @method('PATCH')
                                        <button class="text-xs px-3 py-1.5 rounded-lg bg-emerald-800 text-white font-medium">Setujui</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.verifikasi.reject', $item) }}">
                                        @csrf @method('PATCH')
                                        <button class="text-xs px-3 py-1.5 rounded-lg border border-rose-200 text-rose-600 font-medium">Tolak</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-xs text-slate-400">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-slate-400">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>