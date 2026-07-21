 Global Supply Chain Risk Monitoring System

Global Supply Chain Risk Monitoring System merupakan aplikasi berbasis web yang dikembangkan menggunakan Laravel Framework untuk membantu perusahaan maupun pengguna dalam memantau berbagai faktor yang dapat memengaruhi stabilitas rantai pasok (Global Supply Chain). Sistem ini mengintegrasikan beberapa sumber data eksternal, seperti kondisi cuaca, indikator ekonomi, nilai tukar mata uang, dan berita internasional sebagai dasar dalam melakukan analisis tingkat risiko pada suatu negara.

Melalui aplikasi ini, pengguna dapat memperoleh informasi secara real-time mengenai kondisi yang berpotensi mengganggu proses distribusi barang, seperti cuaca ekstrem, perubahan ekonomi, fluktuasi nilai tukar mata uang, maupun peristiwa internasional. Seluruh informasi tersebut kemudian diolah menjadi Risk Score sehingga pengguna dapat mengevaluasi tingkat risiko setiap negara sebelum menentukan strategi distribusi.Selain menyediakan fitur monitoring, sistem juga dilengkapi dengan visualisasi data, simulasi rute distribusi, perbandingan antar negara, serta halaman administrator untuk mengelola data master yang digunakan oleh sistem.

 A. Fitur Utama
 👤 Halaman User

1. Login User
Halaman autentikasi yang digunakan pengguna untuk masuk ke dalam sistem menggunakan email dan password. Setelah proses login berhasil, pengguna akan diarahkan menuju Dashboard untuk mengakses seluruh fitur monitoring sesuai hak akses yang dimiliki.

2. Dashboard
Dashboard merupakan halaman utama yang menampilkan ringkasan kondisi supply chain secara keseluruhan. Halaman ini berisi statistik jumlah negara, jumlah pelabuhan, jumlah data monitoring, serta grafik tingkat risiko yang memberikan gambaran kondisi rantai pasok global secara cepat.Halaman Dasboard ini juga menampilkan daftar negara yang menjadi objek monitoring dalam sistem. Pengguna dapat melihat informasi setiap negara beserta data yang digunakan dalam proses analisis risiko supply chain.

3. Weather Monitoring
Halaman Weather Monitoring menampilkan kondisi cuaca terkini dari setiap negara berdasarkan data yang diperoleh melalui Weather API. Informasi seperti suhu, kelembapan, kecepatan angin, maupun kondisi cuaca digunakan sebagai salah satu indikator dalam penilaian risiko karena cuaca ekstrem dapat menghambat aktivitas logistik dan distribusi barang.

4. Economic Indicators
Halaman Economic Indicators menyajikan berbagai indikator ekonomi dari setiap negara, seperti pertumbuhan ekonomi, inflasi, maupun indikator ekonomi lainnya. Data ini digunakan sebagai acuan dalam menilai stabilitas ekonomi suatu negara yang berpengaruh terhadap kelancaran aktivitas perdagangan internasional.

5. Exchange Rate
Halaman Exchange Rate menampilkan informasi nilai tukar mata uang berbagai negara secara real-time. Fluktuasi nilai tukar digunakan sebagai salah satu indikator risiko karena dapat memengaruhi biaya impor, ekspor, maupun transaksi perdagangan internasional.

6. News Monitoring
Halaman News Monitoring menyajikan berita internasional terbaru yang berkaitan dengan negara-negara yang dipantau. Berita mengenai konflik, bencana alam, perubahan kebijakan pemerintah, maupun kondisi geopolitik digunakan sebagai referensi dalam mengidentifikasi potensi gangguan terhadap rantai pasok global.

8. Port 
Halaman Port menampilkan informasi mengenai pelabuhan  yang ada disuatu negara yang ingin kita cari. Data pelabuhan membantu pengguna memahami lokasi pelabuhan dari peta pelabuhan yang tertampil

9. Port Map
Port Map & Ship Tracking merupakan fitur yang menampilkan peta interaktif lokasi pelabuhan beserta posisi kapal dan status operasionalnya. Fitur ini membantu pengguna memantau aktivitas pelabuhan serta mendukung analisis dan pemantauan jalur distribusi pada Global Supply Chain.

10. Risk
Halaman Risk merupakan fitur utama aplikasi yang digunakan untuk menghitung tingkat risiko supply chain pada setiap negara. Perhitungan dilakukan dengan mengombinasikan data cuaca, indikator ekonomi, nilai tukar mata uang, serta berita internasional sehingga menghasilkan Risk Score yang dapat digunakan sebagai dasar pengambilan keputusan.

11. Compare Country
Halaman Compare Country memungkinkan pengguna membandingkan tingkat risiko beberapa negara secara bersamaan. Perbandingan dilakukan berdasarkan seluruh indikator yang tersedia sehingga pengguna dapat menentukan negara dengan tingkat risiko paling rendah maupun paling tinggi.

12. Route Simulation
Halaman Route Simulation digunakan untuk melakukan simulasi jalur distribusi barang berdasarkan negara asal dan negara tujuan. Fitur ini membantu pengguna mengevaluasi potensi risiko yang mungkin terjadi selama proses distribusi sehingga dapat memilih rute yang lebih aman dan efisien.

13. Watchlist
Fitur Watchlist memungkinkan pengguna menyimpan daftar negara yang ingin dipantau secara khusus. Negara yang telah ditambahkan ke dalam Watchlist akan lebih mudah diakses sehingga proses monitoring dapat dilakukan dengan lebih cepat.

14. Profile

Halaman Profile digunakan untuk mengelola informasi akun pengguna, seperti memperbarui data pribadi, mengganti password, serta melihat informasi akun yang sedang digunakan.


👨‍💼 Halaman Admin
Administrator memiliki hak akses penuh terhadap seluruh data yang digunakan dalam sistem.

1. Login Admin
Halaman autentikasi administrator untuk mengakses seluruh fitur pengelolaan data.

2. Dashboard Admin
Dashboard Admin menampilkan ringkasan statistik aplikasi, seperti jumlah pengguna, jumlah negara, jumlah pelabuhan, serta aktivitas sistem secara keseluruhan.

3. Manajemen User
Halaman ini digunakan administrator untuk mengelola data pengguna aplikasi. Administrator dapat menambahkan pengguna baru, mengubah informasi pengguna, maupun menghapus akun yang sudah tidak digunakan.

4. Manajemen Port
Halaman ini digunakan untuk mengelola seluruh data pelabuhan yang digunakan dalam sistem. Administrator dapat menambahkan, mengubah, maupun menghapus data pelabuhan.

5. Manajemen Article/News
Halaman Manajemen Article digunakan untuk mengelola artikel atau berita yang akan ditampilkan pada halaman News Monitoring. Administrator dapat menambahkan, memperbarui, maupun menghapus artikel sesuai kebutuhan.


B. Arsitektur Sistem
Global Supply Chain Risk Monitoring System dibangun menggunakan arsitektur **Client–Server** dengan pola **Model–View–Controller (MVC)** yang diterapkan oleh Laravel Framework.

Arsitektur

```text
+---------------------+
|      Client         |
| (Web Browser/User)  |
+----------+----------+
           |
           | HTTP Request
           ▼
+-------------------------------+
|       Laravel Framework       |
|-------------------------------|
| Controller                    |
| Model                         |
| View (Blade Template)         |
| Middleware                    |
+---------------+---------------+
                |
        +-------+-------+
        |               |
        ▼               ▼
+---------------+   +------------------+
|    MySQL      |   | External REST API|
|   Database    |   | Weather API      |
|               |   | News API         |
|               |   | Exchange API     |
|               |   | Economic API     |
+---------------+   +------------------+
```

C. Alur Sistem

1. Pengguna melakukan login ke dalam sistem.
2. Sistem melakukan autentikasi menggunakan Laravel Authentication.
3. Laravel mengambil data dari database maupun API eksternal.
4. Data diproses melalui Model dan Controller.
5. Hasil monitoring ditampilkan dalam bentuk tabel, grafik, dan analisis risiko.
6. Administrator mengelola seluruh data master melalui halaman Admin.



D.  Teknologi

| Teknologi            | Keterangan                 |
| -------------------- | -------------------------- |
| Laravel 12           | Backend Framework          |
| PHP 8.x              | Bahasa Pemrograman         |
| MySQL                | Database Management System |
| Blade Template       | Template Engine            |
| Bootstrap 5          | Framework CSS              |
| HTML5                | Struktur Halaman           |
| CSS3                 | Styling Website            |
| JavaScript           | Interaktivitas             |
| Chart.js             | Visualisasi Data           |
| Laravel Eloquent ORM | ORM Database               |
| Git                  | Version Control            |
| GitHub               | Repository Source Code     |


E. API yang Digunakan

Aplikasi memanfaatkan beberapa REST API eksternal untuk memperoleh data secara real-time.

| API                     | Fungsi                                                       |
| ----------------------- | ------------------------------------------------------------ |
| Weather API             | Mengambil data cuaca setiap negara sebagai indikator risiko. |
| Exchange Rate API       | Mengambil data nilai tukar mata uang terkini.                |
| News API                | Mengambil berita internasional berdasarkan negara.           |
| Economic Indicators API | Mengambil data indikator ekonomi setiap negara.              |

Seluruh data API diproses menjadi **Supply Chain Risk Score** yang digunakan dalam proses analisis risiko.


F.  Instalasi
1. Clone Repository
```bash
git clone https://github.com/username/global-supply-chain-risk-monitoring.git
```

2. Masuk ke Folder Project
```bash
cd global-supply-chain-risk-monitoring
```

3. Install Dependency
```bash
composer install
```

4. Salin File Environment
```bash
cp .env.example .env
```

5. Generate Application Key
```bash
php artisan key:generate
```

6. Konfigurasi Database

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=supply_chain
DB_USERNAME=root
DB_PASSWORD=
```

7. Jalankan Migrasi
```bash
php artisan migrate
```

Jika tersedia data awal:

```bash
php artisan db:seed
```

8. Jalankan Server
```bash
php artisan serve
```

Akses aplikasi melalui:
```
http://127.0.0.1:8000
```



G. Struktur Database

| Nama Tabel          | Deskripsi                                       |
| ------------------- | ----------------------------------------------- |
| users               | Menyimpan data akun pengguna dan administrator. |
| countries           | Menyimpan data negara yang dipantau.            |
| ports               | Menyimpan data pelabuhan internasional.         |
| weather             | Menyimpan data cuaca setiap negara.             |
| economic_indicators | Menyimpan indikator ekonomi setiap negara.      |
| exchange_rates      | Menyimpan data nilai tukar mata uang.           |
| news                | Menyimpan berita internasional.                 |
| risk_scores         | Menyimpan hasil perhitungan tingkat risiko.     |
| watchlists          | Menyimpan daftar negara favorit pengguna.       |

Database dirancang menggunakan relasi antar tabel sehingga data dari berbagai sumber dapat diintegrasikan untuk menghasilkan analisis risiko supply chain yang komprehensif.


H. REST API Endpoints

| Method | Endpoint                    | Deskripsi                                             |
| ------ | --------------------------- | ----------------------------------------------------- |
| GET    | `/api/risk-score`           | Menampilkan hasil perhitungan Risk Score.             |
| POST   | `/api/risk-analysis`        | Melakukan analisis tingkat risiko supply chain.       |
| POST   | `/api/risk-score/calculate` | Menghitung ulang Risk Score berdasarkan data terbaru. |



i. Kontributor

Proyek ini dikembangkan sebagai implementasi sistem monitoring risiko rantai pasok global.
Tim Pengembang
Mutia Sitompul
Program Studi Sistem Informasi


Lisensi
Proyek ini dikembangkan untuk memenuhi tugas ujian akhir semester pada mata kuliah pemograman web 2

