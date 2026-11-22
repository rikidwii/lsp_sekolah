{{-- resources/views/asesi/skema/_kartu-registrasi.blade.php  (FILE BARU)
     Kartu registrasi (e-card) berbentuk SVG. Dipakai oleh tombol "Unduh Kartu Registrasi" di index.blade.php.
     Variabel: $nama (nama asesi, dari database), $skema (nama skema), $noReg (mis. REG-BNSP-2024-8831) --}}
@php
    $nama  = \Illuminate\Support\Str::limit(mb_strtoupper(trim($nama ?? 'Nama Asesi')), 32, '');
    $skema = \Illuminate\Support\Str::limit(mb_strtoupper(trim($skema ?? 'Nama Skema')), 48, '…');
    $noReg = trim($noReg ?? '-');
    $fsNama  = max(18, min(40, (int) floor(392 / (0.68 * max(1, mb_strlen($nama))))));
    $fsSkema = max(12, min(20, (int) floor(392 / (0.62 * max(1, mb_strlen($skema))))));
    $fsReg   = max(18, min(32, (int) floor(330 / (0.62 * max(1, mb_strlen($noReg))))));
@endphp
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 400" width="640" height="400" font-family="Arial, Helvetica, sans-serif">
    <defs>
        <clipPath id="kartuClip"><rect width="640" height="400" rx="36"/></clipPath>
    </defs>
    <g clip-path="url(#kartuClip)">
        {{-- latar putih --}}
        <rect width="640" height="400" fill="#f8f8f9"/>
        {{-- gelombang abu-abu di kanan --}}
        <path d="M330 235 C430 292 540 300 640 262 L640 205 C560 262 430 262 330 235 Z" fill="#e8e8ec"/>
        {{-- area biru tua --}}
        <path d="M0 170 C110 150 215 168 330 235 C430 292 540 300 640 262 L640 400 L0 400 Z" fill="#11304a"/>
        {{-- garis putih diagonal --}}
        <path d="M398 262 C470 300 510 345 545 400" fill="none" stroke="#ffffff" stroke-width="6" stroke-linecap="round"/>

        {{-- icon toga --}}
        <g transform="translate(452 52) scale(4)" fill="none" stroke="#11304a" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/>
            <path d="M22 10v6"/>
            <path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/>
        </g>

        {{-- nama asesi & nama skema --}}
        <text x="48" y="112" font-size="{{ $fsNama }}" font-weight="800" fill="#11304a">{{ $nama }}</text>
        <text x="48" y="144" font-size="{{ $fsSkema }}" font-weight="400" fill="#11304a" letter-spacing="1">{{ $skema }}</text>

        {{-- nomor registrasi --}}
        <text x="48" y="292" font-size="13" font-weight="700" fill="#8fb0c9" letter-spacing="3">NO. REGISTRASI</text>
        <text x="48" y="330" font-size="{{ $fsReg }}" font-weight="800" fill="#ffffff" letter-spacing="1.5">{{ $noReg }}</text>
    </g>
</svg>