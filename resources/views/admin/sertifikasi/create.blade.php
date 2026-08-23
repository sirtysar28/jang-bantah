@extends('layouts.admin')
@section('page-title', 'Tambah Sertifikasi')
@section('content')
<div class="card"><div class="card-header"><span><i class="fas fa-certificate me-2"></i>Tambah Sertifikasi - {{ $aset->nama_aset }}</span></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.sertifikasi.store', $aset) }}">@csrf
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Status *</label><select name="status" class="form-select" required><option value="belum_diajukan">Belum Diajukan</option><option value="persiapan_berkas">Persiapan Berkas</option><option value="diajukan_bpn">Diajukan ke BPN</option><option value="proses_ukur">Proses Ukur</option><option value="proses_validasi">Proses Validasi</option><option value="terbit_sertifikat">Terbit Sertifikat</option><option value="selesai">Selesai</option></select></div>
                <div class="col-md-4"><label class="form-label">No. Sertifikat</label><input type="text" name="nomor_sertifikat" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Jenis Sertifikat</label><select name="jenis_sertifikat" class="form-select"><option value="">Pilih</option><option value="hm">HM</option><option value="hgb">HGB</option><option value="hak_pengelolaan">Hak Pengelolaan</option><option value="hak_use">Hak Use</option></select></div>
                <div class="col-md-3"><label class="form-label">Tgl Pengajuan</label><input type="date" name="tanggal_pengajuan" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">Tgl Terbit</label><input type="date" name="tanggal_terbit" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">No. HM/HGB</label><input type="text" name="nomor_hm_hgb" class="form-control"></div>
                <div class="col-md-3"><label class="form-label">Keterangan</label><input type="text" name="keterangan" class="form-control"></div>
                <div class="col-12"><button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                    <a href="{{ route('admin.aset.show', $aset) }}" class="btn btn-outline-secondary">Batal</a></div>
            </div>
        </form>
    </div>
</div>
@endsection
