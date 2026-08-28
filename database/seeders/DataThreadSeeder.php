<?php

namespace Database\Seeders;

use App\Models\DataThread;
use Illuminate\Database\Seeder;

class DataThreadSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // POSITIF
            ['username' => 'threaduser_eko',   'teks_asli' => 'Mau berbagi pengalaman positif saya dgn Koperasi Merah Putih. Setelah bergabung 6 bulan lalu, usaha warung kelontong saya makin berkembang. Modal lebih mudah dan terjangkau 🙏', 'tanggal' => '2025-01-06', 'sentimen' => 'positif'],
            ['username' => 'umkm_sukses',      'teks_asli' => 'KMP beneran mantap! Saya petani kopi di Jawa Tengah. Dgn adanya koperasi ini saya bisa jual kopi langsung ke konsumen tanpa perantara. Keuntungan jauh lebih besar', 'tanggal' => '2025-01-09', 'sentimen' => 'positif'],
            ['username' => 'ibu_warung',       'teks_asli' => 'Program Koperasi Merah Putih sangat membantu ibu2 pedagang kecil spt saya. Pinjaman modal cukup ringan dan cicilannnya tidak memberatkan. Sangat bersyukur!', 'tanggal' => '2025-01-11', 'sentimen' => 'positif'],
            ['username' => 'kopmer_fans',      'teks_asli' => 'Koperasi Merah Putih hadir sebagai solusi nyata bagi UMKM Indonesia! Semoga program ini terus didukung dan tidak mati di tengah jalan spt program2 sebelumnya', 'tanggal' => '2025-01-14', 'sentimen' => 'positif'],
            ['username' => 'generasi_koop',    'teks_asli' => 'Bangga Indonesia punya program Koperasi Merah Putih! Ini bukti pemerintah serius dukung ekonomi kerakyatan. Anak muda juga harus ikut terlibat aktif', 'tanggal' => '2025-01-17', 'sentimen' => 'positif'],
            ['username' => 'nelayan_senang',   'teks_asli' => 'Koperasi Merah Putih sangat membantu komunitas nelayan di desa kami. Kami bisa beli es dan BBM lebih murah, hasil tangkapan juga lebih mudah dipasarkan', 'tanggal' => '2025-01-19', 'sentimen' => 'positif'],
            ['username' => 'petani_happy',     'teks_asli' => 'Bergabung dgn KMP adalah keputusan terbaik yg pernah saya buat. Harga pupuk jauh lebih murah, dan kami punya akses ke pasar yg lebih luas. Alhamdulillah!', 'tanggal' => '2025-01-21', 'sentimen' => 'positif'],
            ['username' => 'wirausaha_muda',   'teks_asli' => 'Program koperasi ini sangat relevan untuk mendukung wirausaha muda Indonesia. Harapannya bisa semakin banyak anak muda yg memanfaatkan fasilitas KMP ini', 'tanggal' => '2025-01-24', 'sentimen' => 'positif'],
            ['username' => 'sukses_koperasi',  'teks_asli' => 'Tidak percaya sebelumnya, tapi setelah 8 bulan bergabung KMP, usaha tahu tempe saya benar2 berkembang. Omzet naik 40%. Terima kasih Koperasi Merah Putih!', 'tanggal' => '2025-01-26', 'sentimen' => 'positif'],
            ['username' => 'pedagang_senang',  'teks_asli' => 'KMP terbukti membantu pedagang kecil! Saya tidak perlu lagi pinjam uang ke rentenir. Bunga koperasi jauh lebih manusiawi dan prosesnya cepat', 'tanggal' => '2025-01-29', 'sentimen' => 'positif'],

            // NEGATIF
            ['username' => 'kecewa_kmp',       'teks_asli' => 'Sangat kecewa dgn Koperasi Merah Putih di daerah saya! Program ini hanya bagus di kertas, realisasinya sangat buruk. Pengurusnya tidak profesional sama sekali', 'tanggal' => '2025-02-02', 'sentimen' => 'negatif'],
            ['username' => 'protes_koperasi',  'teks_asli' => 'KMP di kecamatan kami tidak transparan! Tidak ada laporan keuangan yg jelas. Uang iuran anggota entah kemana. Minta aparat segera turun tangan!', 'tanggal' => '2025-02-04', 'sentimen' => 'negatif'],
            ['username' => 'gagal_umkm',       'teks_asli' => 'Program Koperasi Merah Putih ini terlalu banyak syarat administrasinya! UMKM kecil spt kami tidak mampu memenuhi semua persyaratan yg diminta. Diskriminatif!', 'tanggal' => '2025-02-06', 'sentimen' => 'negatif'],
            ['username' => 'frustasi_petani',  'teks_asli' => 'Sudah 4 bulan mengajukan pinjaman ke KMP, terus2an ditolak tanpa alasan jelas. Program ini seolah hanya utk orang2 yg punya koneksi saja. Sangat tidak adil!', 'tanggal' => '2025-02-09', 'sentimen' => 'negatif'],
            ['username' => 'thread_kritik',    'teks_asli' => 'Mari kita jujur: Koperasi Merah Putih belum memberikan dampak signifikan. Masalah utamanya adalah lemahnya SDM pengurus dan tidak adanya sistem pengawasan yg ketat', 'tanggal' => '2025-02-11', 'sentimen' => 'negatif'],
            ['username' => 'buruh_marah',      'teks_asli' => 'Koperasi Merah Putih tidak berguna bagi kaum buruh! Kita tidak termasuk kriteria yg dilayani. Program ini hanya fokus ke petani dan pedagang. Sangat tidak inklusif', 'tanggal' => '2025-02-14', 'sentimen' => 'negatif'],
            ['username' => 'warga_kecewa',     'teks_asli' => 'Pengurus KMP di desa kami sering tidak hadir di kantor. Susah banget mau konsultasi. Padahal kami butuh bantuan cepat. Program ini gagal dalam pelayanan!', 'tanggal' => '2025-02-16', 'sentimen' => 'negatif'],
            ['username' => 'korban_kmp',       'teks_asli' => 'Saya jadi korban pengelolaan KMP yg buruk! Dana simpanan saya tidak bisa dicairkan sudah 3 bulan dgn berbagai alasan. Ini namanya PENIPUAN berkedok koperasi!', 'tanggal' => '2025-02-19', 'sentimen' => 'negatif'],
            ['username' => 'suara_pedagang',   'teks_asli' => 'KMP tidak peka kondisi pedagang kecil! Jumlah pinjaman maksimal terlalu kecil, tidak cukup utk modal usaha. Pemerintah perlu evaluasi besaran plafon pinjaman ini', 'tanggal' => '2025-02-21', 'sentimen' => 'negatif'],
            ['username' => 'masyarakat_kritis','teks_asli' => 'Koperasi Merah Putih seperti proyek politisi saja! Ramai saat kampanye, sepi realisasi. Masyarakat capek dijanjikan program yg tidak terlaksana dengan baik', 'tanggal' => '2025-02-23', 'sentimen' => 'negatif'],

            // NETRAL
            ['username' => 'info_kmp_thread',  'teks_asli' => 'Thread ini berisi informasi lengkap ttg syarat dan ketentuan bergabung Koperasi Merah Putih. Silakan dibaca dan disebarluaskan ya. 1/5', 'tanggal' => '2025-03-02', 'sentimen' => 'netral'],
            ['username' => 'analisis_koop',    'teks_asli' => 'Dari data yg ada, program KMP memiliki potensi besar namun juga tantangan serius. Artikel ini mencoba menganalisis secara objektif dari berbagai sisi', 'tanggal' => '2025-03-04', 'sentimen' => 'netral'],
            ['username' => 'sejarah_koperasi', 'teks_asli' => 'Koperasi Merah Putih terinspirasi dari gerakan koperasi yg digagas Bung Hatta. Program ini mencoba menghidupkan kembali semangat koperasi Indonesia', 'tanggal' => '2025-03-07', 'sentimen' => 'netral'],
            ['username' => 'data_program_kmp', 'teks_asli' => 'Berdasarkan laporan kemenkop per Februari 2025: 2.500 unit KMP aktif, 480 ribu anggota terdaftar, total penyaluran pinjaman Rp 8,5 triliun', 'tanggal' => '2025-03-09', 'sentimen' => 'netral'],
            ['username' => 'diskusi_ekonomi',  'teks_asli' => 'Apa pendapat kalian ttg Koperasi Merah Putih? Apakah program ini sudah berjalan sesuai harapan atau masih perlu banyak perbaikan? Share di kolom reply', 'tanggal' => '2025-03-11', 'sentimen' => 'netral'],
            ['username' => 'review_kmp',       'teks_asli' => 'Melakukan kunjungan ke beberapa KMP di Jawa Timur. Hasilnya bervariasi: ada yg sangat baik dikelola, ada pula yg masih perlu banyak pembenahan', 'tanggal' => '2025-03-14', 'sentimen' => 'netral'],
            ['username' => 'perbandingan_koop','teks_asli' => 'Membandingkan KMP dgn koperasi di Korea Selatan dan Jepang. Masih jauh perjalanannya, tapi fondasi yg dibangun cukup menjanjikan jika dikelola dgn baik', 'tanggal' => '2025-03-16', 'sentimen' => 'netral'],
            ['username' => 'sosiolog_desa',    'teks_asli' => 'Dari penelitian lapangan, dampak KMP terhadap kesejahteraan masyarakat desa masih perlu waktu utk dirasakan. Setidaknya butuh 3-5 tahun utk melihat hasilnya', 'tanggal' => '2025-03-19', 'sentimen' => 'netral'],
            ['username' => 'update_kmp',       'teks_asli' => 'Pemerintah berencana memperluas program KMP ke sektor perikanan dan pariwisata mulai Q3 2025. Regulasi pendukung sedang disiapkan kemenkop', 'tanggal' => '2025-03-21', 'sentimen' => 'netral'],
            ['username' => 'thread_edukasi',   'teks_asli' => 'Edukasi koperasi: KMP beroperasi berdasarkan prinsip gotong royong dan demokrasi ekonomi. Setiap anggota punya hak suara yg sama dalam pengambilan keputusan', 'tanggal' => '2025-03-23', 'sentimen' => 'netral'],
        ];

        foreach ($data as $item) {
            DataThread::create($item);
        }
    }
}
