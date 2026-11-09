<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordChangeLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'changed_by',
        'email',
        'changed_at',
        'action',
    ];

    protected $attributes = [
        'action' => 'password_changed',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
