@extends('layouts.app')

@section('title', 'Potensi & Rekomendasi Pertanian')

@section('header-title')
<div>
    <div class="flex items-center gap-2">
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Potensi & Rekomendasi Pertanian</h1>
        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-md bg-emerald-100 text-emerald-800 border border-emerald-200">
            {{ $currentLocation['name'] }}
        </span>
    </div>
    <p class="text-slate-500 text-xs sm:text-sm mt-0.5">
        Pedoman operasional budidaya tani berbasis kondisi meteorologis aktual di kawasan <span class="font-semibold text-slate-700">{{ $currentLocation['name'] }}</span>.
    </p>
</div>
@endsection

@section('content')
<div class="space-y-7">

    @if($error)
        <div class="tani-card p-8 text-center bg-white border border-red-200 rounded-3xl">
            <h2 class="text-lg font-bold text-slate-900 mb-2">Gagal Memuat Panduan Pertanian</h2>
            <p class="text-slate-600 mb-4 text-sm">{{ $error }}</p>
            <a href="{{ url()->current() }}?location={{ $locationKey }}&refresh=1" class="btn-primary">Muat Ulang</a>
        </div>
    @elseif($weatherData)
        @php
            $current = $weatherData['current'];
            $daily = $weatherData['daily'];
            $location = $weatherData['location'];
        @endphp

        <!-- Panel Konteks Agro-Ekologis Wilayah Terpilih -->
        <div class="p-5 sm:p-6 rounded-3xl bg-gradient-to-r from-emerald-800 via-teal-800 to-slate-900 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1">
                    <span class="text-xs uppercase tracking-wider text-emerald-300 font-semibold">
                        Profil Wilayah Pertanian
                    </span>
                    <h2 class="text-2xl font-bold text-white">{{ $location['name'] }} — {{ $location['zone'] ?? 'Lombok Timur' }}</h2>
                    <p class="text-xs sm:text-sm text-emerald-100/85 max-w-2xl leading-relaxed">
                        {{ $location['description'] }}
                    </p>
                </div>
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md p-3 rounded-2xl border border-white/15 flex-shrink-0 text-xs">
                    <div>
                        <span class="text-emerald-200 block text-[10px]">Elevasi</span>
                        <span class="font-bold text-white text-sm">{{ $location['elevation'] ?? '-' }}</span>
                    </div>
                    <span class="text-white/30">|</span>
                    <div>
                        <span class="text-emerald-200 block text-[10px]">Kelembapan</span>
                        <span class="font-bold text-white text-sm">{{ $current['relative_humidity_2m'] }}%</span>
                    </div>
                    <span class="text-white/30">|</span>
                    <div>
                        <span class="text-emerald-200 block text-[10px]">Angin</span>
                        <span class="font-bold text-white text-sm">{{ number_format($current['wind_speed_10m'], 1) }} k/j</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rekomendasi Terperinci Tiap Kategori Aktivitas Tani -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Analisis Kelayakan Aktivitas Budidaya</h2>
                    <p class="text-xs text-slate-500">Evaluasi berbasis parameter fisik cuaca (kecepatan angin, presipitasi, suhu, kelembapan)</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-700">
                    5 Aspek Kunci
                </span>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                @foreach($advice as $adv)
                    @php
                        $colorClass = 'border-slate-300 bg-white';
                        $badgeColor = 'bg-slate-100 text-slate-800';
                        if ($adv['type'] === 'success') {
                            $colorClass = 'border-emerald-300 bg-emerald-50/20';
                            $badgeColor = 'bg-emerald-100 text-emerald-800 border-emerald-300';
                        } elseif ($adv['type'] === 'warning') {
                            $colorClass = 'border-amber-300 bg-amber-50/20';
                            $badgeColor = 'bg-amber-100 text-amber-800 border-amber-300';
                        } elseif ($adv['type'] === 'danger') {
                            $colorClass = 'border-rose-300 bg-rose-50/20';
                            $badgeColor = 'bg-rose-100 text-rose-800 border-rose-300';
                        } else {
                            $colorClass = 'border-blue-300 bg-blue-50/20';
                            $badgeColor = 'bg-blue-100 text-blue-800 border-blue-300';
                        }
                    @endphp
                    <div class="tani-card p-5 border-l-4 {{ $colorClass }} flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-2 pb-2 border-b border-slate-100">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-600">
                                    {{ $adv['category'] }}
                                </span>
                                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold border {{ $badgeColor }}">
                                    {{ $adv['status'] }}
                                </span>
                            </div>

                            <h3 class="text-base font-bold text-slate-900 mb-1.5">{{ $adv['title'] }}</h3>

                            <div class="p-2.5 rounded-xl bg-slate-50 text-xs text-slate-700 mb-2.5 border border-slate-100">
                                <strong class="text-slate-900 font-semibold">Alasan Meteorologis:</strong>
                                {{ $adv['reason'] }}
                            </div>

                            <p class="text-xs text-slate-600 leading-relaxed mb-3">
                                {{ $adv['message'] }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-start gap-2 text-xs">
                            <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold text-[10px] flex-shrink-0">
                                TINDAKAN
                            </span>
                            <span class="font-medium text-slate-800 leading-snug">{{ $adv['action'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Panduan Karakteristik Komoditas Unggulan Lombok Timur -->
        <div class="tani-card p-6 sm:p-7">
            <div class="mb-5 pb-3 border-b border-slate-100">
                <h2 class="text-lg font-bold text-slate-900">Sensitivitas Cuaca Komoditas Unggulan Lombok Timur</h2>
                <p class="text-xs text-slate-500">Karakteristik respon tanaman utama terhadap dinamika cuaca lokal</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                <!-- 1. Tembakau Virginia -->
                <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-200">
                    <span class="text-base">🍃</span>
                    <h3 class="font-bold text-slate-900 text-sm mt-1">Tembakau Virginia</h3>
                    <p class="text-slate-500 text-[11px] font-medium">Aikmel, Wanasaba, Selong</p>
                    <p class="text-slate-600 mt-2 leading-relaxed">
                        Sangat sensitif terhadap kelebihan air dan genangan. Hujan deras pada fase pemasakan daun dapat menurunkan kadar nikotin dan mutu daun krosok.
                    </p>
                </div>

                <!-- 2. Bawang Merah & Putih -->
                <div class="p-4 rounded-2xl bg-purple-50/50 border border-purple-200">
                    <span class="text-base">🧅</span>
                    <h3 class="font-bold text-slate-900 text-sm mt-1">Bawang Merah & Putih</h3>
                    <p class="text-slate-500 text-[11px] font-medium">Sembalun & Lereng Rinjani</p>
                    <p class="text-slate-600 mt-2 leading-relaxed">
                        Memerlukan suhu sejuk dan drainase porous. Kelembapan &gt;80% rentan memicu serangan bercak ungu (Alternaria porri) dan layu fusarium.
                    </p>
                </div>

                <!-- 3. Cabai Rawit & Keriting -->
                <div class="p-4 rounded-2xl bg-rose-50/50 border border-rose-200">
                    <span class="text-base">🌶️</span>
                    <h3 class="font-bold text-slate-900 text-sm mt-1">Cabai Rawit / Merah</h3>
                    <p class="text-slate-500 text-[11px] font-medium">Suralaga (Anjani), Aikmel</p>
                    <p class="text-slate-600 mt-2 leading-relaxed">
                        Kombinasi suhu panas dan hujan sporadis meningkatkan risiko gugur bunga dan serangan patek (Antraknosa). Hindari aplikasi semprot saat basah.
                    </p>
                </div>

                <!-- 4. Padi Sawah & Palawija -->
                <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-200">
                    <span class="text-base">🌾</span>
                    <h3 class="font-bold text-slate-900 text-sm mt-1">Padi Sawah & Jagung</h3>
                    <p class="text-slate-500 text-[11px] font-medium">Wanasaba, Selong, Anjani</p>
                    <p class="text-slate-600 mt-2 leading-relaxed">
                        Memerlukan manajemen pengairan berselang (intermittent irrigation) pada fase bunting dan pengisian bulir untuk mencegah rebah saat angin kencang.
                    </p>
                </div>
            </div>
        </div>

        <!-- Disclaimer Akademik / Etika Sistem Informasi -->
        <div class="p-4 rounded-2xl bg-slate-100 border border-slate-200 text-xs text-slate-600 flex items-start gap-3">
            <svg class="w-5 h-5 text-slate-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <strong class="font-bold text-slate-800 block text-xs mb-0.5">Disclaimer Ilmiah & Panduan Umum:</strong>
                <p class="leading-relaxed">
                    Sistem Informasi TaniWeather memberikan rekomendasi preskriptif berdasarkan kalkulasi model numerik cuaca Open-Meteo. Rekomendasi ini berkedudukan sebagai <em>decision-support system</em> (pendukung keputusan) dan tidak bermaksud menggantikan verifikasi langsung kondisi kelembapan tanah, kesuburan lahan, serta konsultasi bersama Penyuluh Pertanian Lapangan (PPL) setempat.
                </p>
            </div>
        </div>

    @endif

</div>
@endsection

