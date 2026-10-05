<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterAsesiRequest;
use App\Http\Requests\Auth\RegisterAsesorRequest;
use App\Models\AsesiProfile;
use App\Models\AsesorProfile;
use App\Models\KompetensiAsesor;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function storeAsesi(RegisterAsesiRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'asesi',
            'status' => 'aktif',
        ]);

        AsesiProfile::create([
            'user_id' => $user->id,
            'asal_sekolah' => $request->asal_sekolah,
        ]);

        $idAsesi = 'ASI-'.now()->format('Y').'-'.str_pad($user->id, 5, '0', STR_PAD_LEFT);

        return redirect()
            ->route('register.success')
            ->with('id_asesi', $idAsesi);
    }

    public function storeAsesor(RegisterAsesorRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'asesor',
            'status' => 'aktif',
        ]);

        $asesorProfile = AsesorProfile::create([
            'user_id' => $user->id,
            'instansi_asal' => $request->instansi_asal,
            'no_registrasi_bnsp' => $request->no_registrasi_bnsp,
            'status_verifikasi' => 'menunggu',
        ]);

        $path = $request->file('sertifikat')->store('sertifikat-asesor', 'public');

        KompetensiAsesor::create([
            'asesor_profile_id' => $asesorProfile->id,
            'nama_kompetensi' => $request->bidang_kompetensi,
            'file_sertifikat' => $path,
        ]);

        $noTiket = 'ASR-'.now()->format('Y').'-'.str_pad($user->id, 5, '0', STR_PAD_LEFT);

        return redirect()
            ->route('register.pending')
            ->with('no_tiket', $noTiket);
    }

    public function success(): View
    {
        return view('auth.register-success');
    }

    public function pending(): View
    {
        return view('auth.pending-verification');
    }
}