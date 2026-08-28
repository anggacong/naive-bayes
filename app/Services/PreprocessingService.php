<?php

namespace App\Services;

use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\Stemmer\StemmerInterface;

class PreprocessingService
{
    private array $kamusNormalisasi;
    private array $stopwords;
    private StemmerInterface $stemmer;

    public function __construct()
    {
        // Load dari database satu kali ke memori agar preprocessing di dalam loop tetap sangat cepat
        $this->kamusNormalisasi = \App\Models\KamusNormalisasi::pluck('formal', 'slang')->toArray();
        $this->stopwords = \App\Models\Stopword::pluck('kata')->toArray();

        $stemmerFactory = new StemmerFactory();
        $this->stemmer = $stemmerFactory->createStemmer();
    }

    /**
     * Jalankan seluruh pipeline preprocessing dan kembalikan tiap tahap.
     */
    public function prosesLengkap(string $teksAsli): array
    {
        $cleaning      = $this->cleaning($teksAsli);
        $caseFolding   = $this->caseFolding($cleaning);
        $tokenizing    = $this->tokenizing($caseFolding);
        $repeatedChar  = $this->repeatedCharRemoval($tokenizing);
        $normalisasi   = $this->normalisasi($repeatedChar);
        $stopword      = $this->stopwordRemoval($normalisasi);
        $stemming      = $this->stemming($stopword);

        return [
            'hasil_cleaning'      => $cleaning,
            'hasil_case_folding'  => $caseFolding,
            'hasil_tokenizing'    => $tokenizing,
            'hasil_repeated_char' => implode(' ', $repeatedChar),
            'hasil_normalisasi'   => implode(' ', $normalisasi),
            'hasil_stopword'      => implode(' ', $stopword),
            'hasil_stemming'      => implode(' ', $stemming),
        ];
    }

    // ─────────────────────────────────────────────────────────────
    // 1. CLEANING
    //    Hapus: URL, mention (@user), hashtag (#tag), RT,
    //           emoji/non-ASCII, angka, tanda baca, spasi berlebih
    // ─────────────────────────────────────────────────────────────
    public function cleaning(string $teks): string
    {
        // Hapus URL (http/https/www)
        $teks = preg_replace('/https?:\/\/\S+|www\.\S+/u', '', $teks);

        // Hapus mention (@username)
        $teks = preg_replace('/@\w+/u', '', $teks);

        // Hapus hashtag (#kata)
        $teks = preg_replace('/#\w+/u', '', $teks);

        // Hapus "RT" di awal tweet
        $teks = preg_replace('/\bRT\b/u', '', $teks);

        // Hapus emoji dan karakter non-ASCII
        $teks = preg_replace('/[^\x00-\x7F]/u', '', $teks);

        // Hapus angka
        $teks = preg_replace('/\d+/u', '', $teks);

        // Hapus tanda baca dan karakter khusus (kecuali spasi & huruf)
        $teks = preg_replace('/[^a-zA-Z\s]/u', ' ', $teks);

        // Normalisasi spasi berlebih
        $teks = preg_replace('/\s+/', ' ', $teks);

        return trim($teks);
    }

    // ─────────────────────────────────────────────────────────────
    // 2. CASE FOLDING
    //    Ubah semua huruf menjadi lowercase
    // ─────────────────────────────────────────────────────────────
    public function caseFolding(string $teks): string
    {
        return strtolower($teks);
    }

    // ─────────────────────────────────────────────────────────────
    // 3. TOKENIZING
    //    Pecah teks menjadi array token berdasarkan spasi
    // ─────────────────────────────────────────────────────────────
    public function tokenizing(string $teks): array
    {
        if (trim($teks) === '') {
            return [];
        }
        return array_values(array_filter(explode(' ', $teks), fn($t) => $t !== ''));
    }

    // ─────────────────────────────────────────────────────────────
    // 4. REPEATED CHARACTER REMOVAL
    //    Kompress karakter berulang > 2: "bagussss" → "bagus"
    // ─────────────────────────────────────────────────────────────
    public function repeatedCharRemoval(array $tokens): array
    {
        return array_map(function (string $token) {
            return preg_replace('/(.)\1{2,}/', '$1$1', $token);
        }, $tokens);
    }

    // ─────────────────────────────────────────────────────────────
    // 5. NORMALISASI
    //    Ganti kata tidak baku/slang dengan kamus normalisasi
    // ─────────────────────────────────────────────────────────────
    public function normalisasi(array $tokens): array
    {
        return array_map(function (string $token) {
            return $this->kamusNormalisasi[$token] ?? $token;
        }, $tokens);
    }

    // ─────────────────────────────────────────────────────────────
    // 6. STOPWORD REMOVAL
    //    Hapus kata-kata stopword bahasa Indonesia
    // ─────────────────────────────────────────────────────────────
    public function stopwordRemoval(array $tokens): array
    {
        return array_values(array_filter($tokens, function (string $token) {
            return !in_array(strtolower($token), $this->stopwords);
        }));
    }

    // ─────────────────────────────────────────────────────────────
    // 7. STEMMING (Sastrawi Nazief-Adriani + Kamus Kata Dasar)
    // ─────────────────────────────────────────────────────────────
    public function stemming(array $tokens): array
    {
        return array_values(array_map(function (string $token) {
            if (strlen($token) <= 2) {
                return $token;
            }
            return $this->stemmer->stem($token);
        }, $tokens));
    }
}
