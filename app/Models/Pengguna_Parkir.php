<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengguna_Parkir extends Model
{
    protected $table = 'pengguna_parkir';
    protected $primaryKey = 'ID_PENGGUNA';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'NAMA',
        'NO_HP',
        'EMAIL',
        'PASSWORD',
        'SALDO',
    ];

    // Relationship: Pengguna_Parkir has many Kendaraan
    public function kendaraans()
    {
        return $this->hasMany(Kendaraan::class, 'ID_PENGGUNA', 'ID_PENGGUNA');
    }

    // Relationship: Pengguna_Parkir has many Parkir
    public function parkirs()
    {
        return $this->hasMany(Parkir::class, 'ID_PENGGUNA', 'ID_PENGGUNA');
    }
}
