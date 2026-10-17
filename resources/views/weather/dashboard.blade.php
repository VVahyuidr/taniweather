@extends('layouts.app')

@section('title', 'Dashboard Cuaca')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
.leaflet-popup-content-wrapper {
    border-radius: 16px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    font-family: 'Instrument Sans', sans-serif;
    padding: 2px;
}
.leaflet-popup-content {
    margin: 10px 12px;
}
</style>
@endpush

@section('header-title')
<div>
    <div class="flex items-center gap-2">
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Dashboard Cuaca & Pertanian</h1>
        <span class="hidden sm:inline-flex px-2 py-0.5 text-xs font-semibold rounded-md bg-emerald-100 text-emerald-800 border border-emerald-200">
            21 Kecamatan Lombok Timur
        </span>
    </div>
    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">
        Pantau parameter cuaca terkini dan panduan agrikultur untuk wilayah <span class="font-semibold text-slate-700">{{ $currentLocation['name'] }}</span>.
    </p>
</div>
@endsection

@section('content')
<div class="space-y-7">

    @if($error)
        <!-- Error State -->
        <div class="tani-card p-8 sm:p-12 text-center bg-white border border-red-200 rounded-3xl shadow-sm">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-red-50 text-red-600 mb-4 border border-red-200" aria-hidden="true">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h2 class="text-xl font-bold text-slate-900 mb-2">Gagal Memuat Data Cuaca</h2>
            <p class="text-slate-600 max-w-md mx-auto mb-6 text-sm">{{ $error }}</p>
            <a href="{{ url()->current() }}?location={{ $locationKey }}&refresh=1" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Coba Muat Ulang
            </a>
        </div>

    @elseif(!$weatherData)
        <!-- Loading State Skeleton -->
        <div class="hero-weather-card p-8 animate-pulse">
            <div class="h-6 w-48 bg-white/20 rounded-lg mb-6"></div>
            <div class="grid lg:grid-cols-2 gap-8 items-center">
                <div class="space-y-4">
                    <div class="h-4 w-32 bg-white/20 rounded"></div>
                    <div class="h-24 w-64 bg-white/20 rounded-2xl"></div>
                    <div class="h-6 w-40 bg-white/20 rounded"></div>
                </div>
                <div class="h-56 w-56 mx-auto bg-white/10 rounded-full"></div>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-8 pt-6 border-t border-white/10">
                <div class="h-20 bg-white/10 rounded-xl"></div>
                <div class="h-20 bg-white/10 rounded-xl"></div>
                <div class="h-20 bg-white/10 rounded-xl"></div>
                <div class="h-20 bg-white/10 rounded-xl"></div>
            </div>
        </div>

    @else
        @php
            $current = $weatherData['current'];
            $daily = $weatherData['daily'];
            $location = $weatherData['location'];
            $service = app(App\Services\OpenMeteoService::class);
            $currentWeatherInfo = $service->getWeatherInfo($current['weather_code'] ?? 0);
        @endphp

        <!-- ============================================================== -->
        <!-- CARD CUACA UTAMA (HERO WEATHER CARD SESUAI REFERENSI VISUAL)   -->
        <!-- ============================================================== -->
        <section class="hero-weather-card" aria-label="Card Cuaca Utama {{ $location['name'] }}">
            <!-- Tekstur Garis Hujan Diagonal Sangat Halus -->
            <div class="hero-rain-texture" aria-hidden="true"></div>
            
            <!-- Ambient Light Glow di Belakang Ilustrasi -->
            <div class="hero-ambient-glow" aria-hidden="true"></div>

            <div class="relative z-10 p-6 sm:p-8 lg:p-9">
                
                <!-- B. HEADER CARD: Pin Lokasi, Nama Wilayah, Badge Lokasi Aktif, & Badge Open-Meteo -->
                <div class="flex flex-wrap items-center justify-between gap-3 pb-6 border-b border-white/15">
                    <!-- Kiri Atas: Lokasi & Status -->
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <div class="w-8 h-8 rounded-lg bg-blue-500/20 text-cyan-300 border border-blue-400/30 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="text-base sm:text-lg font-bold text-white tracking-wide">
                            {{ $location['name'] }}, Lombok Timur
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/25 text-emerald-300 border border-emerald-400/30 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Lokasi Aktif
                        </span>
                        @if(!$location['is_kecamatan'])
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-amber-500/20 text-amber-200 border border-amber-400/30">
                                Kawasan Khusus (Kec. {{ ucwords($location['parent_kecamatan']) }})
                            </span>
                        @endif
                    </div>

                    <!-- Kanan Atas: Badge Open-Meteo -->
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-blue-200 border border-white/15 backdrop-blur-sm shadow-sm">
                            <svg class="w-3.5 h-3.5 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            Data dari Open-Meteo
                        </span>
                    </div>
                </div>

                <!-- BODY HERO CARD: Kolom Kiri vs Kolom Kanan -->
                <div class="grid lg:grid-cols-12 gap-8 items-center py-7">
                    
                    <!-- C. KOLOM KIRI: Cuaca Saat Ini, Suhu Raksasa, Kondisi, Min/Max, & Curah Hujan -->
                    <div class="lg:col-span-7 space-y-4">
                        
                        <!-- Label Cuaca Saat Ini -->
                        <div class="flex items-center gap-2 text-blue-200/85">
                            <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                            <span class="text-xs font-semibold uppercase tracking-widest">Cuaca Saat Ini</span>
                            <span class="text-xs text-blue-200/60">• {{ $location['zone'] ?? 'Lombok Timur' }}</span>
                        </div>

                        <!-- Suhu Aktual Raksasa (100-120px) dengan Satuan °C Rapi di Baseline -->
                        <div class="flex items-baseline whitespace-nowrap overflow-visible">
                            <span class="text-7xl sm:text-8xl md:text-9xl lg:text-[108px] xl:text-[116px] font-black text-white leading-none tracking-tighter drop-shadow-md select-all">
                                {{ number_format($current['temperature_2m'], 1) }}
                            </span>
                            <span class="text-3xl sm:text-4xl lg:text-5xl font-light text-cyan-200 ml-1 sm:ml-2 select-none tracking-normal">
                                °C
                            </span>
                        </div>

                        <!-- Kondisi Cuaca di Bawah Suhu -->
                        <div class="space-y-1">
                            <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">
                                {{ $currentWeatherInfo['label'] }}
                            </h2>
                            <p class="text-sm text-blue-100/80 font-normal max-w-lg">
                                {{ $currentWeatherInfo['description'] }}
                            </p>
                        </div>

                        <!-- Baris Suhu Minimum & Maksimum dengan Dua Ikon Termometer Berbeda Warna -->
                        <div class="pt-3 flex flex-wrap items-center gap-4 sm:gap-6 text-sm border-t border-white/10">
                            <!-- Min Temperature (Ikon Cyan/Biru Muda) -->
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-cyan-500/20 text-cyan-300 border border-cyan-400/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                    </svg>
                                </span>
                                <div>
                                    <span class="text-xs text-blue-200/70 block">Suhu Terendah</span>
                                    <span class="font-bold text-cyan-200">{{ number_format($daily['temperature_2m_min'][0], 1) }}°C</span>
                                </div>
                            </div>

                            <!-- Max Temperature (Ikon Oranye/Amber) -->
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-amber-500/20 text-amber-300 border border-amber-400/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                    </svg>
                                </span>
                                <div>
                                    <span class="text-xs text-blue-200/70 block">Suhu Tertinggi</span>
                                    <span class="font-bold text-amber-200">{{ number_format($daily['temperature_2m_max'][0], 1) }}°C</span>
                                </div>
                            </div>

                            <!-- Curah Hujan Hari Ini dengan Ikon Tetesan Air -->
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-blue-500/20 text-blue-300 border border-blue-400/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                </span>
                                <div>
                                    <span class="text-xs text-blue-200/70 block">Akumulasi Hujan</span>
                                    <span class="font-bold text-white">{{ number_format($daily['precipitation_sum'][0], 1) }} mm</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- D. KOLOM KANAN: Ilustrasi Cuaca Besar & Proporsional Sesuai Kode Cuaca -->
                    <div class="lg:col-span-5 flex justify-center items-center">
                        <div class="relative p-2">
                            <x-weather-illustration :code="$current['weather_code']" class="w-52 h-52 sm:w-60 sm:h-60 lg:w-64 lg:h-64 xl:w-72 xl:h-72" />
                            <div class="text-center mt-2">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-white/10 text-blue-200 border border-white/10">
                                    Kode WMO: {{ $current['weather_code'] }} ({{ $currentWeatherInfo['label'] }})
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- E. EMPAT KARTU STATISTIK DI BAGIAN BAWAH CARD SESUAI REFERENSI -->
                <div class="border-t border-white/15 pt-6 mt-2">
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                        
                        <!-- 1. Kelembapan -->
                        <div class="hero-stat-card">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-9 h-9 rounded-xl bg-cyan-500/25 text-cyan-300 border border-cyan-400/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2.69l5.66 5.66a8 8 0 11-11.31 0z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold uppercase tracking-wider text-blue-100/75">Kelembapan</span>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ $current['relative_humidity_2m'] }}</span>
                                <span class="text-lg font-medium text-cyan-200">%</span>
                            </div>
                            <p class="text-[11px] text-blue-200/70 mt-1">
                                {{ $current['relative_humidity_2m'] > 75 ? 'Tinggi (Lembap)' : ($current['relative_humidity_2m'] < 50 ? 'Rendah (Kering)' : 'Optimal Tanaman') }}
                            </p>
                        </div>

                        <!-- 2. Curah Hujan -->
                        <div class="hero-stat-card">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-9 h-9 rounded-xl bg-blue-500/25 text-blue-300 border border-blue-400/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold uppercase tracking-wider text-blue-100/75">Curah Hujan</span>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ number_format($current['precipitation'], 1) }}</span>
                                <span class="text-lg font-medium text-blue-200">mm</span>
                            </div>
                            <p class="text-[11px] text-blue-200/70 mt-1">
                                Total 24 Jam: {{ number_format($daily['precipitation_sum'][0], 1) }} mm
                            </p>
                        </div>

                        <!-- 3. Kecepatan Angin -->
                        <div class="hero-stat-card">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-9 h-9 rounded-xl bg-teal-500/25 text-teal-300 border border-teal-400/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold uppercase tracking-wider text-blue-100/75">Kecepatan Angin</span>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ number_format($current['wind_speed_10m'], 1) }}</span>
                                <span class="text-xs font-medium text-teal-200">km/jam</span>
                            </div>
                            <p class="text-[11px] text-blue-200/70 mt-1">
                                {{ $current['wind_speed_10m'] > 18 ? 'Angin Kencang (Waspada)' : ($current['wind_speed_10m'] > 10 ? 'Angin Sedang' : 'Tenang (Aman Semprot)') }}
                            </p>
                        </div>

                        <!-- 4. Suhu Maksimum -->
                        <div class="hero-stat-card">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-9 h-9 rounded-xl bg-amber-500/25 text-amber-300 border border-amber-400/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold uppercase tracking-wider text-blue-100/75">Suhu Maksimum</span>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ number_format($daily['temperature_2m_max'][0], 1) }}</span>
                                <span class="text-lg font-medium text-amber-200">°C</span>
                            </div>
                            <p class="text-[11px] text-blue-200/70 mt-1">
                                Puncak Siang Hari Ini
                            </p>
                        </div>
                    </div>

                    <!-- Informasi Waktu Pembaruan & Metadata Geografis di Bawah Card -->
                    <div class="flex flex-wrap items-center justify-between gap-2 mt-5 text-[11px] text-blue-200/60">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Terakhir diperbarui: {{ \Carbon\Carbon::parse($current['time'])->setTimezone('Asia/Makassar')->translatedFormat('l, d F Y, H:i') }} WITA
                        </span>
                        <span>
                            Koordinat: {{ $location['latitude'] }}° S, {{ $location['longitude'] }}° E • Elevasi: {{ $location['elevation'] ?? 'mdpl' }}
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================== -->
        <!-- PRAKIRAAN 7 HARI & GRAFIK CUACA RINGKAS                        -->
        <!-- ============================================================== -->
        <div class="grid lg:grid-cols-12 gap-7">
            
            <!-- Kolom Kiri: Prakiraan 7 Hari (Preview Cepat) -->
            <div class="lg:col-span-5 tani-card p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 tracking-tight">Prakiraan 7 Hari</h2>
                        <p class="text-xs text-slate-500">Estimasi kondisi cuaca harian di {{ $location['name'] }}</p>
                    </div>
                    <a href="{{ route('weather.forecast', ['location' => $locationKey]) }}"
                       class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline flex items-center gap-1">
                        Lengkap
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <div class="space-y-2 flex-1 divide-y divide-slate-100">
                    @for($i = 0; $i < count($daily['time']); $i++)
                        @php
                            $dCode = (int) $daily['weather_code'][$i];
                            $dInfo = $service->getWeatherInfo($dCode);
                            $date = \Carbon\Carbon::parse($daily['time'][$i])->setTimezone('Asia/Makassar');
                            $isToday = $i === 0;
                            $rainProb = $daily['precipitation_probability_max'][$i] ?? null;
                        @endphp
                        <div class="pt-2.5 first:pt-0 flex items-center justify-between gap-3 text-sm">
                            <div class="w-24">
                                <p class="font-semibold text-slate-800 {{ $isToday ? 'text-emerald-700 font-bold' : '' }}">
                                    {{ $isToday ? 'Hari Ini' : $date->translatedFormat('l') }}
                                </p>
                                <p class="text-[11px] text-slate-400">{{ $date->format('d M') }}</p>
                            </div>

                            <div class="flex items-center gap-2 flex-1">
                                <x-weather-icon :code="$dCode" class="w-6 h-6 flex-shrink-0" />
                                <span class="text-xs text-slate-600 truncate">{{ $dInfo['label'] }}</span>
                            </div>

                            <div class="text-right">
                                <span class="font-bold text-slate-900">{{ number_format($daily['temperature_2m_max'][$i], 0) }}°</span>
                                <span class="text-slate-400 text-xs">/ {{ number_format($daily['temperature_2m_min'][$i], 0) }}°</span>
                                <div class="text-[10px] text-blue-600 font-medium">
                                    @if($daily['precipitation_sum'][$i] > 0)
                                        {{ number_format($daily['precipitation_sum'][$i], 1) }} mm
                                    @else
                                        0 mm
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Kolom Kanan: Grafik Tren Suhu & Curah Hujan -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Grafik Suhu -->
                <div class="chart-card">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Tren Fluktuasi Suhu 7 Hari</h2>
                            <p class="text-xs text-slate-500">Perbandingan batas suhu maksimum dan minimum harian</p>
                        </div>
                        <a href="{{ route('weather.analytics', ['location' => $locationKey]) }}"
                           class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline flex items-center gap-1">
                            Detail Analitik &rarr;
                        </a>
                    </div>
                    <div class="h-60 w-full relative">
                        <canvas id="tempChart"></canvas>
                    </div>
                </div>

                <!-- Grafik Curah Hujan -->
                <div class="chart-card">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Distribusi Curah Hujan (mm)</h2>
                            <p class="text-xs text-slate-500">Akumulasi presipitasi harian untuk pertimbangan irigasi</p>
                        </div>
                        <span class="text-xs text-slate-400 font-medium">Satuan Milimeter (mm)</span>
                    </div>
                    <div class="h-48 w-full relative">
                        <canvas id="rainChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- PETA INTERAKTIF SEBARAN 21 KECAMATAN LOMBOK TIMUR (LEAFLET)   -->
        <!-- ============================================================== -->
        <section class="tani-card p-6 sm:p-7" aria-label="Peta Interaktif 21 Kecamatan Lombok Timur">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                        <h2 class="text-lg font-bold text-slate-900">Peta Sebaran 21 Kecamatan & Titik Pantau Cuaca</h2>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Visualisasi spasial 21 kecamatan Lombok Timur. Klik penanda untuk melihat mikroklimat atau beralih pemantauan. Stasiun aktif: <strong class="text-emerald-700">{{ $location['name'] }}</strong>.
                    </p>
                </div>
                <div class="flex items-center gap-2 text-xs">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        {{ $location['name'] }} (Aktif)
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 border border-slate-200">
                        21 Kecamatan + 1 Kawasan
                    </span>
                </div>
            </div>

            <!-- Kontainer Peta Leaflet -->
            <div class="w-full h-80 sm:h-96 rounded-2xl border border-slate-200 overflow-hidden shadow-inner relative z-0" id="eastLombokMap"></div>

            <div class="mt-3.5 flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-500">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Titik koordinat representatif pusat pemerintahan kecamatan (BPS Lombok Timur & OpenStreetMap).
                </span>
                <span>Peta Interaktif © OpenStreetMap Kontributor</span>
            </div>
        </section>

        <!-- ============================================================== -->
        <!-- REKOMENDASI AKTIVITAS PERTANIAN LOMBOK TIMUR                   -->
        <!-- ============================================================== -->
        <section class="tani-card p-6 sm:p-7" aria-label="Rekomendasi Potensi Pertanian">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-5 pb-4 border-b border-slate-100">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <h2 class="text-lg font-bold text-slate-900">Potensi & Rekomendasi Pertanian Hari Ini</h2>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Analisis otomatis berbasis kondisi cuaca aktual untuk mendukung keputusan kerja di sawah/kebun wilayah <span class="font-semibold text-slate-700">{{ $location['name'] }}</span>.
                    </p>
                </div>
                <a href="{{ route('weather.agriculture', ['location' => $locationKey]) }}"
                   class="btn-primary text-xs">
                    Buka Panduan Lengkap Pertanian &rarr;
                </a>
            </div>

            <!-- Grid Kartu Rekomendasi Pertanian -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($advice as $adv)
                    @php
                        $borderStyle = 'border-l-4 ';
                        if ($adv['type'] === 'success') $borderStyle .= 'border-emerald-500 bg-emerald-50/50';
                        elseif ($adv['type'] === 'warning') $borderStyle .= 'border-amber-500 bg-amber-50/50';
                        elseif ($adv['type'] === 'danger') $borderStyle .= 'border-rose-500 bg-rose-50/50';
                        else $borderStyle .= 'border-blue-500 bg-blue-50/50';
                    @endphp
                    <div class="p-4 rounded-xl border border-slate-200 {{ $borderStyle }} flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    {{ $adv['category'] }}
                                </span>
                                <span class="text-xs px-2 py-0.5 rounded font-semibold {{ $adv['type'] === 'success' ? 'bg-emerald-100 text-emerald-800' : ($adv['type'] === 'warning' ? 'bg-amber-100 text-amber-800' : ($adv['type'] === 'danger' ? 'bg-rose-100 text-rose-800' : 'bg-blue-100 text-blue-800')) }}">
                                    {{ $adv['status'] }}
                                </span>
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm mb-1.5">{{ $adv['title'] }}</h3>
                            <p class="text-xs text-slate-600 mb-2 leading-relaxed">{{ $adv['message'] }}</p>
                        </div>
                        <div class="pt-2.5 mt-2 border-t border-slate-200/60 text-[11px] font-medium text-slate-700 flex items-start gap-1.5">
                            <span class="text-emerald-600 font-bold flex-shrink-0">Tindakan:</span>
                            <span>{{ $adv['action'] }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 col-span-full">Belum ada rekomendasi khusus saat ini.</p>
                @endforelse
            </div>

            <!-- Disclaimer Ilmiah Sesuai Pedoman TAHAP 7 -->
            <div class="mt-5 p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-500 flex items-start gap-2.5">
                <svg class="w-4 h-4 text-slate-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p>
                    <strong class="font-semibold text-slate-700">Catatan Pedoman Lapangan:</strong>
                    Rekomendasi di atas adalah panduan umum berbasis komputasi parameter model cuaca Open-Meteo. Selalu kombinasikan dengan inspeksi visual kondisi tanah, drainase, dan fase pertumbuhan tanaman Anda.
                </p>
            </div>
        </section>

    @endif

</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapEl = document.getElementById('eastLombokMap');
    if (!mapEl || typeof L === 'undefined') return;

    const locations = window.locationsData || {};
    const activeKey = window.activeLocationKey || 'selong';
    const activeLoc = locations[activeKey] || locations['selong'];

    // Inisialisasi peta berpusat di Lombok Timur
    const map = L.map('eastLombokMap', {
        scrollWheelZoom: false
    }).setView([activeLoc ? activeLoc.latitude : -8.60, activeLoc ? activeLoc.longitude : 116.52], 10);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap Kontributor'
    }).addTo(map);

    // Plotting penanda untuk seluruh 21 kecamatan + Anjani
    Object.keys(locations).forEach(key => {
        const loc = locations[key];
        const isActive = (key === activeKey);

        const markerColor = isActive ? '#10B981' : (loc.is_kecamatan ? '#2563EB' : '#F59E0B');
        const markerRadius = isActive ? 13 : 8;

        const circleMarker = L.circleMarker([loc.latitude, loc.longitude], {
            radius: markerRadius,
            fillColor: markerColor,
            color: '#FFFFFF',
            weight: isActive ? 3 : 2,
            opacity: 1,
            fillOpacity: 0.95
        }).addTo(map);

        const popupContent = `
            <div style="min-width: 175px;">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 3px;">
                    <strong style="font-size: 13px; color: #0F172A;">${loc.name}</strong>
                    ${isActive ? '<span style="background: #ECFDF5; color: #047857; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 9999px;">Aktif</span>' : ''}
                </div>
                <p style="font-size: 11px; color: #64748B; margin: 0 0 6px 0;">${loc.zone} (${loc.elevation})</p>
                ${!isActive ? `<a href="?location=${loc.key}" style="display: block; text-align: center; background: #10B981; color: white; text-decoration: none; font-size: 11px; font-weight: 600; padding: 5px 8px; border-radius: 8px; margin-top: 4px;">Pantau Cuaca Ini &rarr;</a>` : '<p style="font-size: 10px; color: #10B981; font-weight: 700; margin: 0;">✓ Sedang Aktif</p>'}
            </div>
        `;

        circleMarker.bindPopup(popupContent);

        if (isActive) {
            circleMarker.openPopup();
        }
    });
});
</script>
@endpush