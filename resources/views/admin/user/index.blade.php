@extends('layouts.admin')
@section('page-title', 'Manajemen User')
@section('content')
<div class="card"><div class="card-header d-flex justify-content-between"><span><i class="fas fa-users-cog me-2"></i>Manajemen User</span><a href="{{ route('admin.user.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i></a></div>
    <div class="card-body"><table class="table table-hover"><thead><tr><th>#</th><th>Nama</th><th>Email</th><th>NIP</th><th>OPD</th><th>Role</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>@foreach($users as $u)<tr><td>{{ $u->id }}</td><td>{{ $u->name }}</td><td>{{ $u->email }}</td><td>{{ $u->nip ?? '-' }}</td><td>{{ $u->opd?->singkatan ?? '-' }}</td>
    <td><span class="badge bg-{{ $u->role === 'super_admin' ? 'danger' : ($u->role === 'admin_aset' ? 'primary' : 'secondary') }}">{{ strtoupper(str_replace('_', ' ', $u->role)) }}</span></td>
    <td><span class="badge bg-{{ $u->is_active ? 'success' : 'warning' }}">{{ $u->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
    <td><a href="{{ route('admin.user.edit', $u) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a></td></tr>@endforeach</tbody></table>
    {{ $users->links() }}</div></div>
@endsection
