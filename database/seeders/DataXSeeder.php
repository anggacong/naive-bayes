<?php

namespace Database\Seeders;

use App\Models\DataX;
use Illuminate\Database\Seeder;

class DataXSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // POSITIF
            ['username' => 'wulandari_id',    'teks_asli' => 'Koperasi Merah Putih ini bagus banget! Program pemerintah yg bener2 bermanfaat buat rakyat kecil. Semoga terus berkembang dan maju 🙏', 'tanggal' => '2025-01-05', 'sentimen' => 'positif'],
            ['username' => 'budi_santoso92',  'teks_asli' => 'Alhamdulillah, akhirnya ada program koperasi yg serius dari pemerintah. KMP ini bikin UMKM makin berdaya dan mandiri. Mantap!', 'tanggal' => '2025-01-08', 'sentimen' => 'positif'],
            ['username' => 'sari_ekonomi',    'teks_asli' => 'Koperasi Merah Putih bener2 membantu petani di desa kami. Modal lebih mudah, distribusi lancar. Terima kasih program ini!!!', 'tanggal' => '2025-01-10', 'sentimen' => 'positif'],
            ['username' => 'agus_wirausaha',  'teks_asli' => 'Program kopmer ini keren! Bunga pinjamannya rendah dan prosedurnya gak ribet. Cocok banget buat usaha kecil kayak saya 😊', 'tanggal' => '2025-01-12', 'sentimen' => 'positif'],
            ['username' => 'maya_cooperative','teks_asli' => 'Seneng banget lihat Koperasi Merah Putih makin berkembang. Ini bukti pemerintah serius dukung ekonomi kerakyatan. Salut!!', 'tanggal' => '2025-01-15', 'sentimen' => 'positif'],
            ['username' => 'fitri_umkm',      'teks_asli' => 'Koperasi Merah Putih = harapan baru buat pedagang kecil! Akses modal jd lebih mudah dan transparan. Program ini harus terus jalan', 'tanggal' => '2025-01-18', 'sentimen' => 'positif'],
            ['username' => 'hendro_tani',     'teks_asli' => 'Program KMP membantu banget buat petani desa. Sekarang kami bisa beli pupuk dan alat tani lebih murah lewat koperasi. Mantep!', 'tanggal' => '2025-01-20', 'sentimen' => 'positif'],
            ['username' => 'rizka_ekonomi',   'teks_asli' => 'Akhirnya ada program koperasi yg dijalankan dengan serius dan transparan. KMP ini layak diapresiasi. Semoga makin sukses!', 'tanggal' => '2025-01-22', 'sentimen' => 'positif'],
            ['username' => 'dewi_mart',       'teks_asli' => 'Dengan adanya Koperasi Merah Putih, warung saya makin bisa bersaing. Harga beli barang jd lebih murah karena beli bareng2', 'tanggal' => '2025-01-25', 'sentimen' => 'positif'],
            ['username' => 'pak_karto_76',    'teks_asli' => 'Program bagus ini! Koperasi Merah Putih benar2 membantu masyarakat bawah. Harap pemerintah terus mendukung dan tidak setengah2', 'tanggal' => '2025-01-28', 'sentimen' => 'positif'],

            // NEGATIF
            ['username' => 'kritikus_eko',    'teks_asli' => 'Koperasi Merah Putih cuma angin segar doang! Implementasinya di lapangan sangat buruk. Birokrasi masi ribet, petani masih susah dapet manfaat', 'tanggal' => '2025-02-01', 'sentimen' => 'negatif'],
            ['username' => 'suara_rakyat77',  'teks_asli' => 'Program KMP ini gagal total di daerah kami! Pengurusnya tidak transparan, laporan keuangan gak jelas. Harus diaudit segera!!', 'tanggal' => '2025-02-03', 'sentimen' => 'negatif'],
            ['username' => 'petani_marah',    'teks_asli' => 'Kecewa bgt dgn Koperasi Merah Putih!! Udah daftar 3 bulan gak ada kabar. Prosedurnya rumit banget, kita org awam susah paham', 'tanggal' => '2025-02-05', 'sentimen' => 'negatif'],
            ['username' => 'pedagang_keluh',  'teks_asli' => 'Program koperasi ini tidak adil! Yg dapat manfaat cuma org2 tertentu aja. Pedagang kecil kayak kami malah gak dilirik sama sekali', 'tanggal' => '2025-02-08', 'sentimen' => 'negatif'],
            ['username' => 'anti_korupsi99',  'teks_asli' => 'Koperasi Merah Putih rawan korupsi! Dana program bisa disalahgunakan jika pengawasan lemah. Pemerintah harus lebih ketat ngawasinnya', 'tanggal' => '2025-02-10', 'sentimen' => 'negatif'],
            ['username' => 'warga_desa2025',  'teks_asli' => 'Di desa kami program KMP tidak berjalan sama sekali. Pengurus koperasinya gak aktif, dananya gak tau kemana. Sangat mengecewakan!!', 'tanggal' => '2025-02-12', 'sentimen' => 'negatif'],
            ['username' => 'nelayan_mengeluh','teks_asli' => 'Nelayan kecil seperti kami tidak merasakan manfaat Koperasi Merah Putih. Pinjaman ditolak terus dengan alasan tidak memenuhi syarat yg ribet', 'tanggal' => '2025-02-15', 'sentimen' => 'negatif'],
            ['username' => 'usaha_bangkrut',  'teks_asli' => 'Gak ada gunanya KMP buat kami!! Bunga pinjaman tetap tinggi, tidak ada bedanya dgn bank biasa. Program gagal! Cuma janji palsu', 'tanggal' => '2025-02-18', 'sentimen' => 'negatif'],
            ['username' => 'buruh_protes',    'teks_asli' => 'Program Koperasi Merah Putih sangat tidak merata. Daerah perkotaan dapat banyak bantuan, daerah terpencil tidak dapat apa-apa. Diskriminatif!', 'tanggal' => '2025-02-20', 'sentimen' => 'negatif'],
            ['username' => 'rakyat_kritis',   'teks_asli' => 'KMP cuma formalitas! Realisasi di lapangan jauh dari harapan. Pemerintah jangan hanya pandai bikin program tapi implementasinya amburadul', 'tanggal' => '2025-02-22', 'sentimen' => 'negatif'],

            // NETRAL
            ['username' => 'pengamat_eko',    'teks_asli' => 'Koperasi Merah Putih diluncurkan sbg program andalan pemerintah utk mendorong ekonomi kerakyatan. Program ini menyasar UMKM dan petani desa', 'tanggal' => '2025-03-01', 'sentimen' => 'netral'],
            ['username' => 'info_koperasi',   'teks_asli' => 'Update: Koperasi Merah Putih kini telah beroperasi di 34 provinsi dengan total anggota mencapai 500 ribu orang per Januari 2025', 'tanggal' => '2025-03-03', 'sentimen' => 'netral'],
            ['username' => 'berita_hari_ini', 'teks_asli' => 'Pemerintah mengalokasikan Rp 50 triliun untuk program Koperasi Merah Putih tahun 2025. Dana akan disalurkan melalui kemendag dan kemenkop', 'tanggal' => '2025-03-05', 'sentimen' => 'netral'],
            ['username' => 'jurnalis_eko',    'teks_asli' => 'Bagaimana sebenarnya kinerja Koperasi Merah Putih? Ada yg bilang berhasil, ada juga yg mengeluhkan implementasinya. Perlu evaluasi menyeluruh', 'tanggal' => '2025-03-08', 'sentimen' => 'netral'],
            ['username' => 'akademisi_eko',   'teks_asli' => 'Secara konsep, Koperasi Merah Putih punya potensi besar. Namun keberhasilannya sangat bergantung pada tata kelola dan pengawasan yg baik', 'tanggal' => '2025-03-10', 'sentimen' => 'netral'],
            ['username' => 'data_umkm',       'teks_asli' => 'Statistik KMP 2025: 60% anggota merasa terbantu, 25% tidak merasakan perubahan, 15% menyatakan ada masalah dalam pelaksanaan program', 'tanggal' => '2025-03-12', 'sentimen' => 'netral'],
            ['username' => 'wartawan_lokal',  'teks_asli' => 'Koperasi Merah Putih akan mengadakan rapat anggota tahunan bulan depan. Agenda: laporan keuangan dan rencana program 2026', 'tanggal' => '2025-03-15', 'sentimen' => 'netral'],
            ['username' => 'observer_eko',    'teks_asli' => 'Perlu dilihat lebih jauh apakah program KMP ini benar2 sustainable atau hanya bersifat jangka pendek. Data 2 tahun ke depan akan jadi penentu', 'tanggal' => '2025-03-18', 'sentimen' => 'netral'],
            ['username' => 'peneliti_desa',   'teks_asli' => 'Penelitian kami menunjukkan efektivitas KMP bervariasi antar wilayah. Faktor SDM pengurus dan infrastruktur daerah sangat mempengaruhi hasil', 'tanggal' => '2025-03-20', 'sentimen' => 'netral'],
            ['username' => 'analis_kebijakan','teks_asli' => 'Program Koperasi Merah Putih merupakan kebijakan yg kompleks. Butuh sinergi antara pemerintah pusat, daerah, dan masyarakat agar berhasil', 'tanggal' => '2025-03-22', 'sentimen' => 'netral'],
        ];

        foreach ($data as $item) {
            DataX::create($item);
        }
    }
}
