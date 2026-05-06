<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParkirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Parkir::create([
            'ID_PARKIR' => 1,
            'NO_PLAT' => 'B 1234 XYZ',
            'ID_PENGGUNA' => 1,
            'WAKTU_MASUK' => now()->subHours(2),
            'WAKTU_KELUAR' => now()->subHours(1),
            'BIAYA' => 10000,
            'STATUS_PARKIR' => 'Selesai',
        ]);

        \App\Models\Parkir::create([
            'ID_PARKIR' => 2,
            'NO_PLAT' => 'B 9876 DEF',
            'ID_PENGGUNA' => 2,
            'WAKTU_MASUK' => now()->subHours(3),
            'WAKTU_KELUAR' => now()->subHours(2),
            'BIAYA' => 15000,
            'STATUS_PARKIR' => 'Selesai',
        ]);

        \App\Models\Parkir::create([
            'ID_PARKIR' => 3,
            'NO_PLAT' => 'B 5678 ABC',
            'ID_PENGGUNA' => 1,
            'WAKTU_MASUK' => now()->subMinutes(30),
            'WAKTU_KELUAR' => null,
            'BIAYA' => 0,
            'STATUS_PARKIR' => 'Sedang Parkir',
        ]);
    }
}
