<?php

namespace App\Http\Controllers;

use App\Services\OpenMeteoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WeatherController extends Controller
{
    public function __construct(
        protected OpenMeteoService $weatherService
    ) {}

    /**
     * Dashboard Utama TaniWeather
     */
    public function index(Request $request): View
    {
        $data = $this->resolveWeatherData($request);

        return view('weather.dashboard', $data);
    }

    /**
     * Halaman Prakiraan Cuaca 7 Hari
     */
    public function forecast(Request $request): View
    {
        $data = $this->resolveWeatherData($request);

        return view('weather.forecast', $data);
    }

    /**
     * Halaman Analitik Cuaca & Iklim Pertanian
     */
    public function analytics(Request $request): View
    {
        $data = $this->resolveWeatherData($request);

        return view('weather.analytics', $data);
    }

    /**
     * Halaman Potensi Pertanian & Rekomendasi Budidaya
     */
    public function agriculture(Request $request): View
    {
        $data = $this->resolveWeatherData($request);

        return view('weather.agriculture', $data);
    }

    /**
     * Halaman Tentang TaniWeather & Dokumentasi Proyek
     */
    public function about(Request $request): View
    {
        $locationKey = $request->get('location', 'selong');
        $locations = $this->weatherService->getLocations();

        if (! isset($locations[$locationKey])) {
            $locationKey = 'selong';
        }

        return view('weather.about', [
            'locationKey' => $locationKey,
            'locations' => $locations,
            'currentLocation' => $locations[$locationKey],
        ]);
    }

    /**
     * API endpoint JSON data cuaca untuk pembaruan interaktif / AJAX
     */
    public function api(Request $request): JsonResponse
    {
        $locationKey = $request->get('location', 'selong');
        $locations = $this->weatherService->getLocations();

        if (! isset($locations[$locationKey])) {
            return response()->json([
                'success' => false,
                'error' => 'Lokasi tidak valid. Pilih dari 21 kecamatan Lombok Timur yang tersedia.',
            ], 400);
        }

        $forceRefresh = $request->boolean('refresh');

        try {
            $weatherData = $this->weatherService->getWeather($locationKey, $forceRefresh);
            $current = $weatherData['current'] ?? [];
            $daily = $weatherData['daily'] ?? [];
            $advice = $this->weatherService->getAgriculturalAdvice($current, $daily);
            $weatherInfo = $this->weatherService->getWeatherInfo($current['weather_code'] ?? 0);

            return response()->json([
                'success' => true,
                'locationKey' => $locationKey,
                'data' => $weatherData,
                'weatherInfo' => $weatherInfo,
                'advice' => $advice,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal mengambil data cuaca dari Open-Meteo',
            ], 500);
        }
    }

    /**
     * Resolusi data cuaca dan penanganan lokasi serta cache
     *
     * @return array{locationKey: string, locations: array, currentLocation: array, weatherData: ?array, error: ?string, advice: array}
     */
    protected function resolveWeatherData(Request $request): array
    {
        $locationKey = $request->get('location', 'selong');
        $locations = $this->weatherService->getLocations();

        if (! isset($locations[$locationKey])) {
            $locationKey = 'selong';
        }

        $forceRefresh = $request->boolean('refresh');

        $weatherData = null;
        $error = null;
        $advice = [];

        try {
            $weatherData = $this->weatherService->getWeather($locationKey, $forceRefresh);
            $current = $weatherData['current'] ?? [];
            $daily = $weatherData['daily'] ?? [];
            $advice = $this->weatherService->getAgriculturalAdvice($current, $daily);
        } catch (\Exception $e) {
            $error = 'Gagal memuat data cuaca dari server Open-Meteo. Silakan periksa koneksi internet atau gunakan tombol Muat Ulang.';
        }

        return [
            'locationKey' => $locationKey,
            'locations' => $locations,
            'currentLocation' => $locations[$locationKey],
            'weatherData' => $weatherData,
            'error' => $error,
            'advice' => $advice,
        ];
    }
}
