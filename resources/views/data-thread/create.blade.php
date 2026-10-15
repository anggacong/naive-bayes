@extends('layouts.app')

@section('title', 'Tambah Data Thread')
@section('page-title', 'Tambah Data Thread')
@section('breadcrumb')
    <a href="{{ route('data-thread.index') }}">Data Thread</a>
    <span class="text-gray-500">/</span>
    <span class="current">Tambah</span>
@endsection

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <h2 class="text-base font-bold text-white mb-6 flex items-center gap-2">
            <span class="w-7 h-7 rounded-lg bg-violet-500/20 flex items-center justify-center text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                </svg>
            </span>
            Form Tambah Data Thread
        </h2>

        <form action="{{ route('data-thread.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control"
                       placeholder="Contoh: johndoe (tanpa @)"
                       value="{{ old('username') }}">
                @error('username')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Teks Asli <span class="text-red-400">*</span></label>
                <textarea name="teks_asli" class="form-control" rows="5"
                          placeholder="Masukkan teks thread/postingan asli...">{{ old('teks_asli') }}</textarea>
                @error('teks_asli')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control"
                           value="{{ old('tanggal') }}">
                    @error('tanggal')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Label Sentimen <span class="text-red-400">*</span></label>
                    <select name="sentimen" class="form-control">
                        <option value="">-- Pilih Sentimen --</option>
                        @foreach(['positif' => 'Positif', 'netral' => 'Netral', 'negatif' => 'Negatif'] as $val => $label)
                            <option value="{{ $val }}" {{ old('sentimen') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('sentimen')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center gap-3 mt-4">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Data
                </button>
                <a href="{{ route('data-thread.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
