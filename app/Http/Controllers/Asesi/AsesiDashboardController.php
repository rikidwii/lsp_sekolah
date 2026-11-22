<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;  // SESUAIKAN nama model
use App\Models\Sertifikat;   // SESUAIKAN nama model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * ASUMSI STRUKTUR DATA (ubah bagian yang berbeda dengan databasemu):
 *  users        : name, no_registrasi (nullable) + trait Notifiable (bawaan Laravel)
 *  pendaftarans : id, user_id, skema_id, jadwal_id (nullable), tahap (1-4), status ('proses' | 'selesai')
 *                 relasi: skema(), jadwal()
 *  skemas       : id, nama
 *  jadwals      : id, judul, tanggal (cast 'date'), jam_mulai, jam_selesai, lokasi, asesor_id -> users.id, persiapan (nullable)
 *                 relasi: asesor() => belongsTo(User::class, 'asesor_id')
 *  sertifikats  : id, user_id
 *  Notifikasi memakai tabel `notifications` bawaan Laravel; isi data: ['judul' => ..., 'pesan' => ...]
 */
class AsesiDashboardController extends Controller
{
    private const STEPS = ['Pendaftaran', 'Jadwal Asesmen', 'Uji Penilaian', 'Sertifikat'];

    private const PESAN = [
        0 => 'Kamu belum mendaftar skema sertifikasi. Pilih skema untuk memulai.',
        1 => 'Lengkapi pendaftaran skema dan dokumen persyaratanmu.',
        2 => 'Jadwal asesmen sedang berlangsung, silakan menuju ke ruangan yang dijadwalkan.',
        3 => 'Uji kompetensimu sedang dinilai oleh asesor.',
        4 => 'Sertifikatmu sedang diproses oleh LSP.',
        5 => 'Selamat, seluruh tahapan sertifikasi telah selesai.',
    ];

    public function index(Request $request)
    {
        $user = $request->user();

        // Inisial dari nama: "Ahmad Fauzi" → "AF"
        $initials = collect(preg_split('/\s+/', trim($user->name)))->filter()->take(2)
            ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');

        // Sementara aman dipakai walau model/tabel belum dibuat (halaman tampil dengan data kosong)
        $daftar = class_exists(Pendaftaran::class)
            ? Pendaftaran::with(['skema', 'jadwal.asesor'])->where('user_id', $user->id)->latest()->get()
            : collect();
        $aktif = $daftar->firstWhere('status', 'proses') ?? $daftar->first();

        // 0 = belum mendaftar, 1-4 = tahap berjalan, 5 = semua selesai
        $tahap = $aktif ? ($aktif->status === 'selesai' ? 5 : max(1, min(4, (int) $aktif->tahap))) : 0;

        $steps = collect(self::STEPS)->map(function ($label, $i) use ($tahap) {
            $n = $i + 1;
            $status = $n < $tahap ? 'done' : ($n === $tahap ? 'current' : 'pending');
            $text = ['done' => 'Selesai', 'current' => 'Sedang Berlangsung', 'pending' => $n === 4 ? 'Final' : 'Menunggu'][$status];
            return compact('label', 'status', 'text');
        });

        // Jadwal mendatang (hari ini dan seterusnya), terdekat di urutan pertama
        $jadwal = $daftar->pluck('jadwal')->filter()
            ->filter(fn ($j) => $j->tanggal->gte(today()))->sortBy('tanggal')->values();
        $next = $jadwal->first();
        $selisih = $next ? (int) today()->diffInDays($next->tanggal->copy()->startOfDay(), false) : null;

        $adaNotif = Schema::hasTable('notifications');
        $notifs = $adaNotif ? $user->notifications()->latest()->limit(5)->get() : collect();

        return view('asesi.dashboard.index', [
            'title'      => 'Dashboard',
            'user'       => $user,
            'initials'   => $initials,
            'noReg'      => $user->no_registrasi ?? sprintf('ASI-%s-%05d', $user->created_at->format('Y'), $user->id),
            'aktif'      => (bool) $aktif,
            'skemaAktif' => $aktif?->skema?->nama,
            'tahap'      => $tahap,
            'persen'     => $tahap ? min(100, (int) round($tahap / 4 * 100)) : 0,
            'steps'      => $steps,
            'pesanTahap' => self::PESAN[$tahap],
            'jadwal'     => $jadwal,
            'stat'       => [
                'skema'     => $daftar->count(),
                'terdekat'  => $next?->tanggal->locale('id')->translatedFormat('d M Y'),
                'badgeH'    => $selisih === null ? 'Belum ada' : ($selisih <= 0 ? 'H-0 Hari Ini' : "H-{$selisih}"),
                'sertifikat' => class_exists(Sertifikat::class) ? Sertifikat::where('user_id', $user->id)->count() : 0,
            ],
            'notifs'     => $notifs,
            'unread'     => $adaNotif ? $user->unreadNotifications()->count() : 0,
        ]);
    }

    public function bacaSemua(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return back();
    }
}