<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasis = Notifikasi::where('user_id', Auth::id())->latest()->paginate(20);
        return response()->json(['success' => true, 'data' => $notifikasis]);
    }

    public function markRead(Request $request)
    {
        $request->validate(['id' => 'required|exists:notifikasis,id']);
        Notifikasi::where('id', $request->id)->where('user_id', Auth::id())->update(['is_read' => true, 'read_at' => now()]);
        return response()->json(['success' => true]);
    }
}
