<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanPeriode extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun',
        'nama',
        'aktif',
    ];

    /**
     * Relasi ke data penerima qurban
     */
    public function qurbans()
    {
        return $this->hasMany(Qurban::class, 'qurban_periode_id');
    }

    /**
     * Scope periode aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }
}