<?php

namespace App\Http\Controllers;

use App\Models\DataThread;
use App\Models\UnigramThread;
use App\Services\TfidfService;
use Illuminate\Http\Request;

class UnigramThreadController extends Controller
{
    protected TfidfService $tfidfService;

    public function __construct(TfidfService $tfidfService)
    {
        $this->tfidfService = $tfidfService;
    }

    public function index(Request $request)
    {
        $tab = $request->query('tab', 'dokumen');
        $search = $request->query('search');

        $totalDokumen = DataThread::has('preprocessing')->count();
        $totalUnigram = UnigramThread::count();
        $totalVocab = UnigramThread::distinct('kata')->count('kata');

        // Tab 1: Dokumen & Token TF-IDF
        $dataDokumen = DataThread::with(['preprocessing', 'unigram'])
            ->whereHas('preprocessing', function ($q) {
                $q->whereNotNull('hasil_stemming')->where('hasil_stemming', '!=', '');
            })
            ->when($search, function ($q) use ($search) {
                $q->where('teks_asli', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        // Tab 2: Global Vocabulary TF-IDF
        $vocabulary = [];
        if ($tab === 'vocab') {
            $vocabulary = $this->tfidfService->vocabulary('thread');
            if ($search) {
                $vocabulary = array_values(array_filter($vocabulary, function ($v) use ($search) {
                    return stripos($v->kata, $search) !== false;
                }));
            }
        }

        return view('unigram.thread', compact(
            'dataDokumen',
            'vocabulary',
            'totalDokumen',
            'totalUnigram',
            'totalVocab',
            'tab',
            'search'
        ));
    }

    public function hitung()
    {
        $hasil = $this->tfidfService->hitung('thread');

        if ($hasil['total'] === 0) {
            return redirect()->route('unigram.thread')->with('error', 'Tidak ada data preprocessing yang dapat dihitung TF-IDF. Jalankan preprocessing terlebih dahulu.');
        }

        return redirect()->route('unigram.thread')->with('success', "Berhasil menghitung TF-IDF untuk {$hasil['total']} data Thread dengan {$hasil['vocabulary']} kata unik vocabulary ({$hasil['records']} token terbobot).");
    }

    public function reset()
    {
        UnigramThread::truncate();
        return redirect()->route('unigram.thread')->with('info', 'Semua data pembobotan Unigram (TF-IDF) Data Thread berhasil direset.');
    }
}
