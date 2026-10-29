@extends('layouts.admin')
@section('page-title', 'Detail Aset')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Info Utama -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-map-marked-alt me-2"></i>{{ $aset->nama_aset }}</span>
                <div class="btn-group">
                    <a href="{{ route('admin.aset.edit', $aset) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                    @if(auth()->user()->role === 'super_admin')
                    <form method="POST" action="{{ route('admin.aset.destroy', $aset) }}" style="display:inline;" class="btn-delete-form">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-name="{{ $aset->nama_aset }}"><i class="fas fa-trash me-1"></i>Hapus</button>
                    </form>
                    @endif
                    @if(!$aset->is_validated && auth()->user()->role !== 'opd')
                    <form method="POST" action="{{ route('admin.aset.validate', $aset) }}" style="display:inline;">
                        @csrf @method('POST')
                        <button class="btn btn-sm btn-outline-success"><i class="fas fa-check"></i> Validasi</button>
                    </form>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($aset->foto_lokasi)
                    <div class="col-md-4 mb-3">
                        <img src="{{ Storage::url($aset->foto_lokasi) }}" class="rounded img-fluid" alt="Foto Lokasi">
                    </div>
                    @endif
                    <div class="col-md-{{ $aset->foto_lokasi ? 8 : 12 }}">
                        <table class="table table-sm mb-0">
                            <tr><td class="fw-500" style="width:150px;font-size:.85rem;">Nomor Register</td><td style="font-size:.85rem;">{{ $aset->nomor_register }}</td></tr>
                            <tr><td class="fw-500" style="font-size:.85rem;">Kode Barang</td><td style="font-size:.85rem;">{{ $aset->kode_barang }}</td></tr>
                            <tr><td class="fw-500" style="font-size:.85rem;">Kecamatan</td><td style="font-size:.85rem;">{{ $aset->kecamatan?->nama ?? '-' }}</td></tr>
                            <tr><td class="fw-500" style="font-size:.85rem;">Desa</td><td style="font-size:.85rem;">{{ $aset->desa?->nama ?? '-' }}</td></tr>
                            <tr><td class="fw-500" style="font-size:.85rem;">OPD</td><td style="font-size:.85rem;">{{ $aset->opd?->nama ?? '-' }}</td></tr>
                            <tr><td class="fw-500" style="font-size:.85rem;">Luas</td><td style="font-size:.85rem;">{{ number_format($aset->luas, 2) }} m²</td></tr>
                            <tr><td class="fw-500" style="font-size:.85rem;">Nilai Aset</td><td style="font-size:.85rem;font-weight:600;">Rp {{ number_format($aset->nilai_aset, 0, ',', '.') }}</td></tr>
                            <tr><td class="fw-500" style="font-size:.85rem;">Status</td><td><span class="badge bg-{{ $aset->status === 'aktif' ? 'success' : 'danger' }}">{{ strtoupper(str_replace('_', ' ', $aset->status)) }}</span></td></tr>
                            <tr><td class="fw-500" style="font-size:.85rem;">Tahun Perolehan</td><td style="font-size:.85rem;">{{ $aset->tahun_perolehan ?? '-' }}</td></tr>
                            <tr><td class="fw-500" style="font-size:.85rem;">Sumber Perolehan</td><td style="font-size:.85rem;">{{ strtoupper(str_replace('_', ' ', $aset->sumber_perolehan ?? '-')) }}</td></tr>
                            <tr><td class="fw-500" style="font-size:.85rem;">Koordinat</td><td style="font-size:.85rem;">@if($aset->latitude) {{ $aset->latitude }}, {{ $aset->longitude }} <a href="https://www.google.com/maps?q={{ $aset->latitude }},{{ $aset->longitude }}" target="_blank" class="btn btn-sm btn-outline-primary ms-1"><i class="fas fa-map-marker-alt"></i> Maps</a> @else - @endif</td></tr>
                            <tr><td class="fw-500" style="font-size:.85rem;">Alamat</td><td style="font-size:.85rem;">{{ $aset->alamat ?? '-' }}</td></tr>
                            <tr><td class="fw-500" style="font-size:.85rem;">Keterangan</td><td style="font-size:.85rem;">{{ $aset->keterangan ?? '-' }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Pengamanan & Sertifikasi -->
        <ul class="nav nav-tabs mt-3" id="asetTabs">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#dokumen"><i class="fas fa-file-alt me-1"></i>Dokumen</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#pengamanan-admin"><i class="fas fa-file-shield me-1"></i>Pengamanan Administrasi</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#pengamanan-fisik"><i class="fas fa-shield-halved me-1"></i>Pengamanan Fisik</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#pengamanan-hukum"><i class="fas fa-gavel me-1"></i>Pengamanan Hukum</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#sertifikasi"><i class="fas fa-certificate me-1"></i>Sertifikasi</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#pemanfaatan"><i class="fas fa-handshake me-1"></i>Pemanfaatan</a></li>
        </ul>
        <div class="tab-content mt-3">
            <!-- Dokumen -->
            <div class="tab-pane fade show active" id="dokumen">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.dokumen.store', $aset) }}" enctype="multipart/form-data" class="row g-2 mb-3">
                            @csrf
                            <div class="col-md-3"><select name="jenis_dokumen" class="form-select form-select-sm" required>
                                <option value="">Jenis Dokumen</option>
                                <option value="sertifikat">Sertifikat</option>
                                <option value="ajb">AJB</option>
                                <option value="imb_pbg">IMB/PBG</option>
                                <option value="hibah">Hibah</option>
                                <option value="pelepasan_hak">Pelepasan Hak</option>
                                <option value="sk">SK</option>
                                <option value="berita_acara">Berita Acara</option>
                                <option value="peta_bidang">Peta Bidang</option>
                                <option value="lainnya">Lainnya</option>
                            </select></div>
                            <div class="col-md-3"><input type="file" name="file" class="form-control form-control-sm" required></div>
                            <div class="col-md-2"><input type="text" name="nomor_dokumen" class="form-control form-control-sm" placeholder="No. Dokumen"></div>
                            <div class="col-md-3"><input type="text" name="keterangan" class="form-control form-control-sm" placeholder="Keterangan"></div>
                            <div class="col-md-1"><button type="submit" class="btn btn-sm btn-primary w-100"><i class="fas fa-upload"></i></button></div>
                        </form>
                        <table class="table table-sm">
                            <thead><tr><th>Jenis</th><th>Nama File</th><th>Tanggal</th><th>Aksi</th></tr></thead>
                            <tbody>
                                @foreach($aset->dokumen as $doc)
                                <tr>
                                    <td><span class="badge bg-info">{{ $doc->jenis_dokumen }}</span></td>
                                    <td>{{ $doc->file_name }}</td>
                                    <td>{{ $doc->tanggal_dokumen?->format('d/m/Y') ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.dokumen.download', $doc) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i></a>
                                        <form method="POST" action="{{ route('admin.dokumen.destroy', $doc) }}" style="display:inline;">@csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pengamanan Administrasi -->
            <div class="tab-pane fade" id="pengamanan-admin">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.pengamanan-admin.store', $aset) }}">
                            @csrf
                            <div class="row g-2">
                                @foreach(['sertifikat' => 'Sertifikat', 'imb_pbg' => 'IMB/PBG', 'ajb' => 'AJB', 'hibah' => 'Hibah', 'pelepasan_hak' => 'Pelepasan Hak', 'sk' => 'SK', 'berita_acara' => 'Berita Acara'] as $field => $label)
                                <div class="col-md-3">
                                    <label style="font-size:.8rem;">{{ $label }}</label>
                                    <select name="{{ $field }}" class="form-select form-select-sm">
                                        <option value="tidak_ada" {{ ($aset->pengamananAdministrasi && $aset->pengamananAdministrasi->$field === 'tidak_ada') ? 'selected' : '' }}>Tidak Ada</option>
                                        <option value="ada" {{ ($aset->pengamananAdministrasi && $aset->pengamananAdministrasi->$field === 'ada') ? 'selected' : '' }}>Ada</option>
                                    </select>
                                </div>
                                @endforeach
                                <div class="col-md-4">
                                    <label style="font-size:.8rem;">Dokumen Pendukung</label>
                                    <select name="dokumen_pendukung" class="form-select form-select-sm">
                                        <option value="belum_ada">Belum Ada</option>
                                        <option value="kurang">Kurang</option>
                                        <option value="lengkap">Lengkap</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i>Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Pengamanan Fisik -->
            <div class="tab-pane fade" id="pengamanan-fisik">
                @php $pf = $aset->pengamananFisik; @endphp
                <div class="card">
                    <div class="card-body">
                        @if($pf)
                        <div class="alert alert-info py-2 mb-3" style="font-size:.8rem;">
                            <i class="fas fa-info-circle me-1"></i> Data sudah tersimpan. Terakhir dicek oleh <strong>{{ $pf->checker?->name ?? '-' }}</strong> pada {{ $pf->updated_at->format('d/m/Y H:i') }}.
                            @if($pf->foto_sebelum || $pf->foto_sesudah)
                            <br>
                            @if($pf->foto_sebelum) <img src="{{ Storage::url($pf->foto_sebelum) }}" class="mt-1 me-2" style="height:60px;border-radius:6px;" alt="Sebelum"> @endif
                            @if($pf->foto_sesudah) <img src="{{ Storage::url($pf->foto_sesudah) }}" class="mt-1" style="height:60px;border-radius:6px;" alt="Sesudah"> @endif
                            @endif
                        </div>
                        @endif
                        <form method="POST" action="{{ route('admin.pengamanan-fisik.store', $aset) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-2">
                                @foreach(['patok' => 'Patok', 'pagar' => 'Pagar', 'plang_pemda' => 'Plang Pemda'] as $field => $label)
                                <div class="col-md-3">
                                    <label style="font-size:.8rem;">{{ $label }}</label>
                                    <select name="{{ $field }}" class="form-select form-select-sm">
                                        <option value="tidak_ada" {{ $pf && $pf->$field === 'tidak_ada' ? 'selected' : '' }}>Tidak Ada</option>
                                        <option value="ada" {{ $pf && $pf->$field === 'ada' ? 'selected' : '' }}>Ada</option>
                                        <option value="rusak" {{ $pf && $pf->$field === 'rusak' ? 'selected' : '' }}>Rusak</option>
                                    </select>
                                </div>
                                @endforeach
                                <div class="col-md-3">
                                    <label style="font-size:.8rem;">Kondisi</label>
                                    <select name="kondisi" class="form-select form-select-sm">
                                        <option value="tidak_ditemukan" {{ $pf && $pf->kondisi === 'tidak_ditemukan' ? 'selected' : '' }}>Tidak Ditemukan</option>
                                        <option value="baik" {{ $pf && $pf->kondisi === 'baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="rusak_ringan" {{ $pf && $pf->kondisi === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                        <option value="rusak_berat" {{ $pf && $pf->kondisi === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label style="font-size:.8rem;">Foto Sebelum</label>
                                    <input type="file" name="foto_sebelum" class="form-control form-control-sm" accept="image/*">
                                    @if($pf && $pf->foto_sebelum)<small class="text-muted" style="font-size:.7rem;">Sudah ada file</small>@endif
                                </div>
                                <div class="col-md-3">
                                    <label style="font-size:.8rem;">Foto Sesudah</label>
                                    <input type="file" name="foto_sesudah" class="form-control form-control-sm" accept="image/*">
                                    @if($pf && $pf->foto_sesudah)<small class="text-muted" style="font-size:.7rem;">Sudah ada file</small>@endif
                                </div>
                                <div class="col-md-6">
                                    <label style="font-size:.8rem;">Tanggal Monitoring</label>
                                    <input type="date" name="tanggal_monitoring" class="form-control form-control-sm" value="{{ $pf?->tanggal_monitoring?->format('Y-m-d') ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label style="font-size:.8rem;">Keterangan</label>
                                    <input type="text" name="keterangan" class="form-control form-control-sm" value="{{ $pf?->keterangan ?? '' }}">
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i>Simpan Pengamanan Fisik</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Pengamanan Hukum -->
            <div class="tab-pane fade" id="pengamanan-hukum">
                @php $ph = $aset->pengamananHukum; @endphp
                <div class="card">
                    <div class="card-body">
                        @if($ph)
                        <div class="alert alert-{{ $ph->status_sengketa === 'tidak_sengketa' ? 'success' : 'danger' }} py-2 mb-3" style="font-size:.8rem;">
                            @if($ph->status_sengketa !== 'tidak_sengketa')
                            <i class="fas fa-exclamation-triangle me-1"></i> <strong>Status: {{ strtoupper(str_replace('_', ' ', $ph->status_sengketa)) }}</strong>
                            @if($ph->nomor_perkara) — Perkara No. {{ $ph->nomor_perkara }} @endif
                            @else
                            <i class="fas fa-check-circle me-1"></i> Tidak ada sengketa. Terakhir dicek oleh <strong>{{ $ph->checker?->name ?? '-' }}</strong> pada {{ $ph->updated_at->format('d/m/Y H:i') }}.
                            @endif
                        </div>
                        @endif
                        <form method="POST" action="{{ route('admin.pengamanan-hukum.store', $aset) }}">
                            @csrf
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label style="font-size:.8rem;">Status Sengketa</label>
                                    <select name="status_sengketa" id="statusSengketa" class="form-select form-select-sm" onchange="toggleSengketaFields()">
                                        <option value="tidak_sengketa" {{ $ph && $ph->status_sengketa === 'tidak_sengketa' ? 'selected' : '' }}>Tidak Sengketa</option>
                                        <option value="sengketa" {{ $ph && $ph->status_sengketa === 'sengketa' ? 'selected' : '' }}>Sengketa</option>
                                        <option value="perkara" {{ $ph && $ph->status_sengketa === 'perkara' ? 'selected' : '' }}>Perkara</option>
                                        <option value="gugatan" {{ $ph && $ph->status_sengketa === 'gugatan' ? 'selected' : '' }}>Gugatan</option>
                                    </select>
                                </div>
                                <div class="col-md-3 sengketa-field">
                                    <label style="font-size:.8rem;">No. Perkara</label>
                                    <input type="text" name="nomor_perkara" class="form-control form-control-sm" placeholder="No. Perkara" value="{{ $ph?->nomor_perkara ?? '' }}">
                                </div>
                                <div class="col-md-3 sengketa-field">
                                    <label style="font-size:.8rem;">Pihak Lawan</label>
                                    <input type="text" name="pihak_lawan" class="form-control form-control-sm" placeholder="Pihak Lawan" value="{{ $ph?->pihak_lawan ?? '' }}">
                                </div>
                                <div class="col-md-3 sengketa-field">
                                    <label style="font-size:.8rem;">Status Pengadilan</label>
                                    <select name="status_pengadilan" class="form-select form-select-sm">
                                        <option value="">-- Pilih --</option>
                                        <option value="belum" {{ $ph && $ph->status_pengadilan === 'belum' ? 'selected' : '' }}>Belum</option>
                                        <option value="berjalan" {{ $ph && $ph->status_pengadilan === 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                                        <option value="selesai" {{ $ph && $ph->status_pengadilan === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="menang" {{ $ph && $ph->status_pengadilan === 'menang' ? 'selected' : '' }}>Menang</option>
                                        <option value="kalah" {{ $ph && $ph->status_pengadilan === 'kalah' ? 'selected' : '' }}>Kalah</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label style="font-size:.8rem;">Pendampingan Kejaksaan</label>
                                    <select name="pendampingan_kejaksaan" class="form-select form-select-sm">
                                        <option value="belum" {{ $ph && $ph->pendampingan_kejaksaan === 'belum' ? 'selected' : '' }}>Belum</option>
                                        <option value="berjalan" {{ $ph && $ph->pendampingan_kejaksaan === 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                                        <option value="selesai" {{ $ph && $ph->pendampingan_kejaksaan === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </div>
                                <div class="col-md-3 sengketa-field">
                                    <label style="font-size:.8rem;">Tanggal Sengketa</label>
                                    <input type="date" name="tanggal_sengketa" class="form-control form-control-sm" value="{{ $ph?->tanggal_sengketa?->format('Y-m-d') ?? '' }}">
                                </div>
                                <div class="col-md-12">
                                    <label style="font-size:.8rem;">Riwayat Penyelesaian</label>
                                    <textarea name="riwayat_penyelesaian" class="form-control form-control-sm" rows="2" placeholder="Riwayat Penyelesaian">{{ $ph?->riwayat_penyelesaian ?? '' }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label style="font-size:.8rem;">Keterangan</label>
                                    <input type="text" name="keterangan" class="form-control form-control-sm" value="{{ $ph?->keterangan ?? '' }}">
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i>Simpan Pengamanan Hukum</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sertifikasi -->
            <div class="tab-pane fade" id="sertifikasi">
                @if($aset->sertifikasi)
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.sertifikasi.update', $aset->sertifikasi) }}">
                            @csrf @method('PUT')
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label style="font-size:.8rem;">Status</label>
                                    <select name="status" class="form-select form-select-sm">
                                        @foreach(\App\Models\Sertifikasi::statusOptions() as $val => $label)
                                        <option value="{{ $val }}" {{ $aset->sertifikasi->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3"><input type="text" name="nomor_sertifikat" class="form-control form-control-sm" value="{{ $aset->sertifikasi->nomor_sertifikat }}" placeholder="No. Sertifikat"></div>
                                <div class="col-md-2"><input type="date" name="tanggal_pengajuan" class="form-control form-control-sm" value="{{ $aset->sertifikasi->tanggal_pengajuan?->format('Y-m-d') }}"></div>
                                <div class="col-md-2"><input type="date" name="tanggal_terbit" class="form-control form-control-sm" value="{{ $aset->sertifikasi->tanggal_terbit?->format('Y-m-d') }}"></div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-save"></i></button>
                                </div>
                            </div>
                        </form>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-1" style="font-size:.8rem;">
                                <span>Progress</span>
                                <span>{{ number_format($aset->sertifikasi->progress, 1) }}%</span>
                            </div>
                            <div class="progress progress-jb">
                                <div class="progress-bar bg-success" style="width:{{ $aset->sertifikasi->progress }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="card">
                    <div class="card-body text-center py-4">
                        <p>Belum ada data sertifikasi.</p>
                        <a href="{{ route('admin.sertifikasi.create', $aset) }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Tambah Sertifikasi</a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Pemanfaatan -->
            <div class="tab-pane fade" id="pemanfaatan">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-handshake me-1"></i> {{ $aset->pemanfaatan ? 'Edit Data Pemanfaatan' : 'Tambah Data Pemanfaatan' }}</span>
                        @if($aset->pemanfaatan)
                        <span class="badge bg-{{ ['aktif' => 'success', 'tidak_aktif' => 'secondary', 'selesai' => 'info'][$aset->pemanfaatan->status] ?? 'secondary' }}">{{ ucfirst($aset->pemanfaatan->status) }}</span>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($aset->pemanfaatan)
                            <form method="POST" action="{{ route('admin.pemanfaatan.update', $aset->pemanfaatan) }}" enctype="multipart/form-data">
                                @csrf
                                @include('admin.pemanfaatan._form', ['p' => $aset->pemanfaatan])
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Perbarui Data Pemanfaatan</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.pemanfaatan.store', $aset) }}" enctype="multipart/form-data">
                                @csrf
                                @include('admin.pemanfaatan._form', ['p' => null])
                                <button type="submit" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Simpan Data Pemanfaatan</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-map me-2"></i>Lokasi</div>
            <div class="card-body">
                @if($aset->latitude && $aset->longitude)
                <div id="mapDetail" style="height:250px;border-radius:8px;"></div>
                <p class="text-center mt-2 mb-0" style="font-size:.8rem;">
                    <a href="https://www.google.com/maps?q={{ $aset->latitude }},{{ $aset->longitude }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-external-link-alt me-1"></i>Buka di Google Maps
                    </a>
                </p>
                @else
                <div class="text-center text-muted py-4"><i class="fas fa-map-marker-alt" style="font-size:3rem;opacity:.2;"></i><p>Koordinat belum tersedia</p></div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header"><i class="fas fa-info-circle me-2"></i>Info</div>
            <div class="card-body">
                <table class="table table-sm mb-0" style="font-size:.85rem;">
                    <tr><td>Validasi</td><td><span class="badge bg-{{ $aset->is_validated ? 'success' : 'warning' }}">{{ $aset->is_validated ? 'Sudah' : 'Belum' }}</span></td></tr>
                    <tr><td>Digital</td><td><span class="badge bg-{{ $aset->is_digital ? 'success' : 'secondary' }}">{{ $aset->is_digital ? 'Ya' : 'Belum' }}</span></td></tr>
                    <tr><td>Dibuat</td><td>{{ $aset->created_at->format('d/m/Y H:i') }}</td></tr>
                    <tr><td>Diubah</td><td>{{ $aset->updated_at->format('d/m/Y H:i') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Toggle sengketa fields show/hide
function toggleSengketaFields() {
    var val = document.getElementById('statusSengketa').value;
    var fields = document.querySelectorAll('.sengketa-field');
    fields.forEach(function(el) {
        el.style.display = (val === 'tidak_sengketa') ? 'none' : '';
    });
}
toggleSengketaFields();

// Hapus aset
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
            Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
            $form.submit();
        }
    });
});

@if($aset->latitude && $aset->longitude)
var map = L.map('mapDetail').setView([{{ $aset->latitude }}, {{ $aset->longitude }}], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OSM'
}).addTo(map);
L.marker([{{ $aset->latitude }}, {{ $aset->longitude }}]).addTo(map).bindPopup('<b>{{ $aset->nama_aset }}</b>').openPopup();
@endif
</script>
@endpush
