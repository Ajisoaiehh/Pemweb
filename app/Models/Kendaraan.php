<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    protected $table = 'kendaraan';
    protected $primaryKey = 'NO_PLAT';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ID_PENGGUNA',
        'ID_QR',
        'JENIS_KENDARAAN',
        'STATUS_KENDARAAN',
    ];

    // Relationship: Kendaraan belongs to Pengguna_Parkir
    public function penggunaParkir()
    {
        return $this->belongsTo(Pengguna_Parkir::class, 'ID_PENGGUNA', 'ID_PENGGUNA');
    }

    // Relationship: Kendaraan belongs to QR_Code
    public function qrCode()
    {
        return $this->belongsTo(QR_Code::class, 'ID_QR', 'ID_QR');
    }

    // Relationship: Kendaraan has many Parkir
    public function parkirs()
    {
        return $this->hasMany(Parkir::class, 'NO_PLAT', 'NO_PLAT');
    }
}
