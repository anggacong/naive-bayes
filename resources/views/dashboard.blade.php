@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
{{-- Stats Grid --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="card-stat indigo fade-in-up">
        <div class="stat-icon bg-indigo-500/15 text-indigo-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
        <div>
            <div class="text-2xl font-extrabold text-white">{{ $totalData }}</div>
            <div class="text-xs text-slate-500 font-medium mt-0.5">Total Data</div>
        </div>
    </div>
    <div class="card-stat cyan fade-in-up">
        <div class="stat-icon bg-cyan-500/15 text-cyan-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
        </div>
        <div>
            <div class="text-2xl font-extrabold text-white">{{ $totalX }}</div>
            <div class="text-xs text-slate-500 font-medium mt-0.5">Data X (Twitter)</div>
        </div>
    </div>
    <div class="card-stat violet fade-in-up">
        <div class="stat-icon bg-violet-500/15 text-violet-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
            </svg>
        </div>
        <div>
            <div class="text-2xl font-extrabold text-white">{{ $totalThread }}</div>
            <div class="text-xs text-slate-500 font-medium mt-0.5">Data Thread</div>
        </div>
    </div>
    <div class="card-stat green fade-in-up">
        <div class="stat-icon bg-emerald-500/15 text-emerald-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h2m0 0h2m-6-12h.01M9 16h.01M9 12h.01M15 16h.01M15 12h.01M15 8h.01M9 8h.01"/>
            </svg>
        </div>
        <div>
            <div class="text-sm font-bold text-emerald-400 leading-tight">Koperasi</div>
            <div class="text-xs text-slate-500 font-medium mt-0.5">Merah Putih</div>
        </div>
    </div>
</div>

{{-- Sentimen Cards --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

    {{-- Sentimen Data X --}}
    <div class="card fade-in-up">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-base font-bold text-white">Distribusi Sentimen – Data X</h2>
                <p class="text-xs text-slate-500 mt-0.5">Twitter / X Platform</p>
            </div>
            <a href="{{ route('data-x.index') }}" class="btn btn-outline btn-sm">
                Lihat Data →
            </a>
        </div>

        @php
            $totalXSentimen = array_sum($sentimenX);
        @endphp

        <div class="space-y-4">
            @foreach(['positif' => ['hex' => '#10b981'], 'netral' => ['hex' => '#94a3b8'], 'negatif' => ['hex' => '#ef4444']] as $label => $config)
                @php
                    $jumlah = $sentimenX[$label] ?? 0;
                    $pct = $totalXSentimen > 0 ? round(($jumlah / $totalXSentimen) * 100) : 0;
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="flex items-center gap-2 text-sm font-medium text-slate-300">
                            @if($label === 'positif')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @elseif($label === 'negatif')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10h.01M15 10h.01M9 15h6M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                            {{ ucfirst($label) }}
                        </span>
                        <span class="text-sm font-bold text-white">{{ $jumlah }} <span class="text-xs text-slate-500 font-normal">({{ $pct }}%)</span></span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $pct }}%; background: {{ $config['hex'] }};"></div>
                    </div>
                </div>
            @endforeach
        </div>
        @if($totalXSentimen === 0)
            <p class="text-center text-slate-600 text-sm mt-4">Belum ada data</p>
        @endif
    </div>

    {{-- Sentimen Data Thread --}}
    <div class="card fade-in-up">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-base font-bold text-white">Distribusi Sentimen – Data Thread</h2>
                <p class="text-xs text-slate-500 mt-0.5">Threads Platform</p>
            </div>
            <a href="{{ route('data-thread.index') }}" class="btn btn-outline btn-sm">
                Lihat Data →
            </a>
        </div>

        @php $totalThSentimen = array_sum($sentimenThread); @endphp
        <div class="space-y-4">
            @foreach(['positif' => ['hex' => '#10b981'], 'netral' => ['hex' => '#94a3b8'], 'negatif' => ['hex' => '#ef4444']] as $label => $config)
                @php
                    $jumlah = $sentimenThread[$label] ?? 0;
                    $pct = $totalThSentimen > 0 ? round(($jumlah / $totalThSentimen) * 100) : 0;
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="flex items-center gap-2 text-sm font-medium text-slate-300">
                            @if($label === 'positif')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @elseif($label === 'negatif')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10h.01M15 10h.01M9 15h6M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                            {{ ucfirst($label) }}
                        </span>
                        <span class="text-sm font-bold text-white">{{ $jumlah }} <span class="text-xs text-slate-500 font-normal">({{ $pct }}%)</span></span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $pct }}%; background: {{ $config['hex'] }};"></div>
                    </div>
                </div>
            @endforeach
        </div>
        @if($totalThSentimen === 0)
            <p class="text-center text-slate-600 text-sm mt-4">Belum ada data</p>
        @endif
    </div>
</div>

{{-- Quick Links --}}
<div class="card fade-in-up">
    <h2 class="text-base font-bold text-white mb-5">Menu Cepat</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('data-x.create') }}" class="flex flex-col items-center gap-3 p-5 rounded-xl border border-[#3f3f5a] hover:border-indigo-500/50 hover:bg-indigo-500/5 transition-all group">
            <div class="w-12 h-12 rounded-xl bg-indigo-500/15 flex items-center justify-center text-indigo-400 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-slate-400 text-center group-hover:text-indigo-400 transition-colors">Tambah Data X</span>
        </a>
        <a href="{{ route('data-thread.create') }}" class="flex flex-col items-center gap-3 p-5 rounded-xl border border-[#3f3f5a] hover:border-violet-500/50 hover:bg-violet-500/5 transition-all group">
            <div class="w-12 h-12 rounded-xl bg-violet-500/15 flex items-center justify-center text-violet-400 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-slate-400 text-center group-hover:text-violet-400 transition-colors">Tambah Thread</span>
        </a>
        <a href="{{ route('preprocessing.x') }}" class="flex flex-col items-center gap-3 p-5 rounded-xl border border-[#3f3f5a] hover:border-cyan-500/50 hover:bg-cyan-500/5 transition-all group">
            <div class="w-12 h-12 rounded-xl bg-cyan-500/15 flex items-center justify-center text-cyan-400 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-slate-400 text-center group-hover:text-cyan-400 transition-colors">Preprocessing X</span>
        </a>
        <a href="{{ route('preprocessing.thread') }}" class="flex flex-col items-center gap-3 p-5 rounded-xl border border-[#3f3f5a] hover:border-emerald-500/50 hover:bg-emerald-500/5 transition-all group">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/15 flex items-center justify-center text-emerald-400 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-slate-400 text-center group-hover:text-emerald-400 transition-colors">Preprocessing Thread</span>
        </a>
    </div>
</div>
@endsection
