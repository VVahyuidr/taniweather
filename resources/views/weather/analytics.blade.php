@extends('layouts.app')

@section('title', 'Analitik Cuaca & Iklim Pertanian')

@section('header-title')
<div>
    <div class="flex items-center gap-2">
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Analitik Cuaca & Iklim</h1>
        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-md bg-blue-100 text-blue-800 border border-blue-200">
            {{ $currentLocation['name'] }}
        </span>
    </div>
    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">
        Visualisasi data time-series suhu, presipitasi, dan dinamika angin untuk evaluasi mikroklimat pertanian di <span class="font-semibold text-slate-700">{{ $currentLocation['name'] }}</span>.
    </p>
</div>
@endsection

@section('content')
<div class="space-y-7">

    @if($error)
        <div class="tani-card p-8 text-center bg-white border border-red-200 rounded-3xl">
            <h2 class="text-lg font-bold text-slate-900 mb-2">Gagal Memuat Analitik</h2>
            <p class="text-slate-600 mb-4 text-sm">{{ $error }}</p>
            <a href="{{ url()->current() }}?location={{ $locationKey }}&refresh=1" class="btn-primary">Muat Ulang</a>
        </div>
    @elseif($weatherData)
        @php
            $daily = $weatherData['daily'];
            $location = $weatherData['location'];
            $maxTemps = $daily['temperature_2m_max'];
            $minTemps = $daily['temperature_2m_min'];
            $rainSums = $daily['precipitation_sum'];
            $winds = $daily['wind_speed_10m_max'] ?? [12, 14, 15, 11, 10, 13, 12];

            $avgMax = count($maxTemps) ? array_sum($maxTemps) / count($maxTemps) : 0;
            $avgMin = count($minTemps) ? array_sum($minTemps) / count($minTemps) : 0;
            $totalRain = array_sum($rainSums);
            $maxRain = count($rainSums) ? max($rainSums) : 0;
            $hottestTemp = count($maxTemps) ? max($maxTemps) : 0;
        @endphp

        <!-- 4 Kartu Metrik Analitik Utama -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- 1. Rata-rata Suhu Maks -->
            <div class="stat-card">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Rata-Rata Suhu Maks</span>
                <div class="flex items-baseline gap-1 mt-1">
                    <span class="text-2xl sm:text-3xl font-extrabold text-amber-600">{{ number_format($avgMax, 1) }}</span>
                    <span class="text-sm font-semibold text-slate-500">°C</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Rerata 7 hari ke depan</p>
            </div>

            <!-- 2. Rata-rata Suhu Min -->
            <div class="stat-card">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Rata-Rata Suhu Min</span>
                <div class="flex items-baseline gap-1 mt-1">
                    <span class="text-2xl sm:text-3xl font-extrabold text-cyan-600">{{ number_format($avgMin, 1) }}</span>
                    <span class="text-sm font-semibold text-slate-500">°C</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Rerata malam & dini hari</p>
            </div>

            <!-- 3. Total Kumulatif Hujan -->
            <div class="stat-card">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Akumulasi Hujan 7 Hari</span>
                <div class="flex items-baseline gap-1 mt-1">
                    <span class="text-2xl sm:text-3xl font-extrabold text-blue-600">{{ number_format($totalRain, 1) }}</span>
                    <span class="text-sm font-semibold text-slate-500">mm</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">
                    {{ $totalRain > 20 ? 'Kategori Cukup Basah' : ($totalRain > 5 ? 'Kategori Moderat' : 'Kategori Kering / Rendah') }}
                </p>
            </div>

            <!-- 4. Puncak Terpanas -->
            <div class="stat-card">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Suhu Tertinggi Pekan Ini</span>
                <div class="flex items-baseline gap-1 mt-1">
                    <span class="text-2xl sm:text-3xl font-extrabold text-rose-600">{{ number_format($hottestTemp, 1) }}</span>
                    <span class="text-sm font-semibold text-slate-500">°C</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Potensi evapotranspirasi tinggi</p>
            </div>
        </div>

        <!-- Grafik Time-Series 1: Fluktuasi Suhu Maks vs Min -->
        <div class="chart-card">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Grafik Fluktuasi Suhu Harian (Derajat Celsius)</h2>
                    <p class="text-xs text-slate-500">Amplitudo suhu siang vs malam untuk analisis fisiologi tanaman</p>
                </div>
                <div class="flex items-center gap-4 text-xs">
                    <span class="flex items-center gap-1.5 font-medium text-slate-600">
                        <span class="w-3 h-3 rounded-full bg-blue-600"></span> Suhu Maksimum
                    </span>
                    <span class="flex items-center gap-1.5 font-medium text-slate-600">
                        <span class="w-3 h-3 rounded-full bg-emerald-600"></span> Suhu Minimum
                    </span>
                </div>
            </div>
            <div class="h-72 w-full relative">
                <canvas id="tempChart"></canvas>
            </div>
        </div>

        <!-- Dua Kolom: Grafik Curah Hujan & Grafik Angin -->
        <div class="grid lg:grid-cols-2 gap-6">
            <!-- Grafik Curah Hujan -->
            <div class="chart-card">
                <div class="mb-4 pb-2 border-b border-slate-100">
                    <h2 class="text-base font-bold text-slate-900">Distribusi Curah Hujan Harian</h2>
                    <p class="text-xs text-slate-500">Volume presipitasi dalam milimeter (mm)</p>
                </div>
                <div class="h-64 w-full relative">
                    <canvas id="rainChart"></canvas>
                </div>
            </div>

            <!-- Grafik Kecepatan Angin -->
            <div class="chart-card">
                <div class="mb-4 pb-2 border-b border-slate-100">
                    <h2 class="text-base font-bold text-slate-900">Kecepatan Angin Maksimum Harian</h2>
                    <p class="text-xs text-slate-500">Kecepatan hembusan angin (km/jam)</p>
                </div>
                <div class="h-64 w-full relative">
                    <canvas id="windChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabel Rekapitulasi Data Meteorologi 7 Hari -->
        <div class="tani-card p-6 overflow-hidden">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Tabel Rekapitulasi Parameter Meteorologi</h2>
                    <p class="text-xs text-slate-500">Data mentah resmi model prakiraan Open-Meteo untuk lokasi {{ $location['name'] }}</p>
                </div>
                <span class="text-xs text-slate-400 font-mono">Format ISO-8601</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 border-b border-slate-200">
                            <th class="py-2.5 px-3 font-semibold">Tanggal</th>
                            <th class="py-2.5 px-3 font-semibold">Suhu Maks (°C)</th>
                            <th class="py-2.5 px-3 font-semibold">Suhu Min (°C)</th>
                            <th class="py-2.5 px-3 font-semibold">Curah Hujan (mm)</th>
                            <th class="py-2.5 px-3 font-semibold">Peluang Hujan (%)</th>
                            <th class="py-2.5 px-3 font-semibold">Angin Maks (km/j)</th>
                            <th class="py-2.5 px-3 font-semibold">Indeks UV</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-mono text-slate-700">
                        @for($i = 0; $i < count($daily['time']); $i++)
                            @php
                                $dDate = \Carbon\Carbon::parse($daily['time'][$i])->setTimezone('Asia/Makassar');
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors {{ $i === 0 ? 'bg-emerald-50/30' : '' }}">
                                <td class="py-2.5 px-3 font-sans font-medium text-slate-900">
                                    {{ $dDate->translatedFormat('d M Y (D)') }}
                                    @if($i === 0)
                                        <span class="ml-1 text-[10px] text-emerald-600 font-bold font-mono">[Hari Ini]</span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3 font-bold text-amber-700">{{ number_format($daily['temperature_2m_max'][$i], 1) }}</td>
                                <td class="py-2.5 px-3 font-bold text-cyan-700">{{ number_format($daily['temperature_2m_min'][$i], 1) }}</td>
                                <td class="py-2.5 px-3 {{ $daily['precipitation_sum'][$i] > 0 ? 'text-blue-700 font-bold' : 'text-slate-400' }}">
                                    {{ number_format($daily['precipitation_sum'][$i], 1) }}
                                </td>
                                <td class="py-2.5 px-3">{{ $daily['precipitation_probability_max'][$i] ?? 0 }}%</td>
                                <td class="py-2.5 px-3">{{ number_format($daily['wind_speed_10m_max'][$i] ?? 0, 1) }}</td>
                                <td class="py-2.5 px-3">{{ $daily['uv_index_max'][$i] ?? '-' }}</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

    @endif

</div>
@endsection

