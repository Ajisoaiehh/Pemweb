<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Skip default user creation to avoid conflicts
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Seed custom parking system data
        $this->call([
            GerbangSeeder::class,
            Pengguna_ParkirSeeder::class,
            KendaraanSeeder::class,
            QR_CodeSeeder::class,
            ParkirSeeder::class,
            PembayaranSeeder::class,
        ]);
    }
}
