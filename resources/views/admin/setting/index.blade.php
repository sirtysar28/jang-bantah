@extends('layouts.admin')
@section('page-title', 'Pengaturan Sistem')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-envelope me-2"></i> Pengaturan SMTP (Notifikasi Email)
            </div>
            <div class="card-body">
                <p style="font-size:.9rem;">
                    Konfigurasi server email untuk mengirim notifikasi ke email admin, misalnya saat ada pengajuan/permohonan data aset baru dari OPD yang menunggu validasi.
                </p>
                <form method="POST" action="{{ route('admin.setting.smtp') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-600">Aktifkan Notifikasi Email</label>
                            <div class="form-check form-switch mt-1">
                                <input class="form-check-input" type="checkbox" role="switch" id="smtpEnabled" name="smtp_enabled" value="1" {{ $smtp['smtp_enabled'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="smtpEnabled" style="font-size:.85rem;">Aktifkan pengiriman email via SMTP</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-600">SMTP Host <span class="text-danger">*</span></label>
                            <input type="text" name="smtp_host" class="form-control" value="{{ old('smtp_host', $smtp['smtp_host']) }}" placeholder="mail.domain.com" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label fw-600">Port <span class="text-danger">*</span></label>
                            <input type="number" name="smtp_port" class="form-control" value="{{ old('smtp_port', $smtp['smtp_port']) }}" placeholder="587" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-600">Enkripsi <span class="text-danger">*</span></label>
                            <select name="smtp_encryption" class="form-select" required>
                                <option value="tls" {{ old('smtp_encryption', $smtp['smtp_encryption']) === 'tls' ? 'selected' : '' }}>TLS (587)</option>
                                <option value="ssl" {{ old('smtp_encryption', $smtp['smtp_encryption']) === 'ssl' ? 'selected' : '' }}>SSL (465)</option>
                                <option value="none" {{ old('smtp_encryption', $smtp['smtp_encryption']) === 'none' ? 'selected' : '' }}>Tanpa Enkripsi (25)</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-600">Username (Email)</label>
                            <input type="text" name="smtp_username" class="form-control" value="{{ old('smtp_username', $smtp['smtp_username']) }}" placeholder="email@domain.com">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-600">Password SMTP</label>
                            <input type="password" name="smtp_password" class="form-control" value="" placeholder="{{ $smtp['smtp_username'] ? '•••••••• (biarkan kosong jika tidak diubah)' : 'Password email' }}" autocomplete="new-password">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-600">Nama Pengirim <span class="text-danger">*</span></label>
                            <input type="text" name="smtp_from_name" class="form-control" value="{{ old('smtp_from_name', $smtp['smtp_from_name']) }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-600">Alamat Pengirim <span class="text-danger">*</span></label>
                            <input type="email" name="smtp_from_address" class="form-control" value="{{ old('smtp_from_address', $smtp['smtp_from_address']) }}" placeholder="noreply@domain.go.id" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Email Tujuan Notifikasi Admin</label>
                        <input type="text" name="notification_email" class="form-control" value="{{ old('notification_email', $smtp['notification_email']) }}" placeholder="admin1@domain.go.id, admin2@domain.go.id">
                        <div class="form-text" style="font-size:.8rem;">Pisahkan beberapa email dengan koma. Kosongkan untuk otomatis memakai email semua user Super Admin &amp; Admin Aset yang aktif.</div>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan Pengaturan SMTP</button>
                </form>

                <hr class="my-4">
                <h6 class="fw-600 mb-3"><i class="fas fa-paper-plane me-1 text-primary"></i> Uji Koneksi Email</h6>
                <form method="POST" action="{{ route('admin.setting.test-mail') }}" class="d-flex gap-2 align-items-end" style="max-width:520px;">
                    @csrf
                    <div class="flex-grow-1">
                        <label class="form-label fw-600">Kirim Email Percobaan Ke</label>
                        <input type="email" name="test_email" class="form-control" placeholder="Kosongkan = email Anda ({{ auth()->user()->email }})">
                    </div>
                    <button type="submit" class="btn btn-success"><i class="fas fa-send me-1"></i>Kirim Test</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-database me-2"></i>Backup Database</div>
            <div class="card-body">
                <p style="font-size:.9rem;">Lakukan backup database secara berkala untuk menjaga keamanan data.</p>
                <form method="POST" action="{{ route('admin.setting.backup') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger"><i class="fas fa-download me-1"></i>Backup Sekarang</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-info-circle me-2"></i>Informasi Sistem</div>
            <div class="card-body">
                <table class="table table-sm mb-0" style="font-size:.85rem;">
                    <tr><td>Aplikasi</td><td class="fw-500">JANG BANTAH v1.0</td></tr>
                    <tr><td>Framework</td><td class="fw-500">Laravel {{ app()->version() }}</td></tr>
                    <tr><td>PHP</td><td class="fw-500">{{ PHP_VERSION }}</td></tr>
                    <tr><td>Database</td><td class="fw-500">{{ config('database.default') }}</td></tr>
                    <tr><td>Server</td><td class="fw-500">{{ $_SERVER['SERVER_SOFTWARE'] ?? 'CLI' }}</td></tr>
                    <tr>
                        <td>Status Notifikasi Email</td>
                        <td class="fw-500">
                            @if($smtp['smtp_enabled'])
                                <span class="badge bg-success"><i class="fas fa-check me-1"></i>Aktif</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-xmark me-1"></i>Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
