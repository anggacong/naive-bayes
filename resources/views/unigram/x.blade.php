@extends('layouts.app')

@section('title', 'Unigram (TF-IDF) Data X')
@section('page-title', 'Pembobotan Unigram (TF-IDF) Data X (Twitter)')

@section('content')
<div class="space-y-6">

    {{-- Stats Banner --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <div class="text-xs text-slate-400">Total Dokumen Preprocessed</div>
                <div class="text-2xl font-bold text-slate-100 mt-0.5">{{ number_format($totalDokumen) }}</div>
            </div>
        </div>

        <div class="card p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                </svg>
            </div>
            <div>
                <div class="text-xs text-slate-400">Total Token Unigram (TF-IDF)</div>
                <div class="text-2xl font-bold text-slate-100 mt-0.5">{{ number_format($totalUnigram) }}</div>
            </div>
        </div>

        <div class="card p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <div>
                <div class="text-xs text-slate-400">Kata Unik (Vocabulary)</div>
                <div class="text-2xl font-bold text-slate-100 mt-0.5">{{ number_format($totalVocab) }}</div>
            </div>
        </div>
    </div>

    {{-- Main Container --}}
    <div class="card p-6">
        {{-- Header & Action Buttons --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-[#3f3f5a]">
            <div>
                <h2 class="text-lg font-semibold text-slate-100">Ekstraksi Fitur Unigram & TF-IDF</h2>
                <p class="text-xs text-slate-400 mt-1">Pembobotan kata Unigram menggunakan metode Term Frequency-Inverse Document Frequency (TF-IDF)</p>
            </div>

            <div class="flex items-center gap-3">
                <form id="form-hitung-tfidf" action="{{ route('unigram.x.hitung') }}" method="POST">
                    @csrf
                    <button type="button" onclick="konfirmasiHitung()" class="btn btn-primary bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-medium px-4 py-2 rounded-lg text-xs flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Hitung TF-IDF
                    </button>
                </form>

                @if($totalUnigram > 0)
                <form id="form-reset-tfidf" action="{{ route('unigram.x.reset') }}" method="POST">
                    @csrf
                    <button type="button" onclick="konfirmasiReset()" class="btn bg-rose-600/20 hover:bg-rose-600 text-rose-300 hover:text-white border border-rose-500/30 text-xs px-4 py-2 rounded-lg font-medium">
                        Reset TF-IDF
                    </button>
                </form>
                @endif
            </div>
        </div>

        {{-- Navigation Tabs --}}
        <div class="flex items-center justify-between gap-4 mt-6">
            <div class="flex items-center gap-2 border-b border-[#3f3f5a] w-full">
                <a href="{{ route('unigram.x', ['tab' => 'dokumen']) }}"
                   class="px-4 py-2.5 text-xs font-semibold border-b-2 flex items-center gap-2 transition-colors {{ $tab === 'dokumen' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Tampilan Per Dokumen
                </a>
                <a href="{{ route('unigram.x', ['tab' => 'vocab']) }}"
                   class="px-4 py-2.5 text-xs font-semibold border-b-2 flex items-center gap-2 transition-colors {{ $tab === 'vocab' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Vocabulary Global TF-IDF
                </a>
            </div>
        </div>

        {{-- Search Toolbar --}}
        <div class="mt-4 flex items-center justify-between">
            <form action="{{ route('unigram.x') }}" method="GET" class="flex items-center gap-2">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <input type="text" name="search" value="{{ $search }}" placeholder="{{ $tab === 'dokumen' ? 'Cari teks atau @username...' : 'Cari kata dalam vocabulary...' }}"
                       class="px-3 py-1.5 bg-[#1a1a27] border border-[#3f3f5a] rounded-lg text-xs text-slate-200 placeholder-slate-500 focus:outline-none focus:border-indigo-500 w-64">
                <button type="submit" class="btn btn-primary text-xs px-3 py-1.5 rounded-lg">Cari</button>
                @if($search)
                    <a href="{{ route('unigram.x', ['tab' => $tab]) }}" class="text-xs text-slate-400 hover:text-slate-200">Reset</a>
                @endif
            </form>
        </div>

        {{-- TAB 1: PER DOKUMEN (Tabel Terstruktur Rapi) --}}
        @if($tab === 'dokumen')
        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#1c1c2b] text-slate-400 uppercase text-[10px] border-b border-[#3f3f5a]">
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4 w-36">Pengguna</th>
                        <th class="py-3 px-4 min-w-[280px]">Teks Asli & Hasil Stemming</th>
                        <th class="py-3 px-4 w-28 text-center">Sentimen</th>
                        <th class="py-3 px-4 min-w-[340px]">Token & Bobot TF-IDF (Kata : Bobot)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2d2d42]">
                    @forelse($dataDokumen as $index => $item)
                    @php
                        $badge = match($item->sentimen) {
                            'positif' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                            'negatif' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                            default   => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                        };
                    @endphp
                    <tr class="hover:bg-[#1f1f30] transition-colors align-top">
                        <td class="py-3.5 px-4 text-center text-slate-400 font-mono">
                            {{ $dataDokumen->firstItem() + $index }}
                        </td>

                        <td class="py-3.5 px-4">
                            <span class="font-semibold text-indigo-400 block">
                                {{ $item->username ? '@' . $item->username : '-' }}
                            </span>
                            <span class="text-[10px] text-slate-500 mt-0.5 block">
                                {{ $item->tanggal ? $item->tanggal->format('d/m/Y') : '-' }}
                            </span>
                        </td>

                        <td class="py-3.5 px-4 space-y-2">
                            <div>
                                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Teks Asli:</span>
                                <p class="text-slate-300 leading-relaxed text-xs">{{ $item->teks_asli }}</p>
                            </div>
                            <div class="p-2 rounded-lg bg-[#161622] border border-[#2d2d42]">
                                <span class="text-[10px] font-semibold text-indigo-400 uppercase tracking-wider block mb-0.5">Hasil Stemming:</span>
                                <p class="font-mono text-slate-200 text-xs">{{ $item->preprocessing->hasil_stemming ?? '-' }}</p>
                            </div>
                        </td>

                        <td class="py-3.5 px-4 text-center">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border inline-block {{ $badge }}">
                                {{ $item->sentimen ?? 'netral' }}
                            </span>
                            <div class="text-[10px] text-slate-500 mt-1">
                                {{ $item->unigram->count() }} token
                            </div>
                        </td>

                        <td class="py-3.5 px-4">
                            @if($item->unigram->isEmpty())
                                <span class="text-xs text-amber-400 italic">Belum dihitung TF-IDF.</span>
                            @else
                                <div class="flex flex-wrap gap-1.5 max-h-48 overflow-y-auto pr-1">
                                    @foreach($item->unigram as $u)
                                    <div class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-[#252538] border border-[#3f3f5a] text-xs hover:border-indigo-500/50 transition-colors"
                                         title="TF: {{ round($u->tf, 4) }} | DF: {{ $u->df }} | IDF: {{ round($u->idf, 4) }}">
                                        <span class="font-semibold text-slate-200">{{ $u->kata }}</span>
                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-indigo-500/20 text-indigo-300 font-mono font-bold">
                                            {{ round($u->tfidf, 4) }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-slate-400">
                            Belum ada data preprocessing yang siap dihitung.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-6">
                {{ $dataDokumen->links() }}
            </div>
        </div>
        @endif

        {{-- TAB 2: GLOBAL VOCABULARY --}}
        @if($tab === 'vocab')
        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#1c1c2b] text-slate-400 uppercase text-[10px] border-b border-[#3f3f5a]">
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Kata / Term (Unigram)</th>
                        <th class="py-3 px-4 text-center">DF (Doc Freq)</th>
                        <th class="py-3 px-4 text-center">IDF</th>
                        <th class="py-3 px-4 text-right">TF-IDF (Positif)</th>
                        <th class="py-3 px-4 text-right">TF-IDF (Negatif)</th>
                        <th class="py-3 px-4 text-right">TF-IDF (Netral)</th>
                        <th class="py-3 px-4 text-right font-bold text-indigo-400">Total TF-IDF</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2d2d42]">
                    @forelse($vocabulary as $idx => $v)
                    <tr class="hover:bg-[#1f1f30] transition-colors">
                        <td class="py-2.5 px-4 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                        <td class="py-2.5 px-4 font-semibold text-slate-200">{{ $v->kata }}</td>
                        <td class="py-2.5 px-4 text-center text-slate-300 font-mono">{{ $v->df }}</td>
                        <td class="py-2.5 px-4 text-center text-slate-300 font-mono">{{ $v->idf }}</td>
                        <td class="py-2.5 px-4 text-right text-emerald-400 font-mono">{{ round($v->tfidf_positif, 4) }}</td>
                        <td class="py-2.5 px-4 text-right text-rose-400 font-mono">{{ round($v->tfidf_negatif, 4) }}</td>
                        <td class="py-2.5 px-4 text-right text-amber-400 font-mono">{{ round($v->tfidf_netral, 4) }}</td>
                        <td class="py-2.5 px-4 text-right font-bold text-indigo-400 font-mono">{{ round($v->total_tfidf, 4) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-12 text-slate-400">
                            Belum ada data vocabulary TF-IDF. Klik tombol "Hitung TF-IDF" terlebih dahulu.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @endif

    </div>
</div>

<script>
function konfirmasiHitung() {
    Swal.fire({
        title: 'Hitung Pembobotan TF-IDF Data X?',
        html: '<p class="text-sm text-slate-300 mb-3">Sistem akan menghitung Term Frequency (TF), Inverse Document Frequency (IDF), dan bobot TF-IDF untuk seluruh token kata hasil praproses Data X.</p>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Jalankan',
        cancelButtonText: 'Tidak',
        buttonsStyling: false,
        background: '#1e1e2e',
        color: '#f8fafc',
        customClass: {
            popup: 'border border-[#3f3f5a] rounded-2xl shadow-2xl bg-[#1e1e2e] text-slate-100 p-6',
            title: 'text-lg font-bold text-white mb-2',
            htmlContainer: 'text-sm text-slate-300',
            actions: 'flex items-center justify-center gap-3 mt-4',
            confirmButton: 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-600/30 transition-all duration-200 cursor-pointer hover:scale-[1.02] active:scale-95',
            cancelButton: 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm bg-red-600 hover:bg-red-500 text-white shadow-lg shadow-red-600/30 transition-all duration-200 cursor-pointer hover:scale-[1.02] active:scale-95'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Sedang Menghitung TF-IDF...',
                html: '<p class="text-sm text-slate-400 mt-2">Mohon tunggu, bobot TF, DF, IDF, dan TF-IDF sedang dikalkulasi.</p>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                background: '#1e1e2e',
                color: '#f8fafc',
                customClass: {
                    popup: 'border border-[#3f3f5a] rounded-2xl shadow-2xl bg-[#1e1e2e] text-slate-100 p-6',
                    title: 'text-lg font-bold text-white mb-2',
                }
            });
            document.getElementById('form-hitung-tfidf').submit();
        }
    });
}

function konfirmasiReset() {
    Swal.fire({
        title: 'Reset Pembobotan TF-IDF Data X?',
        text: 'Semua hasil ekstraksi unigram dan pembobotan TF-IDF Data X akan dihapus. Anda dapat menghitungnya kembali kapan saja.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Reset',
        cancelButtonText: 'Tidak',
        buttonsStyling: false,
        background: '#1e1e2e',
        color: '#f8fafc',
        customClass: {
            popup: 'border border-[#3f3f5a] rounded-2xl shadow-2xl bg-[#1e1e2e] text-slate-100 p-6',
            title: 'text-lg font-bold text-white mb-2',
            htmlContainer: 'text-sm text-slate-300',
            actions: 'flex items-center justify-center gap-3 mt-4',
            confirmButton: 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-600/30 transition-all duration-200 cursor-pointer hover:scale-[1.02] active:scale-95',
            cancelButton: 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm bg-red-600 hover:bg-red-500 text-white shadow-lg shadow-red-600/30 transition-all duration-200 cursor-pointer hover:scale-[1.02] active:scale-95'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-reset-tfidf').submit();
        }
    });
}
</script>
@endsection

