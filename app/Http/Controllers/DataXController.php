<?php

namespace App\Http\Controllers;

use App\Models\DataX;
use App\Models\PreprocessingX;
use Illuminate\Http\Request;

class DataXController extends Controller
{
    public function index()
    {
        $data = DataX::latest()->paginate(15);
        return view('data-x.index', compact('data'));
    }

    public function create()
    {
        return view('data-x.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username'  => 'nullable|string|max:100',
            'teks_asli' => 'required|string',
            'tanggal'   => 'nullable|date',
            'sentimen'  => 'required|in:positif,negatif,netral',
        ]);

        DataX::create($validated);

        return redirect()->route('data-x.index')
            ->with('success', 'Data X berhasil ditambahkan.');
    }

    public function edit(DataX $dataX)
    {
        return view('data-x.edit', compact('dataX'));
    }

    public function update(Request $request, DataX $dataX)
    {
        $validated = $request->validate([
            'username'  => 'nullable|string|max:100',
            'teks_asli' => 'required|string',
            'tanggal'   => 'nullable|date',
            'sentimen'  => 'required|in:positif,negatif,netral',
        ]);

        $dataX->update($validated);

        // Hapus preprocessing lama jika ada (agar diproses ulang)
        $dataX->preprocessing()->delete();

        return redirect()->route('data-x.index')
            ->with('success', 'Data X berhasil diperbarui.');
    }

    public function destroy(DataX $dataX)
    {
        $dataX->delete();
        return redirect()->route('data-x.index')
            ->with('success', 'Data X berhasil dihapus.');
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

                DataX::create([
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

                    DataX::create([
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

        return redirect()->route('data-x.index')
            ->with('success', "Berhasil mengimpor {$count} data X.");
    }
}
