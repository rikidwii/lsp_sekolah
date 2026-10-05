<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AsesorProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerifikasiPendaftaranController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'menunggu');

        $asesor = AsesorProfile::with('user')
            ->when($status !== 'semua', fn ($q) => $q->where('status_verifikasi', $status))
            ->latest('id')
            ->get();

        return view('admin.verifikasi.index', [
            'title' => 'Verifikasi Pendaftaran',
            'asesor' => $asesor,
            'statusFilter' => $status,
        ]);
    }

    public function approve(AsesorProfile $asesorProfile): RedirectResponse
    {
        $asesorProfile->update(['status_verifikasi' => 'disetujui']);

        return back()->with('success', "Akun asesor {$asesorProfile->user->name} disetujui.");
    }

    public function reject(Request $request, AsesorProfile $asesorProfile): RedirectResponse
    {
        $asesorProfile->update([
            'status_verifikasi' => 'ditolak',
            'catatan_verifikasi' => $request->input('catatan'),
        ]);

        return back()->with('success', "Akun asesor {$asesorProfile->user->name} ditolak.");
    }
}