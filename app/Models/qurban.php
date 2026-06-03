<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class qurban extends Model
{
    use HasFactory;

    protected $table = 'qurbans';
    protected $fillable = ['nama', 'alamat', 'rt', 'rw', 'nomor_hp', 'jumlah_kupon', 'qurban_periode_id', 'created_by', 'updated_by'];

    // relasi dengan kuponqurban
    public function kuponqurban()
    {
        return $this->hasMany(kuponqurban::class, 'qurban_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function periode()
    {
        return $this->belongsTo(QurbanPeriode::class, 'qurban_periode_id');
    }
}
