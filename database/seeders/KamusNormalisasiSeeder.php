<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KamusNormalisasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Jika tabel sudah berisi data (dikelola via UI), lewati seeding otomatis
        if (\App\Models\KamusNormalisasi::count() > 0) {
            $this->command->info('KamusNormalisasi: tabel sudah berisi data, seeding dilewati.');
            return;
        }

        // Sumber data awal dari file arsip — digunakan saat fresh install / reset database
        $kamus = require app_path('Data/kamus_normalisasi.php');
        $now = \Illuminate\Support\Carbon::now();
        $data = [];

        foreach ($kamus as $slang => $formal) {
            $data[] = [
                'slang'      => $slang,
                'formal'     => $formal,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Chunk insert agar tidak melebihi batas parameter database
        foreach (array_chunk($data, 500) as $chunk) {
            \Illuminate\Support\Facades\DB::table('kamus_normalisasi')->insert($chunk);
        }

        $this->command->info('KamusNormalisasi: ' . count($data) . ' kata berhasil di-seed.');
    }
}
