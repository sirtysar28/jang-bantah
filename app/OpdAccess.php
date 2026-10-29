<?php

namespace App;

use App\Models\Aset;
use App\Models\User;

/**
 * Pembatasan akses data aset per OPD.
 *
 * User dengan role "opd" hanya boleh melihat dan menginput
 * aset milik OPD-nya sendiri, tidak bisa melihat aset OPD lain.
 * Role super_admin, admin_aset, dan pimpinan tetap melihat semua aset.
 */
class OpdAccess
{
    /**
     * Apakah user dibatasi hanya pada OPD-nya sendiri?
     */
    public static function limited(?User $user): bool
    {
        return $user && $user->role === 'opd';
    }

    /**
     * Batasi query aset ke OPD milik user (untuk halaman daftar/index).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeAset($query, ?User $user)
    {
        if (self::limited($user)) {
            $query->where('opd_id', $user->opd_id);
        }

        return $query;
    }

    /**
     * Batasi query model yang berelasi ke aset (sertifikasi, monitoring,
     * pemanfaatan, dokumen, dll) ke OPD milik user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeAsetRelation($query, ?User $user)
    {
        if (self::limited($user)) {
            $query->whereHas('aset', fn ($q) => $q->where('opd_id', $user->opd_id));
        }

        return $query;
    }

    /**
     * Cegah akses ke aset milik OPD lain (untuk show/edit/update/relasi).
     */
    public static function authorize(?User $user, Aset $aset): void
    {
        if (self::limited($user) && (string) $aset->opd_id !== (string) $user->opd_id) {
            abort(403, 'Aset ini merupakan aset OPD lain. Anda tidak memiliki akses.');
        }
    }

    /**
     * Paksa opd_id data aset ke OPD milik user saat input/edit,
     * sehingga OPD tidak bisa mendaftarkan aset atas nama OPD lain.
     */
    public static function forceOpdId(?User $user, array $data): array
    {
        if (self::limited($user)) {
            $data['opd_id'] = $user->opd_id;
        }

        return $data;
    }
}
