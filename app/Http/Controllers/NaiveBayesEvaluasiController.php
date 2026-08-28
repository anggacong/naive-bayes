<?php

namespace App\Http\Controllers;

use App\Models\NaiveBayesModel;
use App\Models\NaiveBayesHasil;
use App\Services\NaiveBayesService;
use Illuminate\Http\Request;

class NaiveBayesEvaluasiController extends Controller
{
    protected NaiveBayesService $nbService;

    public function __construct(NaiveBayesService $nbService)
    {
        $this->nbService = $nbService;
    }

    public function index(Request $request)
    {
        $sumber = $request->query('sumber', 'x');

        $isTrained = NaiveBayesModel::where('sumber', $sumber)->exists();
        $isTested = NaiveBayesHasil::where('sumber', $sumber)
            ->where('split', 'test')
            ->whereNotNull('sentimen_prediksi')
            ->exists();

        $evaluasi = [];
        if ($isTested) {
            $evaluasi = $this->nbService->evaluasi($sumber);
        }

        return view('naive-bayes.evaluasi', compact(
            'sumber',
            'isTrained',
            'isTested',
            'evaluasi'
        ));
    }
}
