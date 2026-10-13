<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class HakAkses extends Model
{
    protected $table = 'hak_akses';

    protected $fillable = ['nama_hak_akses'];

    public function pengguna(): HasMany
    {
        return $this->hasMany(User::class, 'hak_akses_id');
    }

    public function getRoleKeyAttribute(): string
    {
        return match ($this->nama_hak_akses) {
            'Admin' => 'admin',
            'Kepala Yayasan' => 'kepala_yayasan',
            'Kepala Sekolah' => 'kepala_sekolah',
            default => Str::slug($this->nama_hak_akses, '_'),
        };
    }
}
