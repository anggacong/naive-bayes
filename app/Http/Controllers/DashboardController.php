<?php

namespace App\Http\Controllers;

use App\Models\DataX;
use App\Models\DataThread;

class DashboardController extends Controller
{
    public function index()
    {
        $totalX      = DataX::count();
        $totalThread = DataThread::count();

        $sentimenX = DataX::selectRaw("sentimen, COUNT(*) as jumlah")
            ->groupBy('sentimen')
            ->pluck('jumlah', 'sentimen')
            ->toArray();

        $sentimenThread = DataThread::selectRaw("sentimen, COUNT(*) as jumlah")
            ->groupBy('sentimen')
            ->pluck('jumlah', 'sentimen')
            ->toArray();

        $totalData = $totalX + $totalThread;

        return view('dashboard', compact(
            'totalX',
            'totalThread',
            'sentimenX',
            'sentimenThread',
            'totalData'
        ));
    }
}
