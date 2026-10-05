<?php

namespace Database\Seeders;

use App\Models\SkemaSertifikasi;
use App\Models\User;
use Illuminate\Database\Seeder;

class SkemaSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        $data = [
            ['kode_skema' => 'SKM-001', 'nama_skema' => 'Junior Web Developer', 'kategori' => 'Teknologi Informasi', 'deskripsi' => 'Skema sertifikasi kompetensi junior web developer.', 'durasi_ujian' => '3 Jam', 'kuota_gelombang' => 30, 'biaya' => 350000, 'batas_daftar' => now()->addMonth(), 'status' => 'aktif'],
            ['kode_skema' => 'SKM-002', 'nama_skema' => 'Digital Marketing', 'kategori' => 'Pemasaran', 'deskripsi' => 'Skema sertifikasi kompetensi digital marketing.', 'durasi_ujian' => '2 Jam', 'kuota_gelombang' => 25, 'biaya' => 300000, 'batas_daftar' => now()->addMonth(), 'status' => 'aktif'],
            ['kode_skema' => 'SKM-003', 'nama_skema' => 'Akuntansi Dasar', 'kategori' => 'Keuangan', 'deskripsi' => 'Skema sertifikasi kompetensi akuntansi dasar.', 'durasi_ujian' => '2.5 Jam', 'kuota_gelombang' => 20, 'biaya' => 275000, 'batas_daftar' => now()->addMonth(), 'status' => 'aktif'],
        ];

        foreach ($data as $item) {
            SkemaSertifikasi::updateOrCreate(
                ['kode_skema' => $item['kode_skema']],
                $item + ['dibuat_oleh' => $admin?->id]
            );
        }
    }
}