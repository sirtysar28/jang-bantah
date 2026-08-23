@extends('layouts.admin')
@section('page-title', 'Tambah Kecamatan')
@section('content')
<div class="card"><div class="card-header"><span><i class="fas fa-plus me-2"></i>Tambah Kecamatan</span></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.kecamatan.store') }}">@csrf
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Nama *</label><input type="text" name="nama" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Kode *</label><input type="text" name="kode" class="form-control" required></div>
                <div class="col-md-12"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="2"></textarea></div>
                <div class="col-md-6"><label class="form-label">Latitude</label><input type="number" step="any" name="latitude" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Longitude</label><input type="number" step="any" name="longitude" class="form-control"></div>
                <div class="col-12"><button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                    <a href="{{ route('admin.kecamatan.index') }}" class="btn btn-outline-secondary">Batal</a></div>
            </div>
        </form>
    </div>
</div>
@endsection
