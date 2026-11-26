<?php

// app/Http/Controllers/Asesor/AsesiController.php  (versi baru, menggantikan yang lama)

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AsesiController extends Controller
{
    public function index(Request $request): View
    {
        $q      = trim((string) $request->query('q', ''));
        $status = $request->query('status');

        $query = User::where('role', 'asesi')
            ->with('asesiProfile')
            ->orderBy('name');

        // Pencarian nama atau NIS/NISN
        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhereHas('asesiProfile', fn ($p) => $p->where('nis_nisn', 'like', "%{$q}%"));
            });
        }

        // TODO (setelah ada tabel asesmen/jadwal): filter berdasarkan status, skema, dan tanggal.
        // Sementara, tab selain "Semua Asesi" dikosongkan karena belum ada data statusnya.
        if (in_array($status, ['menunggu', 'proses', 'selesai'], true)) {
            $query->whereRaw('1 = 0');
        }

        $total = User::where('role', 'asesi')->count();

        $daftarAsesi = $query->paginate(10)->withQueryString()->through(fn (User $u) => (object) [
            'id'        => $u->id,
            'nama'      => $u->name,
            'nis'       => $u->asesiProfile?->nis_nisn ?? '-',
            'sekolah'   => $u->asesiProfile?->asal_sekolah ?? '-',
            'foto'      => null,   // isi URL foto bila kolomnya sudah ada
            'skema'     => null,   // TODO: dari pendaftaran/asesmen
            'tanggal'   => null,   // TODO: jadwal asesmen
            'jam'       => null,
            'ruang'     => null,
            'status'    => null,   // menunggu | proses | kompeten | belum_kompeten
            'url_nilai' => '#',
            'url_lihat' => '#',
        ]);

        return view('asesor.asesi.index', [
            'title'       => 'Daftar Asesi',
            'daftarAsesi' => $daftarAsesi,
            'ringkasan'   => [
                'total'    => $total,
                'persen'   => null,   // kenaikan dibanding periode lalu (opsional)
                'periode'  => null,   // mis. 'Semester Ganjil 2024' (opsional)
                'menunggu' => 0,
                'proses'   => 0,
                'selesai'  => 0,
            ],
            'skemaList'   => [],
        ]);
    }
}