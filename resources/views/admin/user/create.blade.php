@extends('layouts.admin')
@section('page-title', 'Tambah User')
@section('content')
<div class="card"><div class="card-header"><span><i class="fas fa-user-plus me-2"></i>Tambah User</span></div>
    <div class="card-body"><form method="POST" action="{{ route('admin.user.store') }}">@csrf
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Nama *</label><input type="text" name="name" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">NIP</label><input type="text" name="nip" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Password *</label><input type="password" name="password" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Confirm Password *</label><input type="password" name="password_confirmation" class="form-control" required></div>
            <div class="col-md-3"><label class="form-label">Jabatan</label><input type="text" name="jabatan" class="form-control"></div>
            <div class="col-md-3"><label class="form-label">Telepon</label><input type="text" name="phone" class="form-control"></div>
            <div class="col-md-3"><label class="form-label">OPD</label><select name="opd_id" class="form-select"><option value="">Pilih</option>@foreach($opds as $o)<option value="{{ $o->id }}">{{ $o->nama }}</option>@endforeach</select></div>
            <div class="col-md-3"><label class="form-label">Role *</label><select name="role" class="form-select" required><option value="super_admin">Super Admin</option><option value="admin_aset">Admin Bidang Aset</option><option value="opd">OPD</option><option value="pimpinan">Pimpinan</option></select></div>
            <div class="col-12"><button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button><a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary">Batal</a></div>
        </div></form></div></div>
@endsection
