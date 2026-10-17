@extends('layouts.app')

@section('title', 'Tentang TaniWeather')

@section('header-title')
<div>
    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Tentang Sistem Informasi</h1>
    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">
        Profil sistem, tujuan akademik, metodologi data meteorologi, dan cakupan pemantauan seluruh 21 kecamatan Lombok Timur.
    </p>
</div>
@endsection

@section('content')
<div class="space-y-7 max-w-5xl">

    <!-- Hero Card Tentang Sistem -->
    <div class="tani-card p-6 sm:p-8 bg-gradient-to-br from-[#0B132B] via-[#111D38] to-[#1E3A8A] text-white rounded-3xl relative overflow-hidden shadow-xl border border-slate-700">
        <div class="relative z-10 space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                Tugas Mata Kuliah Proyek Sistem Informasi
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                TaniWeather
            </h2>
            <p class="text-base sm:text-lg font-medium text-emerald-300">
                Sistem Informasi Monitoring Cuaca dan Potensi Pertanian di Lombok Timur
            </p>
            <p class="text-slate-300 text-xs sm:text-sm max-w-3xl leading-relaxed pt-2">
                Aplikasi sistem informasi berbasis web yang dirancang untuk memantau dinamika cuaca lokal secara real-time pada <strong>seluruh 21 kecamatan di Kabupaten Lombok Timur</strong> dan menerjemahkan data meteorologi menjadi rekomendasi praktis bagi aktivitas pertanian.
            </p>
        </div>
    </div>

    <!-- 1. Latar Belakang & Tujuan -->
    <div class="grid md:grid-cols-2 gap-6">
        <div class="tani-card p-6">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center mb-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-900 mb-2">Latar Belakang & Urgensi</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Kabupaten Lombok Timur memiliki bentang alam yang sangat beragam, mencakup 21 kecamatan dengan variasi iklim meso yang kontras. Kawasan utara didominasi oleh lereng dan kaldera Gunung Rinjani (Sembalun, Suela, Sambelia) berhawa sejuk-dingin, kawasan tengah merupakan sabuk persawahan subak produktif (Selong, Masbagik, Aikmel, Wanasaba, Sikur), dan kawasan selatan bertopografi perbukitan kapur serta pesisir kering (Keruak, Jerowaru). Perbedaan mikroklimat antarkecamatan ini membutuhkan data cuaca spasial presisi.
            </p>
        </div>

        <div class="tani-card p-6">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center mb-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-900 mb-2">Tujuan & Manfaat</h3>
            <ul class="text-xs text-slate-600 space-y-1.5 list-disc list-inside leading-relaxed">
                <li>Menyajikan dashboard visual cuaca real-time untuk 21 kecamatan administratif Lombok Timur.</li>
                <li>Menghasilkan peringatan dan anjuran preskriptif untuk waktu penyemprotan, pemupukan, dan irigasi.</li>
                <li>Membantu efisiensi biaya saprodi (pestisida dan pupuk) dengan menghindari aplikasi saat kondisi cuaca berisiko.</li>
                <li>Menyediakan peta spasial sebaran kecamatan dan keterhubungan mikroklimat pertanian.</li>
                <li>Menjadi media edukasi pemanfaatan open-data meteorologi dalam bidang agriteknologi.</li>
            </ul>
        </div>
    </div>

    <!-- 2. Wilayah Pemantauan Seluruh 21 Kecamatan + Kawasan Khusus -->
    <div class="tani-card p-6 sm:p-7">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Daftar Terverifikasi 21 Kecamatan di Kabupaten Lombok Timur</h3>
                <p class="text-xs text-slate-500">Koordinat representatif titik pusat pemerintahan/ibukota kecamatan (Verifikasi: BPS Lombok Timur & OpenStreetMap)</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800">
                21 Kecamatan + 1 Kawasan Khusus
            </span>
        </div>

        <div class="overflow-x-auto max-h-96 custom-scrollbar">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="sticky top-0 z-10 bg-slate-100">
                    <tr class="text-slate-700 border-b border-slate-200">
                        <th class="py-2.5 px-3 font-semibold">No</th>
                        <th class="py-2.5 px-3 font-semibold">Kecamatan / Lokasi</th>
                        <th class="py-2.5 px-3 font-semibold">Wilayah & Zona</th>
                        <th class="py-2.5 px-3 font-semibold">Elevasi</th>
                        <th class="py-2.5 px-3 font-semibold">Lintang (Lat)</th>
                        <th class="py-2.5 px-3 font-semibold">Bujur (Lon)</th>
                        <th class="py-2.5 px-3 font-semibold">Dasar Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600">
                    @php $no = 1; @endphp
                    @foreach($locations as $k => $loc)
                        <tr class="hover:bg-slate-50 transition-colors {{ $k === $locationKey ? 'bg-emerald-50/50 font-medium' : '' }}">
                            <td class="py-2.5 px-3 font-mono text-slate-400">
                                @if($loc['is_kecamatan'])
                                    {{ $no++ }}
                                @else
                                    <span class="text-[10px] text-amber-600 font-bold">*</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3">
                                <a href="{{ route('weather.dashboard', ['location' => $k]) }}" class="font-bold text-slate-900 hover:text-emerald-600 flex items-center gap-1.5">
                                    {{ $loc['name'] }}
                                    @if($k === $locationKey)
                                        <span class="text-[9px] text-emerald-700 font-bold bg-emerald-100 px-1.5 py-0.2 rounded">(Aktif)</span>
                                    @endif
                                </a>
                                @if(!$loc['is_kecamatan'])
                                    <span class="text-[10px] text-amber-600 block">Kawasan Agribisnis (Kec. {{ ucwords($loc['parent_kecamatan']) }})</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3">{{ $loc['zone'] }}</td>
                            <td class="py-2.5 px-3 font-mono text-slate-700">{{ $loc['elevation'] }}</td>
                            <td class="py-2.5 px-3 font-mono">{{ $loc['latitude'] }}° S</td>
                            <td class="py-2.5 px-3 font-mono">{{ $loc['longitude'] }}° E</td>
                            <td class="py-2.5 px-3 text-[11px] text-slate-500">{{ $loc['verification_source'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="text-[11px] text-slate-400 mt-3">
            * Anjani dimasukkan sebagai kawasan agribisnis khusus di dalam wilayah administratif Kecamatan Suralaga.
        </p>
    </div>

    <!-- 3. Arsitektur Teknologi & Sumber Data Open-Meteo -->
    <div class="tani-card p-6">
        <h3 class="text-base font-bold text-slate-900 mb-3">Sumber Data & Arsitektur Teknologi</h3>
        <div class="grid sm:grid-cols-3 gap-4 text-xs">
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="font-bold text-slate-900 block mb-1">Open-Meteo Weather API</span>
                <p class="text-slate-600 leading-relaxed">
                    Penyedia layanan data meteorologi global tanpa batas kuota berbayar, memanfaatkan perpaduan model numerik ECMWF, GFS, dan DWD dengan resolusi temporal per jam dan harian.
                </p>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="font-bold text-slate-900 block mb-1">Laravel 13 & Tailwind v4</span>
                <p class="text-slate-600 leading-relaxed">
                    Framework backend PHP modern dengan arsitektur MVC, caching pintar berdurasi 10 menit, serta frontend terkompilasi melalui Vite dan Tailwind CSS v4.
                </p>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="font-bold text-slate-900 block mb-1">World Meteorological Org. (WMO)</span>
                <p class="text-slate-600 leading-relaxed">
                    Pengelompokan kondisi atmosfer mengacu pada kode internasional WMO (0–99) yang diterjemahkan secara presisi ke dalam terminologi Bahasa Indonesia.
                </p>
            </div>
        </div>
    </div>

    <!-- 4. Batasan Sistem & Catatan Ilmiah -->
    <div class="p-5 rounded-2xl bg-amber-50/70 border border-amber-200 text-xs text-amber-900 space-y-2">
        <div class="flex items-center gap-2 font-bold text-amber-950 text-sm">
            <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Batasan Informasi & Ruang Lingkup Sistem
        </div>
        <p class="leading-relaxed">
            1. <strong>Representasi Titik Spasial:</strong> Koordinat setiap kecamatan adalah titik representatif pusat ibukota kecamatan / kantor camat, dan tidak merepresentasikan garis delimitasi batas administratif resmi secara kartografis.
        </p>
        <p class="leading-relaxed">
            2. <strong>Sifat Data:</strong> Seluruh prakiraan dan data aktual bersumber dari model kalkulasi atmosfer Open-Meteo berdasarkan interpolasi grid geolokasi, bukan sensor fisik IoT stasiun cuaca tanah (AWS) in-situ di setiap petak sawah.
        </p>
        <p class="leading-relaxed">
            3. <strong>Pedoman Umum:</strong> Rekomendasi aktivitas pertanian (penyemprotan, irigasi, pemupukan) diformulasikan sebagai aturan inferensi untuk panduan umum pendukung keputusan, bukan jaminan mutlak hasil panen.
        </p>
    </div>

</div>
@endsection
