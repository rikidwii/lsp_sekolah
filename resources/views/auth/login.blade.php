<x-layouts.guest title="Login — Lembaga Sertifikasi">
    <x-slot:leftPanel>
        <div>
            <div class="flex items-center gap-2 mb-8">
                <span class="font-bold tracking-wide">LEMBAGA SERTIFIKASI</span>
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
            </div>
            <p class="text-xs text-emerald-300 uppercase mb-6">Pihak Pertama · Lisensi LSP-P1 · BNSP</p>
            <span class="inline-block text-xs bg-white/10 px-3 py-1 rounded-full mb-4">✓ Akses Masuk Terpadu</span>
            <h2 class="text-3xl font-bold leading-snug">Selamat Datang di <span class="text-emerald-300">Portal Sertifikasi Kompetensi.</span></h2>
            <p class="mt-4 text-emerald-100/80 text-sm">Masuk ke akun Anda untuk mengelola jadwal uji kompetensi, verifikasi portofolio APL-01 & APL-02, serta pantau penerbitan sertifikat resmi berstandar nasional.</p>
        </div>
        <p class="text-xs text-emerald-200/60">© {{ date('Y') }} LSP Sekolah. Lembaga Sertifikasi Profesi Terlisensi BNSP Republik Indonesia.</p>
    </x-slot:leftPanel>

    <div class="flex items-center justify-between mb-6 text-sm">
        <a href="{{ route('home') }}" class="text-slate-400 hover:text-slate-600">← Kembali ke Beranda</a>
    </div>

    <h1 class="text-2xl font-bold text-slate-900">Halo, Selamat Datang!</h1>
    <p class="text-slate-500 mt-1 mb-6">Silakan masuk untuk memulai langkah profesional Anda.</p>

    @if ($errors->any())
        <div class="mb-4 px-4 py-3 rounded-lg bg-rose-50 text-rose-600 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">ALAMAT EMAIL / ID ASESI *</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>

        <div>
            <div class="flex justify-between mb-1">
                <label class="block text-xs font-semibold text-slate-500">KATA SANDI *</label>
                <a href="#" class="text-xs text-emerald-700">Lupa kata sandi?</a>
            </div>
            <input type="password" name="password" required
                   class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="remember" class="rounded border-slate-300 text-emerald-700">
            Ingat saya di perangkat ini
        </label>

        <button type="submit" class="w-full bg-emerald-800 hover:bg-emerald-900 text-white font-medium py-3 rounded-lg">
            Masuk Sekarang →
        </button>

        <div class="bg-emerald-50 text-emerald-800 text-sm rounded-lg px-4 py-3">
            Petunjuk Akses: Akun Asesor memerlukan persetujuan admin LSP sebelum dapat digunakan. Untuk Asesi, silakan gunakan ID/email pendaftaran.
        </div>

        <p class="text-center text-sm text-slate-500">
            Belum memiliki akun LSP Sekolah? <a href="{{ route('register') }}" class="text-emerald-700 font-medium">Daftar →</a>
        </p>
    </form>
</x-layouts.guest>