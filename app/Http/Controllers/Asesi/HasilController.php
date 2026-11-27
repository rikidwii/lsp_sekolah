<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class HasilController extends Controller
{
    private const PASS = 75; // ambang batas kelulusan LSP

    public function index(Request $request): View
    {
        $hasil = $this->data($request);
        $lulus = $hasil->where('status', 'K');

        return view('asesi.hasil.index', [
            'title' => 'Hasil & Kelulusan',
            'hasil' => $hasil,
            'stat'  => [
                'total'    => $hasil->count(),
                'lulus'    => $lulus->count(),
                'belum'    => $hasil->count() - $lulus->count(),
                'persen'   => $hasil->count() ? round($lulus->count() / $hasil->count() * 100, 1) : 0,
                'rata'     => $hasil->count() ? round($hasil->avg('skor'), 1) : 0,
                'sertifikat' => $lulus->whereNotNull('no_sertifikat')->count(),   // sertifikat sudah terbit
                'menunggu'   => $lulus->whereNull('no_sertifikat')->count(),      // hasil K tetapi sertifikat belum terbit
            ],
            'jurusan' => $hasil->pluck('jurusan')->unique()->values(),
        ]);
    }

    public function show(Request $request, int $id): View
    {
        $h = $this->data($request)->firstWhere('id', $id);
        abort_if(! $h, 404);

        return view('asesi.hasil.show', ['title' => 'Hasil & Kelulusan', 'h' => $h, 'pass' => self::PASS]);
    }

    /**
     * Unduh Transkrip Nilai (PDF), memakai template yang sama dengan halaman Skema
     * (resources/views/asesi/skema/transkrip.blade.php).
     */
    public function transkrip(Request $request, int $id)
    {
        $h = $this->data($request)->firstWhere('id', $id);
        abort_if(! $h, 404);

        $user  = $request->user();
        $level = strtoupper($h->level ?? '');
        $jenis = $h->jenis ?? (str_contains($level, 'KKNI') ? 'KKNI' : (str_contains($level, 'OKUPASI') ? 'Okupasi' : 'Klaster'));

        $hasil = [
            'asesi' => [
                'nama'  => $user?->name ?? '-',
                'nis'   => $user?->asesiProfile?->nis_nisn ?? '-',
                'kelas' => $h->jurusan,
            ],
            'skema' => [
                'nama' => $h->nama, 'kode' => $h->kode, 'jenis' => $jenis,
                'no_registrasi' => $h->no_registrasi ?? $h->kode,
            ],
            'asesor'  => $h->asesor,
            'tanggal' => $h->tanggal_uji->copy()->locale('id')->translatedFormat('d F Y'),
            'teori'   => ['skor' => $h->teori,   'passing' => self::PASS],
            'praktik' => ['skor' => $h->praktik, 'passing' => self::PASS],
            'rekomendasi' => $h->status,
            // Bila backend sudah menyediakan daftar KUK per unit ($u->kuk), dipakai apa adanya;
            // kalau belum, tiap unit dianggap punya satu butir penilaian.
            'units' => $h->units->map(fn ($u) => [
                'kode' => $u->kode, 'judul' => $u->judul, 'standar' => $u->standar ?? 'Standar SKKNI',
                'kuk'  => isset($u->kuk) ? collect($u->kuk)->map(fn ($k) => (array) $k)->all() : [[
                    'no' => $u->no . '.1', 'judul' => $u->judul, 'kriteria' => $u->kriteria,
                    'hasil' => $u->hasil, 'catatan' => $u->catatan ?? null, 'bukti' => $u->bukti ?? null,
                ]],
            ])->all(),
        ];

        $file = 'Transkrip-' . $h->kode . '.pdf';

        if (class_exists(Pdf::class)) {
            return Pdf::loadView('asesi.skema.transkrip', ['hasil' => $hasil])->setPaper('a4')->download($file);
        }

        return view('asesi.skema.transkrip', ['hasil' => $hasil]); // preview HTML bila dompdf belum terpasang
    }

    /**
     * DATA DUMMY. Saat backend jadi, ganti isi method ini dengan query database milik asesi login
     * ($request->user()), lalu petakan ke objek dengan field yang sama seperti di bawah.
     * Skor akhir = teori*30% + praktik*70%.
     */
    private function data(Request $request): Collection
    {
        $lv = function (int $v, ?string $ket) {
            [$label, $tone] = match (true) {
                $v >= 90 => ['Tercapai Sempurna', 'green'],
                $v >= 80 => ['Tercapai' . ($ket ? " ($ket)" : ''), 'green'],
                $v >= self::PASS => ['Tercapai Batas Minimal', 'amber'],
                default => ['Belum Memenuhi Ambang Kelulusan (Min. ' . self::PASS . ')', 'red'],
            };
            return (object) compact('label', 'tone');
        };
        $u = fn (array $rows) => collect($rows)->map(fn ($r, $i) => (object) ['no' => $i + 1, 'kode' => $r[0], 'judul' => $r[1], 'kriteria' => $r[2], 'hasil' => $r[3]]);

        $rows = [
            ['id' => 1, 'status' => 'K', 'terkini' => true, 'level' => 'KKNI', 'kode' => 'SKM-JWD-001', 'tanggal_uji' => Carbon::create(2024, 10, 20),
             'jurusan' => 'Rekayasa Perangkat Lunak', 'nama' => 'Junior Web Developer', 'asesor' => 'Dr. Hendra Gunawan, S.Kom, M.T.', 'asesor_reg' => 'MET.000.002819 2021',
             'catatan' => 'Peserta menunjukkan pemahaman yang sangat baik dalam struktur HTML dan CSS modern. Logika JavaScript untuk interaksi DOM juga dikuasai dengan baik. Sedikit perlu peningkatan dalam manajemen state untuk aplikasi skala besar, namun secara keseluruhan memenuhi standar kompetensi Junior Web Developer.',
             'teori' => 88, 'praktik' => 82, 'ket_teori' => 'Kuis Esai & Pilihan Ganda', 'ket_praktik' => 'Demonstrasi Lab Komputer', 'no_sertifikat' => null, 'aktif_sampai' => null, 'pleno' => '088/PLENO-LSP/X/2024',
             'units' => $u([['J.620100.004.01', 'Menggunakan Struktur Data', 'Pemahaman tipe data, array, objek, dan manipulasi data dasar.', 'K'],
                            ['J.620100.011.01', 'Menerapkan Bahasa Pemrograman Berorientasi Objek', 'Konsep Class, Object, Inheritance, dan Polymorphism.', 'K'],
                            ['J.620100.012.01', 'Mengimplementasikan Rancangan User Interface', 'Translasi desain ke HTML/CSS yang responsif.', 'K'],
                            ['J.620100.015.01', 'Menulis Kode dengan Prinsip Sesuai Guidelines dan Best Practices', 'Clean code, penamaan variabel, struktur direktori.', 'BK'],
                            ['J.620100.016.01', 'Mengimplementasikan Pemrograman Terstruktur', 'Fungsi, perulangan, percabangan, dan alur logika.', 'K']])],
            ['id' => 2, 'status' => 'K', 'terkini' => false, 'level' => 'KLASTER', 'kode' => 'SKM-RPL-001', 'tanggal_uji' => Carbon::create(2023, 5, 5),
             'jurusan' => 'Rekayasa Perangkat Lunak', 'nama' => 'Rekayasa Perangkat Lunak – Junior Programmer', 'asesor' => 'Dra. Siti Aminah, M.Kom', 'asesor_reg' => 'MET.000.001742 2019',
             'catatan' => 'Hasil demonstrasi pemrograman berorientasi objek sangat terstruktur dan seluruh unit uji berhasil diselesaikan dengan baik tanpa catatan perbaikan.',
             'teori' => 94, 'praktik' => 91, 'ket_teori' => null, 'ket_praktik' => null, 'no_sertifikat' => 'BNSP-LSP-2023-04519', 'aktif_sampai' => 'Mei 2026', 'pleno' => '041/PLENO-LSP/V/2023',
             'units' => $u([['J.620100.001.01', 'Menggunakan Struktur Data', 'Pemahaman tipe data dan struktur data dasar.', 'K'],
                            ['J.620100.002.01', 'Mengimplementasikan Pemrograman Terstruktur', 'Fungsi, perulangan, percabangan.', 'K'],
                            ['J.620100.003.01', 'Menerapkan Pemrograman Berorientasi Objek', 'Class, Object, Inheritance.', 'K'],
                            ['J.620100.005.01', 'Menggunakan Library atau Komponen Pre-Existing', 'Pemanfaatan library secara tepat.', 'K'],
                            ['J.620100.007.01', 'Melakukan Pengujian Unit Program', 'Skenario uji dan pelaporan hasil.', 'K'],
                            ['J.620100.009.01', 'Mendokumentasikan Kode Program', 'Komentar dan dokumentasi teknis.', 'K']])],
            ['id' => 3, 'status' => 'BK', 'terkini' => false, 'level' => 'KLASTER', 'kode' => 'SKM-NET-002', 'tanggal_uji' => Carbon::create(2024, 1, 15),
             'jurusan' => 'Teknik Komputer & Jaringan', 'nama' => 'Teknisi Jaringan Madya (Network Technician)', 'asesor' => 'Ir. Bambang Wicaksono, M.T.', 'asesor_reg' => 'MET.000.003921 2020',
             'catatan' => 'Konfigurasi routing MikroTik dan subnetting IPv6 perlu penguatan sebelum dapat direkomendasikan Kompeten. Disarankan mengikuti pendalaman materi.',
             'teori' => 78, 'praktik' => 64, 'ket_teori' => null, 'ket_praktik' => null, 'no_sertifikat' => null, 'aktif_sampai' => null, 'pleno' => '007/PLENO-LSP/I/2024',
             'units' => $u([['J.611000.001.01', 'Merencanakan Topologi Jaringan', 'Pemilihan topologi sesuai kebutuhan.', 'K'],
                            ['J.611000.004.01', 'Mengonfigurasi Routing Dinamis MikroTik', 'Konfigurasi OSPF/RIP dan verifikasi rute.', 'BK'],
                            ['J.611000.006.01', 'Melakukan Subnetting IPv4 dan IPv6', 'Pembagian subnet dan alokasi alamat.', 'BK'],
                            ['J.611000.009.01', 'Mengamankan Jaringan Dasar', 'Firewall dan kontrol akses sederhana.', 'K'],
                            ['J.611000.011.01', 'Mendokumentasikan Jaringan', 'Diagram dan dokumentasi konfigurasi.', 'K']])],
        ];

        return collect($rows)->map(function ($r) use ($lv) {
            $r['skor'] = round($r['teori'] * 0.3 + $r['praktik'] * 0.7, 1);
            $r['teori_info'] = $lv($r['teori'], $r['ket_teori']);
            $r['praktik_info'] = $lv($r['praktik'], $r['ket_praktik']);
            $r['unit_k'] = $r['units']->where('hasil', 'K')->count();
            $r['unit_bk'] = $r['units']->where('hasil', 'BK')->count();
            $r['inisial'] = collect(preg_split('/[\s.,]+/', preg_replace('/^(Dr|Dra|Ir)\./', '', $r['asesor'])))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');

            return (object) $r;
        });
    }
}