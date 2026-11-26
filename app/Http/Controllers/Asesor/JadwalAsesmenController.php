<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class JadwalAsesmenController extends Controller
{
    private const PER_PAGE = 8;

    /** Zona waktu yang dipakai untuk menentukan "hari ini" dan "jam sekarang". */
    private const TZ = 'Asia/Jakarta';

    /**
     * Rute halaman "Nilai" pada menu Daftar Asesi + nama parameter ID asesinya.
     * SESUAIKAN dengan rute milikmu (cek: php artisan route:list --name=daftar-asesi).
     * Jika rute tidak ditemukan / parameternya tidak cocok, tombol "Nilai" akan mengarah ke '#'.
     */
    private const RUTE_NILAI  = 'asesor.daftar-asesi.nilai';
    private const PARAM_NILAI = 'asesi';

    /** Presensi dibuka lewat rute index: ?presensi={id sesi} (tidak perlu rute tambahan di web.php). */

    private ?array $rosterCache = null;

    /**
     * Halaman Jadwal Asesmen (mode Daftar & Kalender).
     *
     * Query string: tampilan (daftar|kalender), bulan (YYYY-MM), q, skema, status, tanggal, page.
     */
    public function index(Request $request)
    {
        // Cetak presensi satu sesi (dibuka dari tombol "Cetak Presensi")
        if ($request->filled('presensi')) {
            return $this->presensi($request, (string) $request->query('presensi'));
        }

        $tampilan = $request->query('tampilan') === 'kalender' ? 'kalender' : 'daftar';
        $sekarang = CarbonImmutable::now(self::TZ);
        $hariIni  = $sekarang->startOfDay();
        $bulan    = $this->parseBulan($request->query('bulan'), $hariIni);

        $semuaSesi = $this->sesiBulan($bulan, $sekarang);

        $data = [
            'tampilan'       => $tampilan,
            'hariIni'        => $hariIni,
            'bulan'          => $bulan,
            'bulanLabel'     => $bulan->locale('id')->translatedFormat('F Y'),
            'bulanSebelum'   => $bulan->subMonthNoOverflow()->format('Y-m'),
            'bulanSesudah'   => $bulan->addMonthNoOverflow()->format('Y-m'),
            'adalahBulanIni' => $bulan->isSameMonth($hariIni),
            'ringkasan'      => [
                // Dipakai tampilan kalender (tidak diubah)
                'total_sesi'     => $semuaSesi->count(),
                // Total asesmen (skema) milik asesor yang BELUM selesai dilakukan
                // (masih ada asesi yang belum dinilai). Berkurang otomatis saat semua asesi sebuah skema sudah dinilai.
                'total_asesmen'  => $this->hitungAsesmenBelumDilakukan(),
                // Total asesi dari semua skema milik asesor yang BELUM dinilai
                'asesi_menunggu' => $this->hitungAsesiMenunggu(),
            ],
        ];

        if ($tampilan === 'kalender') {
            return view('asesor.jadwal.index', $data + $this->dataKalender($request, $bulan, $hariIni, $semuaSesi));
        }

        // ---- Mode Daftar ----
        $disaring = $this->saring($semuaSesi, $request);
        $urut     = $this->urutkan($disaring);

        $terakhir = max(1, (int) ceil($urut->count() / self::PER_PAGE));
        $halaman  = min(max(1, (int) $request->query('page', 1)), $terakhir);

        $paginator = new LengthAwarePaginator(
            $urut->forPage($halaman, self::PER_PAGE)->values(),
            $urut->count(),
            self::PER_PAGE,
            $halaman,
            ['path' => $request->url(), 'query' => $request->except('page')]
        );

        // Ringkasan per hari: jumlah sesi = jumlah JAM (waktu mulai) berbeda pada hari itu
        $infoHari = $disaring->groupBy('tanggal_key')->map(fn (Collection $g) => [
            'sesi'    => $g->pluck('mulai')->unique()->count(),
            'asesmen' => $g->count(),
            'asesi'   => (int) $g->sum('jumlah_asesi'),
            'skema'   => $g->pluck('skema')->unique()->count(),
        ]);

        return view('asesor.jadwal.index', $data + [
            'filter'      => [
                'q'      => (string) $request->query('q', ''),
                'skema'  => (string) $request->query('skema', ''),
                'status' => (string) $request->query('status', ''),
            ],
            // Otomatis dari daftar skema milik asesor (judul skema yang dibuat admin)
            'daftarSkema' => collect($this->skemaAsesor())->pluck('judul')->sort()->values(),
            'opsiStatus'  => [
                'berlangsung' => 'Sedang Berjalan',
                'terjadwal'   => 'Terjadwal',
                'selesai'     => 'Selesai',
            ],
            'paginator'   => $paginator,
            'kelompok'    => $paginator->getCollection()->groupBy('tanggal_key'),
            'infoHari'    => $infoHari,
        ]);
    }

    /**
     * Ekspor jadwal (mengikuti bulan & filter aktif) ke CSV.
     */
    public function export(Request $request)
    {
        $sekarang = CarbonImmutable::now(self::TZ);
        $bulan    = $this->parseBulan($request->query('bulan'), $sekarang->startOfDay());

        $sesi = $this->urutkan(
            $this->saring($this->sesiBulan($bulan, $sekarang), $request)
        );

        $namaFile = 'jadwal-asesmen-' . $bulan->format('Y-m') . '.csv';

        return response()->streamDownload(function () use ($sesi) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar terbuka rapi di Excel

            fputcsv($out, ['Tanggal', 'Sesi', 'Waktu Mulai', 'Skema', 'Kode', 'Bidang Keahlian', 'Jumlah Asesi', 'Status']);

            foreach ($sesi as $s) {
                fputcsv($out, [
                    $s['tanggal_key'],
                    $s['sesi_ke'],
                    $s['mulai'],
                    $s['skema'],
                    $s['kode'],
                    $s['bidang'],
                    $s['jumlah_asesi'],
                    $s['status_label'],
                ]);
            }

            fclose($out);
        }, $namaFile, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Cetak presensi satu sesi skema (nama, NIS/NISN, asal sekolah).
     * Teknis & format sama dengan "Ekspor Jadwal": langsung mengunduh file yang dibuka di Excel.
     * Dipanggil dari index() lewat ?presensi={id sesi}; format id: KODE_YYYY-MM-DD_HHMM
     */
    private function presensi(Request $request, string $sesi)
    {
        $bagian  = explode('_', $sesi);
        $tanggal = $bagian[1] ?? '';

        abort_unless(preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal) === 1, 404);

        try {
            $bulan = CarbonImmutable::createFromFormat('!Y-m-d', $tanggal, self::TZ)->startOfMonth();
        } catch (\Throwable) {
            abort(404);
        }

        $data = $this->sesiBulan($bulan, CarbonImmutable::now(self::TZ))->firstWhere('id', $sesi);
        abort_if($data === null, 404);

        $namaFile = 'presensi-' . Str::slug($data['skema']) . '-' . $data['tanggal_key'] . '.csv';

        return response()->streamDownload(function () use ($data) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, ['DAFTAR HADIR (PRESENSI) ASESMEN']);
            fputcsv($out, ['Skema', $data['skema']]);
            fputcsv($out, ['Kode Skema', $data['kode']]);
            fputcsv($out, ['Bidang Keahlian', $data['bidang']]);
            fputcsv($out, ['Tanggal', $data['tanggal']->locale('id')->translatedFormat('l, j F Y')]);
            fputcsv($out, ['Waktu Mulai', $data['mulai'] . ' WIB']);
            fputcsv($out, []);
            fputcsv($out, ['No', 'Nama', 'NIS/NISN', 'Asal Sekolah', 'Tanda Tangan']);

            foreach ($data['peserta'] as $i => $p) {
                fputcsv($out, [$i + 1, $p['nama'], $p['nis'], $p['sekolah'], '']);
            }

            fclose($out);
        }, $namaFile, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    // ------------------------------------------------------------------
    // Data kalender (tidak diubah)
    // ------------------------------------------------------------------

    private function dataKalender(Request $request, CarbonImmutable $bulan, CarbonImmutable $hariIni, Collection $semuaSesi): array
    {
        $sesiPerTanggal = $semuaSesi->groupBy('tanggal_key');

        // Minggu dimulai hari Senin
        $awal  = $bulan->startOfMonth()->startOfWeek(CarbonInterface::MONDAY);
        $akhir = $bulan->endOfMonth()->endOfWeek(CarbonInterface::SUNDAY)->startOfDay();

        $minggu = [];
        for ($tgl = $awal; $tgl->lte($akhir); $tgl = $tgl->addDay()) {
            $minggu[(int) floor($awal->diffInDays($tgl) / 7)][] = [
                'tanggal'     => $tgl,
                'key'         => $tgl->toDateString(),
                'dalam_bulan' => $tgl->isSameMonth($bulan),
                'hari_ini'    => $tgl->isSameDay($hariIni),
                'minggu'      => $tgl->dayOfWeek === CarbonInterface::SUNDAY,
            ];
        }

        // Tentukan tanggal terpilih: query -> hari ini -> sesi terdekat berikutnya -> sesi terakhir
        $kunci = $sesiPerTanggal->keys();
        $dipilih = (string) $request->query('tanggal', '');

        if (! $kunci->contains($dipilih)) {
            $dipilih = $kunci->contains($hariIni->toDateString())
                ? $hariIni->toDateString()
                : ($kunci->first(fn ($k) => $k >= $hariIni->toDateString()) ?? $kunci->last());
        }

        return [
            'minggu'          => array_values($minggu),
            'sesiPerTanggal'  => $sesiPerTanggal,
            'tanggalTerpilih' => $dipilih,
        ];
    }

    // ------------------------------------------------------------------
    // Filter & pengurutan
    // ------------------------------------------------------------------

    private function saring(Collection $sesi, Request $request): Collection
    {
        $q      = mb_strtolower(trim((string) $request->query('q', '')));
        $skema  = (string) $request->query('skema', '');
        $status = (string) $request->query('status', '');

        return $sesi->filter(function (array $s) use ($q, $skema, $status) {
            if ($skema !== '' && $s['skema'] !== $skema) {
                return false;
            }
            if ($status !== '' && $s['status'] !== $status) {
                return false;
            }
            if ($q !== '') {
                $jerami = mb_strtolower(implode(' ', [
                    $s['skema'],
                    $s['kode'],
                    $s['bidang'],
                    collect($s['peserta'])->pluck('nama')->implode(' '),
                ]));
                if (! str_contains($jerami, $q)) {
                    return false;
                }
            }
            return true;
        })->values();
    }

    /**
     * Urutan daftar: hari ini -> mendatang (terdekat dulu) -> selesai (terbaru dulu).
     */
    private function urutkan(Collection $sesi): Collection
    {
        return $sesi->sortBy([
            ['grup', 'asc'],
            ['urut', 'asc'],
            ['mulai', 'asc'],
        ])->values();
    }

    // ------------------------------------------------------------------
    // Pembentukan sesi
    // ------------------------------------------------------------------

    /**
     * Bangun daftar sesi (asesmen terjadwal) untuk satu bulan.
     *
     * Status:
     *  - selesai      : tanggal sudah lewat, ATAU semua asesinya sudah dinilai
     *  - berlangsung  : hari ini dan jam mulai sudah tercapai (jam sekarang >= jam mulai)
     *  - terjadwal    : hari ini tapi jam mulai belum tiba, atau besok dan seterusnya
     *
     * Nomor sesi dihitung otomatis dari jumlah JAM mulai yang berbeda pada hari itu
     * (beberapa skema pada jam yang sama = sesi yang sama).
     */
    private function sesiBulan(CarbonImmutable $bulan, CarbonImmutable $sekarang): Collection
    {
        $hariIni = $sekarang->startOfDay();
        $skemaMaster = $this->skemaAsesor();
        $roster = $this->rosterSemuaSkema();
        $hasil = collect();

        foreach ($this->templat() as $t) {
            $tgl = match (true) {
                $t['hari'] === 'hari_ini' => $hariIni,
                $t['hari'] === 'besok'    => $hariIni->addDay(),
                default                   => $bulan->startOfMonth()->addDays(min((int) $t['hari'], $bulan->daysInMonth) - 1),
            };

            if (! $tgl->isSameMonth($bulan)) {
                continue;
            }

            $kunciSkema = $t['skema'];
            $sk         = $skemaMaster[$kunciSkema];
            $peserta    = $roster[$kunciSkema];

            $jumlah       = count($peserta);
            $sudahDinilai = count(array_filter($peserta, fn ($p) => $p['dinilai']));
            $semuaDinilai = $jumlah > 0 && $sudahDinilai === $jumlah;

            // Status
            $mulaiAt = $tgl->setTimeFromTimeString($t['mulai']);
            if ($tgl->lt($hariIni)) {
                $status = 'selesai';
            } elseif ($tgl->isSameDay($hariIni)) {
                $status = $mulaiAt->lte($sekarang) ? 'berlangsung' : 'terjadwal';
            } else {
                $status = 'terjadwal';
            }
            if ($status === 'berlangsung' && $semuaDinilai) {
                $status = 'selesai';
            }

            // Badge
            [$badge, $nada] = match ($status) {
                'selesai'     => [($semuaDinilai ? 'Selesai Dinilai' : 'Selesai') . " ({$jumlah} Asesi)", 'emerald'],
                'berlangsung' => ['Sedang Berjalan', 'emerald'],
                default       => ['Terjadwal', 'sky'],
            };

            $grup = $status === 'berlangsung' ? 0 : ($status === 'terjadwal' ? 1 : 2);
            $id   = $sk['kode'] . '_' . $tgl->toDateString() . '_' . str_replace(':', '', $t['mulai']);

            $s = [
                'id'            => $id,
                'tanggal'       => $tgl,
                'tanggal_key'   => $tgl->toDateString(),
                'mulai'         => $t['mulai'],
                'skema'         => $sk['judul'],
                'kode'          => $sk['kode'],
                'bidang'        => $sk['bidang'],
                'tuk'           => $sk['tuk'],
                'tipe'          => 'uji', // dipertahankan agar tampilan kalender tetap bekerja
                'jumlah_asesi'  => $jumlah,
                'peserta'       => $peserta,
                // Dipakai tampilan kalender: hanya sesi tertentu yang menampilkan daftar asesi di panel
                'asesi'         => ($t['kalender'] ?? false) ? $peserta : [],
                'sudah_dinilai' => $sudahDinilai,
                'belum_dinilai' => $jumlah - $sudahDinilai,
                'status'        => $status,
                'status_label'  => ['selesai' => 'Selesai', 'berlangsung' => 'Sedang Berjalan', 'terjadwal' => 'Terjadwal'][$status],
                'badge'         => $badge,
                'nada'          => $nada,
                'grup'          => $grup,
                'urut'          => $grup === 2 ? -$tgl->getTimestamp() : $tgl->getTimestamp(),
                'url_presensi'  => $this->urlPresensi($id),
            ];

            $s['aksi'] = $this->aksi($s, $t);

            $hasil->push($s);
        }

        // Nomor sesi per hari = urutan jam mulai yang berbeda pada hari itu
        return $hasil
            ->groupBy('tanggal_key')
            ->sortKeys()
            ->flatMap(function (Collection $hari) {
                $waktu = $hari->pluck('mulai')->unique()->sort()->values()->all();

                return $hari->sortBy('mulai')->values()->map(fn (array $s) => $s + [
                    'sesi_ke'    => array_search($s['mulai'], $waktu, true) + 1,
                    'sesi_total' => count($waktu),
                ]);
            })
            ->values();
    }

    /**
     * Tombol aksi per kartu.
     *  - Mulai Penilaian: membuka pop up daftar asesi (hijau saat berlangsung, abu-abu saat terjadwal)
     *  - Cetak Presensi : unduh daftar asesi sesi tersebut
     */
    private function aksi(array $s, array $t): array
    {
        // Semua status (berlangsung, terjadwal, selesai) memakai tombol yang sama.
        // "Mulai Penilaian" hanya aktif (hijau) saat sesi sedang berjalan; selain itu abu-abu (pop up tetap bisa dibuka).
        return [
            ['label' => 'Mulai Penilaian', 'gaya' => $s['status'] === 'berlangsung' ? 'primary' : 'muted', 'url' => null, 'modal' => true, 'blank' => false],
            ['label' => 'Cetak Presensi',  'gaya' => 'ghost', 'url' => $s['url_presensi'], 'modal' => false, 'blank' => false],
        ];
    }

    private function parseBulan(mixed $param, CarbonImmutable $hariIni): CarbonImmutable
    {
        if (is_string($param) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $param)) {
            return CarbonImmutable::createFromFormat('!Y-m', $param, self::TZ)->startOfMonth();
        }

        return $hariIni->startOfMonth();
    }

    private function urlPresensi(string $idSesi): string
    {
        return route('asesor.jadwal.index', ['presensi' => $idSesi]);
        // (langsung mengunduh file, sama seperti tombol "Ekspor Jadwal")
    }

    private function urlNilai(string $nis): string
    {
        if (! Route::has(self::RUTE_NILAI)) {
            return '#';
        }

        try {
            return route(self::RUTE_NILAI, [self::PARAM_NILAI => $nis]);
        } catch (\Throwable) {
            return '#';
        }
    }

    // ------------------------------------------------------------------
    // Skema & asesi milik asesor  (DATA CONTOH — ganti dengan query database)
    // ------------------------------------------------------------------

    /**
     * Daftar skema yang ditugaskan admin kepada asesor yang login.
     * Judul skema dari sini otomatis menjadi isi dropdown filter "Skema".
     *
     * Contoh jika sudah ada model/tabel:
     *
     *   Skema::with('bidang')
     *       ->whereHas('asesor', fn ($q) => $q->where('asesor_id', auth()->user()->asesorProfile->id))
     *       ->get()
     *       ->mapWithKeys(fn ($s) => [$s->id => [
     *           'judul' => $s->judul, 'kode' => $s->kode, 'bidang' => $s->bidang->nama,
     *           'tuk' => $s->tuk?->nama ?? '-', 'jumlah_asesi' => $s->asesi()->count(),
     *       ]])->all();
     */
    private function skemaAsesor(): array
    {
        return [
            'jwd' => ['judul' => 'Junior Web Developer',              'kode' => 'SKM-JWD-2026-04', 'bidang' => 'Teknologi Informasi',        'tuk' => 'Lab RPL Gedung B', 'jumlah_asesi' => 4],
            'dgm' => ['judul' => 'Desainer Grafis Muda',              'kode' => 'SKM-DGM-2026-02', 'bidang' => 'Desain & Multimedia',        'tuk' => 'Lab Multimedia',   'jumlah_asesi' => 3],
            'net' => ['judul' => 'Network Administrator',             'kode' => 'SKM-NET-2026-05', 'bidang' => 'Teknologi Informasi',        'tuk' => 'Lab Jaringan A',   'jumlah_asesi' => 5],
            'rpl' => ['judul' => 'Rekayasa Perangkat Lunak',          'kode' => 'SKM-RPL-2026-01', 'bidang' => 'Teknologi Informasi',        'tuk' => 'Lab RPL 1',        'jumlah_asesi' => 6],
            'tkj' => ['judul' => 'Teknik Komputer & Jaringan (TKJ)',  'kode' => 'SKM-TKJ-2026-03', 'bidang' => 'Teknologi Informasi',        'tuk' => 'Lab Jaringan A',   'jumlah_asesi' => 6],
            'dkv' => ['judul' => 'Desain Komunikasi Visual (DKV)',    'kode' => 'SKM-DKV-2026-01', 'bidang' => 'Desain & Multimedia',        'tuk' => 'Lab Animasi',      'jumlah_asesi' => 5],
            'akl' => ['judul' => 'Akuntansi & Keuangan Lembaga',      'kode' => 'SKM-AKL-2026-02', 'bidang' => 'Bisnis & Manajemen',         'tuk' => 'Lab Akuntansi',    'jumlah_asesi' => 5],
            'agr' => ['judul' => 'Agribisnis Tanaman Pangan',         'kode' => 'SKM-AGR-2026-01', 'bidang' => 'Agribisnis & Agroteknologi', 'tuk' => 'Lab Agribisnis',   'jumlah_asesi' => 4],
        ];
    }

    /**
     * Penanda asesi yang SUDAH dinilai, format "kunciSkema:nis".
     * Ganti dengan query tabel penilaian. Setiap kali asesor menyimpan penilaian,
     * asesi tersebut otomatis keluar dari hitungan "Asesi Menunggu Uji".
     */
    private function sudahDinilai(): array
    {
        return ['jwd:2021001'];
    }

    /**
     * Roster asesi per skema (nama, NIS/NISN, asal sekolah, status dinilai).
     */
    private function rosterSemuaSkema(): array
    {
        if ($this->rosterCache !== null) {
            return $this->rosterCache;
        }

        $kolam = [
            ['Agus Santoso', 'SMKN 1'], ['Budi Pratama', 'SMKN 1'], ['Citra Wulandari', 'SMK BM'], ['Danang Yoga', 'SMKN 2'],
            ['Eka Putri', 'SMKN 2'], ['Fajar Ramadhan', 'SMK BM'], ['Gita Lestari', 'SMKN 1'], ['Hendra Saputra', 'SMK PGRI 3'],
            ['Indah Permata', 'SMKN 3'], ['Joko Wibowo', 'SMKN 2'], ['Kartika Sari', 'SMK BM'], ['Lukman Hakim', 'SMKN 1'],
            ['Maya Anggraini', 'SMK PGRI 3'], ['Nanda Pratiwi', 'SMKN 3'], ['Oki Firmansyah', 'SMKN 2'], ['Putri Ayu', 'SMK BM'],
            ['Rizky Maulana', 'SMKN 1'], ['Siti Nurhaliza', 'SMKN 3'], ['Tegar Wijaya', 'SMK PGRI 3'], ['Umar Faruq', 'SMKN 2'],
            ['Vina Melati', 'SMK BM'], ['Wahyu Hidayat', 'SMKN 1'], ['Yuni Astuti', 'SMKN 3'], ['Zaki Mubarak', 'SMK PGRI 3'],
        ];

        $dinilai = $this->sudahDinilai();
        $offset  = 0;
        $hasil   = [];

        foreach ($this->skemaAsesor() as $kunci => $sk) {
            $daftar = [];

            for ($i = 0; $i < $sk['jumlah_asesi']; $i++) {
                $idx = ($offset + $i) % count($kolam);
                [$nama, $sekolah] = $kolam[$idx];
                $nis = (string) (2021001 + $idx);

                $daftar[] = [
                    'nama'      => $nama,
                    'sekolah'   => $sekolah,
                    'nis'       => $nis,
                    'inisial'   => Str::of($nama)->explode(' ')->filter()->take(2)
                        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode(''),
                    'dinilai'   => in_array($kunci . ':' . $nis, $dinilai, true),
                    'url_nilai' => $this->urlNilai($nis),
                ];
            }

            $offset += $sk['jumlah_asesi'];
            $hasil[$kunci] = $daftar;
        }

        return $this->rosterCache = $hasil;
    }

    private function hitungAsesiMenunggu(): int
    {
        return (int) collect($this->rosterSemuaSkema())
            ->sum(fn (array $daftar) => count(array_filter($daftar, fn ($p) => ! $p['dinilai'])));
    }

    /**
     * Jumlah asesmen (skema) milik asesor yang belum selesai dilakukan,
     * yaitu skema yang masih memiliki asesi belum dinilai.
     */
    private function hitungAsesmenBelumDilakukan(): int
    {
        return collect($this->rosterSemuaSkema())
            ->filter(fn (array $daftar) => count(array_filter($daftar, fn ($p) => ! $p['dinilai'])) > 0)
            ->count();
    }

    // ------------------------------------------------------------------
    // Jadwal contoh (hapus setelah terhubung ke database)
    // ------------------------------------------------------------------

    private function t($hari, string $mulai, string $skema, array $opsi = []): array
    {
        return array_merge(['hari' => $hari, 'mulai' => $mulai, 'skema' => $skema], $opsi);
    }

    private function templat(): array
    {
        return [
            // ---- Hari ini & besok (mengikuti tanggal berjalan) ----
            $this->t('hari_ini', '08:00', 'jwd', ['kalender' => true]),
            $this->t('hari_ini', '08:00', 'dgm'),   // jam sama dengan skema di atas -> sesi yang sama
            $this->t('hari_ini', '13:00', 'net'),
            $this->t('besok',    '09:00', 'rpl'),

            // ---- Tanggal tetap setiap bulan ----
            $this->t(1,  '08:00', 'tkj'),
            $this->t(3,  '09:00', 'akl'),
            $this->t(5,  '13:00', 'dgm'),
            $this->t(8,  '08:30', 'rpl'),
            $this->t(10, '08:00', 'jwd'),
            $this->t(12, '09:00', 'net'),
            $this->t(15, '08:00', 'tkj'),
            $this->t(15, '13:00', 'dkv'),
            $this->t(19, '08:00', 'agr'),
            $this->t(22, '09:00', 'dkv'),
            $this->t(22, '13:30', 'rpl'),
            $this->t(26, '09:00', 'dkv'),
            $this->t(28, '08:30', 'tkj'),
        ];
    }
}