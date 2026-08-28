<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Stopword;

class StopwordController extends Controller
{
    public function index(Request $request)
    {
        $query = Stopword::query();

        if ($request->filled('q')) {
            $query->where('kata', 'like', '%' . $request->q . '%');
        }

        $stopwords = $query->orderBy('kata', 'asc')
                           ->paginate(25)
                           ->withQueryString();

        return view('stopword.index', compact('stopwords'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kata' => 'required|string|max:255|unique:stopwords,kata',
        ], [
            'kata.unique'    => 'Kata ini sudah ada dalam daftar stopword.',
            'kata.required'  => 'Kata stopword wajib diisi.',
        ]);

        Stopword::create([
            'kata' => strtolower(trim($request->kata)),
        ]);

        return redirect()->back()->with('success', 'Kata stopword baru berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $stopword = Stopword::findOrFail($id);
        $stopword->delete();

        return redirect()->back()->with('success', 'Kata stopword berhasil dihapus.');
    }
}
