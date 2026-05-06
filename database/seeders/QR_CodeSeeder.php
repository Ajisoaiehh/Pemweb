<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QR_CodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\QR_Code::create([
            'ID_QR' => 1,
            'NO_PLAT' => 'B 1234 XYZ',
            'TIPE' => 'Masuk',
            'WAKTU_DIBUAT' => now(),
            'VALID_UNTIL' => now()->addHours(24),
        ]);

        \App\Models\QR_Code::create([
            'ID_QR' => 2,
            'NO_PLAT' => 'B 5678 ABC',
            'TIPE' => 'Masuk',
            'WAKTU_DIBUAT' => now(),
            'VALID_UNTIL' => now()->addHours(24),
        ]);

        \App\Models\QR_Code::create([
            'ID_QR' => 3,
            'NO_PLAT' => 'B 9876 DEF',
            'TIPE' => 'Keluar',
            'WAKTU_DIBUAT' => now()->subHours(1),
            'VALID_UNTIL' => now()->addHours(23),
        ]);

        \App\Models\QR_Code::create([
            'ID_QR' => 4,
            'NO_PLAT' => 'B 5432 GHI',
            'TIPE' => 'Masuk',
            'WAKTU_DIBUAT' => now(),
            'VALID_UNTIL' => now()->addHours(24),
        ]);
    }
}
