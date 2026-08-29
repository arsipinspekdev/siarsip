<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\SuratKeluar;
use Illuminate\Support\Facades\DB;

final class SuratKeluarObserver
{
    /**
     * Setelah record baru dibuat, assign no_agenda sebagai jumlah record aktif.
     */
    public function created(SuratKeluar $suratKeluar): void
    {
        $nextNo = SuratKeluar::withoutTrashed()->count();
        $suratKeluar->updateQuietly(['no_agenda' => $nextNo]);
    }

    /**
     * Setelah soft-delete, resequence semua record yang masih aktif.
     */
    public function deleted(SuratKeluar $suratKeluar): void
    {
        self::resequenceAll();
    }

    /**
     * Jika record dipulihkan dari trash, resequence ulang.
     */
    public function restored(SuratKeluar $suratKeluar): void
    {
        self::resequenceAll();
    }

    /**
     * Assign ulang no_agenda berurutan 1,2,3... untuk semua record aktif.
     */
    public static function resequenceAll(): void
    {
        $ids = DB::table('surat_keluar')
            ->whereNull('deleted_at')
            ->orderBy('created_at')
            ->orderBy('id')
            ->pluck('id');

        foreach ($ids as $seq => $id) {
            DB::table('surat_keluar')
                ->where('id', $id)
                ->update(['no_agenda' => $seq + 1]);
        }
    }
}
