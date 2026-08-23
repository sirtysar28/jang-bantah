<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasis = Notifikasi::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        return view('admin.notifikasi.index', compact('notifikasis'));
    }

    public function markAsRead(Notifikasi $notifikasi)
    {
        $notifikasi->markAsRead();
        return response()->json(['status' => 'ok']);
    }

    public function markAllRead()
    {
        Notifikasi::where('user_id', Auth::id())->unread()->update([
            'is_read' => true, 'read_at' => now()
        ]);
        return redirect()->back()->with('success', 'Semua notifikasi sudah dibaca.');
    }
}
