@extends('layouts.admin')
@section('page-title', 'Edit Desa')
@section('content')
<div class="card"><div class="card-header"><span><i class="fas fa-edit me-2"></i>Edit Desa</span></div>
    <div class="card-body"><form method="POST" action="{{ route('admin.desa.update', $desa) }}">@csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Nama *</label><input type="text" name="nama" class="form-control" value="{{ $desa->nama }}" required></div>
            <div class="col-md-6"><label class="form-label">Kode *</label><input type="text" name="kode" class="form-control" value="{{ $desa->kode }}" required></div>
            <div class="col-md-6"><label class="form-label">Kecamatan *</label><select name="kecamatan_id" class="form-select" required><option value="">Pilih</option>@foreach($kecamatans as $k)<option value="{{ $k->id }}" {{ $desa->kecamatan_id == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>@endforeach</select></div>
            <div class="col-md-6"><label class="form-label">Latitude</label><input type="number" step="any" name="latitude" class="form-control" value="{{ $desa->latitude }}"></div>
            <div class="col-md-6"><label class="form-label">Longitude</label><input type="number" step="any" name="longitude" class="form-control" value="{{ $desa->longitude }}"></div>
            <div class="col-12"><button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button><a href="{{ route('admin.desa.index') }}" class="btn btn-outline-secondary">Batal</a></div>
        </div></form></div></div>
@endsection
