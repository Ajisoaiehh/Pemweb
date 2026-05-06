<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Pembayaran::create([
            'ID_PEMBAYARAN' => 1,
            'ID_PARKIR' => 1,
            'METODE' => 'saldo',
            'STATUS' => 'Berhasil',
            'JUMLAH' => 10000,
            'WAKTU_BAYAR' => now()->subHours(1),
        ]);

        \App\Models\Pembayaran::create([
            'ID_PEMBAYARAN' => 2,
            'ID_PARKIR' => 2,
            'METODE' => 'ovo',
            'STATUS' => 'Berhasil',
            'JUMLAH' => 15000,
            'WAKTU_BAYAR' => now()->subHours(2),
        ]);

        \App\Models\Pembayaran::create([
            'ID_PEMBAYARAN' => 3,
            'ID_PARKIR' => 1,
            'METODE' => 'topup',
            'STATUS' => 'Berhasil',
            'JUMLAH' => 50000,
            'WAKTU_BAYAR' => now()->subDays(1),
        ]);
    }
}
