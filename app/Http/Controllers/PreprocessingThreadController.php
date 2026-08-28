<?php

namespace App\Http\Controllers;

use App\Models\DataThread;
use App\Models\PreprocessingThread;
use App\Services\PreprocessingService;

class PreprocessingThreadController extends Controller
{
    public function __construct(private PreprocessingService $service) {}

    public function index(\Illuminate\Http\Request $request)
    {
        $query = DataThread::with('preprocessing');

        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('username', 'like', "%{$keyword}%")
                  ->orWhere('teks_asli', 'like', "%{$keyword}%")
                  ->orWhereHas('preprocessing', function ($qp) use ($keyword) {
                      $qp->where('hasil_stemming', 'like', "%{$keyword}%")
                         ->orWhere('hasil_cleaning', 'like', "%{$keyword}%")
                         ->orWhere('hasil_normalisasi', 'like', "%{$keyword}%");
                  });
            });
        }

        if ($request->filled('sentimen')) {
            $query->where('sentimen', $request->sentimen);
        }

        if ($request->filled('status')) {
            if ($request->status === 'sudah') {
                $query->has('preprocessing');
            } elseif ($request->status === 'belum') {
                $query->doesntHave('preprocessing');
            }
        }

        $data = $query->latest()->paginate(20)->withQueryString();
        $totalData = DataThread::count();
        $sudahDiproses = PreprocessingThread::count();

        return view('preprocessing.thread', compact('data', 'totalData', 'sudahDiproses'));
    }

    public function proses()
    {
        $dataList = DataThread::all();

        foreach ($dataList as $item) {
            $hasil = $this->service->prosesLengkap($item->teks_asli);

            PreprocessingThread::updateOrCreate(
                ['data_thread_id' => $item->id],
                [
                    'hasil_cleaning'      => $hasil['hasil_cleaning'],
                    'hasil_case_folding'  => $hasil['hasil_case_folding'],
                    'hasil_tokenizing'    => $hasil['hasil_tokenizing'],
                    'hasil_repeated_char' => $hasil['hasil_repeated_char'],
                    'hasil_normalisasi'   => $hasil['hasil_normalisasi'],
                    'hasil_stopword'      => $hasil['hasil_stopword'],
                    'hasil_stemming'      => $hasil['hasil_stemming'],
                ]
            );
        }

        return redirect()->route('preprocessing.thread')
            ->with('success', 'Preprocessing Data Thread berhasil dijalankan untuk ' . $dataList->count() . ' data.');
    }

    public function reset()
    {
        PreprocessingThread::truncate();
        return redirect()->route('preprocessing.thread')
            ->with('success', 'Hasil preprocessing Data Thread berhasil direset.');
    }
}
