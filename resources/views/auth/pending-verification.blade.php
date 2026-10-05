<x-layouts.guest title="Menunggu Verifikasi — Lembaga Sertifikasi">
    <x-slot:leftPanel>
        <div>
            <div class="flex items-center gap-2 mb-8">
                <span class="font-bold tracking-wide">LEMBAGA SERTIFIKASI</span>
            </div>
            <span class="inline-block text-xs bg-white/10 px-3 py-1 rounded-full mb-4">✓ Terakreditasi BNSP Indonesia</span>
            <h2 class="text-3xl font-bold leading-snug">Validasi Portofolio Asesor Sedang <span class="text-emerald-300">Berlangsung.</span></h2>
            <p class="mt-4 text-emerald-100/80 text-sm">Tim verifikator dan Komite Skema LSP Sekolah sedang meninjau kelayakan dokumen, portofolio, dan nomor registrasi BNSP Anda.</p>
        </div>
        <p class="text-xs text-emerald-200/60">Standar Mutu Asesmen ISO 17024</p>
    </x-slot:leftPanel>

    <div class="bg-white rounded-2xl border border-slate-100 p-8 text-center">
        <div class="w-14 h-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4">⏱</div>
        <span class="inline-block text-xs bg-amber-50 text-amber-600 px-3 py-1 rounded-full mb-4">STATUS: MENUNGGU VERIFIKASI ADMIN</span>
        <h1 class="text-xl font-bold text-slate-900">Pendaftaran Terkirim, Menunggu Verifikasi</h1>
        <p class="text-slate-500 text-sm mt-2">Admin LSP Sekolah akan meninjau kredensial, sertifikat asesor, dan nomor registrasi BNSP Anda. Anda akan menerima notifikasi email setelah akun disetujui.</p>

        <div class="mt-6 text-left text-sm bg-slate-50 rounded-xl p-4 space-y-2">
            <div class="flex justify-between"><span class="text-slate-500">No. Tiket Registrasi</span><span class="font-medium">{{ session('no_tiket', '-') }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Estimasi Waktu Proses</span><span class="font-medium">1 × 24 Jam Kerja</span></div>
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('home') }}" class="flex-1 py-3 rounded-lg border border-slate-200 text-sm font-medium">Kembali ke Beranda</a>
            <a href="mailto:admin@lsp-sekolah.sch.id" class="flex-1 py-3 rounded-lg bg-emerald-800 text-white text-sm font-medium">Hubungi Helpdesk</a>
        </div>
    </div>
</x-layouts.guest>