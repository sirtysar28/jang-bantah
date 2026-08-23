<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_asets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_id')->constrained()->cascadeOnDelete();
            $table->string('jenis_dokumen'); // sertifikat, ajb, imb_pbg, hibah, pelepasan_hak, sk, berita_acara, peta_bidang, shp, dwg, lainnya
            $table->string('nomor_dokumen')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->date('tanggal_dokumen')->nullable();
            $table->date('masa_berlaku')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_asets');
    }
};
