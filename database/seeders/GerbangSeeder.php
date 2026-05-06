<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GerbangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Gerbang::create([
            'ID_GERBANG' => 1,
            'LOKASI' => 'Jl. Sudirman',
            'STATUS_PLANG' => 'Aktif',
        ]);

        \App\Models\Gerbang::create([
            'ID_GERBANG' => 2,
            'LOKASI' => 'Jl. Sudirman',
            'STATUS_PLANG' => 'Aktif',
        ]);

        \App\Models\Gerbang::create([
            'ID_GERBANG' => 3,
            'LOKASI' => 'Jl. Thamrin',
            'STATUS_PLANG' => 'Aktif',
        ]);

        \App\Models\Gerbang::create([
            'ID_GERBANG' => 4,
            'LOKASI' => 'Jl. Sudirman',
            'STATUS_PLANG' => 'Aktif',
        ]);
    }
}
