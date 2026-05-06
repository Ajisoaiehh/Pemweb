<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gerbang extends Model
{
    protected $table = 'gerbang';
    protected $primaryKey = 'ID_GERBANG';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'ID_QR',
        'LOKASI',
        'STATUS_PLANG',
    ];

    // Relationship: Gerbang belongs to QR_Code (via ID_QR)
    public function qrCode()
    {
        return $this->belongsTo(QR_Code::class, 'ID_QR', 'ID_QR');
    }

    // Relationship: Gerbang has many QR_Codes (if ID_GERBANG is used in qr_code)
    public function qrCodes()
    {
        return $this->hasMany(QR_Code::class, 'ID_GERBANG', 'ID_GERBANG');
    }
}
