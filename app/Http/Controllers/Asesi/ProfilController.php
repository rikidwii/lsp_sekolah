<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\AsesiProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $profile = AsesiProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nis_nisn' => '',
                'asal_sekolah' => '',
                'kelas_jurusan' => '',
            ]
        );

        return view('asesi.profil.index', compact('user', 'profile'));
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
            'nis_nisn' => ['nullable', 'string', 'max:50'],
            'asal_sekolah' => ['nullable', 'string', 'max:255'],
            'kelas_jurusan' => ['nullable', 'string', 'max:255'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $fotoPath = $user->foto_profil;

        if ($request->hasFile('foto_profil')) {
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }

            $fotoPath = $request->file('foto_profil')->store('profil', 'public');
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'foto_profil' => $fotoPath,
        ]);

        AsesiProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nis_nisn' => $validated['nis_nisn'] ?? '',
                'asal_sekolah' => $validated['asal_sekolah'] ?? '',
                'kelas_jurusan' => $validated['kelas_jurusan'] ?? '',
            ]
        );

        return redirect()
            ->route('asesi.profil.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function destroyFoto(): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        $user->update(['foto_profil' => null]);

        return redirect()
            ->route('asesi.profil.index')
            ->with('success', 'Foto profil berhasil dihapus.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                ->withInput();
        }

        $user->update([
            'password' => $validated['password'],
        ]);

        return redirect()
            ->route('asesi.profil.index')
            ->with('success', 'Password berhasil diperbarui.');
    }
    public function syncDapodik(): RedirectResponse
{
    /** @var User $user */
    $user = Auth::user();

    AsesiProfile::firstOrCreate(
        ['user_id' => $user->id],
        [
            'nis_nisn' => '',
            'asal_sekolah' => '',
            'kelas_jurusan' => '',
        ]
    );

    return redirect()
        ->route('asesi.profil.index')
        ->with('success', 'Data profil berhasil disinkronkan.');
}
}