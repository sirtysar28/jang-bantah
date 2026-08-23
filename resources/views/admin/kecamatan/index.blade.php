@extends('layouts.admin')
@section('page-title', 'Kecamatan')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-map me-2"></i>Master Kecamatan</span>
        <a href="{{ route('admin.kecamatan.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>Tambah Kecamatan</a>
    </div>
    <div class="card-body">
        <div class="alert alert-info py-2 mb-3" style="font-size:.8rem;">
            <i class="fas fa-info-circle me-1"></i>
            Data kecamatan & desa mengacu pada
            <a href="https://id.wikipedia.org/wiki/Daftar_kecamatan_dan_kelurahan_di_Kabupaten_Halmahera_Barat" target="_blank" rel="noopener">Daftar kecamatan & kelurahan Kab. Halmahera Barat (Wikipedia)</a>.
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Wilayah</th>
                        <th>Nama Kecamatan</th>
                        <th>Ibu Kota</th>
                        <th class="text-center">Jumlah Desa</th>
                        <th class="text-center">Jumlah Aset</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kecamatans as $k)
                    <tr>
                        <td>{{ $k->id }}</td>
                        <td><code>{{ $k->kode }}</code></td>
                        <td class="fw-600">{{ $k->nama }}</td>
                        <td>{{ $k->deskripsi ? str_replace('Ibu kota kecamatan: ', '', $k->deskripsi) : '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.desa.index', ['kecamatan_id' => $k->id]) }}"
                               class="badge text-decoration-none {{ $k->desas_count ? 'bg-primary' : 'bg-secondary' }}"
                               title="Lihat daftar desa di {{ $k->nama }}">
                                {{ $k->desas_count }} desa
                            </a>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $k->asets_count ? 'bg-success' : 'bg-light text-dark border' }}">{{ $k->asets_count }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.desa.index', ['kecamatan_id' => $k->id]) }}" class="btn btn-sm btn-outline-info" title="Lihat Desa"><i class="fas fa-list-ul"></i></a>
                            <a href="{{ route('admin.kecamatan.edit', $k) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('admin.kecamatan.destroy', $k) }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Hapus kecamatan {{ $k->nama }}?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-inbox d-block mb-2" style="font-size:1.5rem;"></i>
                            Belum ada data kecamatan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $kecamatans->links() }}
    </div>
</div>
@endsection
