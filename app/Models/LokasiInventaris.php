<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LokasiInventaris extends Model
{
    protected $table = 'lokasi_inventaris';

    protected $fillable = ['unit_pendidikan_id', 'nama_lokasi', 'keterangan'];

    public function unitPendidikan(): BelongsTo
    {
        return $this->belongsTo(UnitPendidikan::class, 'unit_pendidikan_id');
    }

    public function dataInventaris(): HasMany
    {
        return $this->hasMany(Inventory::class, 'lokasi_inventaris_id');
    }
}
