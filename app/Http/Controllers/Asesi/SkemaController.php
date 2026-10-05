<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\SkemaSertifikasi;
use Illuminate\View\View;

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
}