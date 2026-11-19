<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function redirectHome(): RedirectResponse
    {
        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'asesor' => redirect()->route('asesor.dashboard'),
            default => redirect()->route('asesi.dashboard'),
        };
    }

    public function admin(): View
    {
        return view('dashboard.placeholder', ['title' => 'Dashboard']);
    }

    public function asesor(): View
    {
        return view('dashboard.placeholder', ['title' => 'Dashboard']);
    }

    public function asesi(): View
{
    $user = Auth::user();

    // Informasi Asesi
    $asesi = [
        'nama' => $user->name,
        'no_registrasi' => 'ASI-2024-78419',
        'status' => 'Asesi Aktif',
        'foto' => null,
    ];

    // Statistik Dashboard
    $statistik = [
        [
            'judul' => 'STATUS PENDAFTARAN',
            'nilai' => 'Verifikasi Dokumen',
            'keterangan' => '',
            'icon' => 'clipboard',
            'badge' => 'Diproses',
            'warna' => 'emerald',
        ],
        [
            'judul' => 'JUMLAH SKEMA YANG DIIKUTI',
            'nilai' => '3',
            'keterangan' => 'Skema',
            'icon' => 'code',
            'badge' => '+3 Skema',
            'warna' => 'blue',
        ],
        [
            'judul' => 'JADWAL TERDEKAT',
            'nilai' => '25 Okt 2026',
            'keterangan' => '',
            'icon' => 'calendar',
            'badge' => 'H-0 Hari Ini',
            'warna' => 'violet',
        ],
        [
            'judul' => 'SERTIFIKAT DIPEROLEH',
            'nilai' => '0',
            'keterangan' => 'Sertifikat',
            'icon' => 'certificate',
            'badge' => 'Target 1',
            'warna' => 'emerald',
        ],
    ];

    // Progress Sertifikasi
    $progress = [
        'nama_skema' => 'Junior Web Developer',
        'tahap_aktif' => 2,
        'total_tahap' => 4,
        'persentase' => 50,

        'tahapan' => [
            [
                'nama' => 'Pendaftaran',
                'status' => 'Selesai',
            ],
            [
                'nama' => 'Jadwal Asesmen',
                'status' => 'Sedang Berlangsung',
            ],
            [
                'nama' => 'Uji Penilaian',
                'status' => 'Menunggu',
            ],
            [
                'nama' => 'Sertifikat',
                'status' => 'Final',
            ],
        ],
    ];

    // Jadwal Asesmen
    $jadwal = [
        'tanggal' => '25',
        'bulan' => 'OKT',
        'nama' => 'Ujian Praktik - Web Developer',
        'waktu' => '08:00 - 12:00 WIB',
        'tempat' => 'Lab Komputer A (TUK Utama)',
        'asesor' => 'Dr. Ir. Budi Santoso, M.Kom',
        'status' => 'Wajib Hadir',
        'persiapan' => 'Pastikan membawa kartu identitas siswa/KTP, laptop dengan teks editor (VS Code), serta rangkuman portofolio APL-02 yang telah ditandatangani.',
    ];

    // Notifikasi
    $notifikasi = [
        [
            'judul' => 'Dokumen Diterima',
            'waktu' => '2 jam yang lalu',
            'pesan' => 'Pendaftaran skema Junior Web Developer telah diverifikasi tahap awal. Silakan persiapkan diri untuk uji praktik.',
            'baru' => true,
        ],
        [
            'judul' => 'Jadwal Asesmen Ditetapkan',
            'waktu' => 'Kemarin, 14:30',
            'pesan' => 'Ruang asesmen untuk uji kompetensi telah dipindahkan ke Lab Komputer A (Lantai 2).',
            'baru' => false,
        ],
        [
            'judul' => 'Akun Berhasil Dibuat',
            'waktu' => '20 Okt 2024',
            'pesan' => 'Selamat datang di Portal Asesi. Lengkapi biodata profil Anda sebelum batas waktu pendaftaran.',
            'baru' => false,
        ],
    ];

    return view('asesi.dashboard.index', compact(
        'asesi',
        'statistik',
        'progress',
        'jadwal',
        'notifikasi'
    ));
}
}