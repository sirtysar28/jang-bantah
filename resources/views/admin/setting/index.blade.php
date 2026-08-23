@extends('layouts.admin')
@section('page-title', 'Pengaturan Sistem')
@section('content')
<div class="row">
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
                    <tr><td>Framework</td><td>Laravel {{ app()->version() }}</td></tr>
                    <tr><td>PHP</td><td>{{ PHP_VERSION }}</td></tr>
                    <tr><td>Database</td><td>{{ config('database.default') }}</td></tr>
                    <tr><td>Server</td><td>{{ $_SERVER['SERVER_SOFTWARE'] ?? 'CLI' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
