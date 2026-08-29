<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\SuratMasuk;
use Illuminate\Support\Facades\DB;

final class SuratMasukObserver
{
    /**
     * Setelah record baru dibuat, assign no_agenda sebagai jumlah record + 1.
     */
    public function created(SuratMasuk $suratMasuk): void
    {
        $nextNo = SuratMasuk::withoutTrashed()->count();
        $suratMasuk->updateQuietly(['no_agenda' => $nextNo]);
    }

    /**
     * Setelah soft-delete, resequence semua record yang masih aktif
     * sehingga nomor agenda tetap berurutan tanpa celah.
     */
    public function deleted(SuratMasuk $suratMasuk): void
    {
        self::resequenceAll();
    }

    /**
     * Jika record dipulihkan dari trash, resequence ulang.
     */
    public function restored(SuratMasuk $suratMasuk): void
    {
        self::resequenceAll();
    }

    /**
     * Assign ulang no_agenda berurutan 1,2,3... untuk semua record aktif.
     */
    public static function resequenceAll(): void
    {
        $ids = DB::table('surat_masuk')
            ->whereNull('deleted_at')
            ->orderBy('created_at')
            ->orderBy('id')
            ->pluck('id');

        foreach ($ids as $seq => $id) {
            DB::table('surat_masuk')
                ->where('id', $id)
                ->update(['no_agenda' => $seq + 1]);
        }
    }
}
