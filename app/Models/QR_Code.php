<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QR_Code extends Model
{
    protected $table = 'qr_code';
    protected $primaryKey = 'ID_QR';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'NO_PLAT',
        'ID_GERBANG',
        'TIPE',
        'WAKTU_DIBUAT',
        'VALID_UNTIL',
    ];

    // Relationship: QR_Code belongs to Kendaraan
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'NO_PLAT', 'NO_PLAT');
    }

    // Relationship: QR_Code belongs to Gerbang
    public function gerbang()
    {
        return $this->belongsTo(Gerbang::class, 'ID_GERBANG', 'ID_GERBANG');
    }

    // Relationship: QR_Code has one Gerbang (via ID_QR in gerbang)
    public function gerbangViaQr()
    {
        return $this->hasOne(Gerbang::class, 'ID_QR', 'ID_QR');
    }

    // Relationship: QR_Code has many Kendaraan (if used)
    public function kendaraans()
    {
        return $this->hasMany(Kendaraan::class, 'ID_QR', 'ID_QR');
    }
}
