<?php

namespace App\Http\Controllers;

use App\Models\NaiveBayesModel;
use App\Models\NaiveBayesHasil;
use App\Services\NaiveBayesService;
use Illuminate\Http\Request;

class NaiveBayesTrainingController extends Controller
{
    protected NaiveBayesService $nbService;

    public function __construct(NaiveBayesService $nbService)
    {
        $this->nbService = $nbService;
    }

    public function index(Request $request)
    {
        $sumber = $request->query('sumber', 'x');
        $search = $request->query('search');

        $isTrained = NaiveBayesModel::where('sumber', $sumber)->exists();

        $totalTraining = NaiveBayesHasil::where('sumber', $sumber)->where('split', 'train')->count();
        $totalTesting = NaiveBayesHasil::where('sumber', $sumber)->where('split', 'test')->count();

        // Jumlah dokumen per kelas (dari data training)
        $jumlahPerKelas = NaiveBayesHasil::where('sumber', $sumber)
            ->where('split', 'train')
            ->selectRaw('sentimen_asli, COUNT(*) as jumlah')
            ->groupBy('sentimen_asli')
            ->pluck('jumlah', 'sentimen_asli')
            ->toArray();

        // Prior
        $prior = NaiveBayesModel::where('sumber', $sumber)
            ->where('tipe', 'prior')
            ->get();

        // Likelihood (Vocabulary)
        $likelihoodQuery = NaiveBayesModel::where('sumber', $sumber)
            ->where('tipe', 'likelihood')
            ->when($search, function ($q) use ($search) {
                $q->where('kata', 'like', "%{$search}%");
            });

        $totalVocab = (clone $likelihoodQuery)->distinct('kata')->count('kata');

        // Group by kata for cleaner table display
        $likelihoodRaw = $likelihoodQuery->get()->groupBy('kata');
        $likelihoodData = [];
        foreach ($likelihoodRaw as $kata => $rows) {
            $likelihoodData[] = (object)[
                'kata'     => $kata,
                'positif'  => $rows->firstWhere('sentimen', 'positif')->nilai_log ?? null,
                'negatif'  => $rows->firstWhere('sentimen', 'negatif')->nilai_log ?? null,
                'netral'   => $rows->firstWhere('sentimen', 'netral')->nilai_log ?? null,
            ];
        }

        // Manual pagination for likelihood
        $perPage = 15;
        $page = (int) $request->query('page', 1);
        $totalItems = count($likelihoodData);
        $likelihoodPaginated = array_slice($likelihoodData, ($page - 1) * $perPage, $perPage);
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $likelihoodPaginated,
            $totalItems,
            $perPage,
            $page,
            ['path' => route('naive-bayes.training'), 'query' => $request->query()]
        );

        return view('naive-bayes.training', compact(
            'sumber',
            'isTrained',
            'totalTraining',
            'totalTesting',
            'totalVocab',
            'prior',
            'jumlahPerKelas',
            'paginator',
            'search'
        ));
    }

    public function latih(Request $request)
    {
        $sumber = $request->input('sumber', 'x');
        $random = (bool) $request->input('random', 1);
        $hasil  = $this->nbService->latih($sumber, $random);

        if (isset($hasil['error'])) {
            return redirect()->route('naive-bayes.training', ['sumber' => $sumber])
                ->with('error', $hasil['error']);
        }

        $sumberNama = $sumber === 'x' ? 'Data X' : 'Data Thread';
        return redirect()->route('naive-bayes.training', ['sumber' => $sumber])
            ->with('success', "Pelatihan Model Naive Bayes ({$sumberNama}) berhasil! Data dibagi 80% Training ({$hasil['train']} data) dan 20% Testing ({$hasil['test']} data) dengan {$hasil['vocabulary']} kata dalam vocabulary.");
    }

    public function reset(Request $request)
    {
        $sumber = $request->input('sumber', 'x');
        NaiveBayesModel::where('sumber', $sumber)->delete();
        NaiveBayesHasil::where('sumber', $sumber)->delete();

        $sumberNama = $sumber === 'x' ? 'Data X' : 'Data Thread';
        return redirect()->route('naive-bayes.training', ['sumber' => $sumber])
            ->with('info', "Model Pelatihan Naive Bayes ({$sumberNama}) berhasil direset.");
    }
}
