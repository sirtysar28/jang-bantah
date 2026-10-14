@php($p = $p ?? null)
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label fw-600">Kategori Pemanfaatan <span class="text-danger">*</span></label>
        <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
            <option value="disewakan" {{ old('kategori', $p?->kategori) === 'disewakan' ? 'selected' : '' }}>Disewakan</option>
            <option value="kerjasama" {{ old('kategori', $p?->kategori) === 'kerjasama' ? 'selected' : '' }}>Kerjasama</option>
            <option value="pinjam_pakai" {{ old('kategori', $p?->kategori) === 'pinjam_pakai' ? 'selected' : '' }}>Pinjam Pakai</option>
            <option value="bangun_guna_serah" {{ old('kategori', $p?->kategori) === 'bangun_guna_serah' ? 'selected' : '' }}>Bangun Guna Serah</option>
            <option value="idle_asset" {{ old('kategori', $p?->kategori ?? 'idle_asset') === 'idle_asset' ? 'selected' : '' }}>Idle Asset</option>
        </select>
        @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label fw-600">Status</label>
        <select name="status" class="form-select">
            <option value="aktif" {{ old('status', $p?->status ?? 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="tidak_aktif" {{ old('status', $p?->status) === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
            <option value="selesai" {{ old('status', $p?->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label fw-600">Nama Pihak</label>
        <input type="text" name="nama_pihak" class="form-control" value="{{ old('nama_pihak', $p?->nama_pihak) }}" placeholder="Nama penyewa/mitra">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label fw-600">Kontak Pihak</label>
        <input type="text" name="kontak_pihak" class="form-control" value="{{ old('kontak_pihak', $p?->kontak_pihak) }}" placeholder="08xx / email">
    </div>
    <div class="col-md-8 mb-3">
        <label class="form-label fw-600">Alamat Pihak</label>
        <input type="text" name="alamat_pihak" class="form-control" value="{{ old('alamat_pihak', $p?->alamat_pihak) }}" placeholder="Alamat pihak pemanfaat">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label fw-600">Potensi PAD (Rp)</label>
        <input type="number" step="0.01" min="0" name="nilai_potensi_pad" class="form-control" value="{{ old('nilai_potensi_pad', $p?->nilai_potensi_pad ?? 0) }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label fw-600">Realisasi (Rp)</label>
        <input type="number" step="0.01" min="0" name="nilai_realisasi" class="form-control" value="{{ old('nilai_realisasi', $p?->nilai_realisasi ?? 0) }}">
    </div>
    <div class="col-md-2 mb-3">
        <label class="form-label fw-600">Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', $p?->tanggal_mulai?->format('Y-m-d')) }}">
    </div>
    <div class="col-md-2 mb-3">
        <label class="form-label fw-600">Tanggal Selesai</label>
        <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', $p?->tanggal_selesai?->format('Y-m-d')) }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label fw-600">Dokumen Perjanjian (PDF, maks 5MB)</label>
        <input type="file" name="dokumen_perjanjian" class="form-control @error('dokumen_perjanjian') is-invalid @enderror" accept=".pdf">
        @error('dokumen_perjanjian')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @if($p?->dokumen_perjanjian)
            <div class="form-text" style="font-size:.8rem;">
                File saat ini: <a href="{{ Storage::url($p->dokumen_perjanjian) }}" target="_blank"><i class="fas fa-file-pdf me-1"></i>Lihat Dokumen</a> — upload baru untuk mengganti.
            </div>
        @endif
    </div>
    <div class="col-12 mb-3">
        <label class="form-label fw-600">Keterangan</label>
        <textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan tambahan">{{ old('keterangan', $p?->keterangan) }}</textarea>
    </div>
</div>
