<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitPendidikan extends Model
{
    protected $table = 'unit_pendidikan';

    protected $fillable = ['nama_unit', 'alamat', 'keterangan'];

    public function pengguna(): HasMany
    {
        return $this->hasMany(User::class, 'unit_pendidikan_id');
    }

    public function lokasiInventaris(): HasMany
    {
        return $this->hasMany(LokasiInventaris::class, 'unit_pendidikan_id');
    }
}
