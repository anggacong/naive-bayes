@extends('layouts.app')

@section('title', 'Pelatihan Model Naive Bayes')
@section('page-title', 'Pelatihan Model Naive Bayes (Multinomial)')

@section('content')
<div class="space-y-6">

    {{-- Source Switcher & Action Header --}}
    <div class="card p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('naive-bayes.training', ['sumber' => 'x']) }}"
                    class="px-4 py-2 rounded-lg text-xs font-semibold flex items-center gap-2 transition-all {{ $sumber === 'x' ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'bg-gray-50 text-gray-500 hover:text-gray-700 border border-gray-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Model Data X (Twitter)
                </a>
                <a href="{{ route('naive-bayes.training', ['sumber' => 'thread']) }}"
                    class="px-4 py-2 rounded-lg text-xs font-semibold flex items-center gap-2 transition-all {{ $sumber === 'thread' ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'bg-gray-50 text-gray-500 hover:text-gray-700 border border-gray-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                    Model Data Thread
                </a>
            </div>
            <p class="text-xs text-gray-500 mt-2">
                Data latih dibagi dengan rasio <strong class="text-gray-700">80% Training Data</strong> dan <strong class="text-gray-700">20% Testing Data</strong> menggunakan <strong class="text-gray-700">Laplace Smoothing (α = 1)</strong>.
                Pilih mode pembagian: <span class="text-red-600 font-semibold">Acak (Random)</span> atau <span class="text-amber-400 font-semibold">Berurutan (Tidak Acak)</span>.
            </p>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            {{-- Form utama latih --}}
            <form id="form-latih-nb" action="{{ route('naive-bayes.training.latih') }}" method="POST">
                @csrf
                <input type="hidden" name="sumber" value="{{ $sumber }}">
                <input type="hidden" name="random" id="input-random-latih" value="1">
                <div class="flex items-center gap-2">
                    {{-- Tombol Acak (Random) --}}
                    <button type="button" onclick="konfirmasiLatih(1)"
                        class="btn btn-primary bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-medium px-4 py-2 rounded-lg text-xs flex items-center gap-2 shadow-lg shadow-emerald-600/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        {{ $isTrained ? 'Latih Ulang (Acak)' : 'Latih Acak (Random)' }}
                    </button>
                    {{-- Tombol Tidak Acak (Berurutan) --}}
                    <button type="button" onclick="konfirmasiLatih(0)"
                        class="btn bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 text-white font-medium px-4 py-2 rounded-lg text-xs flex items-center gap-2 shadow-lg shadow-amber-600/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        {{ $isTrained ? 'Latih Ulang (Berurutan)' : 'Latih Berurutan (Tidak Acak)' }}
                    </button>
                </div>
            </form>

            @if($isTrained)
            <form id="form-reset-nb" action="{{ route('naive-bayes.training.reset') }}" method="POST">
                @csrf
                <input type="hidden" name="sumber" value="{{ $sumber }}">
                <button type="button" onclick="konfirmasiReset()" class="btn bg-red-100 hover:bg-red-600 text-red-700 hover:text-white border border-red-300 text-xs px-4 py-2 rounded-lg font-medium transition-all">
                    Reset Model
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <div class="card p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-red-100 border border-red-200 flex items-center justify-center text-red-600 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <div class="text-[11px] text-gray-500">Data Training (80%)</div>
                <div class="text-xl font-bold text-gray-800 mt-0.5">{{ number_format($totalTraining) }} data</div>
            </div>
        </div>

        <div class="card p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-red-100 border border-red-200 flex items-center justify-center text-red-600 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <div class="text-[11px] text-gray-500">Data Testing (20%)</div>
                <div class="text-xl font-bold text-gray-800 mt-0.5">{{ number_format($totalTesting) }} data</div>
            </div>
        </div>

        <div class="card p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div>
                <div class="text-[11px] text-gray-500">Vocabulary Size (|V|)</div>
                <div class="text-xl font-bold text-gray-800 mt-0.5">{{ number_format($totalVocab) }} kata</div>
            </div>
        </div>

        <div class="card p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <div class="text-[11px] text-gray-500">Laplace Smoothing</div>
                <div class="text-xl font-bold text-gray-800 mt-0.5">α = 1</div>
            </div>
        </div>
    </div>

    @if(!$isTrained)
    <div class="card p-12 text-center">
        <div class="w-16 h-16 rounded-2xl bg-red-100 border border-red-200 flex items-center justify-center text-red-600 mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
        </div>
        <h3 class="text-base font-semibold text-gray-700">Model Naive Bayes Belum Dilatih</h3>
        <p class="text-xs text-gray-500 mt-1.5 max-w-md mx-auto">
            Klik tombol <strong>"Latih Model"</strong> di atas untuk memproses data training, menghitung Prior Probability P(c) dan Likelihood P(w|c).
        </p>
    </div>
    @else

    {{-- Prior Probability Table --}}
    <div class="card p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            Prior Probability — P(Kelas)
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($prior as $p)
            @php
            $color = match($p->sentimen) {
            'positif' => 'border-emerald-500/30 bg-emerald-500/5 text-emerald-400',
            'negatif' => 'border-rose-500/30 bg-rose-500/5 text-rose-400',
            default => 'border-amber-500/30 bg-amber-500/5 text-amber-400',
            };
            @endphp
            <div class="p-4 rounded-xl border {{ $color }}">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider">{{ $p->sentimen }}</span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 font-mono border border-gray-200">
                        log: {{ round($p->nilai_log, 4) }}
                    </span>
                </div>
                <div class="mt-2 text-2xl font-bold text-gray-800 font-mono">
                    {{ round(exp($p->nilai_log) * 100, 2) }}%
                </div>
                <div class="text-[10px] text-gray-500 mt-1">
                    P({{ $p->sentimen }}) = {{ round(exp($p->nilai_log), 6) }}
                </div>
                @php $docCount = $jumlahPerKelas[$p->sentimen] ?? 0; @endphp
                <div class="mt-2 pt-2 border-t border-current border-opacity-20 flex items-center justify-between">
                    <span class="text-[10px] text-gray-500">Jumlah Dokumen</span>
                    <span class="text-xs font-bold font-mono text-gray-800">
                        {{ $docCount }} <span class="text-[10px] text-gray-500 font-normal">/ {{ $totalTraining }}</span>
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Likelihood (Conditional Probability) Table --}}
    <div class="card p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-gray-200">
            <div>
                <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Conditional Probability — log P(Kata | Kelas)
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Probabilitas kemunculan kata pada tiap kelas dengan Laplace Smoothing</p>
            </div>

            <form action="{{ route('naive-bayes.training') }}" method="GET" class="flex items-center gap-2">
                <input type="hidden" name="sumber" value="{{ $sumber }}">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari kata..."
                    class="px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-700 placeholder-gray-400 focus:outline-none focus:border-red-500 w-48">
                <button type="submit" class="btn btn-primary text-xs px-3 py-1.5 rounded-lg">Cari</button>
                @if($search)
                <a href="{{ route('naive-bayes.training', ['sumber' => $sumber]) }}" class="text-xs text-gray-500 hover:text-gray-700">Reset</a>
                @endif
            </form>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-xs border-collapse">
                <thead>
                    <tr class="bg-red-50 text-gray-500 uppercase text-[10px] border-b border-gray-200">
                        <th class="py-3 px-4 w-12 text-center" style="text-align: center !important;">No</th>
                        <th class="py-3 px-4 text-left" style="text-align: left !important;">Kata (Vocabulary)</th>
                        <th class="py-3 px-4 text-center text-emerald-400" style="text-align: center !important;">log P(w | Positif)</th>
                        <th class="py-3 px-4 text-center text-rose-400" style="text-align: center !important;">log P(w | Negatif)</th>
                        <th class="py-3 px-4 text-center text-amber-400" style="text-align: center !important;">log P(w | Netral)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($paginator as $idx => $row)
                    <tr class="hover:bg-red-50 transition-colors">
                        <td class="py-2.5 px-4 text-center text-gray-500 font-mono" style="text-align: center !important;">{{ ($paginator->currentPage() - 1) * $paginator->perPage() + $idx + 1 }}</td>
                        <td class="py-2.5 px-4 text-left font-semibold text-gray-700 font-mono" style="text-align: left !important;">{{ $row->kata }}</td>
                        <td class="py-2.5 px-4 text-center text-emerald-400 font-mono" style="text-align: center !important;">{{ $row->positif !== null ? round($row->positif, 6) : '-' }}</td>
                        <td class="py-2.5 px-4 text-center text-rose-400 font-mono" style="text-align: center !important;">{{ $row->negatif !== null ? round($row->negatif, 6) : '-' }}</td>
                        <td class="py-2.5 px-4 text-center text-amber-400 font-mono" style="text-align: center !important;">{{ $row->netral !== null ? round($row->netral, 6) : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-gray-500" style="text-align: center !important;">
                            Tidak ada kata yang sesuai dengan pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $paginator->links() }}
        </div>
    </div>
    @endif

</div>

<script>
    function konfirmasiLatih(random) {
        const sumberNama = "{{ $sumber === 'x' ? 'Data X (Twitter)' : 'Data Thread' }}";
        const modeLabel  = random ? '🔀 <b>Acak (Random)</b>' : '📋 <b>Berurutan (Tidak Acak)</b>';
        const modeTeks   = random
            ? 'Data akan <b>diacak terlebih dahulu (shuffle)</b> sebelum dibagi 80% Training dan 20% Testing.'
            : 'Data akan dibagi secara <b>berurutan (sequential)</b> tanpa diacak — 80% pertama sebagai Training dan 20% sisanya sebagai Testing.';
        const konfirmBtn = random
            ? 'Ya, Latih Acak'
            : 'Ya, Latih Berurutan';
        const btnClass   = random
            ? 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-600/30 transition-all duration-200 cursor-pointer hover:scale-[1.02] active:scale-95'
            : 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm bg-amber-600 hover:bg-amber-500 text-white shadow-lg shadow-amber-600/30 transition-all duration-200 cursor-pointer hover:scale-[1.02] active:scale-95';

        // Set hidden input sesuai mode
        document.getElementById('input-random-latih').value = random ? '1' : '0';

        Swal.fire({
            title: 'Latih Model ' + sumberNama + '?',
            html: '<p class="text-sm text-gray-600 mb-2">Mode: ' + modeLabel + '</p>' +
                  '<p class="text-sm text-gray-600 mb-3">' + modeTeks + ' Parameter Prior Probability dan Likelihood (Laplace Smoothing &alpha; = 1) akan dihitung ulang.</p>',
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
                    title: 'Sedang Melatih Model...',
                    html: '<p class="text-sm text-gray-500 mt-2">Mohon tunggu, pembagian split data dan probabilitas Naive Bayes sedang dihitung.</p>',
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
                document.getElementById('form-latih-nb').submit();
            }
        });
    }

    function konfirmasiReset() {
        const sumberNama = "{{ $sumber === 'x' ? 'Data X (Twitter)' : 'Data Thread' }}";
        Swal.fire({
            title: 'Reset Model Naive Bayes ' + sumberNama + '?',
            text: 'Semua parameter model pelatihan dan hasil pengujian untuk ' + sumberNama + ' akan dihapus. Anda dapat melatihnya kembali kapan saja.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Reset',
            cancelButtonText: 'Tidak',
            buttonsStyling: false,
            background: '#ffffff',
            color: '#1f2937',
            customClass: {
                popup: 'border border-gray-200 rounded-2xl shadow-2xl bg-white text-gray-800 p-6',
                title: 'text-lg font-bold text-gray-900 mb-2',
                htmlContainer: 'text-sm text-gray-600',
                actions: 'flex items-center justify-center gap-3 mt-4',
                confirmButton: 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-600/30 transition-all duration-200 cursor-pointer hover:scale-[1.02] active:scale-95',
                cancelButton: 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm bg-red-600 hover:bg-red-500 text-white shadow-lg shadow-red-600/30 transition-all duration-200 cursor-pointer hover:scale-[1.02] active:scale-95'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-reset-nb').submit();
            }
        });
    }
</script>
@endsection