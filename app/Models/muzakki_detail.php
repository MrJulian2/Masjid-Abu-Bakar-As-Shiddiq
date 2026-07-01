<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class muzakki_detail extends Model
{
    use HasFactory;

    protected $table = 'muzakki_details';
    protected $fillable = [
        'muzakki_id',
        'nama',
        'jenis_zakat',
        'berat_beras',
        'nominal_uang',
    ];

    /**
     * Relasi ke data muzakki
     */
    public function muzakki()
    {
        return $this->belongsTo(muzakki::class, 'muzakki_id');
    }
}
