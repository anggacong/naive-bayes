<?php

namespace App\Services;

use App\Models\DataX;
use App\Models\DataThread;
use App\Models\PreprocessingX;
use App\Models\PreprocessingThread;
use App\Models\NaiveBayesModel;
use App\Models\NaiveBayesHasil;
use Illuminate\Support\Collection;

class NaiveBayesService
{
    private const ALPHA       = 1;     // Laplace smoothing
    private const KELAS       = ['positif', 'negatif', 'netral'];
    private const TRAIN_RATIO = 0.8;   // 80% training

    /* ═══════════════════════════════════════════════════════════
     *  TRAINING
     * ═══════════════════════════════════════════════════════════ */

    /**
     * Latih model Naive Bayes dari preprocessing data.
     * @param string $sumber 'x' | 'thread'
     */
    public function latih(string $sumber): array
    {
        // 1. Ambil semua data yang sudah dipreprocess
        $data = $this->ambilData($sumber);

        if ($data->isEmpty()) {
            return ['error' => 'Tidak ada data preprocessing. Jalankan preprocessing terlebih dahulu.'];
        }

        // 2. Shuffle & split 80/20
        $shuffled = $data->shuffle();
        $total    = $shuffled->count();
        $trainN   = (int) ceil($total * self::TRAIN_RATIO);
        $train    = $shuffled->take($trainN);
        $test     = $shuffled->skip($trainN);

        // 3. Hapus data model & hasil lama untuk sumber ini
        NaiveBayesModel::where('sumber', $sumber)->delete();
        NaiveBayesHasil::where('sumber', $sumber)->delete();

        // 4. Hitung P(kelas) — Prior
        $jumlahPerKelas = [];
        foreach (self::KELAS as $kelas) {
            $jumlahPerKelas[$kelas] = $train->where('sentimen', $kelas)->count();
        }

        $priorRecords = [];
        foreach (self::KELAS as $kelas) {
            $priorRecords[] = [
                'sumber'     => $sumber,
                'tipe'       => 'prior',
                'sentimen'   => $kelas,
                'kata'       => null,
                'nilai_log'  => $jumlahPerKelas[$kelas] > 0
                    ? log($jumlahPerKelas[$kelas] / $trainN)
                    : log(self::ALPHA / ($trainN + count(self::KELAS))),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        NaiveBayesModel::insert($priorRecords);

        // 5. Hitung P(kata | kelas) — Likelihood dengan Laplace Smoothing
        // Gabungkan semua token per kelas
        $tokenPerKelas = [];
        foreach (self::KELAS as $kelas) {
            $tokenPerKelas[$kelas] = [];
        }
        foreach ($train as $doc) {
            $tokens = $this->tokenize($doc['stemming']);
            foreach ($tokens as $token) {
                $tokenPerKelas[$doc['sentimen']][] = $token;
            }
        }

        // Vocabulary (semua kata unik dari training)
        $vocab = [];
        foreach ($tokenPerKelas as $kelas => $tokens) {
            foreach ($tokens as $t) {
                $vocab[$t] = true;
            }
        }
        $V = count($vocab);

        // Frekuensi kata per kelas
        $freqPerKelas = [];
        foreach (self::KELAS as $kelas) {
            $freqPerKelas[$kelas] = array_count_values($tokenPerKelas[$kelas]);
        }

        // Hitung dan simpan likelihood
        $likeRecords = [];
        foreach ($vocab as $kata => $_) {
            foreach (self::KELAS as $kelas) {
                $count       = $freqPerKelas[$kelas][$kata] ?? 0;
                $total       = array_sum($freqPerKelas[$kelas]);
                $prob        = ($count + self::ALPHA) / ($total + self::ALPHA * $V);
                $likeRecords[] = [
                    'sumber'     => $sumber,
                    'tipe'       => 'likelihood',
                    'sentimen'   => $kelas,
                    'kata'       => $kata,
                    'nilai_log'  => log($prob),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Simpan dalam batch
        foreach (array_chunk($likeRecords, 1000) as $chunk) {
            NaiveBayesModel::insert($chunk);
        }

        // 6. Simpan split info ke tabel hasil
        $hasilRecords = [];
        foreach ($train as $doc) {
            $hasilRecords[] = [
                'sumber'            => $sumber,
                'data_id'           => $doc['data_id'],
                'split'             => 'train',
                'sentimen_asli'     => $doc['sentimen'],
                'sentimen_prediksi' => null,
                'benar'             => null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        }
        foreach ($test as $doc) {
            $hasilRecords[] = [
                'sumber'            => $sumber,
                'data_id'           => $doc['data_id'],
                'split'             => 'test',
                'sentimen_asli'     => $doc['sentimen'],
                'sentimen_prediksi' => null,
                'benar'             => null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        }
        NaiveBayesHasil::insert($hasilRecords);

        return [
            'total'          => $total,
            'train'          => $trainN,
            'test'           => $test->count(),
            'vocabulary'     => $V,
            'kelas'          => $jumlahPerKelas,
        ];
    }

    /* ═══════════════════════════════════════════════════════════
     *  PENGUJIAN (BATCH)
     * ═══════════════════════════════════════════════════════════ */

    public function uji(string $sumber): array
    {
        // Load model
        $prior      = $this->loadPrior($sumber);
        $likelihood = $this->loadLikelihood($sumber);

        if (empty($prior)) {
            return ['error' => 'Model belum dilatih. Jalankan Pelatihan terlebih dahulu.'];
        }

        // Ambil data testing
        $testing = NaiveBayesHasil::where('sumber', $sumber)
            ->where('split', 'test')
            ->get();

        if ($testing->isEmpty()) {
            return ['error' => 'Tidak ada data testing.'];
        }

        // Ambil preprocessing data
        $prepData = $this->ambilDataById($sumber, $testing->pluck('data_id')->toArray());

        $benar = 0;
        foreach ($testing as $item) {
            $doc     = $prepData->firstWhere('data_id', $item->data_id);
            $stemmed = $doc ? $doc['stemming'] : '';
            $prediksi = $this->prediksiSatu($stemmed, $prior, $likelihood);

            $item->sentimen_prediksi = $prediksi;
            $item->benar             = ($prediksi === $item->sentimen_asli);
            $item->save();

            if ($item->benar) $benar++;
        }

        $total   = $testing->count();
        $akurasi = $total > 0 ? round($benar / $total * 100, 2) : 0;

        return [
            'total'   => $total,
            'benar'   => $benar,
            'salah'   => $total - $benar,
            'akurasi' => $akurasi,
        ];
    }

    /* ═══════════════════════════════════════════════════════════
     *  PREDIKSI SATU TEKS (Real-time)
     * ═══════════════════════════════════════════════════════════ */

    public function prediksiTeks(string $sumber, string $teks): array
    {
        $prior      = $this->loadPrior($sumber);
        $likelihood = $this->loadLikelihood($sumber);

        if (empty($prior)) {
            return ['error' => 'Model belum dilatih.'];
        }

        $prediksi = $this->prediksiSatu($teks, $prior, $likelihood);
        $skor     = $this->hitungSkor($teks, $prior, $likelihood);

        return [
            'prediksi' => $prediksi,
            'skor'     => $skor,
        ];
    }

    /* ═══════════════════════════════════════════════════════════
     *  EVALUASI
     * ═══════════════════════════════════════════════════════════ */

    public function evaluasi(string $sumber): array
    {
        $hasil = NaiveBayesHasil::where('sumber', $sumber)
            ->where('split', 'test')
            ->whereNotNull('sentimen_prediksi')
            ->get();

        if ($hasil->isEmpty()) {
            return ['error' => 'Belum ada hasil pengujian.'];
        }

        $kelas = self::KELAS;

        // Confusion Matrix
        $matrix = [];
        foreach ($kelas as $actual) {
            foreach ($kelas as $pred) {
                $matrix[$actual][$pred] = 0;
            }
        }
        foreach ($hasil as $item) {
            $matrix[$item->sentimen_asli][$item->sentimen_prediksi]++;
        }

        // Hitung TP, FP, FN per kelas
        $metrik = [];
        foreach ($kelas as $k) {
            $tp = $matrix[$k][$k];
            $fp = array_sum(array_column($matrix, $k)) - $tp;
            $fn = array_sum($matrix[$k]) - $tp;

            $presisi = ($tp + $fp) > 0 ? $tp / ($tp + $fp) : 0;
            $recall  = ($tp + $fn) > 0 ? $tp / ($tp + $fn) : 0;
            $f1      = ($presisi + $recall) > 0
                ? 2 * $presisi * $recall / ($presisi + $recall) : 0;

            $metrik[$k] = [
                'tp'      => $tp,
                'fp'      => $fp,
                'fn'      => $fn,
                'presisi' => round($presisi, 4),
                'recall'  => round($recall, 4),
                'f1'      => round($f1, 4),
                'support' => array_sum($matrix[$k]),
            ];
        }

        $total   = $hasil->count();
        $benar   = $hasil->where('benar', true)->count();
        $akurasi = $total > 0 ? round($benar / $total * 100, 2) : 0;

        // Macro Average
        $macroPresisi = round(array_sum(array_column($metrik, 'presisi')) / count($kelas), 4);
        $macroRecall  = round(array_sum(array_column($metrik, 'recall'))  / count($kelas), 4);
        $macroF1      = round(array_sum(array_column($metrik, 'f1'))      / count($kelas), 4);

        // Weighted Average
        $weightedPresisi = 0; $weightedRecall = 0; $weightedF1 = 0;
        foreach ($kelas as $k) {
            $w = $metrik[$k]['support'] / max($total, 1);
            $weightedPresisi += $metrik[$k]['presisi'] * $w;
            $weightedRecall  += $metrik[$k]['recall']  * $w;
            $weightedF1      += $metrik[$k]['f1']      * $w;
        }

        return [
            'total'            => $total,
            'benar'            => $benar,
            'akurasi'          => $akurasi,
            'matrix'           => $matrix,
            'metrik'           => $metrik,
            'kelas'            => $kelas,
            'macro_presisi'    => round($macroPresisi, 4),
            'macro_recall'     => round($macroRecall, 4),
            'macro_f1'         => round($macroF1, 4),
            'weighted_presisi' => round($weightedPresisi, 4),
            'weighted_recall'  => round($weightedRecall, 4),
            'weighted_f1'      => round($weightedF1, 4),
        ];
    }

    /* ═══════════════════════════════════════════════════════════
     *  PRIVATE HELPERS
     * ═══════════════════════════════════════════════════════════ */

    private function prediksiSatu(string $teks, array $prior, array $likelihood): string
    {
        $skor = $this->hitungSkor($teks, $prior, $likelihood);
        return array_key_first(
            array_filter($skor, fn($v) => $v === max($skor))
        );
    }

    private function hitungSkor(string $teks, array $prior, array $likelihood): array
    {
        $tokens = $this->tokenize($teks);
        $skor   = [];

        foreach (self::KELAS as $kelas) {
            $s = $prior[$kelas] ?? log(1 / count(self::KELAS));
            foreach ($tokens as $kata) {
                $s += $likelihood[$kelas][$kata] ?? log(self::ALPHA / 1); // OOV smoothing
            }
            $skor[$kelas] = $s;
        }

        return $skor;
    }

    private function loadPrior(string $sumber): array
    {
        $rows = NaiveBayesModel::where('sumber', $sumber)
            ->where('tipe', 'prior')
            ->get();

        $prior = [];
        foreach ($rows as $row) {
            $prior[$row->sentimen] = $row->nilai_log;
        }
        return $prior;
    }

    private function loadLikelihood(string $sumber): array
    {
        $rows = NaiveBayesModel::where('sumber', $sumber)
            ->where('tipe', 'likelihood')
            ->get();

        $likelihood = [];
        foreach ($rows as $row) {
            $likelihood[$row->sentimen][$row->kata] = $row->nilai_log;
        }
        return $likelihood;
    }

    private function ambilData(string $sumber): Collection
    {
        if ($sumber === 'x') {
            return PreprocessingX::whereNotNull('hasil_stemming')
                ->where('hasil_stemming', '!=', '')
                ->with('dataX')
                ->get()
                ->filter(fn($r) => $r->dataX && $r->dataX->sentimen)
                ->map(fn($r) => [
                    'data_id'  => $r->data_x_id,
                    'stemming' => $r->hasil_stemming,
                    'sentimen' => $r->dataX->sentimen,
                ])
                ->values();
        }

        return PreprocessingThread::whereNotNull('hasil_stemming')
            ->where('hasil_stemming', '!=', '')
            ->with('dataThread')
            ->get()
            ->filter(fn($r) => $r->dataThread && $r->dataThread->sentimen)
            ->map(fn($r) => [
                'data_id'  => $r->data_thread_id,
                'stemming' => $r->hasil_stemming,
                'sentimen' => $r->dataThread->sentimen,
            ])
            ->values();
    }

    private function ambilDataById(string $sumber, array $ids): Collection
    {
        if ($sumber === 'x') {
            return PreprocessingX::whereIn('data_x_id', $ids)
                ->get()
                ->map(fn($r) => [
                    'data_id'  => $r->data_x_id,
                    'stemming' => $r->hasil_stemming ?? '',
                ]);
        }

        return PreprocessingThread::whereIn('data_thread_id', $ids)
            ->get()
            ->map(fn($r) => [
                'data_id'  => $r->data_thread_id,
                'stemming' => $r->hasil_stemming ?? '',
            ]);
    }

    private function tokenize(string $text): array
    {
        $text = trim($text);
        if (empty($text)) return [];
        return preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    }
}
