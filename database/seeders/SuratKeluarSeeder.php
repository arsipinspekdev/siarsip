<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SuratKeluar;
use App\Models\User;
use Illuminate\Database\Seeder;

final class SuratKeluarSeeder extends Seeder
{
    public function run(): void
    {
        $petugas = User::where('username', 'petugas')->first() ?? User::first();

        $suratKeluarList = [
            [
                'nomor_surat' => '005/310/ARSIP/2026',
                'tanggal_surat' => '2026-08-11',
                'tujuan_surat' => 'Seluruh Kepala Sub Bagian & Staf',
                'perihal' => 'Himbauan Pengarsipan Berkas Digital Sesuai Jadwal Retensi Arsip',
                'dibuat_oleh_id' => $petugas?->id,
            ],
            [
                'nomor_surat' => '090/420/ARSIP/2026',
                'tanggal_surat' => '2026-08-16',
                'tujuan_surat' => 'Kepala Dinas Komunikasi dan Informatika',
                'perihal' => 'Permohonan Integrasi Backup Server Arsip ke Data Center Pemerintah Daerah',
                'dibuat_oleh_id' => $petugas?->id,
            ],
            [
                'nomor_surat' => '800/512/ARSIP/2026',
                'tanggal_surat' => '2026-08-21',
                'tujuan_surat' => 'Kepala Badan Kepegawaian Daerah',
                'perihal' => 'Pengusulan Nama Staf untuk Mengikuti Bimtek Kearsipan Tingkat Lanjut',
                'dibuat_oleh_id' => $petugas?->id,
            ],
            [
                'nomor_surat' => '420/601/ARSIP/2026',
                'tanggal_surat' => '2026-08-24',
                'tujuan_surat' => 'Dinas Perpustakaan dan Kearsipan Daerah',
                'perihal' => 'Laporan Audit Kearsipan Internal Triwulan II Tahun Anggaran 2026',
                'dibuat_oleh_id' => $petugas?->id,
            ],
            [
                'nomor_surat' => '100/705/ARSIP/2026',
                'tanggal_surat' => '2026-08-27',
                'tujuan_surat' => 'Sekretaris Daerah Kabupaten/Kota',
                'perihal' => 'Penyampaian Rekapitulasi Surat Masuk dan Keluar Bulan Agustus 2026',
                'dibuat_oleh_id' => $petugas?->id,
            ],
        ];

        foreach ($suratKeluarList as $data) {
            SuratKeluar::firstOrCreate(
                ['nomor_surat' => $data['nomor_surat']],
                $data
            );
        }
    }
}
