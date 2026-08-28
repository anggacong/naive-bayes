<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\KamusNormalisasi;

class KamusNormalisasiController extends Controller
{
    public function index(Request $request)
    {
        $query = KamusNormalisasi::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->where('slang', 'like', "%{$q}%")
                  ->orWhere('formal', 'like', "%{$q}%");
            });
        }

        $kamus = $query->orderBy('slang', 'asc')
                      ->paginate(20)
                      ->withQueryString();

        return view('kamus-normalisasi.index', compact('kamus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'slang' => 'required|string|max:255|unique:kamus_normalisasi,slang',
            'formal' => 'required|string|max:255',
        ], [
            'slang.unique' => 'Kata slang ini sudah terdaftar dalam kamus.',
            'slang.required' => 'Kata slang wajib diisi.',
            'formal.required' => 'Kata formal wajib diisi.',
        ]);

        KamusNormalisasi::create([
            'slang' => strtolower(trim($request->slang)),
            'formal' => strtolower(trim($request->formal)),
        ]);

        return redirect()->back()->with('success', 'Kata slang baru berhasil ditambahkan ke kamus.');
    }

    public function update(Request $request, $id)
    {
        $kamus = KamusNormalisasi::findOrFail($id);

        $request->validate([
            'slang' => 'required|string|max:255|unique:kamus_normalisasi,slang,' . $id,
            'formal' => 'required|string|max:255',
        ], [
            'slang.unique' => 'Kata slang ini sudah terdaftar dalam kamus.',
            'slang.required' => 'Kata slang wajib diisi.',
            'formal.required' => 'Kata formal wajib diisi.',
        ]);

        $kamus->update([
            'slang' => strtolower(trim($request->slang)),
            'formal' => strtolower(trim($request->formal)),
        ]);

        return redirect()->back()->with('success', 'Kata slang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kamus = KamusNormalisasi::findOrFail($id);
        $kamus->delete();

        return redirect()->back()->with('success', 'Kata slang berhasil dihapus dari kamus.');
    }
}
