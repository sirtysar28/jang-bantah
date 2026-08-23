@extends('layouts.admin')
@section('page-title', 'Data Tanah')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="fas fa-map-marked-alt me-2"></i>Data Tanah / Aset</span>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.aset.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Tambah Aset</a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama/nomor..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="kecamatan_id" class="form-select form-select-sm">
                    <option value="">Semua Kecamatan</option>
                    @foreach($kecamatans as $k)
                    <option value="{{ $k->id }}" {{ request('kecamatan_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="opd_id" class="form-select form-select-sm">
                    <option value="">Semua OPD</option>
                    @foreach($opds as $o)
                    <option value="{{ $o->id }}" {{ request('opd_id') == $o->id ? 'selected' : '' }}>{{ $o->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="sengketa" {{ request('status') === 'sengketa' ? 'selected' : '' }}>Sengketa</option>
                    <option value="tidak_aktif" {{ request('status') === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="sertifikasi" class="form-select form-select-sm">
                    <option value="">Sertifikasi</option>
                    <option value="ada" {{ request('sertifikasi') === 'ada' ? 'selected' : '' }}>Sudah Sertifikat</option>
                    <option value="belum" {{ request('sertifikasi') === 'belum' ? 'selected' : '' }}>Belum Sertifikat</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-sm btn-outline-primary w-100"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nomor Register</th>
                        <th>Nama Aset</th>
                        <th>Kecamatan</th>
                        <th>OPD</th>
                        <th>Luas (m²)</th>
                        <th>Nilai Aset</th>
                        <th>Status</th>
                        <th>Sertifikat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($asets as $aset)
                    <tr>
                        <td>{{ $asets->firstItem() + $loop->iteration - 1 }}</td>
                        <td><code>{{ $aset->nomor_register }}</code></td>
                        <td>{{ $aset->nama_aset }}</td>
                        <td>{{ $aset->kecamatan?->nama ?? '-' }}</td>
                        <td>{{ $aset->opd?->singkatan ?? $aset->opd?->nama ?? '-' }}</td>
                        <td>{{ number_format($aset->luas, 2) }}</td>
                        <td>Rp {{ number_format($aset->nilai_aset, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $aset->status === 'aktif' ? 'success' : ($aset->status === 'sengketa' ? 'danger' : 'secondary') }}">
                                {{ strtoupper(str_replace('_', ' ', $aset->status)) }}
                            </span>
                        </td>
                        <td>
                            @if($aset->sertifikasi)
                            <span class="badge bg-{{ in_array($aset->sertifikasi->status, ['terbit_sertifikat', 'selesai']) ? 'success' : 'warning' }}">
                                {{ $aset->sertifikasi->status_label }}
                            </span>
                            @else
                            <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.aset.show', $aset) }}" class="btn btn-outline-primary" title="Detail"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.aset.edit', $aset) }}" class="btn btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                @if(auth()->user()->role === 'super_admin')
                                <form method="POST" action="{{ route('admin.aset.destroy', $aset) }}" style="display:inline;" class="btn-delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-outline-danger btn-delete" data-name="{{ $aset->nama_aset }}" title="Hapus"><i class="fas fa-trash"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $asets->appends(request()->query())->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Hapus aset dengan konfirmasi
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        var $form = $(this).closest('form');
        var name = $(this).data('name');

        Swal.fire({
            title: 'Hapus Aset?',
            html: 'Yakin ingin menghapus <strong>"' + name + '"</strong>?<br><small class="text-danger">Data sertifikasi, dokumen, monitoring, dan semua data terkait akan ikut terhapus. Tindakan ini tidak dapat dibatalkan.</small>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash me-1"></i>Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(function(result) {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Silakan tunggu',
                    allowOutsideClick: false,
                    didOpen: function() { Swal.showLoading(); }
                });
                $form.submit();
            }
        });
    });
});
</script>
@endpush
