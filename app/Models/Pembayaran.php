<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'ID_PEMBAYARAN';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'ID_PARKIR',
        'METODE',
        'STATUS',
        'JUMLAH',
        'WAKTU_BAYAR',
    ];

    // Relationship: Pembayaran belongs to Parkir
    public function parkir()
    {
        return $this->belongsTo(Parkir::class, 'ID_PARKIR', 'ID_PARKIR');
    }
}
