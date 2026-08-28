<?php

namespace App\Http\Controllers;

use App\Models\DataThread;
use App\Models\PreprocessingThread;
use Illuminate\Http\Request;

class DataThreadController extends Controller
{
    public function index(Request $request)
    {
        $query = DataThread::query();

        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('username', 'like', "%{$keyword}%")
                  ->orWhere('teks_asli', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('sentimen')) {
            $query->where('sentimen', $request->sentimen);
        }

        $data = $query->latest()->paginate(15)->withQueryString();
        return view('data-thread.index', compact('data'));
    }

    public function create()
    {
        return view('data-thread.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username'  => 'nullable|string|max:100',
            'teks_asli' => 'required|string',
            'tanggal'   => 'nullable|date',
            'sentimen'  => 'required|in:positif,negatif,netral',
        ]);

        DataThread::create($validated);

        return redirect()->route('data-thread.index')
            ->with('success', 'Data Thread berhasil ditambahkan.');
    }

    public function edit(DataThread $dataThread)
    {
        return view('data-thread.edit', compact('dataThread'));
    }

    public function update(Request $request, DataThread $dataThread)
    {
        $validated = $request->validate([
            'username'  => 'nullable|string|max:100',
            'teks_asli' => 'required|string',
            'tanggal'   => 'nullable|date',
            'sentimen'  => 'required|in:positif,negatif,netral',
        ]);

        $dataThread->update($validated);

        // Hapus preprocessing lama jika ada
        $dataThread->preprocessing()->delete();

        return redirect()->route('data-thread.index')
            ->with('success', 'Data Thread berhasil diperbarui.');
    }

    public function destroy(DataThread $dataThread)
    {
        $dataThread->delete();
        return redirect()->route('data-thread.index')
            ->with('success', 'Data Thread berhasil dihapus.');
    }

    /**
     * Import data dari CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        $count = 0;

        if (in_array($extension, ['xlsx', 'xls'])) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray(null, true, true, true);

            if (empty($rows)) {
                return redirect()->back()->with('error', 'File Excel kosong.');
            }

            $headerRow = array_shift($rows);
            $headers = [];
            foreach ($headerRow as $colLetter => $colName) {
                if ($colName !== null) {
                    $headers[$colLetter] = strtolower(trim($colName));
                }
            }

            foreach ($rows as $row) {
                if (empty(array_filter($row))) {
                    continue;
                }

                $data = [];
                foreach ($headers as $colLetter => $headerName) {
                    $data[$headerName] = $row[$colLetter] ?? null;
                }

                $sentimenRaw = strtolower(trim($data['sentimen'] ?? 'netral'));
                $sentimen = in_array($sentimenRaw, ['positif', 'negatif', 'netral']) ? $sentimenRaw : 'netral';

                $tanggal = null;
                $tanggalRaw = $data['tanggal'] ?? null;
                if (!empty($tanggalRaw)) {
                    if (is_numeric($tanggalRaw)) {
                        try {
                            $tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggalRaw)->format('Y-m-d');
                        } catch (\Exception $e) {
                            $tanggal = null;
                        }
                    } else {
                        try {
                            $tanggal = \Illuminate\Support\Carbon::parse($tanggalRaw)->format('Y-m-d');
                        } catch (\Exception $e) {
                            $tanggal = null;
                        }
                    }
                }

                $teksAsli = $data['text'] ?? ($data['teks_asli'] ?? ($data['teks'] ?? ''));
                if (empty(trim($teksAsli))) {
                    continue;
                }

                DataThread::create([
                    'username'  => $data['username'] ?? null,
                    'teks_asli' => $teksAsli,
                    'tanggal'   => $tanggal,
                    'sentimen'  => $sentimen,
                ]);
                $count++;
            }
        } else {
            $handle = fopen($file->getPathname(), 'r');
            $header = fgetcsv($handle);
            if ($header) {
                $header = array_map(function($h) { return strtolower(trim($h)); }, $header);
                while (($row = fgetcsv($handle)) !== false) {
                    $data = array_combine($header, $row);
                    $sentimenRaw = strtolower(trim($data['sentimen'] ?? 'netral'));
                    $sentimen = in_array($sentimenRaw, ['positif', 'negatif', 'netral']) ? $sentimenRaw : 'netral';

                    $tanggal = null;
                    if (!empty($data['tanggal'])) {
                        try {
                            $tanggal = \Illuminate\Support\Carbon::parse($data['tanggal'])->format('Y-m-d');
                        } catch (\Exception $e) {
                            $tanggal = null;
                        }
                    }

                    $teksAsli = $data['text'] ?? ($data['teks_asli'] ?? ($data['teks'] ?? ''));
                    if (empty(trim($teksAsli))) {
                        continue;
                    }

                    DataThread::create([
                        'username'  => $data['username'] ?? null,
                        'teks_asli' => $teksAsli,
                        'tanggal'   => $tanggal,
                        'sentimen'  => $sentimen,
                    ]);
                    $count++;
                }
            }
            fclose($handle);
        }

        return redirect()->route('data-thread.index')
            ->with('success', "Berhasil mengimpor {$count} data Thread.");
    }

    /**
     * Hapus semua Data Thread jika seluruh proses (Preprocessing, Unigram, Naive Bayes) telah di-reset
     */
    public function destroyAll()
    {
        $hasPreprocessing = \App\Models\PreprocessingThread::count() > 0;
        $hasUnigram       = \App\Models\UnigramThread::count() > 0;
        $hasNbHasil       = \App\Models\NaiveBayesHasil::where('sumber', 'thread')->count() > 0;
        $hasNbModel       = \App\Models\NaiveBayesModel::where('sumber', 'thread')->count() > 0;

        if ($hasPreprocessing || $hasUnigram || $hasNbHasil || $hasNbModel) {
            return redirect()->back()->with('error', 'Gagal menghapus data! Seluruh proses (Preprocessing, Unigram, atau Naive Bayes Data Thread) harus di-reset terlebih dahulu.');
        }

        DataThread::query()->delete();

        return redirect()->route('data-thread.index')
            ->with('success', 'Semua Data Thread berhasil dihapus.');
    }
}
