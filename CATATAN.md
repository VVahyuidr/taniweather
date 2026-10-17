# CATATAN.MD - TaniWeather Project

## Identitas Proyek
- Judul resmi: Sistem Informasi Monitoring Cuaca dan Potensi Pertanian di Lombok Timur
- Nama aplikasi: TaniWeather
- Stack teknologi: Laravel 13, Laravel Sail, Docker, Tailwind CSS v4, Vite, Open-Meteo API, Chart.js
- Sumber data: Open-Meteo API (https://open-meteo.com/)

---

## Fitur Selesai dan Terverifikasi
| Nama Fitur | Deskripsi | File Utama Terkait | Pengujian yang Dilakukan | Hasil Pengujian | Tanggal Verifikasi |
|---|---|---|---|---|---|
| **Identitas Visual & Logo TaniWeather** | Logo kustom SVG (awan cuaca + tunas daun pertanian hijau), favicon SVG `public/favicon.svg`, brand header & sidebar, serta antarmuka penuh Bahasa Indonesia. Menghilangkan logo default Laravel. | `public/favicon.svg`, `resources/views/layouts/app.blade.php`, `resources/views/weather/about.blade.php` | Pengecekan visual pada sidebar, header, browser tab, dan pemeriksaan output HTML via curl. | Identitas TaniWeather konsisten, favicon ter-render di browser, tidak ada lagi logo Laravel pada UI. | 2026-10-16 |
| **Card Cuaca Utama (Hero Card Referensi)** | Card navy-ke-biru-royal (`hero-weather-card`) dengan tekstur garis hujan diagonal halus (`hero-rain-texture`), ambient glow, pin lokasi + nama wilayah + badge `Lokasi Aktif` (kiri atas), badge `Data dari Open-Meteo` (kanan atas), suhu raksasa (108-116px) dengan °C rapat di baseline, kondisi cuaca, suhu min/max dengan dua ikon termometer berbeda warna, curah hujan, ilustrasi cuaca SVG besar proporsional, serta 4 kartu statistik sejajar di bawah (Kelembapan, Curah Hujan, Kecepatan Angin, Suhu Maksimum) dan waktu pembaruan. | `resources/views/weather/dashboard.blade.php`, `resources/views/components/weather-illustration.blade.php`, `resources/css/app.css` | Manual curl test, inspeksi struktur HTML, pengujian visual rendering desktop & mobile, feature test `test_dashboard_page_loads_successfully`. | Komposisi visual 100% sesuai referensi; angka suhu raksasa tidak mematahkan unit °C; 4 kartu statistik sejajar horizontal; ilustrasi cuaca dinamis sesuai kode WMO. | 2026-10-16 |
| **Ilustrasi Cuaca SVG Kustom** | Komponen ilustrasi SVG (`<x-weather-illustration>`) dinamis dan berlapis untuk 7 kategori kondisi cuaca: Cerah (matahari bersinar), Cerah Berawan (matahari di belakang awan putih), Berawan/Mendung (awan berlapis), Kabut (awan dengan garis mist), Hujan Ringan (awan tetesan air biru), Hujan Lebat (awan gelap tetesan padat), dan Badai Petir (awan petir kuning elektrik). Tidak menggunakan emoji sebagai ilustrasi utama. | `resources/views/components/weather-illustration.blade.php`, `resources/views/components/weather-icon.blade.php` | Pengujian rendering dengan berbagai kode WMO (0, 1, 2, 3, 45, 51, 65, 95). | Setiap kondisi cuaca menampilkan ilustrasi yang unik, proporsional, tidak keluar card, dan tidak monoton. | 2026-10-16 |
| **5 Lokasi Terverifikasi Lombok Timur** | Pemilih lokasi mencakup 5 kecamatan: Selong (-8.6500, 116.5333), Aikmel (-8.5678, 116.5305), Sembalun (-8.3592, 116.5247), Wanasaba (-8.5678, 116.5672), dan Anjani (-8.6030, 116.5080). Koordinat diverifikasi melalui OpenStreetMap & BPS. | `app/Services/OpenMeteoService.php` (konstanta `LOCATIONS`) | Pengujian beralih lokasi melalui parameter URL dan dropdown form untuk kelima lokasi; automated test `test_all_five_locations_can_be_loaded`. | Seluruh lokasi berhasil mengambil data aktual Open-Meteo; Sembalun menunjukkan suhu lebih sejuk khas dataran tinggi; semua data statistik terperbarui otomatis. | 2026-10-16 |
| **Layout & Sidebar Navigasi Responsif** | Sidebar navy gelap tetap di kiri selebar 264px (`w-[264px]`), konten utama sebelah kanan ter-offset rapi (`lg:pl-[264px]`) tanpa double padding, background abu-abu terang (`#F8FAFC`), padding desktop 28–36px (`p-5 sm:p-7 lg:p-8`), menu aktif dengan highlight hijau lembut (`bg-emerald-500/15 text-emerald-300 border-l-4 border-emerald-500`), dan drawer navigasi mobile dengan backdrop overlay. | `resources/views/layouts/app.blade.php`, `resources/css/app.css` | Pengujian responsivitas desktop dan mobile, drawer toggle JavaScript test, verifikasi tidak ada horizontal scrollbar. | Tampilan layout stabil, sidebar tidak berada di tengah, tidak bertumpuk dengan konten, dan beradaptasi mulus. | 2026-10-16 |
| **Halaman Prakiraan Cuaca 7 Hari** | Halaman dedicated (`/prakiraan`) yang menampilkan komparasi hari ini vs besok serta rincian 7 hari ke depan (hari & tanggal, kondisi cuaca, suhu maks/min, volume hujan, probabilitas hujan, kecepatan angin, dan status kesiapan aktivitas tani). | `resources/views/weather/forecast.blade.php`, `app/Http/Controllers/WeatherController.php::forecast` | Pengujian HTTP GET `/prakiraan?location=sembalun`, feature test `test_forecast_page_loads_successfully`. | Halaman merender 7 kartu harian lengkap dengan metrik presisi dan indikator ramah tani / waspada hujan. | 2026-10-16 |
| **Halaman Analitik Cuaca & Iklim** | Halaman dedicated (`/analitik`) dengan 3 grafik Chart.js (Fluktuasi Suhu Maks/Min, Distribusi Curah Hujan, Kecepatan Angin Maksimum), 4 kartu ringkasan metrik analitik (Rata-rata Maks/Min, Akumulasi Hujan, Hari Terpanas), dan tabel rekapitulasi data meteorologi time-series. | `resources/views/weather/analytics.blade.php`, `resources/js/app.js`, `app/Http/Controllers/WeatherController.php::analytics` | Pengujian HTTP GET `/analitik?location=aikmel`, feature test `test_analytics_page_loads_successfully`, kompilasi Vite JS. | Grafik Chart.js dan tabel data ter-render sempurna tanpa error JavaScript di konsol. | 2026-10-16 |
| **Halaman Potensi Pertanian Lombok Timur** | Halaman dedicated (`/potensi-pertanian`) yang menyajikan analisis kelayakan aktivitas pertanian (Penyemprotan, Irigasi, Pemupukan Akar, Panen/Penjemuran, dan Deteksi Risiko Jamur) berdasarkan parameter fisik cuaca dengan alasan ilmiah transparan, panduan komoditas lokal (Tembakau Virginia, Bawang Merah, Cabai, Padi Sawah), dan disclaimer ilmiah. | `resources/views/weather/agriculture.blade.php`, `app/Services/OpenMeteoService.php::getAgriculturalAdvice`, `app/Http/Controllers/WeatherController.php::agriculture` | Pengujian HTTP GET `/potensi-pertanian?location=wanasaba`, feature test `test_agriculture_page_loads_successfully`. | Rekomendasi muncul sesuai parameter cuaca aktual; memuat alasan meteorologis dan tindakan konkret bagi petani. | 2026-10-16 |
| **Halaman Tentang Sistem Informasi** | Halaman dedicated (`/tentang`) berisi identitas resmi sistem, judul resmi tugas mata kuliah Proyek SI, latar belakang, tujuan & manfaat, tabel 5 wilayah pemantauan beserta koordinat & elevasi, penjelasan sumber data Open-Meteo, serta batasan sistem. | `resources/views/weather/about.blade.php`, `app/Http/Controllers/WeatherController.php::about` | Pengujian HTTP GET `/tentang`, feature test `test_about_page_loads_successfully`. | Halaman merender seluruh rincian informasi dan tabel geografis secara komprehensif. | 2026-10-16 |
| **Fitur Interaktif, Dropdown & Refresh Cache** | Dropdown lokasi otomatis berpindah halaman dengan mempertahankan rute aktif (`{{ url()->current() }}`), tombol refresh dengan parameter `?refresh=1` langsung mem-bypass/menghapus cache (`Cache::forget`), animasi putar ikon refresh, endpoint API JSON `/api/weather`, dan fallback jika lokasi tidak valid. | `app/Http/Controllers/WeatherController.php`, `app/Services/OpenMeteoService.php`, `resources/views/layouts/app.blade.php` | Pengujian cache hit/bust dengan `?refresh=1`, automated test `test_weather_api_returns_json` dan `test_invalid_location_falls_back_to_selong`. | Refresh mengambil data segar langsung dari Open-Meteo; API mengembalikan JSON berstruktur lengkap; lokasi dipertahankan saat berpindah antar menu navigasi. | 2026-10-16 |
| **Pelepasan Aman Fitur Notes** | Memeriksa dan memastikan fitur percobaan Notes tidak aktif dalam navigasi, route, maupun controller, tanpa menjalankan perintah destruktif pada database atau volume Docker. | `routes/web.php`, `app/Http/Controllers/WeatherController.php`, `resources/views/layouts/app.blade.php` | Grep kode pencarian referensi 'notes' dan pemeriksaan route list Laravel Sail. | Aplikasi bersih dari menu/route Notes; tidak ada tabel atau data yang dihapus secara berbahaya. | 2026-10-16 |
| **Automated Testing & Code Formatting** | Pembuatan pengujian otomatis feature test komprehensif (10 test case, 43 assertions) dan standardisasi gaya kode menggunakan Laravel Pint. | `tests/Feature/WeatherFeatureTest.php`, Pint code formatter | Menjalankan `./vendor/bin/sail artisan test` dan `./vendor/bin/sail bin pint --format agent`. | 100% tests passed (10 tests, 43 assertions) dalam durasi ~25s; Laravel Pint status passed. | 2026-10-16 |

---

## Fitur Dalam Pengerjaan
- Tidak ada fitur yang tertunda dalam implementasi saat ini. Seluruh fitur utama pada TAHAP 1 sampai TAHAP 11 telah diimplementasikan dan lolos pengujian teknis.

---

## Perubahan Terbaru (Audit & Perbaikan)
- **Audit lengkap**: Seluruh 21 kecamatan Lombok Timur sudah lengkap dalam `OpenMeteoService.php` dengan koordinat representatif unik. Tidak ada duplikasi koordinat Selong.
- **Integrasi Open-Meteo**: Endpoint `/api/weather`, caching 10 menit, parameter lengkap (current, daily, timezone Asia/Makassar), penanganan error, dan refresh `?refresh=1` berfungsi.
- **Dashboard**: Hero weather card dengan gradasi navy-royal, suhu besar, ilustrasi SVG dinamis 7 kondisi, 4 kartu statistik, prakiraan 7 hari, grafik suhu/hujan, peta Leaflet interaktif 21 titik, rekomendasi pertanian, dan waktu pembaruan jujur.
- **Navigasi**: Semua 5 menu (Dashboard, Prakiraan, Analitik, Potensi Pertanian, Tentang) memiliki halaman dan rute berfungsi. Sidebar responsif dengan dropdown pencarian kecamatan dan filter wilayah.
- **Perbaikan desain konsistensi**: Memperbaiki judul halaman dan logo sidebar dari "SawahSky" menjadi "TaniWeather" agar identitas merek konsisten.
- **Build frontend**: `npm run build` berhasil. Tes Laravel (`vendor/bin/sail artisan test`) lulus 9 tes, 300 assertions.

---

## Fitur Belum Dikerjakan (Ide Pengembangan Masa Depan)
- Fitur notifikasi cuaca ekstrem melalui WhatsApp/Telegram Bot untuk kelompok tani.
- Integrasi sensor IoT in-situ LoRaWAN untuk pemantauan kadar air tanah (Soil Moisture).
- Pilihan bahasa multibahasa (Bahasa Sasak / Bahasa Inggris).
- Mode peta interaktif spasial (Leaflet / Mapbox) untuk visualisasi poligon zonasi pertanian Lombok Timur.

---

## Bug dan Kendala (Status: Selesai / Teratasi)
1. **Masalah Kepemilikan & Izin File Docker/Sail (`node_modules` & `public/build`)**:
   - *Penyebab*: Direktori `node_modules` dan `public/build` sebelumnya dimiliki oleh `nobody:nogroup`, menyebabkan error `EACCES: permission denied` saat kompilasi asset dengan Vite.
   - *Perbaikan*: Menyelaraskan kepemilikan file ke user `sail:sail` (`uid=1000`) di dalam kontainer Docker. Vite build kini berjalan lancar dalam ~400ms.
2. **Keterbatasan Offline Font Download**:
   - *Penyebab*: Penambahan bobot font 700 pada paket bunny fonts memicu network fetch yang diblokir oleh lingkungan sandbox.
   - *Perbaikan*: Menggunakan bobot font lokal yang telah ter-cache (`[400, 500, 600]`) sehingga proses kompilasi asset offline 100% berhasil.
3. **Pemberian Padding Ganda pada Desktop Layout**:
   - *Penyebab*: Sidebar sebelumnya menggunakan `lg:relative` sekaligus `main` memiliki `lg:pl-64`, menyebabkan jarak kosong berlebih di desktop.
   - *Perbaikan*: Sidebar dibuat fixed selebar 264px (`w-[264px]`) dan area utama menggunakan offset presisi `lg:pl-[264px]`.
4. **Padding Terbalik pada Dropdown Lokasi**:
   - *Penyebab*: `dropdown-select` memiliki padding kiri 2.5rem dan kanan 1rem padahal ikon panah ada di sebelah kanan.
   - *Perbaikan*: Memperbaiki padding menjadi `padding: 0.625rem 2.5rem 0.625rem 1rem` sehingga teks dan ikon panah sejajar sempurna.
5. **Navigasi Menu Semula Masih Menunjuk ke '#'**:
   - *Penyebab*: Menu Prakiraan Cuaca, Analitik, Potensi Pertanian, dan Tentang sebelumnya berstatus `aria-disabled="true"` dengan link `#`.
   - *Perbaikan*: Mengimplementasikan controller method, route bernama, serta view Blade terpisah untuk kelima menu.

---

## Riwayat Perubahan
- **2026-10-16 (Sesi 1)**: Audit awal proyek, penyesuaian dasar layout SaaS, percobaan card cuaca, dan integrasi awal Open-Meteo API.
- **2026-10-16 (Sesi 2 - Sempurna)**:
  1. Instalasi dan konfigurasi resmi Laravel Boost & AI Guidelines via Laravel Sail.
  2. Pembuatan identitas visual kustom TaniWeather (Logo SVG awan & daun pertanian + Favicon SVG).
  3. Redesign total Card Cuaca Utama (Hero Weather Card) sesuai komposisi referensi: gradasi navy-to-blue royal, tekstur garis hujan diagonal sangat halus (`hero-rain-texture`), suhu raksasa (108-116px) dengan satuan °C rapat di baseline, kondisi cuaca, dua termometer warna berbeda untuk min/max, curah hujan dengan ikon tetesan air, ilustrasi cuaca SVG dinamis 7 kondisi cuaca, serta 4 kartu statistik sejajar di bagian bawah (Kelembapan, Curah Hujan, Kecepatan Angin, Suhu Maksimum) dan waktu pembaruan.
  4. Pembaruan dan verifikasi koordinat geospasial 5 kecamatan Lombok Timur (Selong, Aikmel, Sembalun, Wanasaba, Anjani).
  5. Pembangunan 4 halaman baru lengkap: `/prakiraan`, `/analitik`, `/potensi-pertanian`, dan `/tentang`.
  6. Penyempurnaan interaktivitas: dropdown lokasi auto-submit mempertahankan URL aktif, tombol refresh dengan cache-busting `?refresh=1`, caching 10 menit, dan endpoint JSON `/api/weather`.
  7. Pembuatan 8 test case baru pada `tests/Feature/WeatherFeatureTest.php` (total 10 tests, 43 assertions, 100% lulus).
  8. Standardisasi kode dengan Laravel Pint dan build asset sukses dengan Vite.
  9. Pembaruan dokumentasi resmi `CATATAN.md`.