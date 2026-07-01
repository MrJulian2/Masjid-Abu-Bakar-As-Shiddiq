<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZakatPeriode extends Model
{
    use HasFactory;
    protected $table = 'zakat_periodes';
    protected $fillable = [
        'tahun',
        'nama',
        'aktif',
    ];

    public function muzakkis()
    {
        return $this->hasMany(muzakki::class, 'periode_id');
    }

    public function zakatopsis()
    {
        return $this->hasMany(zakatopsi::class, 'periode_id');
    }
}
