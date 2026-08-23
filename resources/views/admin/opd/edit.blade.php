@extends('layouts.admin')
@section('page-title', 'Edit OPD')
@section('content')
<div class="card"><div class="card-header"><span><i class="fas fa-edit me-2"></i>Edit OPD</span></div>
    <div class="card-body"><form method="POST" action="{{ route('admin.opd.update', $opd) }}">@csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Nama *</label><input type="text" name="nama" class="form-control" value="{{ $opd->nama }}" required></div>
            <div class="col-md-6"><label class="form-label">Kode *</label><input type="text" name="kode" class="form-control" value="{{ $opd->kode }}" required></div>
            <div class="col-md-4"><label class="form-label">Singkatan</label><input type="text" name="singkatan" class="form-control" value="{{ $opd->singkatan }}"></div>
            <div class="col-md-4"><label class="form-label">Kepala OPD</label><input type="text" name="kepala_opd" class="form-control" value="{{ $opd->kepala_opd }}"></div>
            <div class="col-md-4"><label class="form-label">NIP Kepala</label><input type="text" name="nip_kepala" class="form-control" value="{{ $opd->nip_kepala }}"></div>
            <div class="col-md-12"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control" rows="2">{{ $opd->alamat }}</textarea></div>
            <div class="col-md-6"><label class="form-label">Telepon</label><input type="text" name="telepon" class="form-control" value="{{ $opd->telepon }}"></div>
            <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ $opd->email }}"></div>
            <div class="col-12"><button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button><a href="{{ route('admin.opd.index') }}" class="btn btn-outline-secondary">Batal</a></div>
        </div></form></div></div>
@endsection
