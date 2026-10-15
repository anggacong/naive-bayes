@extends('layouts.app')

@section('title', 'Stopwords')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Stopwords</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Kelola kata-kata yang akan dihilangkan saat preprocessing (stopword removal).
                Total: <span class="font-semibold text-violet-600 dark:text-red-600">{{ $stopwords->total() }} kata</span>
            </p>
        </div>
        <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')"
            class="inline-flex items-center gap-2 px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-xl shadow transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Tambah Stopword
        </button>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="flex items-center gap-3 p-4 rounded-xl bg-green-50 border border-green-200 dark:bg-green-900/20 dark:border-green-800 text-green-700 dark:text-green-400 text-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="flex items-start gap-3 p-4 rounded-xl bg-red-50 border border-red-200 dark:bg-red-900/20 dark:border-red-800 text-red-700 dark:text-red-400 text-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
        <div>{{ $errors->first() }}</div>
    </div>
    @endif

    {{-- Search --}}
    <div class="card p-4 mb-6">
        <form method="GET" action="{{ route('stopwords.index') }}" class="flex gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text" name="q" value="{{ request('q') }}"
                    placeholder="Cari kata stopword..."
                    class="w-full pl-10 pr-4 py-2 text-xs border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:border-red-500 focus:bg-white transition-colors">
            </div>
            <button type="submit"
                class="btn btn-primary text-xs px-4 py-2 rounded-xl">
                Cari
            </button>
            @if(request('q'))
            <a href="{{ route('stopwords.index') }}"
                class="btn btn-outline text-xs px-4 py-2 rounded-xl">
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Stopwords Grid/Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        @if($stopwords->isEmpty())
        <div class="py-16 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
            <p class="text-gray-500 dark:text-gray-400 text-sm">
                @if(request('q'))
                    Tidak ada stopword yang cocok dengan "{{ request('q') }}"
                @else
                    Daftar stopword masih kosong
                @endif
            </p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kata Stopword</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ditambahkan</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($stopwords as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                        <td class="px-6 py-3 text-sm text-gray-400 dark:text-gray-500">
                            {{ $stopwords->firstItem() + $loop->index }}
                        </td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-violet-100 text-violet-800 dark:bg-violet-900/30 dark:text-red-600 font-mono">
                                {{ $item->kata }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-500 dark:text-gray-400">
                            {{ $item->created_at?->format('d M Y') ?? '-' }}
                        </td>
                        <td class="px-6 py-3 text-center">
                            <form method="POST" action="{{ route('stopwords.destroy', $item->id) }}"
                                onsubmit="return confirmDelete(event, '{{ e($item->kata) }}')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 dark:text-red-400 dark:bg-red-900/20 dark:hover:bg-red-900/40 rounded-lg transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $stopwords->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Modal Tambah --}}
<div id="modal-tambah" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="document.getElementById('modal-tambah').classList.add('hidden')"></div>
    <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md p-6 z-10">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Tambah Kata Stopword</h3>
        <form method="POST" action="{{ route('stopwords.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kata Stopword</label>
                <input type="text" name="kata" required
                    placeholder="Contoh: dan, yang, di, ke"
                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 outline-none transition">
                <p class="mt-1 text-xs text-gray-400">Huruf kecil akan diterapkan secara otomatis.</p>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')"
                    class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2 text-sm font-medium text-white bg-violet-600 hover:bg-violet-700 rounded-xl transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(event, kata) {
    event.preventDefault();
    const form = event.target;
    Swal.fire({
        title: 'Hapus Stopword?',
        html: `Kata <strong class="font-mono text-red-600">${kata}</strong> akan dihapus dari daftar stopword.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        buttonsStyling: false,
        background: '#1e1e2e',
        color: '#f8fafc',
        customClass: {
            popup: 'border border-gray-200 rounded-2xl shadow-2xl bg-white text-gray-800 p-6',
            title: 'text-lg font-bold text-white mb-2',
            htmlContainer: 'text-sm text-gray-600',
            actions: 'flex items-center justify-center gap-3 mt-4',
            confirmButton: 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-500 text-white shadow-lg shadow-rose-600/30 transition-all duration-200 cursor-pointer hover:scale-[1.02] active:scale-95',
            cancelButton: 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-sm bg-slate-700 hover:bg-slate-600 text-gray-700 transition-all duration-200 cursor-pointer'
        }
    }).then((result) => {
        if (result.isConfirmed) form.submit();
    });
    return false;
}

@if($errors->any())
document.getElementById('modal-tambah').classList.remove('hidden');
@endif
</script>
@endpush
@endsection
