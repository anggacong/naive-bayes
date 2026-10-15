@extends('layouts.app')

@section('title', 'Pengujian Naive Bayes')
@section('page-title', 'Pengujian Model Naive Bayes')

@section('content')
<div class="space-y-6">

    {{-- Source Switcher Header --}}
    <div class="card p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('naive-bayes.pengujian', ['sumber' => 'x']) }}"
                    class="px-4 py-2 rounded-lg text-xs font-semibold flex items-center gap-2 transition-all {{ $sumber === 'x' ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'bg-gray-50 text-gray-500 hover:text-gray-700 border border-gray-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Pengujian Data X (Twitter)
                </a>
                <a href="{{ route('naive-bayes.pengujian', ['sumber' => 'thread']) }}"
                    class="px-4 py-2 rounded-lg text-xs font-semibold flex items-center gap-2 transition-all {{ $sumber === 'thread' ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'bg-gray-50 text-gray-500 hover:text-gray-700 border border-gray-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                    Pengujian Data Thread
                </a>
            </div>
            <p class="text-xs text-gray-500 mt-2">
                Uji performa model pada <strong class="text-gray-700">20% Data Testing</strong> atau uji coba mandiri kalimat baru secara interaktif.
            </p>
        </div>

        @if($isTrained)
        <form id="form-uji-batch" action="{{ route('naive-bayes.pengujian.batch') }}" method="POST">
            @csrf
            <input type="hidden" name="sumber" value="{{ $sumber }}">
            <input type="hidden" name="random" id="input-random-uji" value="1">
            <div class="flex items-center gap-2">
                {{-- Tombol Uji Acak --}}
                <button type="button" onclick="konfirmasiUjiBatch(1)"
                    class="btn btn-primary bg-gradient-to-r from-red-600 to-red-500 hover:from-red-500 hover:to-red-400 text-white font-medium px-4 py-2 rounded-lg text-xs flex items-center gap-2 shadow-lg shadow-red-600/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Uji Acak (Random)
                </button>
                {{-- Tombol Uji Berurutan --}}
                <button type="button" onclick="konfirmasiUjiBatch(0)"
                    class="btn bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 text-white font-medium px-4 py-2 rounded-lg text-xs flex items-center gap-2 shadow-lg shadow-amber-600/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Uji Berurutan (Tidak Acak)
                </button>
            </div>
        </form>
        @endif
    </div>

    @if(!$isTrained)
    <div class="card p-12 text-center">
        <div class="w-16 h-16 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h3 class="text-base font-semibold text-gray-700">Model Naive Bayes Belum Dilatih</h3>
        <p class="text-xs text-gray-500 mt-1.5 max-w-md mx-auto">
            Silakan latih model terlebih dahulu di menu <strong>Pelatihan Model</strong> sebelum melakukan pengujian data.
        </p>
        <a href="{{ route('naive-bayes.training', ['sumber' => $sumber]) }}" class="btn btn-primary text-xs px-4 py-2 rounded-lg mt-4 inline-block">
            Buka Pelatihan Model
        </a>
    </div>
    @else

    {{-- Live Single-Text Testing Widget --}}
    <div class="card p-6 border border-red-500/30">
        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            Uji Prediksi Teks Baru (Real-time Live Testing)
        </h3>
        <p class="text-xs text-gray-500 mt-1">Masukkan kalimat opini baru tentang Koperasi Merah Putih untuk melihat hasil prediksi sentimen dan skor log-probability.</p>

        <div class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-3">
                <textarea id="live-teks-input" rows="3" placeholder="Contoh: Program Koperasi Merah Putih sangat bermanfaat dan membantu perekonomian warga kecil..."
                    class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 placeholder-gray-400 focus:outline-none focus:border-red-500"></textarea>

                <div class="flex items-center justify-between">
                    <button type="button" onclick="ujiTeksLive()" class="btn btn-primary text-xs px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Prediksi Sentimen
                    </button>
                    <span id="live-status" class="text-xs text-gray-500 italic"></span>
                </div>
            </div>

            {{-- Live Result Display Box --}}
            <div id="live-result-box" class="p-4 rounded-xl bg-gray-50 border border-gray-200 flex flex-col justify-center">
                <div class="text-[11px] text-gray-500 uppercase font-semibold">Hasil Prediksi Sentimen:</div>
                <div id="live-badge" class="mt-2 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-bold w-fit bg-gray-100 text-gray-500 border border-gray-200">
                    Belum ada input
                </div>

                <div id="live-scores" class="mt-4 space-y-1.5 text-xs text-gray-500">
                    <div class="text-[10px] uppercase font-semibold text-gray-500">Log-Probability Skor:</div>
                    <div class="flex justify-between font-mono text-[11px]">
                        <span class="text-emerald-400">Positif:</span> <span id="score-positif">-</span>
                    </div>
                    <div class="flex justify-between font-mono text-[11px]">
                        <span class="text-rose-400">Negatif:</span> <span id="score-negatif">-</span>
                    </div>
                    <div class="flex justify-between font-mono text-[11px]">
                        <span class="text-amber-400">Netral:</span> <span id="score-netral">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Testing Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-red-100 border border-red-200 flex items-center justify-center text-red-600 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <div class="text-[11px] text-gray-500">Total Data Testing (20%)</div>
                <div class="text-xl font-bold text-gray-800 mt-0.5">{{ number_format($totalTesting) }}</div>
            </div>
        </div>

        <div class="card p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-red-100 border border-red-200 flex items-center justify-center text-red-600 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>
            <div>
                <div class="text-[11px] text-gray-500">Data Telah Diuji</div>
                <div class="text-xl font-bold text-gray-800 mt-0.5">{{ number_format($totalDiuji) }} data</div>
            </div>
        </div>

        <div class="card p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <div class="text-[11px] text-gray-500">Prediksi Benar</div>
                <div class="text-xl font-bold text-gray-800 mt-0.5">{{ number_format($totalBenar) }} data</div>
            </div>
        </div>

        <div class="card p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-teal-500/10 border border-teal-500/20 flex items-center justify-center text-teal-400 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                </svg>
            </div>
            <div>
                <div class="text-[11px] text-gray-500">Akurasi Pengujian</div>
                <div class="text-xl font-bold text-emerald-400 mt-0.5">{{ $akurasi }}%</div>
            </div>
        </div>
    </div>

    {{-- Distribusi Hasil Prediksi per Kelas --}}
    @if($totalDiuji > 0)
    <div class="card p-6">
        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
            </svg>
            Distribusi Hasil Prediksi per Kelas
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- Positif --}}
            <div class="p-4 rounded-xl border border-emerald-500/30 bg-emerald-500/5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">Positif</span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 font-mono border border-gray-200">
                        {{ $distribusiPrediksi['positif']['persen'] }}%
                    </span>
                </div>
                <div class="text-3xl font-extrabold text-gray-800 font-mono">
                    {{ number_format($distribusiPrediksi['positif']['jumlah']) }}
                    <span class="text-base font-normal text-gray-500 ml-1">data</span>
                </div>
                <div class="mt-3">
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-emerald-500 h-1.5 rounded-full transition-all duration-700"
                            style="width: {{ $distribusiPrediksi['positif']['persen'] }}%"></div>
                    </div>
                </div>
                <div class="text-[10px] text-gray-500 mt-2">dari {{ number_format($totalDiuji) }} data yang diuji</div>
            </div>

            {{-- Negatif --}}
            <div class="p-4 rounded-xl border border-rose-500/30 bg-rose-500/5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-400">Negatif</span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 font-mono border border-gray-200">
                        {{ $distribusiPrediksi['negatif']['persen'] }}%
                    </span>
                </div>
                <div class="text-3xl font-extrabold text-gray-800 font-mono">
                    {{ number_format($distribusiPrediksi['negatif']['jumlah']) }}
                    <span class="text-base font-normal text-gray-500 ml-1">data</span>
                </div>
                <div class="mt-3">
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-rose-500 h-1.5 rounded-full transition-all duration-700"
                            style="width: {{ $distribusiPrediksi['negatif']['persen'] }}%"></div>
                    </div>
                </div>
                <div class="text-[10px] text-gray-500 mt-2">dari {{ number_format($totalDiuji) }} data yang diuji</div>
            </div>

            {{-- Netral --}}
            <div class="p-4 rounded-xl border border-amber-500/30 bg-amber-500/5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-400">Netral</span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 font-mono border border-gray-200">
                        {{ $distribusiPrediksi['netral']['persen'] }}%
                    </span>
                </div>
                <div class="text-3xl font-extrabold text-gray-800 font-mono">
                    {{ number_format($distribusiPrediksi['netral']['jumlah']) }}
                    <span class="text-base font-normal text-gray-500 ml-1">data</span>
                </div>
                <div class="mt-3">
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-amber-500 h-1.5 rounded-full transition-all duration-700"
                            style="width: {{ $distribusiPrediksi['netral']['persen'] }}%"></div>
                    </div>
                </div>
                <div class="text-[10px] text-gray-500 mt-2">dari {{ number_format($totalDiuji) }} data yang diuji</div>
            </div>

        </div>
    </div>
    @endif

    {{-- Testing Data Table --}}
    <div class="card p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-gray-200">
            <div>
                <h3 class="text-sm font-semibold text-gray-700">Tabel Hasil Pengujian Batch (Data Uji 20%)</h3>
                <p class="text-xs text-gray-500 mt-0.5">Perbandingan label sentimen asli vs label hasil prediksi model Naive Bayes</p>
            </div>
            <form method="GET" action="{{ route('naive-bayes.pengujian') }}" class="flex items-center gap-2">
                <input type="hidden" name="sumber" value="{{ $sumber }}">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari data uji..."
                        class="pl-9 pr-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:border-red-500 focus:bg-white w-48 transition-colors">
                </div>
                <button type="submit" class="btn btn-primary text-xs px-3 py-1.5 rounded-lg">Cari</button>
                @if($search)
                <a href="{{ route('naive-bayes.pengujian', ['sumber' => $sumber]) }}" class="btn btn-outline text-xs px-2.5 py-1.5 rounded-lg">Reset</a>
                @endif
            </form>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-red-50 text-gray-500 uppercase text-[10px] border-b border-gray-200">
                        <th class="py-3 px-4 w-12 text-center" style="text-align: center !important;">No</th>
                        <th class="py-3 px-4 text-left" style="text-align: left !important;">Pengguna</th>
                        <th class="py-3 px-4 text-left" style="text-align: left !important;">Teks Postingan</th>
                        <th class="py-3 px-4 text-center" style="text-align: center !important;">Label Asli</th>
                        <th class="py-3 px-4 text-center" style="text-align: center !important;">Prediksi Model</th>
                        <th class="py-3 px-4 text-center" style="text-align: center !important;">Hasil Evaluasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($testingData as $idx => $row)
                    @php
                    $badgeAsli = match($row->sentimen_asli) {
                    'positif' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                    'negatif' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                    default => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                    };

                    $badgePred = match($row->sentimen_prediksi) {
                    'positif' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                    'negatif' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                    'netral' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                    default => 'bg-gray-100 text-gray-500 border-gray-200',
                    };
                    @endphp
                    <tr class="hover:bg-red-50 transition-colors">
                        <td class="py-3 px-4 text-center text-gray-500 font-mono" style="text-align: center !important;">{{ ($testingData->currentPage() - 1) * $testingData->perPage() + $idx + 1 }}</td>
                        <td class="py-3 px-4 text-left font-semibold text-red-600" style="text-align: left !important;">
                            {{ $row->data_asli && $row->data_asli->username ? '@' . $row->data_asli->username : '-' }}
                        </td>
                        <td class="py-3 px-4 text-left text-gray-600 max-w-md line-clamp-2" style="text-align: left !important;">
                            {{ $row->data_asli->teks_asli ?? '-' }}
                        </td>
                        <td class="py-3 px-4 text-center" style="text-align: center !important;">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $badgeAsli }}">
                                {{ $row->sentimen_asli }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center" style="text-align: center !important;">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $badgePred }}">
                                {{ $row->sentimen_prediksi ?? 'BELUM DIUJI' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center" style="text-align: center !important;">
                            @if($row->sentimen_prediksi === null)
                            <span class="text-[10px] text-gray-500 italic">-</span>
                            @elseif($row->benar)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                BENAR
                            </span>
                            @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30">
                                SALAH
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-gray-500">
                            Belum ada data testing. Latih model terlebih dahulu.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $testingData->links() }}
        </div>
    </div>
    @endif

</div>

<script>
    function konfirmasiUjiBatch(random) {
        const sumberNama = "{{ $sumber === 'x' ? 'Data X (Twitter)' : 'Data Thread' }}";
        const modeLabel = random ? '🔀 <b>Acak (Random)</b>' : '📋 <b>Berurutan (Tidak Acak)</b>';
        const modeTeks = random ?
            'Data uji akan <b>diacak urutannya (shuffle)</b> sebelum dievaluasi.' :
            'Data uji akan diproses secara <b>berurutan (sequential)</b> sesuai urutan aslinya.';
        const konfirmBtn = random ? 'Ya, Uji Acak' : 'Ya, Uji Berurutan';
        const btnClass = random ?
            'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm bg-purple-600 hover:bg-purple-500 text-white shadow-lg shadow-purple-600/30 transition-all duration-200 cursor-pointer hover:scale-[1.02] active:scale-95' :
            'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm bg-cyan-600 hover:bg-cyan-500 text-white shadow-lg shadow-cyan-600/30 transition-all duration-200 cursor-pointer hover:scale-[1.02] active:scale-95';

        // Set hidden input sesuai mode
        document.getElementById('input-random-uji').value = random ? '1' : '0';

        Swal.fire({
            title: 'Jalankan Pengujian Batch ' + sumberNama + '?',
            html: '<p class="text-sm text-gray-600 mb-2">Mode: ' + modeLabel + '</p>' +
                '<p class="text-sm text-gray-600 mb-3">' + modeTeks + ' Sistem akan memprediksi seluruh <b>20% Data Testing</b> dan membandingkan hasil klasifikasi dengan label sentimen aktual.</p>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: konfirmBtn,
            cancelButtonText: 'Tidak',
            buttonsStyling: false,
            background: '#ffffff',
            color: '#1f2937',
            customClass: {
                popup: 'border border-gray-200 rounded-2xl shadow-2xl bg-white text-gray-800 p-6',
                title: 'text-lg font-bold text-gray-900 mb-2',
                htmlContainer: 'text-sm text-gray-600',
                actions: 'flex items-center justify-center gap-3 mt-4',
                confirmButton: btnClass,
                cancelButton: 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm bg-red-600 hover:bg-red-500 text-white shadow-lg shadow-red-600/30 transition-all duration-200 cursor-pointer hover:scale-[1.02] active:scale-95'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Sedang Menguji Data...',
                    html: '<p class="text-sm text-gray-500 mt-2">Mohon tunggu, model sedang memprediksi data uji.</p>',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    background: '#ffffff',
                    color: '#1f2937',
                    customClass: {
                        popup: 'border border-gray-200 rounded-2xl shadow-2xl bg-white text-gray-800 p-6',
                        title: 'text-lg font-bold text-gray-900 mb-2',
                    }
                });
                document.getElementById('form-uji-batch').submit();
            }
        });
    }

    function ujiTeksLive() {
        const teks = document.getElementById('live-teks-input').value.trim();
        if (!teks) {
            Swal.fire({
                title: 'Teks Masih Kosong',
                text: 'Silakan ketikkan kalimat opini yang ingin diprediksi sentimennya.',
                icon: 'warning',
                buttonsStyling: false,
                background: '#ffffff',
                color: '#1f2937',
                customClass: {
                    popup: 'border border-gray-200 rounded-2xl shadow-2xl bg-white text-gray-800 p-6',
                    title: 'text-lg font-bold text-gray-900 mb-2',
                    confirmButton: 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm bg-red-600 hover:bg-red-500 text-white shadow-lg shadow-red-600/30 transition-all duration-200 cursor-pointer'
                }
            });
            return;
        }

        const statusEl = document.getElementById('live-status');
        statusEl.textContent = 'Memproses preprocessing & Naive Bayes...';

        fetch("{{ route('naive-bayes.pengujian.prediksi') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    sumber: "{{ $sumber }}",
                    teks: teks
                })
            })
            .then(res => res.json())
            .then(data => {
                statusEl.textContent = '';
                if (!data.success) {
                    Swal.fire({
                        title: 'Gagal Memprediksi',
                        text: data.message || 'Terjadi kesalahan saat pemrosesan teks.',
                        icon: 'error',
                        buttonsStyling: false,
                        background: '#1e1e2e',
                        color: '#f8fafc',
                        customClass: {
                            popup: 'border border-gray-200 rounded-2xl shadow-2xl bg-white text-gray-800 p-6',
                            title: 'text-lg font-bold text-white mb-2',
                            confirmButton: 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition-all duration-200 cursor-pointer'
                        }
                    });
                    return;
                }

                const badgeEl = document.getElementById('live-badge');
                const pred = data.prediksi;

                badgeEl.textContent = pred.toUpperCase();
                badgeEl.className = 'mt-2 inline-flex items-center gap-2 px-4 py-2 rounded-xl text-base font-extrabold uppercase tracking-wide border shadow-lg ';

                if (pred === 'positif') {
                    badgeEl.className += 'bg-emerald-100 text-emerald-700 border-emerald-300 shadow-emerald-200';
                } else if (pred === 'negatif') {
                    badgeEl.className += 'bg-rose-100 text-rose-700 border-rose-300 shadow-rose-200';
                } else {
                    badgeEl.className += 'bg-amber-100 text-amber-700 border-amber-300 shadow-amber-200';
                }

                document.getElementById('score-positif').textContent = (data.skor.positif !== undefined ? data.skor.positif.toFixed(4) : '-');
                document.getElementById('score-negatif').textContent = (data.skor.negatif !== undefined ? data.skor.negatif.toFixed(4) : '-');
                document.getElementById('score-netral').textContent = (data.skor.netral !== undefined ? data.skor.netral.toFixed(4) : '-');
            })
            .catch(err => {
                statusEl.textContent = '';
                Swal.fire({
                    title: 'Error',
                    text: 'Gagal menghubungi server.',
                    icon: 'error',
                    buttonsStyling: false,
                    background: '#ffffff',
                    color: '#1f2937',
                    customClass: {
                        popup: 'border border-gray-200 rounded-2xl shadow-2xl bg-white text-gray-800 p-6',
                        title: 'text-lg font-bold text-gray-900 mb-2',
                        confirmButton: 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm bg-red-600 hover:bg-red-500 text-white shadow-lg shadow-red-600/30 transition-all duration-200 cursor-pointer'
                    }
                });
            });
    }
</script>
@endsection