<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'nip',
        'email',
        'phone',
        'jabatan',
        'opd_id',
        'role',
        'is_active',
        'avatar',
        'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function opd()
    {
        return $this->belongsTo(Opd::class, 'opd_id');
    }

    public function asetsCreated()
    {
        return $this->hasMany(Aset::class, 'created_by');
    }

    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notifikasi::class)->where('is_read', false);
    }

    public function hasRoleCustom($role): bool
    {
        return $this->role === $role;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdminAset(): bool
    {
        return $this->role === 'admin_aset';
    }

    public function isOpd(): bool
    {
        return $this->role === 'opd';
    }

    public function isPimpinan(): bool
    {
        return $this->role === 'pimpinan';
    }
}
