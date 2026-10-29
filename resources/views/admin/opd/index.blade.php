@extends('layouts.admin')
@section('page-title', 'OPD')
@section('content')
<div class="card"><div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2"><span><i class="fas fa-building me-2"></i>Master OPD</span><a href="{{ route('admin.opd.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>Tambah OPD</a></div>
    <div class="card-body"><table class="table table-hover"><thead><tr><th>#</th><th>Kode</th><th>Nama</th><th>Kepala</th><th>Aset</th><th>Aksi</th></tr></thead>
    <tbody>@foreach($opds as $o)<tr><td>{{ $o->id }}</td><td><code>{{ $o->kode }}</code></td><td>{{ $o->nama }}</td><td>{{ $o->kepala_opd ?? '-' }}</td><td>{{ $o->asets_count }}</td>
    <td><a href="{{ route('admin.opd.edit', $o) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
    <form method="POST" action="{{ route('admin.opd.destroy', $o) }}" style="display:inline;">@csrf @method('DELETE')
    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></button></form></td></tr>@endforeach</tbody></table>
    {{ $opds->links() }}</div></div>
@endsection
