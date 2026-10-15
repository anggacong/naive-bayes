@extends('layouts.app')

@section('title', 'Preprocessing Data Thread')
@section('page-title', 'Preprocessing Data Thread')
@section('breadcrumb')
    <span class="current">Preprocessing Data Thread</span>
@endsection

@section('content')

{{-- ─── Status Bar ─── --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="card-stat violet">
        <div class="stat-icon bg-violet-500/15 text-red-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <div>
            <div class="text-xl font-extrabold text-white">{{ $totalData }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Total Data Thread</div>
        </div>
    </div>
    <div class="card-stat green">
        <div class="stat-icon bg-emerald-500/15 text-emerald-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <div class="text-xl font-extrabold text-white">{{ $sudahDiproses }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Sudah Diproses</div>
        </div>
    </div>
    <div class="card-stat amber">
        <div class="stat-icon bg-amber-500/15 text-amber-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <div class="text-xl font-extrabold text-white">{{ $totalData - $sudahDiproses }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Belum Diproses</div>
        </div>
    </div>
    <div class="card-stat cyan">
        <div class="stat-icon bg-cyan-500/15 text-orange-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
        </div>
        <div>
            <div class="text-xl font-extrabold text-white">7</div>
            <div class="text-xs text-gray-500 mt-0.5">Tahap Proses</div>
        </div>
    </div>
</div>

{{-- ─── Action Bar ─── --}}
<div class="card mb-6">
    <div class="flex flex-col md:flex-row md:items-center gap-4">
        <div class="flex-1">
            <h2 class="text-base font-bold text-white mb-1">Pipeline Preprocessing</h2>
            <div class="flex flex-wrap gap-2 mt-3">
                @foreach(['1. Cleaning','2. Case Folding','3. Tokenizing','4. Repeated Char Removal','5. Normalisasi','6. Stopword Removal','7. Stemming'] as $i => $step)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold"
                          style="background: rgba(139,92,246,{{ 0.08 + ($i * 0.02) }}); color: #c4b5fd; border: 1px solid rgba(139,92,246,0.2)">
                        {{ $step }}
                    </span>
                @endforeach
            </div>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0">
            @if($totalData > 0)
                <form id="formProsesThread" action="{{ route('preprocessing.thread.proses') }}" method="POST">
                    @csrf
                    <button type="button" class="btn btn-success" onclick="konfirmasiProsesThread()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Jalankan Preprocessing
                    </button>
                </form>
            @endif
            @if($sudahDiproses > 0)
                <form id="formResetThread" action="{{ route('preprocessing.thread.reset') }}" method="POST">
                    @csrf
                    <button type="button" class="btn btn-warning btn-sm" onclick="konfirmasiResetThread()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

{{-- ─── Search & Filter Bar ─── --}}
<div class="card mb-6 p-4">
    <form method="GET" action="{{ route('preprocessing.thread') }}" class="flex flex-col md:flex-row items-center gap-3">
        <div class="relative flex-1 w-full">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari teks asli, username, atau hasil kata praproses..."
                   class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:border-red-500 focus:bg-white transition-colors">
        </div>

        <div class="flex items-center gap-2 w-full md:w-auto">
            <select name="sentimen" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:border-red-500 focus:bg-white transition-colors">
                <option value="">Semua Sentimen</option>
                <option value="positif" {{ request('sentimen') === 'positif' ? 'selected' : '' }}>Positif</option>
                <option value="negatif" {{ request('sentimen') === 'negatif' ? 'selected' : '' }}>Negatif</option>
                <option value="netral" {{ request('sentimen') === 'netral' ? 'selected' : '' }}>Netral</option>
            </select>

            <select name="status" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:border-red-500 focus:bg-white transition-colors">
                <option value="">Semua Status</option>
                <option value="sudah" {{ request('status') === 'sudah' ? 'selected' : '' }}>Sudah Diproses</option>
                <option value="belum" {{ request('status') === 'belum' ? 'selected' : '' }}>Belum Diproses</option>
            </select>

            <button type="submit" class="btn btn-primary text-xs px-4 py-2 rounded-xl flex items-center gap-1.5 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Cari
            </button>

            @if(request()->hasAny(['q', 'sentimen', 'status']))
            <a href="{{ route('preprocessing.thread') }}" class="btn btn-outline text-xs px-3 py-2 rounded-xl text-gray-500 hover:text-gray-700 shrink-0" title="Reset Pencarian">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Reset
            </a>
            @endif
        </div>
    </form>
</div>

{{-- ─── Preprocessing Table ─── --}}
@if($totalData === 0)
    <div class="card text-center py-16">
        <div class="flex justify-center mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
        </div>
        <p class="text-gray-500 text-sm">Belum ada data Thread. <a href="{{ route('data-thread.create') }}" class="text-red-600 underline">Tambahkan data Thread terlebih dahulu.</a></p>
    </div>
@elseif($data->count() === 0)
    <div class="card text-center py-16">
        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 mx-auto mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <h3 class="text-sm font-semibold text-gray-700">Data Tidak Ditemukan</h3>
        <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">Tidak ada data preprocessing Thread yang cocok dengan kata kunci atau filter pencarian Anda.</p>
        <a href="{{ route('preprocessing.thread') }}" class="btn btn-primary text-xs px-4 py-2 rounded-lg mt-4 inline-flex items-center gap-1.5">
            Reset Pencarian
        </a>
    </div>
@else
    <div class="card p-0 overflow-hidden">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-500 font-medium inline-flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 01-2 2h-4a2 2 0 01-2-2v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    Klik teks untuk melihat detail lengkap
                </span>
                <span class="text-xs text-gray-500">|</span>
                <span class="text-xs text-gray-500">Scroll horizontal untuk melihat semua tahap</span>
            </div>
            @if(request()->hasAny(['q', 'sentimen', 'status']))
                <span class="text-xs text-red-600 font-medium bg-red-100 px-2.5 py-1 rounded-lg border border-red-200">
                    Menampilkan {{ $data->total() }} hasil pencarian
                </span>
            @endif
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th class="w-10 sticky left-0 bg-gray-50 z-10">No</th>
                        <th class="min-w-[140px]">Teks Asli</th>
                        <th>
                            <div class="step-header">
                                <span class="step-number">1</span> Cleaning
                            </div>
                        </th>
                        <th>
                            <div class="step-header">
                                <span class="step-number">2</span> Case Folding
                            </div>
                        </th>
                        <th>
                            <div class="step-header">
                                <span class="step-number">3</span> Tokenizing
                            </div>
                        </th>
                        <th>
                            <div class="step-header">
                                <span class="step-number">4</span> Repeated Char
                            </div>
                        </th>
                        <th>
                            <div class="step-header">
                                <span class="step-number">5</span> Normalisasi
                            </div>
                        </th>
                        <th>
                            <div class="step-header">
                                <span class="step-number">6</span> Stopword Removal
                            </div>
                        </th>
                        <th>
                            <div class="step-header">
                                <span class="step-number">7</span> Stemming
                            </div>
                        </th>
                        <th>Sentimen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $item)
                        @php $prep = $item->preprocessing; @endphp
                        <tr>
                            <td class="text-gray-500 text-sm text-center sticky left-0 bg-white">
                                {{ $data->firstItem() + $index }}
                            </td>
                            <td class="prep-cell">
                                <div class="prep-text"
                                     onclick="openModal('Teks Asli #{{ $item->id }}', {{ json_encode($item->teks_asli) }})">
                                    @if($item->username)
                                        <span class="text-[11px] text-red-600 font-semibold block mb-0.5">{{ '@' . $item->username }}</span>
                                    @endif
                                    {{ Str::limit($item->teks_asli, 80) }}
                                </div>
                            </td>

                            @if($prep)
                                <td class="prep-cell">
                                    <div class="prep-text"
                                         onclick="openModal('Cleaning #{{ $item->id }}', {{ json_encode($prep->hasil_cleaning) }})">
                                        {{ Str::limit($prep->hasil_cleaning, 80) ?: '(kosong)' }}
                                    </div>
                                </td>
                                <td class="prep-cell">
                                    <div class="prep-text"
                                         onclick="openModal('Case Folding #{{ $item->id }}', {{ json_encode($prep->hasil_case_folding) }})">
                                        {{ Str::limit($prep->hasil_case_folding, 80) ?: '(kosong)' }}
                                    </div>
                                </td>
                                <td class="prep-cell">
                                    @php $tokens = $prep->hasil_tokenizing ?? []; @endphp
                                    <div class="token-list"
                                         style="cursor:pointer"
                                         onclick="openModal('Tokenizing #{{ $item->id }}', {{ json_encode(implode(' | ', $tokens)) }})">
                                        @foreach(array_slice($tokens, 0, 5) as $tok)
                                            <span class="token-item">{{ $tok }}</span>
                                        @endforeach
                                        @if(count($tokens) > 5)
                                            <span class="token-item" style="background:rgba(148,163,184,0.1); color:#64748b">
                                                +{{ count($tokens) - 5 }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-gray-500 mt-1">{{ count($tokens) }} token</div>
                                </td>
                                <td class="prep-cell">
                                    <div class="prep-text"
                                         onclick="openModal('Repeated Char Removal #{{ $item->id }}', {{ json_encode($prep->hasil_repeated_char) }})">
                                        {{ Str::limit($prep->hasil_repeated_char, 80) ?: '(kosong)' }}
                                    </div>
                                </td>
                                <td class="prep-cell">
                                    <div class="prep-text"
                                         onclick="openModal('Normalisasi #{{ $item->id }}', {{ json_encode($prep->hasil_normalisasi) }})">
                                        {{ Str::limit($prep->hasil_normalisasi, 80) ?: '(kosong)' }}
                                    </div>
                                </td>
                                <td class="prep-cell">
                                    <div class="prep-text"
                                         onclick="openModal('Stopword Removal #{{ $item->id }}', {{ json_encode($prep->hasil_stopword) }})">
                                        {{ Str::limit($prep->hasil_stopword, 80) ?: '(kosong)' }}
                                    </div>
                                </td>
                                <td class="prep-cell">
                                    <div class="prep-text"
                                         onclick="openModal('Stemming (Hasil Akhir) #{{ $item->id }}', {{ json_encode($prep->hasil_stemming) }})">
                                        {{ Str::limit($prep->hasil_stemming, 80) ?: '(kosong)' }}
                                    </div>
                                </td>
                            @else
                                @for($s = 0; $s < 7; $s++)
                                    <td class="text-center">
                                        <span class="text-gray-700 text-xs italic">belum diproses</span>
                                    </td>
                                @endfor
                            @endif

                            <td>
                                <span class="badge badge-{{ $item->sentimen }}">
                                    @if($item->sentimen === 'positif')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @elseif($item->sentimen === 'negatif')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10h.01M15 10h.01M9 15h6M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @endif
                                    {{ $item->sentimen }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($data->hasPages())
            <div class="p-4 border-t border-gray-200 flex items-center justify-between">
                <span class="text-xs text-gray-500">
                    Menampilkan {{ $data->firstItem() }}–{{ $data->lastItem() }} dari {{ $data->total() }} data
                </span>
                <div class="pagination">
                    @if ($data->onFirstPage())
                        <span class="page-link opacity-40">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </span>
                    @else
                        <a href="{{ $data->previousPageUrl() }}" class="page-link">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                    @endif
                    @foreach ($data->getUrlRange(max(1,$data->currentPage()-2), min($data->lastPage(),$data->currentPage()+2)) as $page => $url)
                        <a href="{{ $url }}" class="page-link {{ $data->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
                    @endforeach
                    @if ($data->hasMorePages())
                        <a href="{{ $data->nextPageUrl() }}" class="page-link">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @else
                        <span class="page-link opacity-40">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endif

<script>
    function konfirmasiProsesThread() {
        Swal.fire({
            title: 'Jalankan Preprocessing Data Thread?',
            html: '<p class="text-sm text-gray-600 mb-3">Sistem akan memproses <b>{{ $totalData }} data Thread</b> melalui 7 tahapan preprocessing:</p>' +
                  '<div class="text-xs text-violet-300 bg-gray-100 p-3.5 rounded-xl border border-gray-200 text-left leading-relaxed mb-2">' +
                  '1. Cleaning &bull; 2. Case Folding &bull; 3. Tokenizing<br>' +
                  '4. Repeated Char Removal &bull; 5. Normalisasi<br>' +
                  '6. Stopword Removal &bull; 7. Stemming</div>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Jalankan',
            cancelButtonText: 'Tidak',
            buttonsStyling: false,
            background: '#1e1e2e',
            color: '#f8fafc',
            customClass: {
                popup: 'border border-gray-200 rounded-2xl shadow-2xl bg-white text-gray-800 p-6',
                title: 'text-lg font-bold text-white mb-2',
                htmlContainer: 'text-sm text-gray-600',
                actions: 'flex items-center justify-center gap-3 mt-4',
                confirmButton: 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-600/30 transition-all duration-200 cursor-pointer hover:scale-[1.02] active:scale-95',
                cancelButton: 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm bg-red-600 hover:bg-red-500 text-white shadow-lg shadow-red-600/30 transition-all duration-200 cursor-pointer hover:scale-[1.02] active:scale-95'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Sedang Memproses Preprocessing...',
                    html: '<p class="text-sm text-gray-500 mt-2">Mohon tunggu, 7 tahapan praproses sedang dijalankan untuk seluruh data Thread.</p>',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    background: '#1e1e2e',
                    color: '#f8fafc',
                    customClass: {
                        popup: 'border border-gray-200 rounded-2xl shadow-2xl bg-white text-gray-800 p-6',
                        title: 'text-lg font-bold text-white mb-2',
                    }
                });
                document.getElementById('formProsesThread').submit();
            }
        });
    }

    function konfirmasiResetThread() {
        Swal.fire({
            title: 'Reset Preprocessing Data Thread?',
            text: 'Hasil 7 tahapan preprocessing Data Thread akan dihapus. Anda dapat menjalankannya kembali kapan saja.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Reset',
            cancelButtonText: 'Tidak',
            buttonsStyling: false,
            background: '#1e1e2e',
            color: '#f8fafc',
            customClass: {
                popup: 'border border-gray-200 rounded-2xl shadow-2xl bg-white text-gray-800 p-6',
                title: 'text-lg font-bold text-white mb-2',
                htmlContainer: 'text-sm text-gray-600',
                actions: 'flex items-center justify-center gap-3 mt-4',
                confirmButton: 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-600/30 transition-all duration-200 cursor-pointer hover:scale-[1.02] active:scale-95',
                cancelButton: 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm bg-red-600 hover:bg-red-500 text-white shadow-lg shadow-red-600/30 transition-all duration-200 cursor-pointer hover:scale-[1.02] active:scale-95'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formResetThread').submit();
            }
        });
    }
</script>

@endsection
