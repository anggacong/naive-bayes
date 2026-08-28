@extends('layouts.app')

@section('title', 'Data Thread')
@section('page-title', 'Data Thread')
@section('breadcrumb')<span class="current">Data Thread</span>@endsection

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <p class="text-sm text-slate-500">Total: <span class="text-violet-400 font-semibold">{{ $data->total() }} data</span></p>
    </div>
    <div class="flex flex-wrap items-center gap-3">
        <form action="{{ route('data-thread.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
            @csrf
            <label for="import_file_thread" class="btn btn-outline btn-sm cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Import Excel / CSV
            </label>
            <input type="file" id="import_file_thread" name="file" accept=".xlsx,.xls,.csv,.txt" class="hidden" onchange="this.form.submit()">
        </form>
        <a href="{{ route('data-thread.create') }}" class="btn btn-primary btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Data
        </a>
    </div>
</div>

<div class="card p-0 overflow-hidden">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th class="w-12">No</th>
                    <th>Username</th>
                    <th>Teks Asli</th>
                    <th>Tanggal</th>
                    <th>Sentimen</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $index => $item)
                    <tr class="fade-in-up">
                        <td class="text-slate-500 text-sm">{{ $data->firstItem() + $index }}</td>
                        <td>
                            <span class="font-semibold text-violet-400 text-sm">{{ $item->username ? '@' . $item->username : '-' }}</span>
                        </td>
                        <td>
                            <div class="max-w-md text-sm text-slate-300 leading-relaxed">
                                {{ Str::limit($item->teks_asli, 120) }}
                                @if(strlen($item->teks_asli) > 120)
                                    <button onclick="openModal('Teks Asli #{{ $item->id }}', {{ json_encode($item->teks_asli) }})"
                                            class="text-violet-400 hover:text-violet-300 text-xs font-medium ml-1 underline">
                                        selengkapnya
                                    </button>
                                @endif
                            </div>
                        </td>
                        <td class="text-sm text-slate-400 whitespace-nowrap">
                            {{ $item->tanggal ? $item->tanggal->format('d M Y') : '-' }}
                        </td>
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
                        <td>
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('data-thread.edit', $item) }}"
                                   class="btn btn-outline btn-icon btn-sm" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('data-thread.destroy', $item) }}" method="POST"
                                      onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline btn-icon btn-sm" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-16 text-slate-600">
                            <div class="flex justify-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            <div class="text-sm">Belum ada data Thread. <a href="{{ route('data-thread.create') }}" class="text-violet-400 underline">Tambahkan sekarang</a></div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($data->hasPages())
        <div class="p-4 border-t border-[#3f3f5a] flex items-center justify-between">
            <span class="text-xs text-slate-500">
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
@endsection
