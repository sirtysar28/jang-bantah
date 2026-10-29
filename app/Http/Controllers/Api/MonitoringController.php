<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MonitoringAset;
use App\OpdAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    public function index()
    {
        $query = MonitoringAset::with('aset');

        // OPD hanya melihat monitoring aset milik OPD-nya sendiri
        OpdAccess::scopeAsetRelation($query, Auth::user());

        return response()->json(['success' => true, 'data' => $query->paginate(20)]);
    }
}
