<?php

namespace App\Http\Controllers;

use App\Models\DataX;
use App\Models\DataThread;
use App\Models\NaiveBayesModel;
use App\Models\NaiveBayesHasil;
use App\Services\NaiveBayesService;
use App\Services\PreprocessingService;
use Illuminate\Http\Request;

class NaiveBayesPengujianController extends Controller
{
    protected NaiveBayesService $nbService;
    protected PreprocessingService $prepService;

    public function __construct(NaiveBayesService $nbService, PreprocessingService $prepService)
    {
        $this->nbService = $nbService;
        $this->prepService = $prepService;
    }

    public function index(Request $request)
    {
        $sumber = $request->query('sumber', 'x');
        $search = $request->query('search');

        $isTrained = NaiveBayesModel::where('sumber', $sumber)->exists();

        // Testing data
        $testingQuery = NaiveBayesHasil::where('sumber', $sumber)
            ->where('split', 'test');

        $totalTesting = (clone $testingQuery)->count();
        $totalDiuji = (clone $testingQuery)->whereNotNull('sentimen_prediksi')->count();
        $totalBenar = (clone $testingQuery)->where('benar', true)->count();
        $akurasi = $totalDiuji > 0 ? round(($totalBenar / $totalDiuji) * 100, 2) : 0;

        $testingData = $testingQuery->paginate(15)->withQueryString();

        // Attach original texts
        $ids = $testingData->pluck('data_id')->toArray();
        if ($sumber === 'x') {
            $asliMap = DataX::whereIn('id', $ids)->get()->keyBy('id');
        } else {
            $asliMap = DataThread::whereIn('id', $ids)->get()->keyBy('id');
        }

        foreach ($testingData as $item) {
            $item->data_asli = $asliMap[$item->data_id] ?? null;
        }

        return view('naive-bayes.pengujian', compact(
            'sumber',
            'isTrained',
            'testingData',
            'totalTesting',
            'totalDiuji',
            'totalBenar',
            'akurasi',
            'search'
        ));
    }

    public function batch(Request $request)
    {
        $sumber = $request->input('sumber', 'x');
        $hasil = $this->nbService->uji($sumber);

        if (isset($hasil['error'])) {
            return redirect()->route('naive-bayes.pengujian', ['sumber' => $sumber])
                ->with('error', $hasil['error']);
        }

        $sumberNama = $sumber === 'x' ? 'Data X' : 'Data Thread';
        return redirect()->route('naive-bayes.pengujian', ['sumber' => $sumber])
            ->with('success', "Pengujian batch ({$sumberNama}) selesai! Dari {$hasil['total']} data testing, {$hasil['benar']} benar ({$hasil['akurasi']}%) dan {$hasil['salah']} salah.");
    }

    public function prediksi(Request $request)
    {
        $request->validate([
            'sumber' => 'required|in:x,thread',
            'teks'   => 'required|string|min:3',
        ]);

        $sumber = $request->input('sumber');
        $teks = $request->input('teks');

        // Preprocess input text
        $prepResult = $this->prepService->prosesLengkap($teks);
        $stemmed = $prepResult['hasil_stemming'];

        // Predict
        $predResult = $this->nbService->prediksiTeks($sumber, $stemmed);

        if (isset($predResult['error'])) {
            return response()->json(['success' => false, 'message' => $predResult['error']], 400);
        }

        return response()->json([
            'success'      => true,
            'teks_asli'    => $teks,
            'teks_stemmed' => $stemmed,
            'prediksi'     => $predResult['prediksi'],
            'skor'         => $predResult['skor'],
        ]);
    }
}
