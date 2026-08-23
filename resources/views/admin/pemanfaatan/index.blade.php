@extends('layouts.admin')
@section('page-title', 'Pemanfaatan Aset')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="fas fa-handshake me-2"></i>Pemanfaatan Aset</span>
        <form method="GET" action="{{ route('admin.pemanfaatan.index') }}" class="d-flex gap-2">
            <select name="kategori" class="form-select form-select-sm" style="width:180px;" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                <option value="disewakan" {{ request('kategori') === 'disewakan' ? 'selected' : '' }}>Disewakan</option>
                <option value="kerjasama" {{ request('kategori') === 'kerjasama' ? 'selected' : '' }}>Kerjasama</option>
                <option value="pinjam_pakai" {{ request('kategori') === 'pinjam_pakai' ? 'selected' : '' }}>Pinjam Pakai</option>
                <option value="bangun_guna_serah" {{ request('kategori') === 'bangun_guna_serah' ? 'selected' : '' }}>Bangun Guna Serah</option>
                <option value="idle_asset" {{ request('kategori') === 'idle_asset' ? 'selected' : '' }}>Idle Asset</option>
            </select>
            <select name="status" class="form-select form-select-sm" style="width:150px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="tidak_aktif" {{ request('status') === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            <a href="{{ route('admin.pemanfaatan.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-undo"></i></a>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-align-middle mb-0">
                <thead style="background:#f8f9fa;">
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Aset</th>
                        <th>Kecamatan</th>
                        <th>OPD</th>
                        <th>Kategori</th>
                        <th>Pihak</th>
                        <th>Potensi PAD</th>
                        <th>Realisasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pemanfaatans as $p)
                    <tr>
                        <td class="text-muted">{{ $pemanfaatans->firstItem() + $loop->iteration - 1 }}</td>
                        <td>
                            <a href="{{ route('admin.aset.show', $p->aset_id) }}" class="text-decoration-none fw-500" style="font-size:.85rem;">
                                {{ $p->aset?->nama_aset ?? '-' }}
                            </a>
                            @if($p->aset?->nomor_register)
                            <br><small class="text-muted">{{ $p->aset->nomor_register }}</small>
                            @endif
                        </td>
                        <td style="font-size:.85rem;">{{ $p->aset?->kecamatan?->nama ?? '-' }}</td>
                        <td style="font-size:.85rem;">{{ $p->aset?->opd?->singkatan ?? $p->aset?->opd?->nama ?? '-' }}</td>
                        <td><span class="badge bg-primary">{{ strtoupper(str_replace('_', ' ', $p->kategori)) }}</span></td>
                        <td style="font-size:.82rem;">{{ $p->nama_pihak ?? '-' }}</td>
                        <td style="font-size:.82rem;">Rp {{ number_format($p->nilai_potensi_pad ?? 0, 0, ',', '.') }}</td>
                        <td style="font-size:.82rem;">Rp {{ number_format($p->nilai_realisasi ?? 0, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $pemStatusBadge = ['aktif' => 'success', 'tidak_aktif' => 'secondary', 'selesai' => 'info'];
                            @endphp
                            <span class="badge bg-{{ $pemStatusBadge[$p->status] ?? 'secondary' }}">{{ ucfirst($p->status) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.aset.show', $p->aset_id) }}" class="btn btn-sm btn-outline-primary" title="Detail Aset"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center py-4 text-muted">
                        <i class="fas fa-handshake d-block mb-2" style="font-size:2rem;opacity:.2;"></i>
                        Belum ada data pemanfaatan aset.
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($pemanfaatans->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
        <small class="text-muted">Menampilkan {{ $pemanfaatans->firstItem() ?? 0 }}-{{ $pemanfaatans->lastItem() ?? 0 }} dari {{ $pemanfaatans->total() }}</small>
        {{ $pemanfaatans->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
