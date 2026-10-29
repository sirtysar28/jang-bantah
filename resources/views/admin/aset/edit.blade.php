@extends('layouts.admin')
@section('page-title', 'Edit Aset')
@section('content')
<div class="card"><div class="card-header"><span><i class="fas fa-edit me-2"></i>Edit Data Aset</span></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.aset.update', $aset) }}" enctype="multipart/form-data">@csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Nomor Register *</label><input type="text" name="nomor_register" class="form-control" value="{{ $aset->nomor_register }}" required></div>
                <div class="col-md-6"><label class="form-label">Kode Barang *</label><input type="text" name="kode_barang" class="form-control" value="{{ $aset->kode_barang }}" required></div>
                <div class="col-12"><label class="form-label">Nama Aset *</label><input type="text" name="nama_aset" class="form-control" value="{{ $aset->nama_aset }}" required></div>
                <div class="col-md-4"><label class="form-label">Kecamatan <span class="text-danger">*</span></label><select name="kecamatan_id" class="form-select" id="kecamatan" required><option value="">Pilih</option>@foreach($kecamatans as $k)<option value="{{ $k->id }}" {{ $aset->kecamatan_id == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>@endforeach</select></div>
                <div class="col-md-4"><label class="form-label">Desa</label><select name="desa_id" class="form-select" id="desa" disabled><option value="">Pilih Desa</option></select><small class="text-muted d-block mt-1" id="desaHint" style="font-size:.7rem;"><i class="fas fa-info-circle me-1"></i>Pilih kecamatan terlebih dahulu.</small></div>
                <div class="col-md-4"><label class="form-label">OPD</label>@if(auth()->user()->role === 'opd')<select class="form-select" disabled>@foreach($opds as $o)<option value="{{ $o->id }}" selected>{{ $o->nama }}</option>@endforeach</select><input type="hidden" name="opd_id" value="{{ auth()->user()->opd_id }}"><small class="text-muted d-block mt-1" style="font-size:.7rem;"><i class="fas fa-lock me-1"></i>Aset tetap tercatat pada OPD Anda.</small>@else<select name="opd_id" class="form-select"><option value="">Pilih</option>@foreach($opds as $o)<option value="{{ $o->id }}" {{ $aset->opd_id == $o->id ? 'selected' : '' }}>{{ $o->nama }}</option>@endforeach</select>@endif</div>
                <div class="col-md-3"><label class="form-label">Luas (m²)</label><input type="number" step="0.01" name="luas" class="form-control" value="{{ $aset->luas }}"></div>
                <div class="col-md-3"><label class="form-label">Nilai Aset</label><input type="number" name="nilai_aset" class="form-control" value="{{ $aset->nilai_aset }}"></div>
                <div class="col-md-3"><label class="form-label">Tahun</label><input type="number" name="tahun_perolehan" class="form-control" value="{{ $aset->tahun_perolehan }}"></div>
                <div class="col-md-3"><label class="form-label">Status *</label><select name="status" class="form-select" required><option value="aktif" {{ $aset->status === 'aktif' ? 'selected' : '' }}>Aktif</option><option value="tidak_aktif" {{ $aset->status === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option><option value="sengketa" {{ $aset->status === 'sengketa' ? 'selected' : '' }}>Sengketa</option><option value="dipinjamkan" {{ $aset->status === 'dipinjamkan' ? 'selected' : '' }}>Dipinjamkan</option><option value="disediakan" {{ $aset->status === 'disediakan' ? 'selected' : '' }}>Disediakan</option></select></div>
                <div class="col-md-4"><label class="form-label">Sumber Perolehan</label><select name="sumber_perolehan" class="form-select"><option value="">Pilih</option><option value="beli" {{ $aset->sumber_perolehan === 'beli' ? 'selected' : '' }}>Beli</option><option value="hibah" {{ $aset->sumber_perolehan === 'hibah' ? 'selected' : '' }}>Hibah</option><option value="swap" {{ $aset->sumber_perolehan === 'swap' ? 'selected' : '' }}>Swap</option><option value="lainnya" {{ $aset->sumber_perolehan === 'lainnya' ? 'selected' : '' }}>Lainnya</option></select></div>
                <div class="col-md-4"><label class="form-label">Latitude</label><input type="number" step="any" name="latitude" class="form-control" value="{{ $aset->latitude }}"></div>
                <div class="col-md-4"><label class="form-label">Longitude</label><input type="number" step="any" name="longitude" class="form-control" value="{{ $aset->longitude }}"></div>
                <div class="col-12"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control" rows="2">{{ $aset->alamat }}</textarea></div>
                <div class="col-md-6"><label class="form-label">Foto Lokasi</label><input type="file" name="foto_lokasi" class="form-control" accept="image/*">@if($aset->foto_lokasi)<small class="text-muted d-block mt-1">File saat ini: {{ $aset->foto_lokasi }}</small>@endif</div>
                <div class="col-md-3">@if(auth()->user()->role === 'opd')<label class="form-label">Validasi</label><input type="text" class="form-control" value="{{ $aset->is_validated ? 'Sudah' : 'Belum' }}" disabled title="Hanya admin yang dapat memvalidasi">@if($aset->is_validated)<input type="hidden" name="is_validated" value="1">@endif@else<label class="form-label">Validasi</label><select name="is_validated" class="form-select"><option value="0">Belum</option><option value="1" {{ $aset->is_validated ? 'selected' : '' }}>Sudah</option></select>@endif</div>
                <div class="col-md-3"><label class="form-label">Digital</label><select name="is_digital" class="form-select"><option value="0">Belum</option><option value="1" {{ $aset->is_digital ? 'selected' : '' }}>Sudah</option></select></div>
                <div class="col-12"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control" rows="3">{{ $aset->keterangan }}</textarea></div>
                <div class="col-12"><button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button><a href="{{ route('admin.aset.show', $aset) }}" class="btn btn-outline-secondary">Batal</a></div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    /* Cascade Kecamatan -> Desa (pre-selected saat edit) */
    const ALL_DESAS = @json($desas->map(fn($d) => ['id' => $d->id, 'nama' => $d->nama, 'kecamatan_id' => $d->kecamatan_id]));
    const SELECTED_DESA = {{ $aset->desa_id ? $aset->desa_id : 'null' }};
    (function () {
        const kecSelect = document.getElementById('kecamatan');
        const desaSelect = document.getElementById('desa');
        const hint = document.getElementById('desaHint');

        function populateDesa(kecId, selectedDesaId) {
            desaSelect.innerHTML = '<option value="">Pilih Desa</option>';
            if (!kecId) {
                desaSelect.disabled = true;
                if (hint) hint.style.display = 'block';
                return;
            }
            const list = ALL_DESAS.filter(d => String(d.kecamatan_id) === String(kecId));
            list.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.id;
                opt.textContent = d.nama;
                if (selectedDesaId && String(d.id) === String(selectedDesaId)) opt.selected = true;
                desaSelect.appendChild(opt);
            });
            desaSelect.disabled = false;
            if (hint) hint.style.display = list.length ? 'none' : 'block';
        }

        kecSelect.addEventListener('change', () => populateDesa(kecSelect.value, null));
        populateDesa(kecSelect.value, SELECTED_DESA);

        /* Validasi sebelum submit: desa harus milik kecamatan yang dipilih */
        const form = kecSelect.closest('form');
        if (form) {
            form.addEventListener('submit', function (e) {
                const kecId = kecSelect.value;
                const desaId = desaSelect.value;
                if (desaId && kecId) {
                    const valid = ALL_DESAS.some(d => String(d.id) === String(desaId) && String(d.kecamatan_id) === String(kecId));
                    if (!valid) {
                        e.preventDefault();
                        alert('Desa yang dipilih tidak sesuai dengan kecamatan. Silakan pilih ulang.');
                        desaSelect.value = '';
                        return false;
                    }
                }
            });
        }
    })();
</script>
@endpush
