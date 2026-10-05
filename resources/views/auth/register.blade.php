@php
    $tab = old('_tab', request('type', 'asesi'));
@endphp

<x-layouts.guest title="Daftar — Lembaga Sertifikasi">
    <x-slot:leftPanel>
        <div>
            <div class="flex items-center gap-2 mb-8">
                <span class="font-bold tracking-wide">LEMBAGA SERTIFIKASI</span>
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
            </div>
            <span class="inline-block text-xs bg-white/10 px-3 py-1 rounded-full mb-4">✓ Terakreditasi BNSP Indonesia</span>
            <h2 class="text-3xl font-bold leading-snug">Tingkatkan Kompetensi, <span class="text-emerald-300">Raih Sertifikasi.</span></h2>
            <p class="mt-4 text-emerald-100/80 text-sm">Bergabunglah dengan portal sertifikasi profesional kami. Validasi keahlian Anda dan buka peluang karir yang lebih luas di dunia industri.</p>
        </div>
        <p class="text-xs text-emerald-200/60">Bergabung dengan 1.000+ kandidat lainnya.</p>
    </x-slot:leftPanel>

    <h1 class="text-2xl font-bold text-slate-900">Pendaftaran {{ $tab === 'asesor' ? 'Asesor' : 'Asesi' }}</h1>
    <p class="text-slate-500 mt-1 mb-6">Lengkapi data di bawah ini untuk membuat akun baru.</p>

    <div class="flex bg-slate-100 rounded-full p-1 mb-6">
        <button type="button" id="tab-asesi" class="flex-1 py-2 rounded-full text-sm font-medium">Daftar sebagai Asesi</button>
        <button type="button" id="tab-asesor" class="flex-1 py-2 rounded-full text-sm font-medium">Daftar sebagai Asesor</button>
    </div>

    {{-- FORM ASESI --}}
    <form id="form-asesi" method="POST" action="{{ route('register.asesi') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="_tab" value="asesi">

        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">NAMA LENGKAP *</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm">
            @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">ALAMAT EMAIL *</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm">
                @error('email') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">NOMOR HP *</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm">
                @error('phone') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">ASAL SEKOLAH/INSTANSI *</label>
            <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah') }}" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm">
            @error('asal_sekolah') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">KATA SANDI *</label>
            <input type="password" name="password" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm">
            <p class="text-xs text-slate-400 mt-1">Minimal 8 karakter, kombinasi huruf dan angka.</p>
            @error('password') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-start gap-2 text-sm text-slate-600">
            <input type="checkbox" name="agree" class="mt-0.5 rounded border-slate-300 text-emerald-700">
            Saya menyetujui Syarat & Ketentuan serta Kebijakan Privasi yang berlaku.
        </label>
        @error('agree') <p class="text-rose-500 text-xs">{{ $message }}</p> @enderror

        <button type="submit" class="w-full bg-emerald-800 hover:bg-emerald-900 text-white font-medium py-3 rounded-lg">Daftar Sekarang →</button>
    </form>

    {{-- FORM ASESOR --}}
    <form id="form-asesor" method="POST" action="{{ route('register.asesor') }}" enctype="multipart/form-data" class="space-y-4 hidden">
        @csrf
        <input type="hidden" name="_tab" value="asesor">

        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">NAMA LENGKAP & GELAR *</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Dr. John Doe, M.Kom" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm">
            @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">ALAMAT EMAIL *</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm">
                @error('email') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">NOMOR HP *</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm">
                @error('phone') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">INSTANSI/INSTITUSI ASAL *</label>
                <input type="text" name="instansi_asal" value="{{ old('instansi_asal') }}" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm">
                @error('instansi_asal') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">NO. REGISTRASI BNSP *</label>
                <input type="text" name="no_registrasi_bnsp" value="{{ old('no_registrasi_bnsp') }}" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm">
                @error('no_registrasi_bnsp') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">BIDANG KOMPETENSI *</label>
            <input type="text" name="bidang_kompetensi" value="{{ old('bidang_kompetensi') }}" placeholder="Pilih / tulis bidang kompetensi" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm">
            @error('bidang_kompetensi') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">UPLOAD SERTIFIKAT ASESOR *</label>
            <input type="file" name="sertifikat" accept=".pdf,.jpg,.jpeg,.png"
                   class="w-full rounded-lg border border-dashed border-slate-300 px-4 py-6 text-sm text-center">
            <p class="text-xs text-slate-400 mt-1">PDF, JPG, PNG hingga 5MB</p>
            @error('sertifikat') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">KATA SANDI *</label>
            <input type="password" name="password" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm">
            @error('password') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-start gap-2 text-sm text-slate-600">
            <input type="checkbox" name="agree" class="mt-0.5 rounded border-slate-300 text-emerald-700">
            Saya menyetujui Syarat & Ketentuan serta Kebijakan Privasi yang berlaku.
        </label>
        @error('agree') <p class="text-rose-500 text-xs">{{ $message }}</p> @enderror

        <div class="bg-sky-50 text-sky-700 text-sm rounded-lg px-4 py-3">
            Pendaftar sebagai Asesor akan diverifikasi terlebih dahulu oleh Admin LSP sebelum akun dapat digunakan sepenuhnya.
        </div>

        <button type="submit" class="w-full bg-emerald-800 hover:bg-emerald-900 text-white font-medium py-3 rounded-lg">Daftar Sekarang →</button>
    </form>

    <p class="text-center text-sm text-slate-500 mt-4">
        Sudah punya akun? <a href="{{ route('login') }}" class="text-emerald-700 font-medium">Login di sini</a>
    </p>

    <script>
        const tabAsesi = document.getElementById('tab-asesi');
        const tabAsesor = document.getElementById('tab-asesor');
        const formAsesi = document.getElementById('form-asesi');
        const formAsesor = document.getElementById('form-asesor');

        function showTab(tab) {
            const isAsesi = tab === 'asesi';
            formAsesi.classList.toggle('hidden', !isAsesi);
            formAsesor.classList.toggle('hidden', isAsesi);
            tabAsesi.classList.toggle('bg-emerald-800', isAsesi);
            tabAsesi.classList.toggle('text-white', isAsesi);
            tabAsesi.classList.toggle('text-slate-600', !isAsesi);
            tabAsesor.classList.toggle('bg-emerald-800', !isAsesi);
            tabAsesor.classList.toggle('text-white', !isAsesi);
            tabAsesor.classList.toggle('text-slate-600', isAsesi);
        }

        tabAsesi.addEventListener('click', () => showTab('asesi'));
        tabAsesor.addEventListener('click', () => showTab('asesor'));
        showTab('{{ $tab }}');
    </script>
</x-layouts.guest>