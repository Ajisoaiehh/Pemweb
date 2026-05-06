<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Pengguna_ParkirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Pengguna_Parkir::create([
            'ID_PENGGUNA' => 1,
            'NAMA' => 'John Doe',
            'EMAIL' => 'john@example.com',
            'PASSWORD' => bcrypt('password123'),
            'SALDO' => 50000,
            'NO_HP' => '081234567890',
        ]);

        \App\Models\Pengguna_Parkir::create([
            'ID_PENGGUNA' => 2,
            'NAMA' => 'Jane Smith',
            'EMAIL' => 'jane@example.com',
            'PASSWORD' => bcrypt('password123'),
            'SALDO' => 75000,
            'NO_HP' => '081987654321',
        ]);

        \App\Models\Pengguna_Parkir::create([
            'ID_PENGGUNA' => 3,
            'NAMA' => 'Bob Johnson',
            'EMAIL' => 'bob@example.com',
            'PASSWORD' => bcrypt('password123'),
            'SALDO' => 25000,
            'NO_HP' => '081555666777',
        ]);
    }
}
