<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parkir extends Model
{
    protected $table = 'parkir';
    protected $primaryKey = 'ID_PARKIR';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'PAR_ID_PARKIR',
        'NO_PLAT',
        'ID_PENGGUNA',
        'WAKTU_MASUK',
        'WAKTU_KELUAR',
        'BIAYA',
        'STATUS_PARKIR',
    ];

    // Relationship: Parkir belongs to self (PAR_ID_PARKIR)
    public function parentParkir()
    {
        return $this->belongsTo(Parkir::class, 'PAR_ID_PARKIR', 'ID_PARKIR');
    }

    // Relationship: Parkir has many child Parkir
    public function childParkirs()
    {
        return $this->hasMany(Parkir::class, 'PAR_ID_PARKIR', 'ID_PARKIR');
    }

    // Relationship: Parkir belongs to Kendaraan
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'NO_PLAT', 'NO_PLAT');
    }

    // Relationship: Parkir belongs to Pengguna_Parkir
    public function penggunaParkir()
    {
        return $this->belongsTo(Pengguna_Parkir::class, 'ID_PENGGUNA', 'ID_PENGGUNA');
    }

    // Relationship: Parkir has many Pembayaran
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'ID_PARKIR', 'ID_PARKIR');
    }
}
