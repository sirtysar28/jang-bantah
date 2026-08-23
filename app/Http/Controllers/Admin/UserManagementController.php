<?php

namespace App\Http\Controllers\Admin;

use App\AuditLogService;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Opd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('opd')->latest()->paginate(10);
        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        $opds = Opd::all();
        return view('admin.user.create', compact('opds'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'nullable|string|unique:users|max:50',
            'email' => 'required|email|unique:users|max:255',
            'phone' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
            'opd_id' => 'nullable|exists:opds,id',
            'role' => 'required|in:super_admin,admin_aset,opd,pimpinan',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);
        return redirect()->route('admin.user.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $opds = Opd::all();
        return view('admin.user.edit', compact('user', 'opds'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
            'opd_id' => 'nullable|exists:opds,id',
            'role' => 'required|in:super_admin,admin_aset,opd,pimpinan',
            'is_active' => 'boolean',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }
        $old = $user->toArray();
        $user->update($validated);
        AuditLogService::log('update', 'User', User::class, $user->id, $old, $validated);
        return redirect()->route('admin.user.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus.');
    }

    public function auditLog(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('module', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        $logs = $query->paginate(10)->appends($request->query());
        $modules = AuditLog::select('module')->distinct()->whereNotNull('module')->orderBy('module')->pluck('module');

        return view('admin.audit-log', compact('logs', 'modules'));
    }
}
