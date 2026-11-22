<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        // TODO (saat backend jadi): ambil jadwal milik asesi ini dari database, lalu kirim ke view.
        // Contoh bentuknya (sesuaikan model & kolomnya):
        //
        // $semua     = Jadwal::with('asesor')->whereHas('pendaftaran', fn ($q) => $q->where('user_id', $user->id))->orderBy('tanggal')->get();
        // $mendatang = $semua->filter(fn ($j) => $j->tanggal->gte(today()))->values();
        // $riwayat   = $semua->filter(fn ($j) => $j->tanggal->lt(today()))->sortByDesc('tanggal')->values();
        //
        // Lalu aktifkan dua baris 'mendatang' dan 'riwayat' di bawah ini.
        // Daftar kolom yang dibutuhkan view ada di komentar paling atas jadwal-asesmen/index.blade.php.

        return view('asesi.jadwal.index', [
            'title' => 'Jadwal Asesmen',
            // 'mendatang' => $mendatang,
            // 'riwayat'   => $riwayat,
        ]);
    }
}