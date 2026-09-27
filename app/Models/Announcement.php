<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'publish_date',
        'status',
        'type',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function getTitleAttribute(): string
    {
        return $this->attributes['judul'];
    }

    public function setTitleAttribute(string $value): void
    {
        $this->attributes['judul'] = $value;
    }

    public function getBodyAttribute(): string
    {
        return $this->attributes['konten'];
    }

    public function setBodyAttribute(string $value): void
    {
        $this->attributes['konten'] = $value;
    }

    public function getPublishDateAttribute(): mixed
    {
        return $this->tanggal_mulai;
    }

    public function setPublishDateAttribute(string $value): void
    {
        $this->attributes['tanggal_mulai'] = $value;
    }

    public function getStatusAttribute(): string
    {
        return $this->attributes['status'] === 'aktif' ? 'Aktif' : 'Tidak Aktif';
    }

    public function setStatusAttribute(string $value): void
    {
        $this->attributes['status'] = $value === 'Aktif' ? 'aktif' : 'arsip';
    }

    public function getTypeAttribute(): string
    {
        return $this->attributes['tipe'];
    }

    public function setTypeAttribute(string $value): void
    {
        $this->attributes['tipe'] = $value;
    }
}