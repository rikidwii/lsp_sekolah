<?php

return [
    'admin' => [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'grid'],
        ['label' => 'Skema Sertifikasi', 'route' => 'placeholder', 'params' => ['title' => 'Skema Sertifikasi'], 'icon' => 'book', 'badge' => '12 Skema'],
        ['label' => 'Kelola Pengguna', 'route' => 'placeholder', 'params' => ['title' => 'Kelola Pengguna'], 'icon' => 'users'],
        ['label' => 'Verifikasi Pendaftaran', 'route' => 'admin.verifikasi.index', 'icon' => 'user-check', 'dynamic_badge' => 'pending_asesor'],
        ['label' => 'Jadwal Asesmen', 'route' => 'placeholder', 'params' => ['title' => 'Jadwal Asesmen'], 'icon' => 'calendar'],
        ['label' => 'Sertifikasi & Blangko', 'route' => 'placeholder', 'params' => ['title' => 'Sertifikasi & Blangko'], 'icon' => 'certificate'],
        ['label' => 'Laporan & Rekapitulasi', 'route' => 'placeholder', 'params' => ['title' => 'Laporan & Rekapitulasi'], 'icon' => 'chart'],
    ],

    'asesor' => [
        ['label' => 'Dashboard', 'route' => 'asesor.dashboard', 'icon' => 'grid'],
        ['label' => 'Daftar Asesi', 'route' => 'asesor.asesi.index', 'icon' => 'users', 'dynamic_badge' => 'total_asesi'],
        ['label' => 'Jadwal Asesmen', 'route' => 'placeholder', 'params' => ['title' => 'Jadwal Asesmen'], 'icon' => 'calendar', 'dot' => true],
        ['label' => 'Riwayat Penilaian', 'route' => 'placeholder', 'params' => ['title' => 'Riwayat Penilaian'], 'icon' => 'clipboard'],
    ],

    'asesi' => [
        ['label' => 'Dashboard', 'route' => 'asesi.dashboard', 'icon' => 'grid'],
        ['label' => 'Daftar Skema', 'route' => 'asesi.skema.index', 'icon' => 'clipboard', 'dynamic_badge' => 'total_skema'],
        ['label' => 'Jadwal Asesmen', 'route' => 'placeholder', 'params' => ['title' => 'Jadwal Asesmen'], 'icon' => 'calendar', 'dot' => true],
        ['label' => 'Hasil & Kelulusan', 'route' => 'placeholder', 'params' => ['title' => 'Hasil & Kelulusan'], 'icon' => 'chart'],
        ['label' => 'Sertifikat Saya', 'route' => 'placeholder', 'params' => ['title' => 'Sertifikat Saya'], 'icon' => 'certificate'],
    ],
];