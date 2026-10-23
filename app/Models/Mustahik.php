<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mustahik extends Model
{
    use HasFactory;

    protected $table = 'mustahiks';

    protected $fillable = [
        'nama',
        'alamat',
        'rt',
        'rw',
        'nomor_hp',
        'periode_id',
        'created_by',
        'updated_by',
    ];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(
            ZakatPeriode::class,
            'periode_id'
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    public function kuponmustahik(): HasMany
    {
        return $this->hasMany(
            KuponMustahik::class,
            'mustahik_id'
        );
    }
}
