<?php

namespace App\Http\Controllers;

use App\Models\DataThread;
use App\Models\NaiveBayesHasil;
use App\Models\PreprocessingThread;
use Illuminate\Http\Request;

class UnigramFrekuensiThreadController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $tab    = $request->query('tab', 'dokumen');

        // Statistik ringkas
        $totalDokumen = PreprocessingThread::whereNotNull('hasil_stemming')
            ->where('hasil_stemming', '!=', '')
            ->count();

        // Hitung total token & vocab dari hasil_stemming
        $rows = PreprocessingThread::whereNotNull('hasil_stemming')
            ->where('hasil_stemming', '!=', '')
            ->pluck('hasil_stemming');

        $allTokens = [];
        foreach ($rows as $stemming) {
            $tokens = preg_split('/\s+/', trim($stemming), -1, PREG_SPLIT_NO_EMPTY);
            foreach ($tokens as $t) {
                $allTokens[] = $t;
            }
        }
        $totalToken = count($allTokens);
        $totalVocab = count(array_unique($allTokens));

        // Hitung token per kelas sentimen
        $rowsWithSentimen = PreprocessingThread::whereNotNull('hasil_stemming')
            ->where('hasil_stemming', '!=', '')
            ->with('dataThread')
            ->get();

        $tokenPerKelas = ['positif' => 0, 'negatif' => 0, 'netral' => 0];
        foreach ($rowsWithSentimen as $row) {
            $sentimen = $row->dataThread->sentimen ?? 'netral';
            $tokens   = preg_split('/\s+/', trim($row->hasil_stemming), -1, PREG_SPLIT_NO_EMPTY);
            if (isset($tokenPerKelas[$sentimen])) {
                $tokenPerKelas[$sentimen] += count($tokens);
            }
        }

        // Statistik dari data training (join NaiveBayesHasil split='train')
        $trainIds = NaiveBayesHasil::where('sumber', 'thread')
            ->where('split', 'train')
            ->pluck('sentimen_asli', 'data_id')
            ->toArray(); // [data_id => sentimen_asli]

        $trainingRows = PreprocessingThread::whereIn('data_thread_id', array_keys($trainIds))
            ->whereNotNull('hasil_stemming')
            ->where('hasil_stemming', '!=', '')
            ->get(['data_thread_id', 'hasil_stemming']);

        $trainTokens        = [];
        $trainTokenPerKelas = ['positif' => 0, 'negatif' => 0, 'netral' => 0];
        foreach ($trainingRows as $row) {
            $tokens   = preg_split('/\s+/', trim($row->hasil_stemming), -1, PREG_SPLIT_NO_EMPTY);
            $sentimen = $trainIds[$row->data_thread_id] ?? 'netral';
            foreach ($tokens as $t) {
                $trainTokens[] = $t;
            }
            if (isset($trainTokenPerKelas[$sentimen])) {
                $trainTokenPerKelas[$sentimen] += count($tokens);
            }
        }
        $trainTotalToken = count($trainTokens);
        $trainKataUnik   = count(array_unique($trainTokens));
        $trainTotalDok   = count($trainIds);

        // Tab 1: Daftar dokumen
        $dataDokumen = DataThread::with('preprocessing')
            ->whereHas('preprocessing', function ($q) {
                $q->whereNotNull('hasil_stemming')->where('hasil_stemming', '!=', '');
            })
            ->when($search, function ($q) use ($search) {
                $q->where('teks_asli', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        // Tab 2: Vocabulary frekuensi global
        $vocabulary = [];
        if ($tab === 'vocab') {
            $vocabulary = $this->hitungVocabulary($search);
        }

        // Tab 3: Vocabulary frekuensi data training
        $vocabularyTraining = [];
        if ($tab === 'vocab-train') {
            $vocabularyTraining = $this->hitungVocabularyTraining($trainIds, $search);
        }

        return view('unigram-frekuensi.thread', compact(
            'dataDokumen',
            'vocabulary',
            'vocabularyTraining',
            'totalDokumen',
            'totalToken',
            'totalVocab',
            'tokenPerKelas',
            'trainTotalToken',
            'trainTokenPerKelas',
            'trainKataUnik',
            'trainTotalDok',
            'tab',
            'search'
        ));
    }

    /**
     * Hitung frekuensi kata per kelas sentimen dari hasil_stemming (semua data).
     */
    private function hitungVocabulary(?string $search = null): array
    {
        $rows = PreprocessingThread::whereNotNull('hasil_stemming')
            ->where('hasil_stemming', '!=', '')
            ->with('dataThread')
            ->get();

        $freq = [];

        foreach ($rows as $row) {
            $sentimen = $row->dataThread->sentimen ?? 'netral';
            $tokens   = preg_split('/\s+/', trim($row->hasil_stemming), -1, PREG_SPLIT_NO_EMPTY);
            foreach ($tokens as $kata) {
                if (!isset($freq[$kata])) {
                    $freq[$kata] = ['positif' => 0, 'negatif' => 0, 'netral' => 0, 'total' => 0];
                }
                $freq[$kata][$sentimen] = ($freq[$kata][$sentimen] ?? 0) + 1;
                $freq[$kata]['total']++;
            }
        }

        // Filter pencarian
        if ($search) {
            $freq = array_filter($freq, fn($k) => stripos($k, $search) !== false, ARRAY_FILTER_USE_KEY);
        }

        // Urutkan dari frekuensi tertinggi
        arsort($freq);

        $result = [];
        foreach ($freq as $kata => $data) {
            $result[] = (object) [
                'kata'            => $kata,
                'total_frekuensi' => $data['total'],
                'frek_positif'    => $data['positif'],
                'frek_negatif'    => $data['negatif'],
                'frek_netral'     => $data['netral'],
            ];
        }

        return $result;
    }

    /**
     * Hitung frekuensi kata per kelas sentimen khusus Data Training (80% split).
     */
    private function hitungVocabularyTraining(array $trainIds, ?string $search = null): array
    {
        if (empty($trainIds)) {
            return [];
        }

        $rows = PreprocessingThread::whereIn('data_thread_id', array_keys($trainIds))
            ->whereNotNull('hasil_stemming')
            ->where('hasil_stemming', '!=', '')
            ->get();

        $freq = [];

        foreach ($rows as $row) {
            $sentimen = $trainIds[$row->data_thread_id] ?? 'netral';
            $tokens   = preg_split('/\s+/', trim($row->hasil_stemming), -1, PREG_SPLIT_NO_EMPTY);
            foreach ($tokens as $kata) {
                if (!isset($freq[$kata])) {
                    $freq[$kata] = ['positif' => 0, 'negatif' => 0, 'netral' => 0, 'total' => 0];
                }
                $freq[$kata][$sentimen] = ($freq[$kata][$sentimen] ?? 0) + 1;
                $freq[$kata]['total']++;
            }
        }

        // Filter pencarian
        if ($search) {
            $freq = array_filter($freq, fn($k) => stripos($k, $search) !== false, ARRAY_FILTER_USE_KEY);
        }

        // Urutkan dari frekuensi tertinggi
        uasort($freq, fn($a, $b) => $b['total'] <=> $a['total']);

        $result = [];
        foreach ($freq as $kata => $data) {
            $result[] = (object) [
                'kata'            => $kata,
                'total_frekuensi' => $data['total'],
                'frek_positif'    => $data['positif'],
                'frek_negatif'    => $data['negatif'],
                'frek_netral'     => $data['netral'],
            ];
        }

        return $result;
    }
}
