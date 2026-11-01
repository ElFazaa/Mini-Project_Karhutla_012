# Pelaporan dan Pemantauan Karhutla

Aplikasi web komunitas untuk melaporkan indikasi kebakaran hutan dan lahan, meninjau informasi, dan mencatat penerusan informasi kepada pihak terkait. Mini Project Proyek 3, Kasus B.

## Identitas mahasiswa

- Nama: **Hasbi Wirya Al Faza**.
- NIM: **251511012**.
- Kelas: **2A-D3**.
- Repository: [Mini-Project_Karhutla_012](https://github.com/ElFazaa/Mini-Project_Karhutla_012).

## Kebutuhan environment

- PHP 8.2 atau versi yang kompatibel dengan dependensi pada `composer.lock`.
- Composer 2.
- MySQL atau MariaDB, misalnya melalui XAMPP.
- Ekstensi PHP: PDO MySQL, Fileinfo, Mbstring, OpenSSL, XML, Ctype, Tokenizer, dan ZIP untuk instalasi dependensi.
- Git dan browser. Chrome atau Edge dapat dipakai untuk memeriksa DevTools Network.

Project menggunakan Laravel 12, Blade, CSS sederhana, dan JavaScript di folder `public/js`.

## Instalasi

Langkah berikut untuk memasang salinan project yang baru melalui PowerShell.

### 1. Ambil source code dan install dependensi

```powershell
git clone https://github.com/ElFazaa/Mini-Project_Karhutla_012.git
cd Mini-Project_Karhutla_012
composer install
```

### 2. Siapkan konfigurasi dan database

Salin template jika `.env` belum ada:

```powershell
if (-not (Test-Path .env)) { Copy-Item .env.example .env }
```

Jalankan MySQL/MariaDB. Buat database `karhutla` melalui phpMyAdmin, atau jalankan SQL berikut:

```sql
CREATE DATABASE IF NOT EXISTS karhutla
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Sesuaikan `.env` dengan akun database lokal:

```dotenv
APP_NAME=Karhutla
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=karhutla
DB_USERNAME=root
DB_PASSWORD=
FILESYSTEM_DISK=local
```

Password database kosong hanya berlaku jika akun database lokal memang tanpa password. File `.env` dikecualikan dari Git.

### 3. Buat key, tabel, dan data demo

```powershell
php artisan key:generate
php artisan config:clear
php artisan migrate
php artisan db:seed
php artisan migrate:status
```

### 4. Jalankan aplikasi

```powershell
php artisan serve
```

Buka [halaman login](http://127.0.0.1:8000/login). Untuk project yang sudah terpasang, aktifkan MySQL/MariaDB lalu jalankan `php artisan serve` dari folder project.

## Akun demo

Password semua akun: **`DemoKarhutla012`**.

| Nama | Email | Role |
|---|---|---|
| Pelapor Demo | pelapor@example.test | Pelapor |
| Pelapor Kedua Demo | pelapor2@example.test | Pelapor |
| Pemantau Demo | pemantau@example.test | Pemantau |
| Koordinator Demo | koordinator@example.test | Koordinator |

Seeder menyiapkan tiga wilayah dan 18 laporan dummy: 12 milik pelapor utama dan enam milik pelapor kedua. Tersedia lima status, catatan peninjauan, penerusan informasi, dan enam bukti PNG dummy.

Seed ulang mengembalikan nama, role, dan password akun demo ke nilai di atas. Seeder laporan melewati data yang sudah ditemukan berdasarkan pemilik dan judul demo yang sama. Jika judul demo diubah, seeder dapat membuat kembali contoh berjudul asli.

## Fitur dan kewenangan

| Role | Fitur |
|---|---|
| Pelapor | Membuat laporan; melihat ringkasan wilayah melalui fetch; melihat daftar, detail, bukti, dan perkembangan laporan sendiri; memperbaiki isi laporan pada status yang diizinkan |
| Pemantau | Melihat laporan dan bukti; membandingkan laporan di wilayah sama; memberi catatan; meminta perbaikan, menandai perlu diteruskan, atau menutup laporan Baru dengan alasan |
| Koordinator | Mencatat penerusan dan penutupan; mengelola wilayah; menambah/mengedit akun dan role |
| Pemantau dan Koordinator | Melihat rekap laporan dengan filter |

Daftar laporan ketiga role menyediakan pencarian judul, filter wilayah/status, dan pagination yang mempertahankan filter. Hak akses diperiksa di server. Pelapor hanya dapat mengakses laporan dan bukti miliknya.

## Alur status dan business rule

Laporan dibuat dengan status **Baru**. Pemantau dapat meminta **Perlu perbaikan**; setelah pemilik memperbaiki dan mengirim ulang, status kembali **Baru**. Jika diperlukan, pemantau menandai **Perlu diteruskan**. Koordinator mencatat penerusan sehingga menjadi **Diteruskan**, kemudian menutup menjadi **Selesai** dengan alasan.

Pemantau juga dapat menutup laporan Baru langsung menjadi Selesai dengan alasan apabila tidak perlu diteruskan.

- **BR-01:** Pelapor hanya mengubah laporan miliknya pada status Baru atau Perlu perbaikan.
- **BR-02:** Laporan Perlu perbaikan harus dikirim ulang sebelum ditinjau kembali.
- **BR-03:** Penerusan hanya dapat dicatat saat status Perlu diteruskan atau Diteruskan. Pihak tujuan tambahan boleh dicatat selama status Diteruskan.
- **BR-04:** Koordinator hanya menutup laporan Diteruskan yang sudah mempunyai catatan penerusan. Alasan wajib diisi.
- **BR-05:** Laporan Selesai tidak dapat diedit, ditinjau ulang, atau ditambahkan penerusan.
- **BR-06:** Wilayah yang dipakai laporan tidak dapat dihapus.
- **BR-07:** Koordinator tidak dapat menurunkan role akunnya sendiri.

Peninjauan memeriksa informasi masyarakat, bukan penetapan resmi kejadian kebakaran. Selesai berarti proses komunitas dalam aplikasi ditutup, bukan pernyataan bahwa kebakaran telah padam. Penerusan informasi berupa pencatatan; aplikasi tidak mengirim pesan kepada pihak eksternal.

## Bukti pendukung

Satu laporan dapat memiliki satu bukti opsional berupa JPG/JPEG, PNG, atau PDF maksimal 2 MB. Laravel Storage menyimpan file secara privat di `storage/app/private/bukti/`, sedangkan database menyimpan path di `bukti_path`. File dibuka melalui endpoint yang memeriksa hak akses. Penyimpanan privat ini tidak memerlukan `storage:link`.

Sumber bukti demo adalah `database/seeders/fixtures/bukti-demo.png`, berupa tulisan BUKTI UJI COBA. Salinan di `storage/app/private/bukti/demo/` bernama `screenshot3.png`, `screenshot6.png`, `screenshot9.png`, `screenshot12.png`, `screenshot15.png`, dan `screenshot18.png`.

## Rekap dan Observer

Rekap untuk pemantau/koordinator menyediakan total laporan, laporan aktif, laporan selesai, serta tabel per wilayah. Filter wilayah dan tanggal memakai **tanggal pembuatan laporan**, bukan waktu kejadian. Semua status selain Selesai dihitung aktif.

`LaporanObserver` mencatat perubahan status ke `storage/logs/laravel.log` setelah transaksi berhasil. Catatan berisi ID laporan, status sebelum/sesudah, ID pelaksana jika tersedia, dan waktu.

```powershell
Select-String -Path storage/logs/laravel.log -Pattern 'Perubahan status laporan' | Select-Object -Last 5
```

## Pengujian manual

1. Coba login benar/salah, logout, dan akses halaman terlindungi sebagai guest.
2. Buat laporan valid; coba field wajib kosong, wilayah tidak valid, dan koordinat di luar rentang.
3. Coba bukti valid, file jenis lain, dan file di atas 2 MB.
4. Login pelapor kedua; coba URL detail, bukti, dan edit milik pelapor pertama. Akses harus ditolak.
5. Jalankan alur perbaikan, kirim ulang, tinjau, teruskan, lalu tutup. Coba tindakan salah urutan dan edit setelah selesai.
6. Akses endpoint staf menggunakan role yang salah; akses harus ditolak dan data tidak berubah.
7. Pada Buat Laporan, buka DevTools Network > Fetch/XHR, lalu ganti wilayah. Periksa GET `/wilayah/{id}/ringkasan`, status 200 dan respons JSON, sementara isian form tetap ada.
8. Gunakan pencarian/filter lalu pindah halaman; filter harus tetap aktif.
9. Ubah status dan periksa catatan Observer pada log.
10. Gunakan filter rekap dan cocokkan ringkasan dengan tabel: total sama dengan aktif ditambah selesai.

Pengujian mandiri pengguna dan screenshot Network perlu dilengkapi sebelum peer testing. Panduan ini tidak menyatakan pengujian mandiri tersebut sudah dilakukan.
