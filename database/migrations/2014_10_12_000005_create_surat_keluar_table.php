<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id(); // Otomatis jadi "No. Agenda"
            $table->string('nomor_surat', 100);
            $table->date('tanggal_surat');
            $table->string('tujuan_surat', 255);
            $table->text('perihal');
            $table->string('file_surat', 255)->nullable();
            $table->foreignId('dibuat_oleh_id')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();

            $table->index('tanggal_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluar');
    }
};
