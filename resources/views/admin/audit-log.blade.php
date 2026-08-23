@extends('layouts.admin')
@section('page-title', 'Audit Log')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="fas fa-history me-2"></i>Audit Log</span>
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari user/modul/aksi..." value="{{ request('search') }}" style="width:200px;">
            <select name="module" class="form-select form-select-sm" style="width:180px;" onchange="this.form.submit()">
                <option value="">Semua Modul</option>
                @foreach($modules as $m)
                <option value="{{ $m }}" {{ request('module') === $m ? 'selected' : '' }}>{{ $m }}</option>
                @endforeach
            </select>
            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-search"></i></button>
            <a href="{{ route('admin.audit-log') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-undo"></i></a>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="auditTable">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Modul</th>
                        <th>Aksi</th>
                        <th>Detail</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>
                            <div class="fw-500">{{ $log->created_at->format('d M Y, H:i:s') }}</div>
                            <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            @if($log->user)
                                <div class="fw-500">{{ $log->user->name }}</div>
                                <small class="text-muted">{{ $log->user->email }}</small>
                            @else
                                <span class="text-muted">System</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $log->module ?? '-' }}</span>
                        </td>
                        <td>
                            @php
                                $actionColor = match($log->action) {
                                    'create' => 'success',
                                    'update' => 'warning',
                                    'delete' => 'danger',
                                    'login' => 'info',
                                    'logout' => 'secondary',
                                    default => 'primary',
                                };
                                $actionIcon = match($log->action) {
                                    'create' => 'plus',
                                    'update' => 'edit',
                                    'delete' => 'trash',
                                    'login' => 'sign-in-alt',
                                    'logout' => 'sign-out-alt',
                                    default => 'circle',
                                };
                            @endphp
                            <span class="badge bg-{{ $actionColor }}">
                                <i class="fas fa-{{ $actionIcon }} me-1"></i>
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td>
                            @if($log->old_values || $log->new_values)
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $log->id }}">
                                <i class="fas fa-eye"></i> Lihat
                            </button>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td><code>{{ $log->ip_address ?? '-' }}</code></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <small class="text-muted">Total: {{ $logs->total() }} log</small>
            {{ $logs->links() }}
        </div>
    </div>
</div>

{{-- Modal Detail --}}
@foreach($logs as $log)
@if($log->old_values || $log->new_values)
<div class="modal fade" id="detailModal{{ $log->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">
                    <i class="fas fa-exchange-alt me-2"></i>
                    Detail Perubahan #{{ $log->id }}
                    <span class="badge bg-{{ $actionColor ?? 'primary' }}">{{ ucfirst($log->action) }} {{ $log->module }}</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @if($log->old_values && count($log->old_values) > 0)
                    <div class="col-md-6">
                        <h6 class="text-danger mb-2"><i class="fas fa-minus-circle me-1"></i> Nilai Lama</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <tbody>
                                    @foreach($log->old_values as $key => $value)
                                    <tr>
                                        <td class="fw-500 bg-light" style="width:40%;">{{ $key }}</td>
                                        <td>{{ is_array($value) ? json_encode($value) : ($value ?? '-') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                    @if($log->new_values && count($log->new_values) > 0)
                    <div class="col-md-6">
                        <h6 class="text-success mb-2"><i class="fas fa-plus-circle me-1"></i> Nilai Baru</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <tbody>
                                    @foreach($log->new_values as $key => $value)
                                    <tr>
                                        <td class="fw-500 bg-light" style="width:40%;">{{ $key }}</td>
                                        <td>{{ is_array($value) ? json_encode($value) : ($value ?? '-') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
                @if($log->user_agent)
                <hr>
                <small class="text-muted">
                    <i class="fas fa-desktop me-1"></i> User Agent: {{ $log->user_agent }}
                </small>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endforeach

@push('scripts')
<script>
/* Audit log sekarang menggunakan pagination server-side (Laravel). */
</script>
@endpush
@endsection
