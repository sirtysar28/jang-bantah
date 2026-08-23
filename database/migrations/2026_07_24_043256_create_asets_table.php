<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asets', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_register')->unique();
            $table->string('kode_barang');
            $table->string('nama_aset');
            $table->foreignId('kecamatan_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('desa_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('opd_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('luas', 15, 2)->nullable()->comment('Luas dalam m2');
            $table->decimal('nilai_aset', 20, 2)->default(0);
            $table->enum('status', ['aktif', 'tidak_aktif', 'sengketa', 'dipinjamkan', 'disediakan'])->default('aktif');
            $table->integer('tahun_perolehan')->nullable();
            $table->enum('sumber_perolehan', ['beli', 'hibah', 'swap', 'masuk_karena_diambil_alih', 'hasil_pengembalian', 'hasil_pendapatan', 'lainnya'])->nullable();
            $table->string('alamat')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->json('polygon')->nullable()->comment('GeoJSON polygon');
            $table->string('foto_lokasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status_dokumen', ['lengkap', 'kurang', 'belum_ada'])->default('belum_ada');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_validated')->default(false);
            $table->boolean('is_digital')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asets');
    }
};
