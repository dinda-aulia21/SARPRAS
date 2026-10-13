<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';

    protected $fillable = [
        'pengguna_id',
        'judul',
        'isi_pengumuman',
        'tanggal_pengumuman',
        'status',
        'title',
        'body',
        'publish_date',
        'type',
    ];

    protected $casts = [
        'tanggal_pengumuman' => 'date',
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
        return $this->attributes['isi_pengumuman'];
    }

    public function setBodyAttribute(string $value): void
    {
        $this->attributes['isi_pengumuman'] = $value;
    }

    public function getPublishDateAttribute(): mixed
    {
        return $this->tanggal_pengumuman;
    }

    public function setPublishDateAttribute(string $value): void
    {
        $this->attributes['tanggal_pengumuman'] = $value;
    }

    public function getStatusAttribute(): string
    {
        return $this->attributes['status'] === 'aktif' ? 'Aktif' : 'Tidak Aktif';
    }

    public function setStatusAttribute(string $value): void
    {
        $this->attributes['status'] = $value === 'Aktif' ? 'aktif' : 'tidak_aktif';
    }

    public function getTypeAttribute(): string
    {
        return $this->attributes['tipe'];
    }

    public function setTypeAttribute(string $value): void
    {
        $this->attributes['tipe'] = $value;
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }
}