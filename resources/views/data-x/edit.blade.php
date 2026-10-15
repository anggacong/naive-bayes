@extends('layouts.app')

@section('title', 'Edit Data X')
@section('page-title', 'Edit Data X')
@section('breadcrumb')
    <a href="{{ route('data-x.index') }}">Data X</a>
    <span class="text-gray-500">/</span>
    <span class="current">Edit #{{ $dataX->id }}</span>
@endsection

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <h2 class="text-base font-bold text-white mb-6 flex items-center gap-2">
            <span class="w-7 h-7 rounded-lg bg-red-100 flex items-center justify-center text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </span>
            Edit Data X #{{ $dataX->id }}
        </h2>

        <form action="{{ route('data-x.update', $dataX) }}" method="POST">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control"
                       placeholder="Contoh: johndoe (tanpa @)"
                       value="{{ old('username', $dataX->username) }}">
                @error('username')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Teks Asli <span class="text-red-400">*</span></label>
                <textarea name="teks_asli" class="form-control" rows="5">{{ old('teks_asli', $dataX->teks_asli) }}</textarea>
                @error('teks_asli')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control"
                           value="{{ old('tanggal', $dataX->tanggal?->format('Y-m-d')) }}">
                    @error('tanggal')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Label Sentimen <span class="text-red-400">*</span></label>
                    <select name="sentimen" class="form-control">
                        @foreach(['positif' => 'Positif', 'netral' => 'Netral', 'negatif' => 'Negatif'] as $val => $label)
                            <option value="{{ $val }}" {{ old('sentimen', $dataX->sentimen) === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('sentimen')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Info preprocessing --}}
            @if($dataX->preprocessing)
                <div class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs mb-4 flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>Data ini sudah pernah dipreprocessing. Jika disimpan, hasil preprocessing lama akan dihapus dan perlu dijalankan ulang.</span>
                </div>
            @endif

            <div class="flex items-center gap-3 mt-4">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
                <a href="{{ route('data-x.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
