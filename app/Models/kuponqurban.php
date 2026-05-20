<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kuponqurban extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_code',
        'status',
        'scanned_by',
        'scanned_at',
        'qurban_id',
    ];

    // relasi dengan qurban
    public function qurban()
    {
        return $this->belongsTo(qurban::class, 'qurban_id');
    }
    public function scannedBy()
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }
}
