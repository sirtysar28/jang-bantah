@extends('layouts.admin')
@section('page-title', 'Tambah Aset')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-plus me-2"></i>Tambah Data Aset Tanah</span>
        <a href="{{ route('admin.aset.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.aset.store') }}" enctype="multipart/form-data">
            @csrf
            {{-- Flag: nomor register dibuat otomatis sistem (dihitung ulang saat simpan agar unik);
                 kode barang diisi manual oleh petugas --}}
            <input type="hidden" name="auto_nomor" value="1">
            @if(auth()->user()->role === 'opd')
            <div class="alert alert-warning d-flex align-items-start gap-2 py-2" style="font-size:.85rem;">
                <i class="fas fa-clock mt-1"></i>
                <div>
                    <b>Aset menunggu persetujuan.</b> Data yang Anda ajukan akan diperiksa
                    terlebih dahulu oleh <b>Admin Aset / Super Admin</b> sebelum tampil di
                    halaman publik. Anda akan menerima email pemberitahuan saat aset disetujui.
                </div>
            </div>
            @endif
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-500" style="font-size:.85rem;">
                        Nomor Register
                        <span class="badge bg-primary ms-1" style="font-size:.62rem;"><i class="fas fa-robot me-1"></i>Otomatis dari sistem</span>
                    </label>
                    <input type="text" class="form-control bg-light" value="{{ old('nomor_register', $nextNomor) }}" readonly tabindex="-1" title="Nomor register dibuat otomatis oleh sistem berdasarkan urutan">
                    <input type="hidden" name="nomor_register" value="">
                    <small class="text-muted d-block mt-1" style="font-size:.7rem;"><i class="fas fa-info-circle me-1"></i>Nomor urut dihitung otomatis sistem saat data disimpan.</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-500" style="font-size:.85rem;">
                        Kode Barang <span class="text-danger">*</span>
                        <span class="badge bg-secondary ms-1" style="font-size:.62rem;"><i class="fas fa-keyboard me-1"></i>Diisi manual</span>
                    </label>
                    <input type="text" name="kode_barang" class="form-control" value="{{ old('kode_barang') }}" required maxlength="100" placeholder="Contoh: 1.03.01.01.01.0001">
                    <small class="text-muted d-block mt-1" style="font-size:.7rem;"><i class="fas fa-info-circle me-1"></i>Ketik kode barang sesuai dokumen/register BMD.</small>
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-500" style="font-size:.85rem;">Nama Aset <span class="text-danger">*</span></label>
                    <input type="text" name="nama_aset" class="form-control" value="{{ old('nama_aset') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-500" style="font-size:.85rem;">Kecamatan <span class="text-danger">*</span></label>
                    <select name="kecamatan_id" class="form-select" id="kecamatan" required>
                        <option value="">Pilih Kecamatan</option>
                        @foreach($kecamatans as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-500" style="font-size:.85rem;">Desa</label>
                    <select name="desa_id" class="form-select" id="desa" disabled>
                        <option value="">Pilih Desa</option>
                    </select>
                    <small class="text-muted d-block mt-1" id="desaHint" style="font-size:.7rem;"><i class="fas fa-info-circle me-1"></i>Pilih kecamatan terlebih dahulu.</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-500" style="font-size:.85rem;">OPD</label>
                    @if(auth()->user()->role === 'opd')
                    <select class="form-select" disabled>
                        @foreach($opds as $o)
                        <option value="{{ $o->id }}" selected>{{ $o->nama }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="opd_id" value="{{ auth()->user()->opd_id }}">
                    <small class="text-muted d-block mt-1" style="font-size:.7rem;"><i class="fas fa-lock me-1"></i>Aset otomatis tercatat atas OPD Anda.</small>
                    @else
                    <select name="opd_id" class="form-select">
                        <option value="">Pilih OPD</option>
                        @foreach($opds as $o)
                        <option value="{{ $o->id }}">{{ $o->nama }}</option>
                        @endforeach
                    </select>
                    @endif
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-500" style="font-size:.85rem;">Luas (m²)</label>
                    <input type="number" name="luas" step="0.01" class="form-control" value="{{ old('luas') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-500" style="font-size:.85rem;">Nilai Aset (Rp)</label>
                    <input type="number" name="nilai_aset" class="form-control" value="{{ old('nilai_aset') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-500" style="font-size:.85rem;">Tahun Perolehan</label>
                    <input type="number" name="tahun_perolehan" class="form-control" value="{{ old('tahun_perolehan') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-500" style="font-size:.85rem;">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="aktif">Aktif</option>
                        <option value="tidak_aktif">Tidak Aktif</option>
                        <option value="sengketa">Sengketa</option>
                        <option value="dipinjamkan">Dipinjamkan</option>
                        <option value="disediakan">Disediakan</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-500" style="font-size:.85rem;">Sumber Perolehan</label>
                    <select name="sumber_perolehan" class="form-select">
                        <option value="">Pilih</option>
                        <option value="beli">Beli</option>
                        <option value="hibah">Hibah</option>
                        <option value="swap">Swap</option>
                        <option value="masuk_karena_diambil_alih">Diambil Alih</option>
                        <option value="hasil_pengembalian">Pengembalian</option>
                        <option value="hasil_pendapatan">Pendapatan</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-500" style="font-size:.85rem;">
                        Latitude / Longitude
                        <span class="text-muted fw-normal" style="font-size:.7rem;">— titik koordinat dapat dicek di <a href="https://bhumi.atrbpn.go.id/peta" target="_blank" rel="noopener" class="bhumi-link">Bumi ATR/BPN <i class="fas fa-external-link-alt"></i></a></span>
                    </label>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input type="number" step="any" name="latitude" class="form-control" id="lat" placeholder="Latitude" value="{{ old('latitude') }}">
                        </div>
                        <div class="col-md-6">
                            <input type="number" step="any" name="longitude" class="form-control" id="lng" placeholder="Longitude" value="{{ old('longitude') }}">
                        </div>
                    </div>
                </div>
                @include('admin.aset._polygon', [
                    'polygonJson' => old('polygon'),
                    'centerLat' => old('latitude'),
                    'centerLng' => old('longitude'),
                ])
                <div class="col-md-12">
                    <label class="form-label fw-500" style="font-size:.85rem;">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2">{{ old('alamat') }}</textarea>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-500" style="font-size:.85rem;">Foto Lokasi</label>
                    <input type="file" name="foto_lokasi" class="form-control" accept="image/*">
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-500" style="font-size:.85rem;">Penggunaan</label>
                    <textarea name="penggunaan" class="form-control" rows="4" placeholder="Contoh:&#10;- Perkantoran&#10;- Balai desa&#10;- Taman kota">{{ old('penggunaan') }}</textarea>
                    <small class="text-muted d-block mt-1" style="font-size:.7rem;"><i class="fas fa-info-circle me-1"></i>Bisa diisi lebih dari satu baris (tekan Enter untuk baris baru).</small>
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-500" style="font-size:.85rem;">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button>
                    <a href="{{ route('admin.aset.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    /* Cascade Kecamatan -> Desa */
    const ALL_DESAS = @json($desas->map(fn($d) => ['id' => $d->id, 'nama' => $d->nama, 'kecamatan_id' => $d->kecamatan_id]));
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
        populateDesa(kecSelect.value, null);

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
