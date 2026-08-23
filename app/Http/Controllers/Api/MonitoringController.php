<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MonitoringAset;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        return response()->json(['success' => true, 'data' => MonitoringAset::with('aset')->paginate(20)]);
    }
}
