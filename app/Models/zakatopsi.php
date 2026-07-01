<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class zakatopsi extends Model
{
    use HasFactory;
    protected $table = 'zakatopsis';
    protected $fillable = [
        'jenis',
        'periode_id',
        'nilai_beras',
        'nilai_uang',
        'aktif'
    ];

    public function periode()
    {
        return $this->belongsTo(ZakatPeriode::class, 'periode_id');
    }
}
