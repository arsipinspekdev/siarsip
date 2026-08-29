<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Database\Seeder;

final class SuratMasukSeeder extends Seeder
{
    public function run(): void
    {
        $petugas = User::where('username', 'petugas')->first() ?? User::first();

        $suratMasukList = [
            [
                'nomor_surat' => '005/120/DISDIK/2026',
                'tanggal_surat' => '2026-08-10',
                'tanggal_terima' => '2026-08-12',
                'asal_surat' => 'Dinas Pendidikan dan Kebudayaan',
                'perihal' => 'Undangan Rapat Koordinasi Program Peningkatan Mutu Layanan Administrasi',
                'diterima_oleh_id' => $petugas?->id,
            ],
            [
                'nomor_surat' => '421.3/088/BAPPEDA/2026',
                'tanggal_surat' => '2026-08-14',
                'tanggal_terima' => '2026-08-15',
                'asal_surat' => 'Badan Perencanaan Pembangunan Daerah (BAPPEDA)',
                'perihal' => 'Permintaan Data Laporan Kinerja Instansi Triwulan II Tahun 2026',
                'diterima_oleh_id' => $petugas?->id,
            ],
            [
                'nomor_surat' => '800/412/BKPSDM/2026',
                'tanggal_surat' => '2026-08-18',
                'tanggal_terima' => '2026-08-19',
                'asal_surat' => 'Badan Kepegawaian dan Pengembangan SDM',
                'perihal' => 'Pemberitahuan Pelaksanaan Pelatihan Tata Kelola Kearsipan Digital',
                'diterima_oleh_id' => $petugas?->id,
            ],
            [
                'nomor_surat' => '027/550/BPKAD/2026',
                'tanggal_surat' => '2026-08-20',
                'tanggal_terima' => '2026-08-21',
                'asal_surat' => 'Badan Pengelolaan Keuangan dan Aset Daerah',
                'perihal' => 'Rekonsiliasi Aset dan Inventaris Barang Milik Daerah Semester I',
                'diterima_oleh_id' => $petugas?->id,
            ],
            [
                'nomor_surat' => '100/321/SETDA/2026',
                'tanggal_surat' => '2026-08-22',
                'tanggal_terima' => '2026-08-23',
                'asal_surat' => 'Sekretariat Daerah Bagian Organisasi',
                'perihal' => 'Sosialisasi Standar Operasional Prosedur (SOP) Tata Naskah Dinas Elektronik',
                'diterima_oleh_id' => $petugas?->id,
            ],
            [
                'nomor_surat' => '900/654/INSPEKTORAT/2026',
                'tanggal_surat' => '2026-08-25',
                'tanggal_terima' => '2026-08-26',
                'asal_surat' => 'Inspektorat Daerah',
                'perihal' => 'Jadwal Pengawasan Berkala Pengelolaan Dokumen dan Surat Kedinasan',
                'diterima_oleh_id' => $petugas?->id,
            ],
        ];

        foreach ($suratMasukList as $data) {
            SuratMasuk::firstOrCreate(
                ['nomor_surat' => $data['nomor_surat']],
                $data
            );
        }
    }
}
