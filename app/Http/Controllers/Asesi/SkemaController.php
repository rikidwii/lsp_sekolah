<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\SkemaSertifikasi;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class SkemaController extends Controller
{
    public function index(): View
    {
        $skema = SkemaSertifikasi::where('status', 'aktif')
            ->orderBy('nama_skema')
            ->get();

        return view('asesi.skema.index', [
            'title' => 'Daftar Skema',
            'skema' => $skema,
        ]);
    }
    public function transkrip(string $kode)
    {
        // TODO: ambil hasil asesmen MILIK asesi yang login untuk skema $kode (abort(404) jika tidak ada),
        // lalu susun array $hasil sesuai kontrak di komentar atas transkrip.blade.php
        $hasil = null; // null = pakai data dummy

        if (class_exists(Pdf::class)) {
            return Pdf::loadView('asesi.skema.transkrip', ['hasil' => $hasil])
                ->setPaper('a4')->download("Transkrip-{$kode}.pdf");
        }
        return view('asesi.skema.transkrip', ['hasil' => $hasil]); // preview HTML bila dompdf belum terpasang
    }
    public function sertifikat(string $kode)
{
    // TODO backend: ambil sertifikat milik asesi login yang sudah diterbitkan admin
    abort(404, 'Sertifikat belum diterbitkan.');
}
}