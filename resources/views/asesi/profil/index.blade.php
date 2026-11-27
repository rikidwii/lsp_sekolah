{{-- resources/views/asesi/profil/index.blade.php --}}
@php
    $a = 'fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"';
    $ic = [
        'user'   => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
        'check'  => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
        'trash'  => '<path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z"/>',
        'upload' => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/>',
        'lock'   => '<rect width="18" height="11" x="3" y="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
        'eye'    => '<path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>',
        'save'   => '<path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8M7 3v5h8"/>',
        'sync'   => '<path d="M3 12a9 9 0 0 1 15-6.7L21 8M21 3v5h-5M21 12a9 9 0 0 1-15 6.7L3 16M3 21v-5h5"/>',
    ];
    $card = 'bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,.04)]';
    $inp  = 'w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-[#0b5a49] focus:ring-2 focus:ring-emerald-100';

    $parts = preg_split('/\s+/', trim($user->name ?? 'A'));
    $initials = strtoupper(mb_substr($parts[0] ?? 'A', 0, 1) . (isset($parts[1]) ? mb_substr($parts[1], 0, 1) : ''));
    $fotoUrl = $user->foto_profil ? asset('storage/' . $user->foto_profil) : null;
@endphp

<x-layouts.app :title="$title ?? 'Profil Saya'">
<div id="profil-asesi-page" class="space-y-5 max-w-[1100px]">

    {{-- Header --}}
    <div>
        <nav class="text-[11px] text-slate-400 flex items-center gap-2" aria-label="Breadcrumb">
            <span>Portal Asesi</span><span>›</span><span class="font-semibold text-[#0b5a49]">Profil Saya</span>
        </nav>
        <div class="mt-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Profil Saya</h1>
                <p class="mt-1 text-sm text-slate-500">Kelola biodata asesi, foto profil, dan keamanan akun sertifikasi Anda.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 self-start sm:self-auto">
                <button type="submit" form="form-sync-dapodik"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-xs transition-colors cursor-pointer">
                    <svg class="w-4 h-4 text-slate-500" {!! $a !!}>{!! $ic['sync'] !!}</svg> Sinkronisasi Dapodik
                </button>
                <button type="submit" form="form-profil-asesi"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0b5a49] hover:bg-[#074237] px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition-colors cursor-pointer">
                    <svg class="w-4 h-4" {!! $a !!}>{!! $ic['save'] !!}</svg> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    {{-- Notifikasi sukses & error --}}
    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-semibold text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs text-rose-800">
            <p class="font-bold">Periksa kembali input yang dimasukkan:</p>
            <ul class="mt-1 list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form utama: Foto + Biodata --}}
    <form id="form-profil-asesi" method="POST" action="{{ route('asesi.profil.update') }}" enctype="multipart/form-data"
          class="grid grid-cols-1 lg:grid-cols-[310px_minmax(0,1fr)] gap-5 items-start">
        @csrf
        @method('PUT')

        {{-- Kolom kiri: Kartu Foto Profil --}}
        <section class="{{ $card }} p-5 text-center">
            <div class="relative mx-auto w-28 h-28">
                <img id="preview-foto" src="{{ $fotoUrl ?? '' }}" alt="Foto {{ $user->name }}"
                     class="w-28 h-28 rounded-2xl object-cover border border-slate-200 {{ $fotoUrl ? '' : 'hidden' }}">
                <div id="placeholder-foto"
                     class="w-28 h-28 rounded-2xl grid place-items-center text-3xl font-bold text-white {{ $fotoUrl ? 'hidden' : '' }}"
                     style="background:linear-gradient(135deg,#0f766e,#14b8a6)">
                    {{ $initials }}
                </div>
                <span class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-emerald-500 ring-2 ring-white text-white grid place-items-center">
                    <svg class="w-4 h-4" stroke-width="2.6" {!! $a !!}>{!! $ic['check'] !!}</svg>
                </span>
            </div>

            <h2 class="mt-4 text-lg font-bold text-slate-900 break-words">{{ $user->name }}</h2>
            <p class="text-xs text-slate-500">{{ $user->email }}</p>

            <div class="mt-4 rounded-xl border border-slate-100 bg-slate-50 p-3 text-left text-xs space-y-2">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-slate-400">NIS / NISN</span>
                    <span class="font-mono font-semibold text-slate-700">{{ $profile->nis_nisn ?: '-' }}</span>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <span class="text-slate-400">Status Akun</span>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-semibold uppercase">{{ $user->status ?? 'aktif' }}</span>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <span class="text-slate-400">Role</span>
                    <span class="font-semibold text-slate-700 capitalize">{{ $user->role ?? 'asesi' }}</span>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-2">
                <button type="button" id="btn-hapus-foto" @if (! $user->foto_profil) disabled @endif
                        class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white hover:bg-rose-50 hover:text-rose-700 hover:border-rose-200 px-3 py-2 text-xs font-semibold text-slate-700 transition-colors disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">
                    <svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['trash'] !!}</svg> Hapus
                </button>
                <label class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 px-3 py-2 text-xs font-semibold text-[#0b5a49] transition-colors cursor-pointer">
                    <svg class="w-3.5 h-3.5" {!! $a !!}>{!! $ic['upload'] !!}</svg> Ubah Foto
                    <input id="input-foto-profil" type="file" name="foto_profil" accept=".jpg,.jpeg,.png,.webp" class="sr-only">
                </label>
            </div>
            <p class="mt-2.5 text-[11px] text-slate-400 leading-relaxed">Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.</p>
        </section>

        {{-- Kolom kanan: Informasi Pribadi --}}
        <section class="{{ $card }} p-5 sm:p-6">
            <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                <span class="w-9 h-9 rounded-xl bg-emerald-50 text-[#0b5a49] grid place-items-center">
                    <svg class="w-4 h-4" {!! $a !!}>{!! $ic['user'] !!}</svg>
                </span>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Informasi Pribadi</h2>
                    <p class="text-xs text-slate-500">Biodata utama asesi yang tersimpan di akun dan profil sertifikasi</p>
                </div>
            </div>

            <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label class="block sm:col-span-2">
                    <span class="block mb-1.5 text-xs font-semibold text-slate-700">Nama Lengkap</span>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="{{ $inp }}">
                </label>

                <label class="block">
                    <span class="block mb-1.5 text-xs font-semibold text-slate-700">NIS / NISN</span>
                    <input type="text" name="nis_nisn" value="{{ old('nis_nisn', $profile->nis_nisn) }}" placeholder="Contoh: 0081234567" class="{{ $inp }}">
                </label>

                <label class="block">
                    <span class="block mb-1.5 text-xs font-semibold text-slate-700">Email</span>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="{{ $inp }}">
                </label>

                <label class="block">
                    <span class="block mb-1.5 text-xs font-semibold text-slate-700">Nomor Telepon / WhatsApp</span>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 081234567890" class="{{ $inp }}">
                </label>

                <label class="block">
                    <span class="block mb-1.5 text-xs font-semibold text-slate-700">Asal Sekolah</span>
                    <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah', $profile->asal_sekolah) }}" placeholder="Contoh: SMKN 1 Purwosari" class="{{ $inp }}">
                </label>

                <label class="block sm:col-span-2">
                    <span class="block mb-1.5 text-xs font-semibold text-slate-700">Kelas / Jurusan</span>
                    <input type="text" name="kelas_jurusan" value="{{ old('kelas_jurusan', $profile->kelas_jurusan) }}" placeholder="Contoh: XII RPL / PPLG" class="{{ $inp }}">
                </label>

                <label class="block sm:col-span-2">
                    <span class="block mb-1.5 text-xs font-semibold text-slate-700">Alamat Lengkap</span>
                    <textarea name="address" rows="3" placeholder="Masukkan alamat lengkap" class="{{ $inp }} resize-none">{{ old('address', $user->address) }}</textarea>
                </label>
            </div>

            <div class="mt-5 pt-4 border-t border-slate-100 flex flex-wrap justify-end gap-2.5">
                <a href="{{ route('asesi.dashboard') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white hover:bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700 transition-colors">
                    Kembali ke Dashboard
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0b5a49] hover:bg-[#074237] px-5 py-2 text-xs font-semibold text-white transition-colors cursor-pointer">
                    <svg class="w-4 h-4" {!! $a !!}>{!! $ic['save'] !!}</svg> Simpan Perubahan
                </button>
            </div>
        </section>
    </form>

    {{-- Form terpisah: Sinkronisasi Dapodik --}}
    <form id="form-sync-dapodik" method="POST" action="{{ route('asesi.profil.sync') }}" class="hidden">
        @csrf
    </form>

    {{-- Form terpisah: Hapus Foto Profil --}}
    <form id="form-hapus-foto" method="POST" action="{{ route('asesi.profil.foto.destroy') }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    {{-- Form terpisah: Ubah Password --}}
    <section class="{{ $card }} p-5 sm:p-6">
        <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
            <span class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 grid place-items-center">
                <svg class="w-4 h-4" {!! $a !!}>{!! $ic['lock'] !!}</svg>
            </span>
            <div>
                <h2 class="text-base font-bold text-slate-900">Ubah Password</h2>
                <p class="text-xs text-slate-500">Gunakan minimal 8 karakter untuk menjaga keamanan akun Anda</p>
            </div>
        </div>

        <form method="POST" action="{{ route('asesi.profil.password') }}" class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-4">
            @csrf
            @method('PUT')

            <label class="block">
                <span class="block mb-1.5 text-xs font-semibold text-slate-700">Password Saat Ini</span>
                <div class="relative">
                    <input type="password" name="current_password" required autocomplete="current-password"
                           placeholder="Masukkan password saat ini" class="{{ $inp }} pr-10">
                    <button type="button" data-toggle-pw aria-label="Lihat password"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                        <svg class="w-4 h-4" {!! $a !!}>{!! $ic['eye'] !!}</svg>
                    </button>
                </div>
            </label>

            <label class="block">
                <span class="block mb-1.5 text-xs font-semibold text-slate-700">Password Baru</span>
                <div class="relative">
                    <input type="password" name="password" required minlength="8" autocomplete="new-password"
                           placeholder="Minimal 8 karakter" class="{{ $inp }} pr-10">
                    <button type="button" data-toggle-pw aria-label="Lihat password baru"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                        <svg class="w-4 h-4" {!! $a !!}>{!! $ic['eye'] !!}</svg>
                    </button>
                </div>
            </label>

            <label class="block">
                <span class="block mb-1.5 text-xs font-semibold text-slate-700">Konfirmasi Password Baru</span>
                <div class="relative">
                    <input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"
                           placeholder="Ulangi password baru" class="{{ $inp }} pr-10">
                    <button type="button" data-toggle-pw aria-label="Lihat konfirmasi password"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                        <svg class="w-4 h-4" {!! $a !!}>{!! $ic['eye'] !!}</svg>
                    </button>
                </div>
            </label>

            <div class="md:col-span-3 flex justify-end pt-2">
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0b5a49] hover:bg-[#074237] px-5 py-2.5 text-xs font-semibold text-white transition-colors cursor-pointer">
                    Perbarui Password
                </button>
            </div>
        </form>
    </section>
</div>

<script>
(() => {
    const root = document.getElementById('profil-asesi-page');
    if (!root) return;

    const inputFoto = document.getElementById('input-foto-profil');
    const previewFoto = document.getElementById('preview-foto');
    const placeholderFoto = document.getElementById('placeholder-foto');
    const btnHapusFoto = document.getElementById('btn-hapus-foto');
    const formHapusFoto = document.getElementById('form-hapus-foto');
    const hasSavedPhoto = @json((bool) $user->foto_profil);

    if (inputFoto && previewFoto) {
        inputFoto.addEventListener('change', () => {
            const file = inputFoto.files && inputFoto.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                previewFoto.src = e.target.result;
                previewFoto.classList.remove('hidden');
                if (placeholderFoto) placeholderFoto.classList.add('hidden');
                if (btnHapusFoto) btnHapusFoto.disabled = false;
            };
            reader.readAsDataURL(file);
        });
    }

    if (btnHapusFoto && formHapusFoto) {
        btnHapusFoto.addEventListener('click', () => {
            if (!confirm('Hapus foto profil saat ini?')) return;

            if (inputFoto && inputFoto.files && inputFoto.files.length > 0 && !hasSavedPhoto) {
                inputFoto.value = '';
                previewFoto.src = '';
                previewFoto.classList.add('hidden');
                if (placeholderFoto) placeholderFoto.classList.remove('hidden');
                btnHapusFoto.disabled = true;
                return;
            }

            formHapusFoto.submit();
        });
    }

    root.querySelectorAll('[data-toggle-pw]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const input = btn.closest('.relative')?.querySelector('input');
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    });
})();
</script>
</x-layouts.app>