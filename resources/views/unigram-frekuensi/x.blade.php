@extends('layouts.app')

@section('title', 'Unigram Frekuensi Data X')
@section('page-title', 'Unigram Frekuensi Data X (Twitter)')

@section('content')
<div class="space-y-6">

    {{-- Stats Banner --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <div class="text-xs text-slate-400">Total Dokumen</div>
                <div class="text-2xl font-bold text-slate-100 mt-0.5">{{ number_format($totalDokumen) }}</div>
            </div>
        </div>
        <div class="card p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                </svg>
            </div>
            <div>
                <div class="text-xs text-slate-400">Total Token</div>
                <div class="text-2xl font-bold text-slate-100 mt-0.5">{{ number_format($totalToken) }}</div>
            </div>
        </div>
        <div class="card p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div>
                <div class="text-xs text-slate-400">Kata Unik (Vocabulary)</div>
                <div class="text-2xl font-bold text-slate-100 mt-0.5">{{ number_format($totalVocab) }}</div>
            </div>
        </div>
    </div>

    {{-- Token Per Kelas --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach([
        'positif' => ['label' => 'Token Positif', 'color' => 'emerald', 'icon' => 'M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        'negatif' => ['label' => 'Token Negatif', 'color' => 'rose', 'icon' => 'M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        'netral' => ['label' => 'Token Netral', 'color' => 'amber', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ] as $kelas => $cfg)
        <div class="card p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-{{ $cfg['color'] }}-500/10 border border-{{ $cfg['color'] }}-500/20 flex items-center justify-center text-{{ $cfg['color'] }}-400 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cfg['icon'] }}" />
                </svg>
            </div>
            <div>
                <div class="text-xs text-slate-400">{{ $cfg['label'] }}</div>
                <div class="text-2xl font-bold text-slate-100 mt-0.5">{{ number_format($tokenPerKelas[$kelas] ?? 0) }}</div>
                <div class="text-[10px] text-slate-500 mt-0.5">
                    {{ $totalToken > 0 ? round(($tokenPerKelas[$kelas] ?? 0) / $totalToken * 100, 1) : 0 }}% dari total token
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Statistik Data Training --}}
    @if($trainTotalDok > 0)
    <div class="card p-5">
        <div class="flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18" />
            </svg>
            <h3 class="text-sm font-semibold text-slate-200">Statistik Data Training</h3>
            <span class="text-[10px] px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 font-mono">80% split</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            {{-- Baris 1: Ringkasan Umum --}}
            <div class="p-3.5 rounded-xl bg-[#1a1a27] border border-[#3f3f5a] text-center">
                <div class="text-[10px] text-slate-400 uppercase font-semibold tracking-wide">Dokumen Training</div>
                <div class="text-xl font-bold text-slate-100 font-mono mt-1">{{ number_format($trainTotalDok) }}</div>
            </div>
            <div class="p-3.5 rounded-xl bg-[#1a1a27] border border-[#3f3f5a] text-center">
                <div class="text-[10px] text-slate-400 uppercase font-semibold tracking-wide">Total Token</div>
                <div class="text-xl font-bold text-indigo-400 font-mono mt-1">{{ number_format($trainTotalToken) }}</div>
            </div>
            <div class="p-3.5 rounded-xl bg-[#1a1a27] border border-[#3f3f5a] text-center">
                <div class="text-[10px] text-slate-400 uppercase font-semibold tracking-wide">Kata Unik</div>
                <div class="text-xl font-bold text-purple-400 font-mono mt-1">{{ number_format($trainKataUnik) }}</div>
            </div>

            {{-- Baris 2: Token Per Kelas --}}
            <div class="p-3.5 rounded-xl bg-emerald-500/5 border border-emerald-500/20 text-center">
                <div class="text-[10px] text-emerald-400 uppercase font-semibold tracking-wide">Token Positif</div>
                <div class="text-xl font-bold text-emerald-400 font-mono mt-1">{{ number_format($trainTokenPerKelas['positif']) }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">
                    {{ $trainTotalToken > 0 ? round($trainTokenPerKelas['positif'] / $trainTotalToken * 100, 1) : 0 }}% dari total
                </div>
            </div>
            <div class="p-3.5 rounded-xl bg-rose-500/5 border border-rose-500/20 text-center">
                <div class="text-[10px] text-rose-400 uppercase font-semibold tracking-wide">Token Negatif</div>
                <div class="text-xl font-bold text-rose-400 font-mono mt-1">{{ number_format($trainTokenPerKelas['negatif']) }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">
                    {{ $trainTotalToken > 0 ? round($trainTokenPerKelas['negatif'] / $trainTotalToken * 100, 1) : 0 }}% dari total
                </div>
            </div>
            <div class="p-3.5 rounded-xl bg-amber-500/5 border border-amber-500/20 text-center">
                <div class="text-[10px] text-amber-400 uppercase font-semibold tracking-wide">Token Netral</div>
                <div class="text-xl font-bold text-amber-400 font-mono mt-1">{{ number_format($trainTokenPerKelas['netral']) }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">
                    {{ $trainTotalToken > 0 ? round($trainTokenPerKelas['netral'] / $trainTotalToken * 100, 1) : 0 }}% dari total
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="card p-5 border border-dashed border-[#3f3f5a]">
        <div class="flex items-center gap-3 text-slate-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-xs">Statistik data training belum tersedia. Jalankan <strong class="text-slate-300">Pelatihan Model</strong> terlebih dahulu.</span>
        </div>
    </div>
    @endif

    {{-- Main Container --}}

    <div class="card p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-[#3f3f5a]">
            <div>
                <h2 class="text-lg font-semibold text-slate-100">Unigram Frekuensi Data X</h2>
                <p class="text-xs text-slate-400 mt-1">Frekuensi kemunculan kata (unigram) dari hasil preprocessing Data X</p>
            </div>
        </div>

        {{-- Navigation Tabs --}}
        <div class="flex items-center gap-4 mt-6">
            <div class="flex items-center gap-2 border-b border-[#3f3f5a] w-full">
                <a href="{{ route('unigram-frekuensi.x', ['tab' => 'dokumen']) }}"
                    class="px-4 py-2.5 text-xs font-semibold border-b-2 flex items-center gap-2 transition-colors {{ $tab === 'dokumen' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-200' }}">
                    Per Dokumen
                </a>
                <a href="{{ route('unigram-frekuensi.x', ['tab' => 'vocab']) }}"
                    class="px-4 py-2.5 text-xs font-semibold border-b-2 flex items-center gap-2 transition-colors {{ $tab === 'vocab' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-200' }}">
                    Vocabulary Global
                </a>
                <a href="{{ route('unigram-frekuensi.x', ['tab' => 'vocab-train']) }}"
                    class="px-4 py-2.5 text-xs font-semibold border-b-2 flex items-center gap-2 transition-colors {{ $tab === 'vocab-train' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-200' }}">
                    Vocabulary Training (80%)
                </a>
            </div>
        </div>

        {{-- Search --}}
        <div class="mt-4 flex items-center justify-between">
            <form action="{{ route('unigram-frekuensi.x') }}" method="GET" class="flex items-center gap-2">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="{{ $tab === 'dokumen' ? 'Cari teks atau @username...' : ($tab === 'vocab' ? 'Cari kata dalam vocabulary global...' : 'Cari kata dalam vocabulary training...') }}"
                    class="px-3 py-1.5 bg-[#1a1a27] border border-[#3f3f5a] rounded-lg text-xs text-slate-200 placeholder-slate-500 focus:outline-none focus:border-indigo-500 w-64">
                <button type="submit" class="btn btn-primary text-xs px-3 py-1.5 rounded-lg">Cari</button>
                @if($search)
                <a href="{{ route('unigram-frekuensi.x', ['tab' => $tab]) }}" class="text-xs text-slate-400 hover:text-slate-200">Reset</a>
                @endif
            </form>
        </div>

        {{-- TAB 1: PER DOKUMEN --}}
        @if($tab === 'dokumen')
        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#1c1c2b] text-slate-400 uppercase text-[10px] border-b border-[#3f3f5a]">
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4 w-36">Pengguna</th>
                        <th class="py-3 px-4 min-w-[280px]">Teks Asli & Hasil Stemming</th>
                        <th class="py-3 px-4 w-28 text-center">Sentimen</th>
                        <th class="py-3 px-4 min-w-[300px]">Token & Frekuensi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2d2d42]">
                    @forelse($dataDokumen as $index => $item)
                    @php
                    $badge = match($item->sentimen) {
                    'positif' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                    'negatif' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                    default => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                    };
                    $stemming = $item->preprocessing->hasil_stemming ?? '';
                    $freq = $stemming ? array_count_values(preg_split('/\s+/', trim($stemming), -1, PREG_SPLIT_NO_EMPTY)) : [];
                    arsort($freq);
                    @endphp
                    <tr class="hover:bg-[#1f1f30] transition-colors align-top">
                        <td class="py-3.5 px-4 text-center text-slate-400 font-mono">{{ $dataDokumen->firstItem() + $index }}</td>
                        <td class="py-3.5 px-4">
                            <span class="font-semibold text-indigo-400 block">{{ $item->username ? '@' . $item->username : '-' }}</span>
                            <span class="text-[10px] text-slate-500 mt-0.5 block">{{ $item->tanggal ? $item->tanggal->format('d/m/Y') : '-' }}</span>
                        </td>
                        <td class="py-3.5 px-4 space-y-2">
                            <div>
                                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Teks Asli:</span>
                                <p class="text-slate-300 leading-relaxed text-xs">{{ $item->teks_asli }}</p>
                            </div>
                            <div class="p-2 rounded-lg bg-[#161622] border border-[#2d2d42]">
                                <span class="text-[10px] font-semibold text-indigo-400 uppercase tracking-wider block mb-0.5">Hasil Stemming:</span>
                                <p class="font-mono text-slate-200 text-xs">{{ $stemming ?: '-' }}</p>
                            </div>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border inline-block {{ $badge }}">{{ $item->sentimen ?? 'netral' }}</span>
                            <div class="text-[10px] text-slate-500 mt-1">{{ count($freq) }} token unik</div>
                        </td>
                        <td class="py-3.5 px-4">
                            @if(empty($freq))
                            <span class="text-xs text-amber-400 italic">Belum ada data stemming.</span>
                            @else
                            <div class="flex flex-wrap gap-1.5 max-h-48 overflow-y-auto pr-1">
                                @foreach($freq as $kata => $count)
                                <div class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-[#252538] border border-[#3f3f5a] text-xs hover:border-indigo-500/50 transition-colors">
                                    <span class="font-semibold text-slate-200">{{ $kata }}</span>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-indigo-500/20 text-indigo-300 font-mono font-bold">{{ $count }}</span>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-slate-400">Belum ada data preprocessing yang tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-6">{{ $dataDokumen->links() }}</div>
        </div>
        @endif

        {{-- TAB 2: VOCABULARY GLOBAL --}}
        @if($tab === 'vocab')
        <div class="mt-6 overflow-x-auto">
            @if(empty($vocabulary))
            <div class="text-center py-12 text-slate-400">Belum ada data preprocessing. Jalankan preprocessing terlebih dahulu.</div>
            @else
            <table class="w-full text-xs border-collapse">
                <thead>
                    <tr class="bg-[#1c1c2b] text-slate-400 uppercase text-[10px] border-b border-[#3f3f5a]">
                        <th class="py-3 px-4 w-12 text-center" style="text-align: center !important;">No</th>
                        <th class="py-3 px-4 text-left" style="text-align: left !important;">Kata</th>
                        <th class="py-3 px-4 text-center" style="text-align: center !important;">Total Frekuensi</th>
                        <th class="py-3 px-4 text-center text-emerald-400" style="text-align: center !important;">Positif</th>
                        <th class="py-3 px-4 text-center text-rose-400" style="text-align: center !important;">Negatif</th>
                        <th class="py-3 px-4 text-center text-amber-400" style="text-align: center !important;">Netral</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2d2d42]">
                    @foreach($vocabulary as $no => $v)
                    <tr class="hover:bg-[#1f1f30] transition-colors">
                        <td class="py-2.5 px-4 text-center text-slate-400 font-mono" style="text-align: center !important;">{{ $no + 1 }}</td>
                        <td class="py-2.5 px-4 text-left font-semibold text-slate-200" style="text-align: left !important;">{{ $v->kata }}</td>
                        <td class="py-2.5 px-4 text-center font-bold text-indigo-400 font-mono" style="text-align: center !important;">{{ number_format($v->total_frekuensi) }}</td>
                        <td class="py-2.5 px-4 text-center text-emerald-400 font-mono" style="text-align: center !important;">{{ number_format($v->frek_positif) }}</td>
                        <td class="py-2.5 px-4 text-center text-rose-400 font-mono" style="text-align: center !important;">{{ number_format($v->frek_negatif) }}</td>
                        <td class="py-2.5 px-4 text-center text-amber-400 font-mono" style="text-align: center !important;">{{ number_format($v->frek_netral) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3 text-xs text-slate-500 text-right">Menampilkan {{ count($vocabulary) }} kata unik</div>
            @endif
        </div>
        @endif

        {{-- TAB 3: VOCABULARY TRAINING --}}
        @if($tab === 'vocab-train')
        <div class="mt-6 overflow-x-auto">
            @if($trainTotalDok === 0)
            <div class="text-center py-12 text-slate-400">
                <p>Statistik dan Vocabulary Data Training belum tersedia.</p>
                <p class="text-xs text-slate-500 mt-1">Silakan jalankan <strong class="text-slate-300">Pelatihan Model Naive Bayes</strong> terlebih dahulu.</p>
            </div>
            @elseif(empty($vocabularyTraining))
            <div class="text-center py-12 text-slate-400">Tidak ada kata dalam vocabulary data training yang cocok dengan kata pencarian.</div>
            @else
            <table class="w-full text-xs border-collapse">
                <thead>
                    <tr class="bg-[#1c1c2b] text-slate-400 uppercase text-[10px] border-b border-[#3f3f5a]">
                        <th class="py-3 px-4 w-12 text-center" style="text-align: center !important;">No</th>
                        <th class="py-3 px-4 text-left" style="text-align: left !important;">Kata (Vocabulary Training)</th>
                        <th class="py-3 px-4 text-center" style="text-align: center !important;">Total Frekuensi (Training)</th>
                        <th class="py-3 px-4 text-center text-emerald-400" style="text-align: center !important;">Positif</th>
                        <th class="py-3 px-4 text-center text-rose-400" style="text-align: center !important;">Negatif</th>
                        <th class="py-3 px-4 text-center text-amber-400" style="text-align: center !important;">Netral</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2d2d42]">
                    @foreach($vocabularyTraining as $no => $vt)
                    <tr class="hover:bg-[#1f1f30] transition-colors">
                        <td class="py-2.5 px-4 text-center text-slate-400 font-mono" style="text-align: center !important;">{{ $no + 1 }}</td>
                        <td class="py-2.5 px-4 text-left font-semibold text-slate-200 font-mono" style="text-align: left !important;">{{ $vt->kata }}</td>
                        <td class="py-2.5 px-4 text-center font-bold text-indigo-400 font-mono" style="text-align: center !important;">{{ number_format($vt->total_frekuensi) }}</td>
                        <td class="py-2.5 px-4 text-center text-emerald-400 font-mono" style="text-align: center !important;">{{ number_format($vt->frek_positif) }}</td>
                        <td class="py-2.5 px-4 text-center text-rose-400 font-mono" style="text-align: center !important;">{{ number_format($vt->frek_negatif) }}</td>
                        <td class="py-2.5 px-4 text-center text-amber-400 font-mono" style="text-align: center !important;">{{ number_format($vt->frek_netral) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3 text-xs text-slate-500 text-right">Menampilkan {{ count($vocabularyTraining) }} kata unik dari Data Training (80% split)</div>
            @endif
        </div>
        @endif

    </div>
</div>
@endsection