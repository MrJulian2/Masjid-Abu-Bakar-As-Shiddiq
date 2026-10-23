<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KuponMustahik extends Model
{
    use HasFactory;

    protected $table = 'kuponmustahiks';

    protected $fillable = [
        'mustahik_id',
        'qr_code',
        'status',
        'scanned_by',
        'scanned_at',
        'note',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function mustahik(): BelongsTo
    {
        return $this->belongsTo(Mustahik::class, 'mustahik_id');
    }

    public function scanner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }
}
