<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengamanan_administrasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_id')->constrained()->cascadeOnDelete();
            $table->enum('sertifikat', ['ada', 'tidak_ada'])->default('tidak_ada');
            $table->enum('imb_pbg', ['ada', 'tidak_ada'])->default('tidak_ada');
            $table->enum('ajb', ['ada', 'tidak_ada'])->default('tidak_ada');
            $table->enum('hibah', ['ada', 'tidak_ada'])->default('tidak_ada');
            $table->enum('pelepasan_hak', ['ada', 'tidak_ada'])->default('tidak_ada');
            $table->enum('sk', ['ada', 'tidak_ada'])->default('tidak_ada');
            $table->enum('berita_acara', ['ada', 'tidak_ada'])->default('tidak_ada');
            $table->enum('dokumen_pendukung', ['lengkap', 'kurang', 'belum_ada'])->default('belum_ada');
            $table->text('keterangan')->nullable();
            $table->foreignId('checked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('pengamanan_fisiks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_id')->constrained()->cascadeOnDelete();
            $table->enum('patok', ['ada', 'tidak_ada', 'rusak'])->default('tidak_ada');
            $table->enum('pagar', ['ada', 'tidak_ada', 'rusak'])->default('tidak_ada');
            $table->enum('plang_pemda', ['ada', 'tidak_ada', 'rusak'])->default('tidak_ada');
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat', 'tidak_ditemukan'])->default('tidak_ditemukan');
            $table->string('foto_sebelum')->nullable();
            $table->string('foto_sesudah')->nullable();
            $table->date('tanggal_monitoring')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('checked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('pengamanan_hukums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_id')->constrained()->cascadeOnDelete();
            $table->enum('status_sengketa', ['tidak_sengketa', 'sengketa', 'perkara', 'gugatan'])->default('tidak_sengketa');
            $table->string('nomor_perkara')->nullable();
            $table->string('pihak_lawan')->nullable();
            $table->enum('status_pengadilan', ['belum', 'berjalan', 'selesai', 'menang', 'kalah'])->nullable();
            $table->enum('pendampingan_kejaksaan', ['belum', 'berjalan', 'selesai'])->default('belum');
            $table->date('tanggal_sengketa')->nullable();
            $table->text('riwayat_penyelesaian')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('checked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengamanan_hukums');
        Schema::dropIfExists('pengamanan_fisiks');
        Schema::dropIfExists('pengamanan_administrasis');
    }
};
