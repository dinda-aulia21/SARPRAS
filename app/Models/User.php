<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'pengguna';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hak_akses_id',
        'unit_pendidikan_id',
        'nama_lengkap',
        'username',
        'name',
        'email',
        'password',
        'role',
        'foto',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function hakAkses(): BelongsTo
    {
        return $this->belongsTo(HakAkses::class, 'hak_akses_id');
    }

    public function unitPendidikan(): BelongsTo
    {
        return $this->belongsTo(UnitPendidikan::class, 'unit_pendidikan_id');
    }

    public function pengumuman(): HasMany
    {
        return $this->hasMany(Announcement::class, 'pengguna_id');
    }

    public function getNameAttribute(): ?string
    {
        return $this->attributes['nama_lengkap'] ?? null;
    }

    public function setNameAttribute(string $value): void
    {
        $this->attributes['nama_lengkap'] = $value;
    }

    public function getRoleAttribute(): ?string
    {
        return $this->hakAkses?->role_key;
    }

    public function setRoleAttribute(string $value): void
    {
        $roleName = match ($value) {
            'admin' => 'Admin',
            'kepala_yayasan' => 'Kepala Yayasan',
            'kepala_sekolah' => 'Kepala Sekolah',
            default => throw new \InvalidArgumentException("Hak akses tidak dikenal: {$value}"),
        };

        $this->attributes['hak_akses_id'] = HakAkses::where('nama_hak_akses', $roleName)->value('id')
            ?? throw new \RuntimeException("Hak akses {$roleName} belum tersedia.");
    }

}
