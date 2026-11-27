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

        // TODO (saat backend jadi): ambil jadwal milik asesi ini (hari ini dan seterusnya), lalu kirim sebagai $jadwal.
        // Riwayat selesai tidak lagi ditampilkan di halaman ini (sudah ada di menu "Daftar Skema").
        //
        // $jadwal = Jadwal::with(['skema', 'asesor'])
        //     ->whereHas('pendaftaran', fn ($q) => $q->where('user_id', $user->id))
        //     ->whereDate('tanggal', '>=', today())
        //     ->orderBy('tanggal')
        //     ->get()
        //     ->map(fn ($j) => (object) [
        //         'tanggal'      => $j->tanggal,                 // Carbon
        //         'waktu_mulai'  => $j->waktu_mulai,             // "08:00" (hanya jam mulai)
        //         'jenis'        => $j->skema->tipe,             // Klaster | Okupasi | KKNI
        //         'judul'        => $j->skema->nama,
        //         'bidang'       => $j->skema->kategori,         // bidang keahlian (opsional)
        //         'asesor'       => $j->asesor->name,
        //         'catatan'      => $j->catatan,                 // opsional
        //         'catatan_tone' => 'info',                      // warning | info
        //         'no_registrasi' => $j->pendaftaran->no_registrasi, // untuk Kartu Registrasi PNG
        //         'nama_asesi'   => $user->name,                 // opsional (default: nama user login)
        //     ]);
        //
        // Lalu aktifkan baris 'jadwal' di bawah ini.

        return view('asesi.jadwal.index', [
            'title' => 'Jadwal Asesmen',
            // 'jadwal' => $jadwal,
        ]);
    }
}