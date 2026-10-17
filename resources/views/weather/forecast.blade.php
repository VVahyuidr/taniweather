@extends('layouts.app')

@section('title', 'Prakiraan Cuaca 7 Hari')

@section('header-title')
<div>
    <div class="flex items-center gap-2">
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Prakiraan Cuaca 7 Hari</h1>
        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-md bg-blue-100 text-blue-800 border border-blue-200">
            {{ $currentLocation['name'] }}
        </span>
    </div>
    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">
        Prospek mingguan cuaca dan dinamika atmosfer untuk perencanaan aktivitas pertanian di <span class="font-semibold text-slate-700">{{ $currentLocation['name'] }}, Lombok Timur</span>.
    </p>
</div>
@endsection

@section('content')
<div class="space-y-7">

    @if($error)
        <div class="tani-card p-8 text-center bg-white border border-red-200 rounded-3xl">
            <h2 class="text-lg font-bold text-slate-900 mb-2">Gagal Memuat Prakiraan</h2>
            <p class="text-slate-600 mb-4 text-sm">{{ $error }}</p>
            <a href="{{ url()->current() }}?location={{ $locationKey }}&refresh=1" class="btn-primary">Muat Ulang</a>
        </div>
    @elseif($weatherData)
        @php
            $daily = $weatherData['daily'];
            $location = $weatherData['location'];
            $service = app(App\Services\OpenMeteoService::class);
        @endphp

        <!-- Highlight Hari Ini & Besok -->
        <div class="grid md:grid-cols-2 gap-5">
            @for($i = 0; $i < 2; $i++)
                @php
                    $isToday = $i === 0;
                    $date = \Carbon\Carbon::parse($daily['time'][$i])->setTimezone('Asia/Makassar');
                    $code = (int) $daily['weather_code'][$i];
                    $info = $service->getWeatherInfo($code);
                    $rainSum = (float) ($daily['precipitation_sum'][$i] ?? 0);
                    $rainProb = $daily['precipitation_probability_max'][$i] ?? 0;
                    $windMax = $daily['wind_speed_10m_max'][$i] ?? 0;
                    $uv = $daily['uv_index_max'][$i] ?? '-';
                @endphp
                <div class="tani-card p-6 relative overflow-hidden {{ $isToday ? 'border-emerald-300 ring-2 ring-emerald-500/20' : '' }}">
                    @if($isToday)
                        <div class="absolute top-0 right-0 bg-emerald-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl uppercase tracking-wider">
                            Hari Ini
                        </div>
                    @else
                        <div class="absolute top-0 right-0 bg-blue-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl uppercase tracking-wider">
                            Besok
                        </div>
                    @endif

                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                {{ $isToday ? 'Kondisi Hari Ini' : 'Prospek Besok' }}
                            </span>
                            <h2 class="text-lg font-bold text-slate-900 mt-0.5">
                                {{ $date->translatedFormat('l, d F Y') }}
                            </h2>
                            <p class="text-sm font-semibold text-slate-700 mt-2 flex items-center gap-2">
                                <x-weather-icon :code="$code" class="w-6 h-6" />
                                <span>{{ $info['label'] }}</span>
                            </p>
                        </div>
                        <div class="text-right pt-2">
                            <span class="text-3xl font-extrabold text-slate-900">{{ number_format($daily['temperature_2m_max'][$i], 1) }}°</span>
                            <span class="text-sm text-slate-400 block font-normal">Min {{ number_format($daily['temperature_2m_min'][$i], 1) }}°C</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2 mt-5 pt-4 border-t border-slate-100 text-center text-xs">
                        <div class="p-2 rounded-lg bg-slate-50">
                            <span class="text-slate-400 block text-[10px]">Curah Hujan</span>
                            <span class="font-bold text-slate-800">{{ number_format($rainSum, 1) }} mm</span>
                        </div>
                        <div class="p-2 rounded-lg bg-slate-50">
                            <span class="text-slate-400 block text-[10px]">Peluang Hujan</span>
                            <span class="font-bold text-blue-600">{{ $rainProb }}%</span>
                        </div>
                        <div class="p-2 rounded-lg bg-slate-50">
                            <span class="text-slate-400 block text-[10px]">Angin Maks</span>
                            <span class="font-bold text-slate-800">{{ number_format($windMax, 1) }} km/j</span>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <!-- Tabel & Kartu Rinci 7 Hari -->
        <div class="tani-card p-6 sm:p-7">
            <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Rincian Lengkap Harian (7 Hari ke Depan)</h2>
                    <p class="text-xs text-slate-500">Estimasi suhu, presipitasi, dan stabilitas cuaca di {{ $location['name'] }}</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-700">
                    Model: Open-Meteo
                </span>
            </div>

            <div class="grid gap-3.5">
                @for($i = 0; $i < count($daily['time']); $i++)
                    @php
                        $date = \Carbon\Carbon::parse($daily['time'][$i])->setTimezone('Asia/Makassar');
                        $code = (int) $daily['weather_code'][$i];
                        $info = $service->getWeatherInfo($code);
                        $rainSum = (float) ($daily['precipitation_sum'][$i] ?? 0);
                        $rainProb = $daily['precipitation_probability_max'][$i] ?? 0;
                        $windMax = $daily['wind_speed_10m_max'][$i] ?? 0;
                        $uv = $daily['uv_index_max'][$i] ?? 0;
                        $isToday = $i === 0;
                    @endphp
                    <div class="p-4 rounded-2xl border border-slate-200 hover:border-blue-300 hover:bg-blue-50/20 transition-all flex flex-col md:flex-row md:items-center justify-between gap-4 {{ $isToday ? 'bg-emerald-50/30 border-emerald-200' : 'bg-white' }}">

                        <!-- Hari & Tanggal -->
                        <div class="flex items-center gap-3 md:w-56">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0">
                                <x-weather-icon :code="$code" class="w-7 h-7" />
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-bold text-slate-900 text-sm">
                                        {{ $isToday ? 'Hari Ini' : $date->translatedFormat('l') }}
                                    </h3>
                                    @if($isToday)
                                        <span class="px-1.5 py-0.5 text-[10px] font-bold rounded bg-emerald-500 text-white">AKTIF</span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500">{{ $date->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>

                        <!-- Kondisi & Deskripsi -->
                        <div class="md:flex-1">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $info['badge'] ?? 'bg-slate-100 text-slate-800' }}">
                                {{ $info['label'] }}
                            </span>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-1">{{ $info['description'] }}</p>
                        </div>

                        <!-- Parameter Cuaca -->
                        <div class="grid grid-cols-4 gap-3 text-center md:w-80">
                            <div>
                                <span class="text-[10px] text-slate-400 block uppercase">Suhu</span>
                                <span class="font-extrabold text-slate-900 text-sm">
                                    {{ number_format($daily['temperature_2m_max'][$i], 0) }}°
                                    <span class="font-normal text-slate-400 text-xs">/ {{ number_format($daily['temperature_2m_min'][$i], 0) }}°</span>
                                </span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 block uppercase">Hujan</span>
                                <span class="font-bold text-slate-800 text-sm">{{ number_format($rainSum, 1) }} mm</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 block uppercase">Peluang</span>
                                <span class="font-bold text-blue-600 text-sm">{{ $rainProb }}%</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 block uppercase">Angin</span>
                                <span class="font-bold text-slate-800 text-sm">{{ number_format($windMax, 1) }} k/j</span>
                            </div>
                        </div>

                        <!-- Status Aktivitas Tani -->
                        <div class="md:w-44 text-right">
                            @if($rainSum > 5 || $rainProb > 70)
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-rose-700 bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-200">
                                    Waspada Hujan
                                </span>
                            @elseif($windMax > 20)
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200">
                                    Angin Kencang
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                                    Ramah Aktivitas Tani
                                </span>
                            @endif
                        </div>
                    </div>
                @endfor
            </div>
        </div>

    @endif

</div>
@endsection

