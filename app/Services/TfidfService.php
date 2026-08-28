<?php

namespace App\Services;

use App\Models\PreprocessingX;
use App\Models\PreprocessingThread;
use App\Models\UnigramX;
use App\Models\UnigramThread;
use Illuminate\Support\Facades\DB;

class TfidfService
{
    /**
     * Hitung TF-IDF untuk semua data preprocessing.
     * @param string $sumber 'x' | 'thread'
     */
    public function hitung(string $sumber): array
    {
        return $sumber === 'x' ? $this->hitungX() : $this->hitungThread();
    }

    /* ───────────────────────── DATA X ───────────────────────── */

    private function hitungX(): array
    {
        $rows = PreprocessingX::whereNotNull('hasil_stemming')
            ->where('hasil_stemming', '!=', '')
            ->with('dataX')
            ->get();

        if ($rows->isEmpty()) {
            return ['total' => 0, 'vocabulary' => 0, 'records' => 0];
        }

        // Kumpulkan token per dokumen
        $dokumen = [];
        foreach ($rows as $row) {
            $tokens = $this->tokenize($row->hasil_stemming);
            if (!empty($tokens)) {
                $dokumen[$row->data_x_id] = [
                    'tokens'   => $tokens,
                    'sentimen' => $row->dataX->sentimen ?? 'netral',
                ];
            }
        }

        $N = count($dokumen);

        // Hitung Document Frequency (DF)
        $df = [];
        foreach ($dokumen as $doc) {
            foreach (array_unique($doc['tokens']) as $kata) {
                $df[$kata] = ($df[$kata] ?? 0) + 1;
            }
        }

        // Hapus data lama
        UnigramX::whereIn('data_x_id', array_keys($dokumen))->delete();

        // Hitung TF, IDF, TF-IDF dan simpan
        $records = [];
        foreach ($dokumen as $docId => $doc) {
            $tokens     = $doc['tokens'];
            $totalToken = count($tokens);
            $freq       = array_count_values($tokens);

            foreach ($freq as $kata => $count) {
                $tf     = $count / $totalToken;
                $dfKata = $df[$kata] ?? 1;
                $idf    = log(($N + 1) / ($dfKata + 1)) + 1; // smooth IDF
                $tfidf  = $tf * $idf;

                $records[] = [
                    'data_x_id'  => $docId,
                    'kata'       => $kata,
                    'tf'         => round($tf, 8),
                    'df'         => $dfKata,
                    'idf'        => round($idf, 8),
                    'tfidf'      => round($tfidf, 8),
                    'sentimen'   => $doc['sentimen'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        foreach (array_chunk($records, 500) as $chunk) {
            UnigramX::insert($chunk);
        }

        return [
            'total'      => $N,
            'vocabulary' => count($df),
            'records'    => count($records),
        ];
    }

    /* ─────────────────────── DATA THREAD ─────────────────────── */

    private function hitungThread(): array
    {
        $rows = PreprocessingThread::whereNotNull('hasil_stemming')
            ->where('hasil_stemming', '!=', '')
            ->with('dataThread')
            ->get();

        if ($rows->isEmpty()) {
            return ['total' => 0, 'vocabulary' => 0, 'records' => 0];
        }

        $dokumen = [];
        foreach ($rows as $row) {
            $tokens = $this->tokenize($row->hasil_stemming);
            if (!empty($tokens)) {
                $dokumen[$row->data_thread_id] = [
                    'tokens'   => $tokens,
                    'sentimen' => $row->dataThread->sentimen ?? 'netral',
                ];
            }
        }

        $N = count($dokumen);

        $df = [];
        foreach ($dokumen as $doc) {
            foreach (array_unique($doc['tokens']) as $kata) {
                $df[$kata] = ($df[$kata] ?? 0) + 1;
            }
        }

        UnigramThread::whereIn('data_thread_id', array_keys($dokumen))->delete();

        $records = [];
        foreach ($dokumen as $docId => $doc) {
            $tokens     = $doc['tokens'];
            $totalToken = count($tokens);
            $freq       = array_count_values($tokens);

            foreach ($freq as $kata => $count) {
                $tf     = $count / $totalToken;
                $dfKata = $df[$kata] ?? 1;
                $idf    = log(($N + 1) / ($dfKata + 1)) + 1;
                $tfidf  = $tf * $idf;

                $records[] = [
                    'data_thread_id' => $docId,
                    'kata'           => $kata,
                    'tf'             => round($tf, 8),
                    'df'             => $dfKata,
                    'idf'            => round($idf, 8),
                    'tfidf'          => round($tfidf, 8),
                    'sentimen'       => $doc['sentimen'],
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            }
        }

        foreach (array_chunk($records, 500) as $chunk) {
            UnigramThread::insert($chunk);
        }

        return [
            'total'      => $N,
            'vocabulary' => count($df),
            'records'    => count($records),
        ];
    }

    /* ─────────────────── VOCABULARY GLOBAL ────────────────────── */

    public function vocabulary(string $sumber): array
    {
        $table = $sumber === 'x' ? 'unigram_x' : 'unigram_thread';

        return DB::table($table)
            ->select(
                'kata',
                DB::raw('SUM(tfidf) as total_tfidf'),
                DB::raw('MAX(df) as df'),
                DB::raw('ROUND(MAX(idf), 6) as idf'),
                DB::raw('SUM(CASE WHEN sentimen="positif" THEN tfidf ELSE 0 END) as tfidf_positif'),
                DB::raw('SUM(CASE WHEN sentimen="negatif" THEN tfidf ELSE 0 END) as tfidf_negatif'),
                DB::raw('SUM(CASE WHEN sentimen="netral"  THEN tfidf ELSE 0 END) as tfidf_netral')
            )
            ->groupBy('kata')
            ->orderByDesc('total_tfidf')
            ->get()
            ->toArray();
    }

    /* ───────────────────── HELPER ─────────────────────────────── */

    private function tokenize(string $text): array
    {
        $text = trim($text);
        if (empty($text)) return [];
        return preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    }
}
