<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StopwordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Jika tabel sudah berisi data (dikelola via UI), lewati seeding otomatis
        if (\App\Models\Stopword::count() > 0) {
            $this->command->info('Stopwords: tabel sudah berisi data, seeding dilewati.');
            return;
        }

        // Sumber data awal dari file arsip — digunakan saat fresh install / reset database
        $stopwords = require app_path('Data/stopwords.php');
        $now = \Illuminate\Support\Carbon::now();
        $data = [];

        foreach ($stopwords as $kata) {
            $data[] = [
                'kata'       => $kata,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($data, 500) as $chunk) {
            \Illuminate\Support\Facades\DB::table('stopwords')->insert($chunk);
        }

        $this->command->info('Stopwords: ' . count($data) . ' kata berhasil di-seed.');
    }
}
