<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AsesorDashboardController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        $user = $request->user();
        $namaAsesor = $user?->name ?? 'Budi Santoso, M.Kom';
        $noReg = $user?->asesorProfile?->no_registrasi ?? 'MET.000.002819 2021';
        $bidang = $user?->asesorProfile?->bidang ?? 'TIK & Rekayasa Perangkat Lunak';

        $ringkasan = [
            'asesi_aktif' => 142,
            'kenaikan_asesi' => '+12% dari bulan lalu',
            'jadwal_hari_ini' => 8,
            'rincian_jadwal' => '4 Sesi Pagi • 4 Sesi Siang',
            'penilaian_selesai' => 89,
            'catatan_selesai' => 'Minggu ini • Tersinkronisasi',
            'belum_dinilai' => 12,
            'catatan_belum' => 'Perlu tindakan segera',
        ];

        $tahunTersedia = ['2024', '2025', '2026'];
        $tahunAktif = in_array((string) $request->query('tahun', '2024'), $tahunTersedia, true)
            ? (string) $request->query('tahun', '2024')
            : '2024';

        $statistikTahunan = [
            '2024' => [
                'bulan_aktif_label' => 'Oktober (Asesmen Berjalan)',
                'bulan_aktif_index' => 5, // Okt (indeks 0..7 pada Mei..Des)
                'tooltip_bulan' => 'Oktober 2024',
                'tooltip_total' => 44,
                'tooltip_kelulusan' => '92.5%',
                'bulan' => [
                    ['label' => 'Mei', 'total' => 36, 'kompeten' => 16],
                    ['label' => 'Jun', 'total' => 25, 'kompeten' => 12],
                    ['label' => 'Jul', 'total' => 21, 'kompeten' => 13],
                    ['label' => 'Agu', 'total' => 39, 'kompeten' => 23],
                    ['label' => 'Sep', 'total' => 29, 'kompeten' => 11],
                    ['label' => 'Okt', 'total' => 44, 'kompeten' => 30],
                    ['label' => 'Nov', 'total' => 35, 'kompeten' => 16],
                    ['label' => 'Des', 'total' => 26, 'kompeten' => 11],
                ],
            ],
            '2025' => [
                'bulan_aktif_label' => 'Oktober (Asesmen Berjalan)',
                'bulan_aktif_index' => 5,
                'tooltip_bulan' => 'Oktober 2025',
                'tooltip_total' => 48,
                'tooltip_kelulusan' => '94.0%',
                'bulan' => [
                    ['label' => 'Mei', 'total' => 32, 'kompeten' => 18],
                    ['label' => 'Jun', 'total' => 28, 'kompeten' => 15],
                    ['label' => 'Jul', 'total' => 24, 'kompeten' => 14],
                    ['label' => 'Agu', 'total' => 41, 'kompeten' => 25],
                    ['label' => 'Sep', 'total' => 33, 'kompeten' => 17],
                    ['label' => 'Okt', 'total' => 48, 'kompeten' => 32],
                    ['label' => 'Nov', 'total' => 37, 'kompeten' => 20],
                    ['label' => 'Des', 'total' => 30, 'kompeten' => 16],
                ],
            ],
            '2026' => [
                'bulan_aktif_label' => 'November (Asesmen Berjalan)',
                'bulan_aktif_index' => 6,
                'tooltip_bulan' => 'November 2026',
                'tooltip_total' => 52,
                'tooltip_kelulusan' => '95.0%',
                'bulan' => [
                    ['label' => 'Mei', 'total' => 34, 'kompeten' => 19],
                    ['label' => 'Jun', 'total' => 30, 'kompeten' => 17],
                    ['label' => 'Jul', 'total' => 27, 'kompeten' => 16],
                    ['label' => 'Agu', 'total' => 42, 'kompeten' => 27],
                    ['label' => 'Sep', 'total' => 35, 'kompeten' => 20],
                    ['label' => 'Okt', 'total' => 46, 'kompeten' => 31],
                    ['label' => 'Nov', 'total' => 52, 'kompeten' => 35],
                    ['label' => 'Des', 'total' => 38, 'kompeten' => 24],
                ],
            ],
        ];

        $jadwalTerdekat = [
            [
                'tanggal' => '25',
                'bulan' => 'OKT',
                'skema' => 'Skema Junior Web Developer',
                'badge' => 'Siap Diuji',
                'badge_type' => 'success',
                'waktu' => '08:00 - 10:00 WIB',
                'lokasi' => 'Lab Komputer RPL A',
                'metode' => 'offline',
                'jumlah_asesi' => 24,
            ],
            [
                'tanggal' => '25',
                'bulan' => 'OKT',
                'skema' => 'Skema Desain Grafis Muda',
                'badge' => 'Siap Diuji',
                'badge_type' => 'success',
                'waktu' => '10:30 - 12:30 WIB',
                'lokasi' => 'Lab Multimedia',
                'metode' => 'offline',
                'jumlah_asesi' => 18,
            ],
            [
                'tanggal' => '26',
                'bulan' => 'OKT',
                'skema' => 'Skema Network Administrator',
                'badge' => 'H-1',
                'badge_type' => 'warning',
                'waktu' => '09:00 - 11:30 WIB',
                'lokasi' => 'Google Meet / Daring',
                'metode' => 'online',
                'jumlah_asesi' => 15,
            ],
        ];

        $statusKelulusan = [
            'persentase' => 65,
            'label_tengah' => 'Lulus',
            'kompeten' => 58,
            'sedang_proses' => 18,
            'belum_kompeten' => 13,
        ];

        return view('asesor.dashboard.index', [
            'title' => 'Dashboard Asesor',
            'namaAsesor' => $namaAsesor,
            'noReg' => $noReg,
            'bidang' => $bidang,
            'tanggalHeader' => 'Selasa, 25 Okt 2026',
            'ringkasan' => $ringkasan,
            'tahunTersedia' => $tahunTersedia,
            'tahunAktif' => $tahunAktif,
            'statistikTahunan' => $statistikTahunan,
            'jadwalTerdekat' => $jadwalTerdekat,
            'statusKelulusan' => $statusKelulusan,
        ]);
    }
}