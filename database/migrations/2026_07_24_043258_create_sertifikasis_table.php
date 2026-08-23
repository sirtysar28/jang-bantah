<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sertifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_id')->constrained()->cascadeOnDelete();
            $table->enum('status', [
                'belum_diajukan',
                'persiapan_berkas',
                'diajukan_bpn',
                'proses_ukur',
                'proses_validasi',
                'terbit_sertifikat',
                'selesai'
            ])->default('belum_diajukan');
            $table->string('nomor_sertifikat')->nullable();
            $table->date('tanggal_pengajuan')->nullable();
            $table->date('tanggal_terbit')->nullable();
            $table->string('nomor_hm_hgb')->nullable();
            $table->enum('jenis_sertifikat', ['hm', 'hgb', 'hak_pengelolaan', 'hak_use'])->nullable();
            $table->text('keterangan')->nullable();
            $table->decimal('progress', 5, 2)->default(0)->comment('Progress percentage');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('monitoring_asets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_id')->constrained()->cascadeOnDelete();
            $table->string('jenis'); // validasi, sertifikasi, digitalisasi, dokumen, fisik
            $table->enum('status', ['pending', 'berjalan', 'selesai', 'gagal'])->default('pending');
            $table->decimal('progress', 5, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('pemanfaatan_asets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_id')->constrained()->cascadeOnDelete();
            $table->enum('kategori', ['disewakan', 'kerjasama', 'pinjam_pakai', 'bangun_guna_serah', 'idle_asset'])->default('idle_asset');
            $table->string('nama_pihak')->nullable();
            $table->text('alamat_pihak')->nullable();
            $table->string('kontak_pihak')->nullable();
            $table->decimal('nilai_potensi_pad', 20, 2)->default(0);
            $table->decimal('nilai_realisasi', 20, 2)->default(0);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('dokumen_perjanjian')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['aktif', 'tidak_aktif', 'selesai'])->default('aktif');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemanfaatan_asets');
        Schema::dropIfExists('monitoring_asets');
        Schema::dropIfExists('sertifikasis');
    }
};
