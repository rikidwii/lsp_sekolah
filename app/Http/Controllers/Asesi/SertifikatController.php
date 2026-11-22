<?php

// app/Http/Controllers/Asesi/SertifikatController.php  (FILE BARU)

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\Sertifikat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SertifikatController extends Controller
{
    /**
     * Halaman "Sertifikat Saya".
     * Selama tabel `sertifikat` belum dibuat, halaman memakai DATA DEMO. Begitu tabelnya ada,
     * otomatis membaca database (hanya sertifikat milik asesi yang login dan sudah diterbitkan admin).
     */
    public function index(Request $request)
    {
        $sertifikat = Schema::hasTable('sertifikat') ? $this->dariDatabase() : $this->dataDemo();

        return view('asesi.sertifikat.index', [
            'sertifikat'   => $sertifikat,
            'namaLsp'      => config('app.lsp_name', 'LSP SMKN 1 Surabaya'),
            'sinkronisasi' => now()->locale('id')->translatedFormat('d F Y, H:i') . ' WIB',
        ]);
    }

    /** "Lihat Detail": menampilkan berkas sertifikat yang diunggah admin (inline di browser). */
    public function lihat(Sertifikat $sertifikat)
    {
        $this->pastikanMilikSendiri($sertifikat);

        return Storage::disk('local')->response($sertifikat->file_path);
    }

    /** "Unduh PDF": langsung mengunduh berkas yang diunggah admin (Content-Disposition: attachment). */
    public function unduh(Sertifikat $sertifikat)
    {
        $this->pastikanMilikSendiri($sertifikat);

        $ext  = pathinfo($sertifikat->file_path, PATHINFO_EXTENSION) ?: 'pdf';
        $nama = 'Sertifikat-' . Str::slug($sertifikat->nomor) . '.' . $ext;

        return Storage::disk('local')->download($sertifikat->file_path, $nama);
    }

    // ------------------------------------------------------------------------------------------

    private function dariDatabase(): Collection
    {
        return Sertifikat::with('pendaftaran.skema')
            ->whereNotNull('diterbitkan_at')                       // hanya yang sudah diluncurkan admin
            ->whereHas('pendaftaran', fn ($q) => $q->where('user_id', auth()->id()))
            ->orderByDesc('diterbitkan_at')
            ->get()
            ->map(function (Sertifikat $s) {
                $skema = $s->pendaftaran->skema;
                $ext   = strtolower(pathinfo($s->file_path, PATHINFO_EXTENSION));

                return (object) [
                    'id'             => $s->id,
                    'kode'           => $skema->kode,
                    'nama'           => $skema->nama,
                    'jenis'          => $skema->tipe,                // Klaster | Okupasi | KKNI
                    'nomor'          => $s->nomor,
                    'nama_pemilik'   => auth()->user()->name,
                    'tanggal_terbit' => $s->diterbitkan_at,
                    'berlaku_sampai' => $s->berlaku_sampai,
                    'aktif'          => ! $s->berlaku_sampai || $s->berlaku_sampai->endOfDay()->isFuture(),
                    'tipe_berkas'    => $ext === 'pdf' ? 'pdf' : (in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) ? 'image' : null),
                    'lihat_url'      => route('asesi.sertifikat.lihat', $s),
                    'download_url'   => route('asesi.sertifikat.unduh', $s),
                    'url_verifikasi' => null,   // isi bila ada tautan verifikasi publik BNSP
                    'demo'           => false,
                ];
            });
    }

    private function dataDemo(): Collection
    {
        $pemilik = auth()->user()?->name ?? 'Ahmad Fauzi, S.Kom.';

        $buat = fn (array $d) => (object) ($d + [
    'nama_pemilik' => $pemilik,
    'tipe_berkas' => null,
    'lihat_url' => '#',
    'download_url' => '#',
    'url_verifikasi' => null,
    'demo' => true,
]);

        return collect([
            $buat([
                'id' => 1, 'kode' => 'SKM-NET-003', 'nama' => 'Teknik Komputer dan Jaringan – Network Administrator', 'jenis' => 'KKNI Level II',
                'nomor' => 'BNSP-LSP-2025-09812', 'tanggal_terbit' => Carbon::create(2025, 8, 12), 'berlaku_sampai' => Carbon::create(2028, 8, 12),
            ]),
            $buat([
                'id' => 2, 'kode' => 'SKM-RPL-001', 'nama' => 'Rekayasa Perangkat Lunak – Junior Programmer', 'jenis' => 'Okupasi',
                'nomor' => 'BNSP-LSP-2026-04519', 'tanggal_terbit' => Carbon::create(2026, 5, 5), 'berlaku_sampai' => Carbon::create(2029, 5, 5),
            ]),
        ])->map(function ($s) {
            $s->aktif = $s->berlaku_sampai->endOfDay()->isFuture();

            return $s;
        });
    }

    private function pastikanMilikSendiri(Sertifikat $sertifikat): void
    {
        abort_unless(
            $sertifikat->diterbitkan_at && $sertifikat->pendaftaran?->user_id === auth()->id(),
            404
        );
    }
}