<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriInventaris extends Model
{
    protected $table = 'kategori_inventaris';

    protected $fillable = ['nama_kategori', 'keterangan'];

    public function dataInventaris(): HasMany
    {
        return $this->hasMany(Inventory::class, 'kategori_inventaris_id');
    }
}
