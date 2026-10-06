<x-layouts.guest title="Pendaftaran Berhasil — Lembaga Sertifikasi">
    <x-slot:leftPanel>
        <div>
            <div class="flex items-center gap-2 mb-8">
                <span class="font-bold tracking-wide">LEMBAGA SERTIFIKASI</span>
            </div>
            <span class="inline-block text-xs bg-white/10 px-3 py-1 rounded-full mb-4">✓ Akun Asesi Aktif</span>
            <h2 class="text-3xl font-bold leading-snug">Selamat Bergabung dengan Kami <span class="text-emerald-300">Sertifikasi Kompetensi.</span></h2>
        </div>
        <p class="text-xs text-emerald-200/60">Terhubung langsung dengan Master Asesor BNSP & Dunia Industri</p>
    </x-slot:leftPanel>

    <div class="bg-white rounded-2xl border border-slate-100 p-8 text-center">
        <div class="w-14 h-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4">✓</div>
        <span class="inline-block text-xs bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full mb-4">REGISTRASI BERHASIL</span>
        <h1 class="text-xl font-bold text-slate-900">Pendaftaran Berhasil!</h1>
        <p class="text-slate-500 text-sm mt-2">Akun Asesi Anda telah aktif. Silakan masuk (login) ke dashboard untuk mulai memilih skema sertifikasi.</p>

        <div class="mt-6 text-left text-sm bg-slate-50 rounded-xl p-4 space-y-2">
            <div class="flex justify-between"><span class="text-slate-500">ID Asesi / No. Akun</span><span class="font-medium">{{ session('id_asesi', '-') }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Status Akun</span><span class="text-emerald-600 font-medium">Aktif & Terdaftar</span></div>
        </div>

        <a href="{{ route('login') }}" class="mt-6 block w-full py-3 rounded-lg bg-emerald-800 text-white text-sm font-medium">Login Sekarang →</a>
        <a href="{{ route('guest.index') }}" class="mt-3 block w-full py-3 rounded-lg border border-slate-200 text-sm font-medium">Kembali ke Beranda</a>
</x-layouts.guest>