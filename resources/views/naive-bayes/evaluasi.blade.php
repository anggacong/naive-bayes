@extends('layouts.app')

@section('title', 'Evaluasi Model Naive Bayes')
@section('page-title', 'Evaluasi Kinerja Model Naive Bayes')

@section('content')
<div class="space-y-6">

    {{-- Source Switcher Header --}}
    <div class="card p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('naive-bayes.evaluasi', ['sumber' => 'x']) }}"
                    class="px-4 py-2 rounded-lg text-xs font-semibold flex items-center gap-2 transition-all {{ $sumber === 'x' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'bg-gray-50 text-gray-500 hover:text-gray-700 border border-gray-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Evaluasi Data X (Twitter)
                </a>
                <a href="{{ route('naive-bayes.evaluasi', ['sumber' => 'thread']) }}"
                    class="px-4 py-2 rounded-lg text-xs font-semibold flex items-center gap-2 transition-all {{ $sumber === 'thread' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'bg-gray-50 text-gray-500 hover:text-gray-700 border border-gray-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                    Evaluasi Data Thread
                </a>
            </div>
            <p class="text-xs text-gray-500 mt-2">
                Evaluasi performa klasifikasi sentimen menggunakan <strong class="text-gray-700">Confusion Matrix 3×3</strong>, Akurasi, Presisi, Recall, dan F1-Score.
            </p>
        </div>

        @if($isTrained)
        <a href="{{ route('naive-bayes.pengujian', ['sumber' => $sumber]) }}" class="btn btn-primary text-xs px-4 py-2 rounded-lg font-medium flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Pengujian
        </a>
        @endif
    </div>

    @if(!$isTested)
    <div class="card p-12 text-center">
        <div class="w-16 h-16 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h3 class="text-base font-semibold text-gray-700">Belum Ada Hasil Pengujian untuk Dievaluasi</h3>
        <p class="text-xs text-gray-500 mt-1.5 max-w-md mx-auto">
            Jalankan <strong>Pengujian Batch</strong> pada menu Pengujian terlebih dahulu agar matriks evaluasi dan performa model dapat dihitung.
        </p>
        <a href="{{ route('naive-bayes.pengujian', ['sumber' => $sumber]) }}" class="btn btn-primary text-xs px-4 py-2 rounded-lg mt-4 inline-block">
            Buka Halaman Pengujian
        </a>
    </div>
    @else

    {{-- Main Evaluation Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card p-5 flex items-center gap-4 bg-gradient-to-br from-[#1e1e2d] to-emerald-950/20 border-emerald-500/30">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-emerald-400 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <div class="text-xs text-emerald-400 font-semibold uppercase tracking-wider">Akurasi Model</div>
                <div class="text-2xl font-black text-gray-800 mt-0.5">{{ $evaluasi['akurasi'] }}%</div>
                <div class="text-[10px] text-gray-500 mt-0.5">{{ $evaluasi['benar'] }} dari {{ $evaluasi['total'] }} benar</div>
            </div>
        </div>

        <div class="card p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <div>
                <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Macro Precision</div>
                <div class="text-2xl font-black text-gray-800 mt-0.5">{{ round($evaluasi['macro_presisi'] * 100, 2) }}%</div>
                <div class="text-[10px] text-gray-500 mt-0.5">Nilai: {{ $evaluasi['macro_presisi'] }}</div>
            </div>
        </div>

        <div class="card p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-100 border border-red-200 flex items-center justify-center text-red-600 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </div>
            <div>
                <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Macro Recall</div>
                <div class="text-2xl font-black text-gray-800 mt-0.5">{{ round($evaluasi['macro_recall'] * 100, 2) }}%</div>
                <div class="text-[10px] text-gray-500 mt-0.5">Nilai: {{ $evaluasi['macro_recall'] }}</div>
            </div>
        </div>

        <div class="card p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-100 border border-red-200 flex items-center justify-center text-red-600 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <div>
                <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Macro F1-Score</div>
                <div class="text-2xl font-black text-gray-800 mt-0.5">{{ round($evaluasi['macro_f1'] * 100, 2) }}%</div>
                <div class="text-[10px] text-gray-500 mt-0.5">Nilai: {{ $evaluasi['macro_f1'] }}</div>
            </div>
        </div>
    </div>

    {{-- Confusion Matrix Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Confusion Matrix 3×3
            </h3>
            <p class="text-xs text-gray-500 mb-4">Matriks kontingensi antara kelas aktual (baris) dan kelas prediksi (kolom)</p>

            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse">
                    <thead>
                        <tr>
                            <th class="p-2 text-xs text-gray-500 font-medium"></th>
                            <th colspan="3" class="p-2 text-xs font-bold text-red-600 border-b border-gray-200 uppercase">
                                Prediksi Model
                            </th>
                        </tr>
                        <tr class="text-[11px] text-gray-500 bg-gray-50">
                            <th class="p-2.5 text-left border-r border-gray-200">Kelas Asli \ Prediksi</th>
                            <th class="p-2.5 text-emerald-400 font-bold">Positif</th>
                            <th class="p-2.5 text-rose-400 font-bold">Negatif</th>
                            <th class="p-2.5 text-amber-400 font-bold">Netral</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#2d2d42] text-xs">
                        @foreach($evaluasi['kelas'] as $actual)
                        <tr>
                            <th class="p-3 text-left font-bold uppercase text-[11px] border-r border-gray-200 {{ $actual === 'positif' ? 'text-emerald-400' : ($actual === 'negatif' ? 'text-rose-400' : 'text-amber-400') }}">
                                {{ $actual }}
                            </th>
                            @foreach($evaluasi['kelas'] as $pred)
                            @php
                            $val = $evaluasi['matrix'][$actual][$pred];
                            $isDiag = ($actual === $pred);
                            $bgColor = $isDiag
                            ? ($val > 0 ? 'bg-emerald-500/20 text-emerald-300 font-black border border-emerald-500/30' : 'bg-red-50 text-gray-500 font-medium')
                            : ($val > 0 ? 'bg-rose-500/10 text-rose-300 font-semibold' : 'bg-gray-50 text-gray-500');
                            @endphp
                            <td class="p-3 {{ $bgColor }} text-sm font-mono transition-all">
                                {{ $val }}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex items-center gap-4 text-[11px] text-gray-500">
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded bg-emerald-500/30 border border-emerald-500/50 inline-block"></span>
                    <span>Diagonal (Prediksi Benar)</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded bg-rose-500/20 border border-rose-500/40 inline-block"></span>
                    <span>Off-Diagonal (Prediksi Salah)</span>
                </div>
            </div>
        </div>

        {{-- Visual Metrics Breakdown --}}
        <div class="card p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Performa Klasifikasi per Kelas
                </h3>
                <p class="text-xs text-gray-500 mb-4">Grafik perbandingan skor F1, Presisi, dan Recall</p>

                <div class="space-y-4">
                    @foreach($evaluasi['kelas'] as $k)
                    @php
                    $m = $evaluasi['metrik'][$k];
                    $f1Pct = round($m['f1'] * 100, 1);
                    $barColor = match($k) {
                    'positif' => 'from-emerald-600 to-teal-500',
                    'negatif' => 'from-rose-600 to-red-500',
                    default => 'from-amber-600 to-yellow-500',
                    };
                    @endphp
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold uppercase tracking-wider {{ $k === 'positif' ? 'text-emerald-400' : ($k === 'negatif' ? 'text-rose-400' : 'text-amber-400') }}">
                                Sentimen {{ $k }} (Support: {{ $m['support'] }})
                            </span>
                            <span class="font-mono font-bold text-gray-700">F1: {{ $f1Pct }}%</span>
                        </div>
                        <div class="w-full h-2.5 rounded-full bg-gray-50 overflow-hidden border border-gray-200">
                            <div class="h-full rounded-full bg-gradient-to-r {{ $barColor }}"></div>
                        </div>
                        <div class="flex items-center justify-between text-[10px] text-gray-500 font-mono">
                            <span>Presisi: {{ round($m['presisi'] * 100, 1) }}% (TP:{{ $m['tp'] }}, FP:{{ $m['fp'] }})</span>
                            <span>Recall: {{ round($m['recall'] * 100, 1) }}% (FN:{{ $m['fn'] }})</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-200 grid grid-cols-2 gap-4 text-xs">
                <div class="p-3 rounded-lg bg-gray-50 border border-gray-200">
                    <div class="text-[10px] text-gray-500 uppercase font-semibold">Weighted Precision</div>
                    <div class="text-base font-bold text-gray-700 mt-0.5">{{ round($evaluasi['weighted_presisi'] * 100, 2) }}%</div>
                </div>
                <div class="p-3 rounded-lg bg-gray-50 border border-gray-200">
                    <div class="text-[10px] text-gray-500 uppercase font-semibold">Weighted F1-Score</div>
                    <div class="text-base font-bold text-gray-700 mt-0.5">{{ round($evaluasi['weighted_f1'] * 100, 2) }}%</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Metrics Table --}}
    <div class="card p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-1">Tabel Klasifikasi & Metrik Lengkap</h3>
        <p class="text-xs text-gray-500 mb-4">Rincian perhitungan True Positive (TP), False Positive (FP), False Negative (FN), Presisi, Recall, dan F1-Score</p>

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-red-50 text-gray-500 uppercase text-[10px] border-b border-gray-200">
                        <th class="py-3 px-4 text-left" style="text-align: left !important;">Kelas Sentimen</th>
                        <th class="py-3 px-4 text-center" style="text-align: center !important;">TP</th>
                        <th class="py-3 px-4 text-center" style="text-align: center !important;">FP</th>
                        <th class="py-3 px-4 text-center" style="text-align: center !important;">FN</th>
                        <th class="py-3 px-4 text-center" style="text-align: center !important;">Precision (Presisi)</th>
                        <th class="py-3 px-4 text-center" style="text-align: center !important;">Recall</th>
                        <th class="py-3 px-4 text-center" style="text-align: center !important;">F1-Score</th>
                        <th class="py-3 px-4 text-center" style="text-align: center !important;">Support (Total Data)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2d2d42]">
                    @foreach($evaluasi['kelas'] as $k)
                    @php $m = $evaluasi['metrik'][$k]; @endphp
                    <tr class="hover:bg-red-50 transition-colors font-mono">
                        <td class="py-3 px-4 font-bold uppercase text-gray-700 font-sans" style="text-align: left !important;">
                            <span class="inline-block w-2 h-2 rounded-full mr-1.5 {{ $k === 'positif' ? 'bg-emerald-400' : ($k === 'negatif' ? 'bg-rose-400' : 'bg-amber-400') }}"></span>
                            {{ $k }}
                        </td>
                        <td class="py-3 px-4 text-center text-gray-600" style="text-align: center !important;">{{ $m['tp'] }}</td>
                        <td class="py-3 px-4 text-center text-gray-600" style="text-align: center !important;">{{ $m['fp'] }}</td>
                        <td class="py-3 px-4 text-center text-gray-600" style="text-align: center !important;">{{ $m['fn'] }}</td>
                        <td class="py-3 px-4 text-center text-emerald-400" style="text-align: center !important;">{{ round($m['presisi'] * 100, 2) }}% ({{ $m['presisi'] }})</td>
                        <td class="py-3 px-4 text-center text-red-600" style="text-align: center !important;">{{ round($m['recall'] * 100, 2) }}% ({{ $m['recall'] }})</td>
                        <td class="py-3 px-4 text-center font-bold text-red-600" style="text-align: center !important;">{{ round($m['f1'] * 100, 2) }}% ({{ $m['f1'] }})</td>
                        <td class="py-3 px-4 text-center text-gray-600" style="text-align: center !important;">{{ $m['support'] }}</td>
                    </tr>
                    @endforeach

                    {{-- Macro Average Row --}}
                    <tr class="bg-gray-50 font-semibold text-gray-700 border-t border-gray-200">
                        <td class="py-3 px-4 font-sans uppercase text-xs" style="text-align: left !important;">Macro Average</td>
                        <td colspan="3" class="py-3 px-4 text-center text-gray-500 font-mono text-[10px]" style="text-align: center !important;">Rata-rata tidak tertimbang</td>
                        <td class="py-3 px-4 text-center font-mono text-emerald-400" style="text-align: center !important;">{{ round($evaluasi['macro_presisi'] * 100, 2) }}%</td>
                        <td class="py-3 px-4 text-center font-mono text-red-600" style="text-align: center !important;">{{ round($evaluasi['macro_recall'] * 100, 2) }}%</td>
                        <td class="py-3 px-4 text-center font-mono text-red-600 font-bold" style="text-align: center !important;">{{ round($evaluasi['macro_f1'] * 100, 2) }}%</td>
                        <td class="py-3 px-4 text-center font-mono text-gray-600" style="text-align: center !important;">{{ $evaluasi['total'] }}</td>
                    </tr>

                    {{-- Weighted Average Row --}}
                    <tr class="bg-gray-50 font-semibold text-gray-700">
                        <td class="py-3 px-4 font-sans uppercase text-xs" style="text-align: left !important;">Weighted Average</td>
                        <td colspan="3" class="py-3 px-4 text-center text-gray-500 font-mono text-[10px]" style="text-align: center !important;">Rata-rata berbobot proporsi</td>
                        <td class="py-3 px-4 text-center font-mono text-emerald-400" style="text-align: center !important;">{{ round($evaluasi['weighted_presisi'] * 100, 2) }}%</td>
                        <td class="py-3 px-4 text-center font-mono text-red-600" style="text-align: center !important;">{{ round($evaluasi['weighted_recall'] * 100, 2) }}%</td>
                        <td class="py-3 px-4 text-center font-mono text-red-600 font-bold" style="text-align: center !important;">{{ round($evaluasi['weighted_f1'] * 100, 2) }}%</td>
                        <td class="py-3 px-4 text-center font-mono text-gray-600" style="text-align: center !important;">{{ $evaluasi['total'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @endif

</div>
@endsection