<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'data_inventaris';

    protected $fillable = [
        'kategori_inventaris_id',
        'lokasi_inventaris_id',
        'kode_barang',
        'nama_barang',
        'jumlah',
        'kondisi',
        'tahun_perolehan',
        'keterangan',
        'foto',
        'name',
        'category',
        'location',
        'quantity',
        'condition',
    ];

    protected $casts = [
        'tahun_perolehan' => 'integer',
        'jumlah' => 'integer',
    ];

    protected $appends = ['name', 'category', 'location', 'quantity', 'condition'];

    protected static function booted(): void
    {
        static::creating(function (Inventory $inventory): void {
            if (! $inventory->kode_barang) {
                $inventory->kode_barang = 'INV-' . Str::upper(Str::random(12));
            }
        });
    }

    public function kategoriInventaris(): BelongsTo
    {
        return $this->belongsTo(KategoriInventaris::class, 'kategori_inventaris_id');
    }

    public function lokasiInventaris(): BelongsTo
    {
        return $this->belongsTo(LokasiInventaris::class, 'lokasi_inventaris_id');
    }

    public function getNameAttribute(): ?string
    {
        return $this->attributes['nama_barang'] ?? null;
    }

    public function setNameAttribute(string $value): void
    {
        $this->attributes['nama_barang'] = $value;
    }

    public function getCategoryAttribute(): ?string
    {
        return $this->kategoriInventaris?->nama_kategori;
    }

    public function setCategoryAttribute(string $value): void
    {
        $category = KategoriInventaris::firstOrCreate(['nama_kategori' => $value]);
        $this->attributes['kategori_inventaris_id'] = $category->id;
    }

    public function getLocationAttribute(): ?string
    {
        return $this->lokasiInventaris?->nama_lokasi;
    }

    public function setLocationAttribute(string $value): void
    {
        $location = LokasiInventaris::firstOrCreate([
            'unit_pendidikan_id' => null,
            'nama_lokasi' => $value,
        ]);
        $this->attributes['lokasi_inventaris_id'] = $location->id;
    }

    public function getQuantityAttribute(): ?int
    {
        return $this->attributes['jumlah'] ?? null;
    }

    public function setQuantityAttribute(int $value): void
    {
        $this->attributes['jumlah'] = $value;
    }

    public function getConditionAttribute(): ?string
    {
        return $this->attributes['kondisi'] ?? null;
    }

    public function setConditionAttribute(string $value): void
    {
        $this->attributes['kondisi'] = $value;
    }
}