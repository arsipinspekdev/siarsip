<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom no_agenda ke surat_masuk
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->unsignedInteger('no_agenda')->nullable()->after('id');
            $table->index('no_agenda');
        });

        // Tambah kolom no_agenda ke surat_keluar
        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->unsignedInteger('no_agenda')->nullable()->after('id');
            $table->index('no_agenda');
        });

        // Seed no_agenda berurutan untuk data yang sudah ada (berdasarkan urutan created_at)
        $this->resequence('surat_masuk');
        $this->resequence('surat_keluar');
    }

    public function down(): void
    {
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->dropIndex(['no_agenda']);
            $table->dropColumn('no_agenda');
        });

        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->dropIndex(['no_agenda']);
            $table->dropColumn('no_agenda');
        });
    }

    /**
     * Assign sequential no_agenda to existing rows ordered by created_at, id.
     */
    private function resequence(string $table): void
    {
        $rows = DB::table($table)
            ->whereNull('deleted_at')
            ->orderBy('created_at')
            ->orderBy('id')
            ->pluck('id');

        foreach ($rows as $seq => $id) {
            DB::table($table)->where('id', $id)->update(['no_agenda' => $seq + 1]);
        }
    }
};
