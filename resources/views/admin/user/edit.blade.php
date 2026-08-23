@extends('layouts.admin')
@section('page-title', 'Edit User')
@section('content')
<div class="card"><div class="card-header"><span><i class="fas fa-user-edit me-2"></i>Edit User</span></div>
    <div class="card-body"><form method="POST" action="{{ route('admin.user.update', $user) }}">@csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Nama *</label><input type="text" name="name" class="form-control" value="{{ $user->name }}" required></div>
            <div class="col-md-6"><label class="form-label">NIP</label><input type="text" name="nip" class="form-control" value="{{ $user->nip }}"></div>
            <div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" value="{{ $user->email }}" required></div>
            <div class="col-md-6"><label class="form-label">Password Baru</label><input type="password" name="password" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">Confirm Password</label><input type="password" name="password_confirmation" class="form-control"></div>
            <div class="col-md-3"><label class="form-label">Jabatan</label><input type="text" name="jabatan" class="form-control" value="{{ $user->jabatan }}"></div>
            <div class="col-md-3"><label class="form-label">Telepon</label><input type="text" name="phone" class="form-control" value="{{ $user->phone }}"></div>
            <div class="col-md-3"><label class="form-label">OPD</label><select name="opd_id" class="form-select"><option value="">Pilih</option>@foreach($opds as $o)<option value="{{ $o->id }}" {{ $user->opd_id == $o->id ? 'selected' : '' }}>{{ $o->nama }}</option>@endforeach</select></div>
            <div class="col-md-3"><label class="form-label">Role *</label><select name="role" class="form-select" required><option value="super_admin" {{ $user->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option><option value="admin_aset" {{ $user->role === 'admin_aset' ? 'selected' : '' }}>Admin Aset</option><option value="opd" {{ $user->role === 'opd' ? 'selected' : '' }}>OPD</option><option value="pimpinan" {{ $user->role === 'pimpinan' ? 'selected' : '' }}>Pimpinan</option></select></div>
            <div class="col-md-3"><label class="form-label">Status</label><select name="is_active" class="form-select"><option value="1" {{ $user->is_active ? 'selected' : '' }}>Aktif</option><option value="0" {{ !$user->is_active ? 'selected' : '' }}>Nonaktif</option></select></div>
            <div class="col-12"><button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button><a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary">Batal</a></div>
        </div></form></div></div>
@endsection
