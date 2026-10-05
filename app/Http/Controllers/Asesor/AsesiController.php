<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class AsesiController extends Controller
{
    public function index(): View
    {
        $asesi = User::where('role', 'asesi')
            ->with('asesiProfile')
            ->orderBy('name')
            ->get();

        return view('asesor.asesi.index', [
            'title' => 'Daftar Asesi',
            'asesi' => $asesi,
        ]);
    }
}