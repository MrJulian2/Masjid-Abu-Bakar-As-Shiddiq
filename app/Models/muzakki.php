<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class muzakki extends Model
{
    use HasFactory;
     protected $table = 'muzakkis';

    protected $fillable = [

        'nama_kk',
        'alamat',
        'no_hp',
        'periode_id',
        'kategori',
        'rt',
        'rw',
        'total_jiwa',
        'total_beras',
        'total_uang',
    ];


        /**
        * Relasi ke data periode zakat
        */
    public function periode()
    {
        return $this->belongsTo(ZakatPeriode::class, 'periode_id');
    }
    
     /**
     * Relasi ke data detail muzakki
     */
    public function details()
    {
        return $this->hasMany(muzakki_detail::class, 'muzakki_id');
    }
}
