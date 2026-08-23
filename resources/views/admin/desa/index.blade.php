@extends('layouts.admin')
@section('page-title', 'Desa')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="fas fa-map-pin me-2"></i>Master Desa</span>
        <a href="{{ route('admin.desa.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>Tambah Desa</a>
    </div>
    <div class="card-body">
        @php
        $currentKec = request('kecamatan_id');
        @endphp
        <form method="GET" action="{{ route('admin.desa.index') }}" class="row g-2 mb-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label mb-1" style="font-size:.8rem;">Filter Kecamatan</label>
                <select name="kecamatan_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Kecamatan</option>
                    @foreach($kecamatans as $k)
                    <option value="{{ $k->id }}" {{ (string) $currentKec === (string) $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label mb-1" style="font-size:.8rem;">Cari Nama/Kode Desa</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Ketik nama atau kode desa...">
                    <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.desa.index') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="fas fa-undo me-1"></i>Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Wilayah</th>
                        <th>Nama Desa</th>
                        <th>Kecamatan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($desas as $d)
                    <tr>
                        <td>{{ $d->id }}</td>
                        <td><code>{{ $d->kode }}</code></td>
                        <td>{{ $d->nama }}</td>
                        <td>
                            @if($d->kecamatan)
                            <a href="{{ route('admin.desa.index', ['kecamatan_id' => $d->kecamatan_id]) }}" class="text-decoration-none">
                                <span class="badge bg-light text-dark border">{{ $d->kecamatan->nama }}</span>
                            </a>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.desa.edit', $d) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('admin.desa.destroy', $d) }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus desa {{ $d->nama }}?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="fas fa-inbox d-block mb-2" style="font-size:1.5rem;"></i>
                            Tidak ada data desa.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <small class="text-muted">Total: {{ $desas->total() }} desa</small>
            {{ $desas->links() }}
        </div>
    </div>
</div>
@endsection
