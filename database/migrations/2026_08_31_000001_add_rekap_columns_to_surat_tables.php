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
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->string('sifat_surat', 50)->nullable()->after('nomor_surat');
            $table->string('pengirim', 255)->nullable()->after('sifat_surat');
            $table->date('tanggal_penomoran')->nullable()->after('pengirim');
            $table->string('disposisi', 255)->nullable()->after('tanggal_penomoran');
            $table->string('pengelola', 255)->nullable()->after('disposisi');
            $table->string('jenis_surat', 100)->nullable()->after('pengelola');
        });

        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->string('sifat_surat', 50)->nullable()->after('nomor_surat');
            $table->string('pengirim', 255)->nullable()->after('sifat_surat');
            $table->date('tanggal_penomoran')->nullable()->after('pengirim');
            $table->string('disposisi', 255)->nullable()->after('tanggal_penomoran');
            $table->string('pengelola', 255)->nullable()->after('disposisi');
            $table->string('jenis_surat', 100)->nullable()->after('pengelola');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->dropColumn(['sifat_surat', 'pengirim', 'tanggal_penomoran', 'disposisi', 'pengelola', 'jenis_surat']);
        });

        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->dropColumn(['sifat_surat', 'pengirim', 'tanggal_penomoran', 'disposisi', 'pengelola', 'jenis_surat']);
        });
    }
};
