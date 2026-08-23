@extends('layouts.admin')
@section('page-title', 'Notifikasi')
@section('content')
<div class="card"><div class="card-header d-flex justify-content-between"><span><i class="fas fa-bell me-2"></i>Notifikasi</span>
    <form method="POST" action="{{ route('admin.notifikasi.read-all') }}" style="display:inline;">@csrf @method('POST')
    <button class="btn btn-sm btn-outline-primary"><i class="fas fa-check-double me-1"></i>Tandai Semua Dibaca</button></form></div>
    <div class="card-body">
        @if($notifikasis->count() > 0)
        <div class="list-group">
            @foreach($notifikasis as $n)
            <div class="list-group-item list-group-item-action {{ $n->is_read ? '' : 'bg-light' }}">
                <div class="d-flex align-items-start gap-3">
                    <i class="fas fa-bell mt-1 text-{{ $n->tipe === 'sertifikat' ? 'primary' : ($n->tipe === 'sengketa' ? 'danger' : 'info') }}"></i>
                    <div>
                        <div class="fw-500" style="font-size:.9rem;">{{ $n->judul }}</div>
                        <div style="font-size:.85rem;color:#555;">{{ $n->pesan }}</div>
                        <small class="text-muted">{{ $n->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        {{ $notifikasis->links() }}
        @else
        <p class="text-center text-muted py-4">Tidak ada notifikasi.</p>
        @endif
    </div>
</div>
@endsection
