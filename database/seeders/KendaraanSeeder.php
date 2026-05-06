<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KendaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Kendaraan::create([
            'NO_PLAT' => 'B 1234 XYZ',
            'ID_PENGGUNA' => 1,
            'JENIS_KENDARAAN' => 'Mobil',
            'STATUS_KENDARAAN' => 'Aktif',
        ]);

        \App\Models\Kendaraan::create([
            'NO_PLAT' => 'B 5678 ABC',
            'ID_PENGGUNA' => 1,
            'JENIS_KENDARAAN' => 'Motor',
            'STATUS_KENDARAAN' => 'Aktif',
        ]);

        \App\Models\Kendaraan::create([
            'NO_PLAT' => 'B 9876 DEF',
            'ID_PENGGUNA' => 2,
            'JENIS_KENDARAAN' => 'Mobil',
            'STATUS_KENDARAAN' => 'Aktif',
        ]);

        \App\Models\Kendaraan::create([
            'NO_PLAT' => 'B 5432 GHI',
            'ID_PENGGUNA' => 3,
            'JENIS_KENDARAAN' => 'Truk',
            'STATUS_KENDARAAN' => 'Aktif',
        ]);
    }
}
