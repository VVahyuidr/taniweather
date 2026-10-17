<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="TaniWeather - Sistem Informasi Monitoring Cuaca dan Potensi Pertanian di Seluruh 21 Kecamatan Lombok Timur">
    <meta name="author" content="Proyek Sistem Informasi">
    <title>{{ $title ?? 'Dashboard' }} | TaniWeather - Cuaca & Pertanian Lombok Timur</title>

    <!-- Favicon TaniWeather -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Chart.js CDN for interactive weather charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>

    @stack('styles')
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] min-h-screen flex antialiased font-sans selection:bg-emerald-500 selection:text-white">

    <!-- Mobile sidebar overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity" aria-hidden="true"></div>

    <!-- Sidebar Kiri (Lebar 264px, Background Navy Gelap, Fixed di Kiri) -->
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-50 w-[264px] bg-[#0B132B] text-white flex flex-col border-r border-slate-800/80 shadow-2xl transform transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0">

        <!-- Logo & Identitas Aplikasi TaniWeather -->
        <div class="p-5 border-b border-slate-800/70 flex-shrink-0 bg-[#070D1F]">
            <a href="{{ route('weather.dashboard', ['location' => $locationKey ?? 'selong']) }}" class="flex items-center gap-3 group" aria-label="TaniWeather Beranda">
                <!-- SVG Logo TaniWeather (Awan + Tunas Daun Pertanian) -->
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-blue-600 via-indigo-700 to-emerald-600 p-0.5 shadow-lg shadow-blue-900/40 flex-shrink-0 group-hover:scale-105 transition-transform">
                    <div class="w-full h-full bg-[#0B132B] rounded-[14px] flex items-center justify-center p-1.5">
                        <svg viewBox="0 0 64 64" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Matahari mini -->
                            <circle cx="25" cy="23" r="10" fill="#F59E0B" />
                            <!-- Awan putih -->
                            <path d="M20 40h24a8.5 8.5 0 0 0 .9-16.9A11.5 11.5 0 0 0 24 20.8 7.5 7.5 0 0 0 20 40z" fill="#F1F5F9" />
                            <!-- Daun/Tunas Hijau Pertanian -->
                            <path d="M42 46c0-7-5.5-12-12-12 0 6.5 5 12 12 12z" fill="#10B981" />
                            <path d="M28 46c0-4.5 3.5-8 8-8 0 4.5-3.5 8-8 8z" fill="#34D399" />
                        </svg>
                    </div>
                </div>
                <div class="min-w-0">
                    <span class="text-xl font-bold tracking-tight text-white block group-hover:text-emerald-400 transition-colors">TaniWeather</span>
                    <p class="text-[11px] font-medium text-slate-400 truncate">Cuaca & Pertanian Lombok Timur</p>
                </div>
            </a>
        </div>

        <!-- Menu Navigasi Utama -->
        <nav class="flex-1 p-3.5 space-y-1.5 overflow-y-auto custom-scrollbar" aria-label="Navigasi Utama">
            <div class="px-3 pt-2 pb-1.5 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                Navigasi Sistem
            </div>

            <!-- 1. Dashboard -->
            <a href="{{ route('weather.dashboard', ['location' => $locationKey ?? 'selong']) }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 group {{ request()->routeIs('weather.dashboard') || request()->routeIs('weather.index') ? 'bg-emerald-500/15 text-emerald-300 font-semibold border-l-4 border-emerald-500 shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('weather.dashboard') || request()->routeIs('weather.index') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- 2. Prakiraan Cuaca -->
            <a href="{{ route('weather.forecast', ['location' => $locationKey ?? 'selong']) }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 group {{ request()->routeIs('weather.forecast') ? 'bg-emerald-500/15 text-emerald-300 font-semibold border-l-4 border-emerald-500 shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('weather.forecast') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Prakiraan Cuaca</span>
            </a>

            <!-- 3. Analitik -->
            <a href="{{ route('weather.analytics', ['location' => $locationKey ?? 'selong']) }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 group {{ request()->routeIs('weather.analytics') ? 'bg-emerald-500/15 text-emerald-300 font-semibold border-l-4 border-emerald-500 shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('weather.analytics') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span>Analitik</span>
            </a>

            <!-- 4. Potensi Pertanian -->
            <a href="{{ route('weather.agriculture', ['location' => $locationKey ?? 'selong']) }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 group {{ request()->routeIs('weather.agriculture') ? 'bg-emerald-500/15 text-emerald-300 font-semibold border-l-4 border-emerald-500 shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('weather.agriculture') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span>Potensi Pertanian</span>
            </a>

            <!-- 5. Tentang -->
            <a href="{{ route('weather.about', ['location' => $locationKey ?? 'selong']) }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 group {{ request()->routeIs('weather.about') ? 'bg-emerald-500/15 text-emerald-300 font-semibold border-l-4 border-emerald-500 shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('weather.about') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Tentang</span>
            </a>

            <!-- Card Lokasi Aktif di Sidebar -->
            @if(isset($locations) && isset($locationKey) && isset($locations[$locationKey]))
                <div class="pt-4 pb-1">
                    <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800 text-xs space-y-1.5">
                        <div class="flex items-center justify-between text-slate-400">
                            <span class="font-medium text-[11px] uppercase tracking-wider">Lokasi Aktif</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        </div>
                        <p class="font-bold text-white text-sm">
                            {{ $locations[$locationKey]['name'] }}
                            @if(!$locations[$locationKey]['is_kecamatan'])
                                <span class="text-[10px] text-amber-400 font-normal block">(Kawasan Agribisnis)</span>
                            @endif
                        </p>
                        <p class="text-[11px] text-slate-400 line-clamp-2">{{ $locations[$locationKey]['description'] }}</p>
                        <div class="pt-1 text-[10px] text-emerald-400 font-mono">
                            {{ $locations[$locationKey]['elevation'] }} • {{ $locations[$locationKey]['region'] }}
                        </div>
                    </div>
                </div>
            @endif
        </nav>

        <!-- Bagian Bawah Sidebar: Sumber Data & Kredensial Tugas -->
        <div class="p-4 border-t border-slate-800/80 bg-[#070D1F] flex-shrink-0 text-xs text-slate-400 space-y-2">
            <div class="flex items-center justify-between">
                <span class="font-semibold text-slate-300">Cakupan Wilayah:</span>
                <span class="text-emerald-400 font-semibold">21 Kecamatan</span>
            </div>
            <div class="flex items-center justify-between pt-1">
                <span class="text-slate-400">Sumber Cuaca:</span>
                <a href="https://open-meteo.com/" target="_blank" rel="noopener noreferrer"
                    class="text-blue-400 hover:text-blue-300 hover:underline flex items-center gap-1 font-medium">
                    Open-Meteo
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>
            <p class="text-[11px] text-slate-500 leading-tight">
                Koordinat terverifikasi representatif pusat pemerintahan kecamatan (BPS Lombok Timur & OSM).
            </p>
            <div class="pt-2 border-t border-slate-800/60 text-[10px] text-slate-500">
                Tugas Mata Kuliah Proyek SI
            </div>
        </div>
    </aside>

    <!-- Konten Utama -->
    <main class="flex-1 lg:pl-[264px] min-h-screen flex flex-col w-full min-w-0" id="mainContent">

        <!-- Top Header Bar -->
        <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-sm min-h-[3.5rem]">
            <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 py-3 sm:py-4">

                <!-- Sisi Kiri: Tombol Menu Mobile & Judul Halaman -->
                <div class="flex items-center gap-2 sm:gap-3 lg:gap-4 min-w-0">
                    <button id="mobileMenuBtn"
                        class="lg:hidden p-2.5 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors"
                        aria-label="Buka navigasi" aria-expanded="false" aria-controls="sidebar">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="min-w-0">
                        @yield('header-title')
                    </div>
                </div>

                <!-- Sisi Kanan: Searchable Location Selector, Refresh Button, & Badge Data -->
                <div class="flex items-center gap-2 sm:gap-3 lg:gap-4 flex-shrink">
                    @if(isset($locations) && isset($locationKey))
                        <!-- Searchable Location Popover Button -->
                        <div class="relative" id="locationSelectorContainer">
                            <button type="button"
                                id="locationDropdownBtn"
                                class="flex items-center gap-2 sm:gap-2.5 px-3 sm:px-3.5 py-2 rounded-xl bg-white text-slate-800 border border-slate-300 hover:border-emerald-500 shadow-sm hover:shadow transition-all text-xs sm:text-sm font-semibold group"
                                aria-haspopup="true"
                                aria-expanded="false"
                                aria-label="Pilih kecamatan dari 21 kecamatan Lombok Timur">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="truncate max-w-[110px] sm:max-w-[150px]">
                                    {{ $locations[$locationKey]['name'] }}
                                </span>
                                <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-700 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu Popover with Search & Region Filters -->
                            <div id="locationDropdownPopover"
                                class="hidden absolute right-0 top-full mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-slate-200 z-50 overflow-hidden transform transition-all duration-150 origin-top-right">

                                <!-- Search Header -->
                                <div class="p-3 bg-slate-50 border-b border-slate-200">
                                    <div class="relative">
                                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        <input type="text"
                                            id="districtSearchInput"
                                            placeholder="Ketik nama kecamatan..."
                                            autocomplete="off"
                                            class="w-full pl-9 pr-3 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-slate-800 placeholder-slate-400">
                                    </div>

                                    <!-- Quick Region Tabs -->
                                    <div class="flex items-center gap-1 mt-2.5 overflow-x-auto custom-scrollbar pb-1 text-[11px]" id="regionFilterTabs">
                                        <button type="button" data-filter="all" class="px-2 py-0.5 rounded-md font-medium whitespace-nowrap bg-emerald-600 text-white">Semua (22)</button>
                                        <button type="button" data-filter="utara" class="px-2 py-0.5 rounded-md font-medium whitespace-nowrap bg-slate-200 text-slate-700 hover:bg-slate-300">Utara</button>
                                        <button type="button" data-filter="tengah" class="px-2 py-0.5 rounded-md font-medium whitespace-nowrap bg-slate-200 text-slate-700 hover:bg-slate-300">Tengah</button>
                                        <button type="button" data-filter="selatan" class="px-2 py-0.5 rounded-md font-medium whitespace-nowrap bg-slate-200 text-slate-700 hover:bg-slate-300">Selatan</button>
                                        <button type="button" data-filter="timur" class="px-2 py-0.5 rounded-md font-medium whitespace-nowrap bg-slate-200 text-slate-700 hover:bg-slate-300">Timur/Barat</button>
                                    </div>
                                </div>

                                <!-- Districts List -->
                                <div class="max-h-80 overflow-y-auto custom-scrollbar p-1.5" id="districtsListContainer">

                                    <!-- Header Kelompok: 21 Kecamatan Resmi -->
                                    <div class="px-3 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider bg-slate-100/70 rounded-lg my-1">
                                        21 Kecamatan Administratif Lombok Timur
                                    </div>

                                    @foreach($locations as $k => $loc)
                                        @php
                                            $isActive = ($k === $locationKey);
                                            $regLower = strtolower($loc['region'] . ' ' . $loc['zone']);
                                        @endphp
                                        <a href="{{ url()->current() }}?location={{ $k }}"
                                            data-name="{{ strtolower($loc['name']) }}"
                                            data-region="{{ $regLower }}"
                                            class="district-item flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-100 transition-colors {{ $isActive ? 'bg-emerald-50 text-emerald-900 font-bold border border-emerald-200' : 'text-slate-700' }}">
                                            <div class="min-w-0 pr-2">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="text-xs font-semibold text-slate-900 {{ $isActive ? 'text-emerald-700 font-bold' : '' }}">
                                                        {{ $loc['name'] }}
                                                    </span>
                                                    @if(!$loc['is_kecamatan'])
                                                        <span class="text-[9px] font-normal px-1.5 py-0.2 rounded bg-amber-100 text-amber-800">
                                                            Kawasan Khusus
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-[10px] text-slate-500 truncate mt-0.5">
                                                    {{ $loc['zone'] }} • {{ $loc['elevation'] }}
                                                </p>
                                            </div>

                                            @if($isActive)
                                                <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </a>

                                        @if($loop->iteration === 21)
                                            <!-- Separator Kawasan Khusus -->
                                            <div class="px-3 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider bg-amber-50 rounded-lg my-1 text-amber-800 border border-amber-200/60">
                                                Kawasan Agribisnis Tambahan
                                            </div>
                                        @endif
                                    @endforeach

                                    <!-- Empty search state -->
                                    <div id="districtEmptySearch" class="hidden p-6 text-center text-xs text-slate-400">
                                        <p>Kecamatan tidak ditemukan</p>
                                        <p class="text-[10px] text-slate-400 mt-1">Periksa kembali ejaan nama kecamatan</p>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="p-2.5 bg-slate-50 border-t border-slate-200 text-[10px] text-slate-500 flex items-center justify-between">
                                    <span>21 Kecamatan Terverifikasi</span>
                                    <span class="font-mono text-emerald-600">Open-Meteo GPS</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Refresh Data (Bust Cache via ?refresh=1) -->
                        <a href="{{ url()->current() }}?location={{ $locationKey }}&refresh=1"
                           id="refreshBtn"
                           class="p-2.5 rounded-xl bg-white text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200 shadow-sm transition-all duration-200"
                           title="Muat ulang data cuaca terkini dari Open-Meteo"
                           aria-label="Muat ulang data cuaca">
                            <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </a>
                    @endif

                    <!-- Badge Sumber Data Open-Meteo -->
                    <span class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl border border-slate-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Open-Meteo Live API
                    </span>
                </div>
            </div>
        </header>

        <!-- Area Konten Halaman (Padding Desktop 28-36px: p-5 sm:p-7 lg:p-8) -->
        <div class="flex-1 p-6 sm:p-7 lg:p-8 max-w-7xl w-full">
            @yield('content')
        </div>

        <!-- Footer Halaman -->
        <footer class="mt-auto border-t border-slate-200 bg-white py-6 px-5 sm:px-7 lg:px-8 text-xs text-slate-500">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
                <div>
                    <p class="font-medium text-slate-700">TaniWeather &copy; {{ date('Y') }}</p>
                    <p class="text-[11px] text-slate-500">Sistem Informasi Monitoring Cuaca dan Potensi Pertanian di Seluruh 21 Kecamatan Lombok Timur</p>
                </div>
                <div class="flex items-center gap-4 text-slate-600">
                    <span class="inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                        21 Kecamatan Terverifikasi
                    </span>
                    <span>•</span>
                    <a href="{{ route('weather.about', ['location' => $locationKey ?? 'selong']) }}" class="text-blue-600 hover:underline font-medium">
                        Dokumentasi Proyek
                    </a>
                </div>
            </div>
        </footer>
    </main>

    <!-- JavaScript Interaktif -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile sidebar toggle
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleBtn = document.getElementById('mobileMenuBtn');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'true');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
                if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    if (sidebar.classList.contains('-translate-x-full')) {
                        openSidebar();
                    } else {
                        closeSidebar();
                    }
                });
            }

            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }

            // Searchable Location Popover Logic
            const dropdownBtn = document.getElementById('locationDropdownBtn');
            const popover = document.getElementById('locationDropdownPopover');
            const searchInput = document.getElementById('districtSearchInput');
            const districtItems = document.querySelectorAll('.district-item');
            const emptyState = document.getElementById('districtEmptySearch');
            const filterTabs = document.querySelectorAll('#regionFilterTabs button');

            let currentRegionFilter = 'all';

            function togglePopover() {
                if (!popover) return;
                const isHidden = popover.classList.contains('hidden');
                if (isHidden) {
                    popover.classList.remove('hidden');
                    if (dropdownBtn) dropdownBtn.setAttribute('aria-expanded', 'true');
                    if (searchInput) {
                        setTimeout(() => searchInput.focus(), 50);
                    }
                } else {
                    popover.classList.add('hidden');
                    if (dropdownBtn) dropdownBtn.setAttribute('aria-expanded', 'false');
                }
            }

            if (dropdownBtn) {
                dropdownBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    togglePopover();
                });
            }

            // Click outside to close popover
            document.addEventListener('click', function(e) {
                if (popover && !popover.classList.contains('hidden')) {
                    const container = document.getElementById('locationSelectorContainer');
                    if (container && !container.contains(e.target)) {
                        popover.classList.add('hidden');
                        if (dropdownBtn) dropdownBtn.setAttribute('aria-expanded', 'false');
                    }
                }
            });

            // Filter logic (Search query & Region tab)
            function filterDistricts() {
                const query = (searchInput ? searchInput.value.toLowerCase().trim() : '');
                let visibleCount = 0;

                districtItems.forEach(item => {
                    const name = item.getAttribute('data-name') || '';
                    const region = item.getAttribute('data-region') || '';

                    const matchesQuery = (query === '' || name.includes(query) || region.includes(query));
                    let matchesRegion = true;

                    if (currentRegionFilter === 'utara') {
                        matchesRegion = region.includes('utara') || region.includes('rinjani');
                    } else if (currentRegionFilter === 'tengah') {
                        matchesRegion = region.includes('tengah');
                    } else if (currentRegionFilter === 'selatan') {
                        matchesRegion = region.includes('selatan') || region.includes('pesisir');
                    } else if (currentRegionFilter === 'timur') {
                        matchesRegion = region.includes('timur') || region.includes('barat');
                    }

                    if (matchesQuery && matchesRegion) {
                        item.style.display = 'flex';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                if (emptyState) {
                    if (visibleCount === 0) {
                        emptyState.classList.remove('hidden');
                    } else {
                        emptyState.classList.add('hidden');
                    }
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', filterDistricts);
            }

            // Region tabs click
            filterTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    filterTabs.forEach(t => {
                        t.classList.remove('bg-emerald-600', 'text-white');
                        t.classList.add('bg-slate-200', 'text-slate-700');
                    });
                    this.classList.remove('bg-slate-200', 'text-slate-700');
                    this.classList.add('bg-emerald-600', 'text-white');

                    currentRegionFilter = this.getAttribute('data-filter') || 'all';
                    filterDistricts();
                });
            });

            // Refresh animation
            const refreshBtn = document.getElementById('refreshBtn');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', function() {
                    const svg = this.querySelector('svg');
                    if (svg) svg.classList.add('animate-spin');
                });
            }
        });
    </script>

    @if(isset($weatherData))
        <script>
            window.weatherData = @json($weatherData);
            window.locationsData = @json($locations);
            window.activeLocationKey = @json($locationKey ?? 'selong');
        </script>
    @endif

    @stack('scripts')
</body>
</html>
