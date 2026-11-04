@extends('layouts.admin')
@section('page-title', 'Import Data Aset')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="fas fa-file-excel me-2"></i>Import Data Aset dari Excel</span>
        <a href="{{ route('admin.aset.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali ke Data Tanah
        </a>
    </div>
    <div class="card-body">
        {{-- Hasil import terakhir --}}
        @if (session('import_result'))
            @php $result = session('import_result'); @endphp
            <div class="alert {{ empty($result['errors']) ? 'alert-success' : 'alert-warning' }}">
                <div class="fw-600 mb-1"><i class="fas fa-circle-info me-1"></i>{{ $result['summary'] }}</div>
                <div class="d-flex gap-3 mb-1" style="font-size:.85rem;">
                    <span><i class="fas fa-check text-success me-1"></i>Tersimpan: <b>{{ $result['inserted'] }}</b></span>
                    <span><i class="fas fa-forward text-secondary me-1"></i>Dilewati (duplikat): <b>{{ $result['skipped'] }}</b></span>
                    <span><i class="fas fa-xmark text-danger me-1"></i>Gagal: <b>{{ count($result['errors']) }}</b></span>
                </div>
                @if (!empty($result['errors']))
                    <hr class="my-2">
                    <div style="font-size:.8rem; max-height:200px; overflow:auto;">
                        @foreach ($result['errors'] as $err)
                            <div class="text-danger">{{ $err }}</div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-7">
                <form method="POST" action="{{ route('admin.aset.import') }}" enctype="multipart/form-data">
                    @csrf
                    <label class="form-label fw-600">Pilih File Excel (.xlsx / .xls / .csv)</label>
                    <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    <small class="text-muted d-block mt-1">Ukuran maksimal 10MB. Data dengan nomor register yang sudah ada akan otomatis dilewati (tidak ditimpa).</small>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-1"></i>Import Sekarang</button>
                        <a href="{{ route('admin.aset.template') }}" class="btn btn-success"><i class="fas fa-download me-1"></i>Download Template</a>
                    </div>
                </form>

                <div class="alert alert-info mt-4" style="font-size:.85rem;">
                    <i class="fas fa-lightbulb me-1"></i>
                    Aset hasil import berstatus <b>belum tervalidasi</b> dan langsung muncul di daftar Data Tanah untuk diverifikasi admin.
                </div>
            </div>
            <div class="col-lg-5">
                <div class="border rounded-3 p-3" style="background:#f8fafc; font-size:.83rem;">
                    <div class="fw-600 mb-2"><i class="fas fa-list me-1"></i>Format Kolom Template</div>
                    <table class="table table-sm mb-2 bg-white">
                        <tbody>
                            <tr><td class="fw-500">nomor_register *</td><td>Unik, wajib diisi</td></tr>
                            <tr><td class="fw-500">kode_barang *</td><td>Wajib diisi</td></tr>
                            <tr><td class="fw-500">nama_aset *</td><td>Wajib diisi</td></tr>
                            <tr><td class="fw-500">kecamatan</td><td>Cocok dengan data master Kecamatan</td></tr>
                            <tr><td class="fw-500">desa</td><td>Cocok dengan data master Desa</td></tr>
                            <tr><td class="fw-500">opd</td><td>Nama/singkatan OPD pengguna</td></tr>
                            <tr><td class="fw-500">luas</td><td>m² (angka)</td></tr>
                            <tr><td class="fw-500">nilai_aset</td><td>Rupiah (angka)</td></tr>
                            <tr><td class="fw-500">status</td><td>aktif / tidak_aktif / sengketa / dipinjamkan / disediakan</td></tr>
                            <tr><td class="fw-500">tahun_perolehan</td><td>Contoh: 2015</td></tr>
                            <tr><td class="fw-500">sumber_perolehan</td><td>beli / hibah / swap / masuk_karena_diambil_alih / hasil_pengembalian / hasil_pendapatan / lainnya</td></tr>
                            <tr><td class="fw-500">alamat, latitude, longitude, keterangan</td><td>Opsional</td></tr>
                        </tbody>
                    </table>
                    <div class="text-muted">* = wajib. Nama kecamatan/desa/OPD harus sama dengan data master agar terhubung otomatis.</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
