<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Artisan;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.setting.index');
    }

    public function backupDatabase()
    {
        try {
            Artisan::call('db:backup');
            return redirect()->back()->with('success', 'Backup database berhasil.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Backup gagal: ' . $e->getMessage());
        }
    }
}
