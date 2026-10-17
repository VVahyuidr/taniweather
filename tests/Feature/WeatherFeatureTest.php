<?php

namespace Tests\Feature;

use App\Services\OpenMeteoService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherFeatureTest extends TestCase
{
    /**
     * Seluruh 21 kecamatan administratif Lombok Timur + 1 kawasan khusus Anjani
     */
    protected array $allDistricts = [
        'aikmel',
        'jerowaru',
        'keruak',
        'labuhan-haji',
        'lenek',
        'masbagik',
        'montong-gading',
        'pringgabaya',
        'pringgasela',
        'sakra',
        'sakra-barat',
        'sakra-timur',
        'sambelia',
        'selong',
        'sembalun',
        'sikur',
        'suela',
        'sukamulia',
        'suralaga',
        'terara',
        'wanasaba',
        'anjani',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        // Mock Open-Meteo API dengan skema respons resmi
        Http::fake([
            'https://api.open-meteo.com/*' => Http::response([
                'latitude' => -8.65,
                'longitude' => 116.53,
                'generationtime_ms' => 0.15,
                'utc_offset_seconds' => 28800,
                'timezone' => 'Asia/Makassar',
                'elevation' => 120.0,
                'current' => [
                    'time' => '2026-10-16T12:00',
                    'interval' => 900,
                    'temperature_2m' => 29.5,
                    'relative_humidity_2m' => 74,
                    'apparent_temperature' => 32.1,
                    'precipitation' => 0.0,
                    'rain' => 0.0,
                    'weather_code' => 1,
                    'wind_speed_10m' => 11.2,
                    'wind_direction_10m' => 135,
                    'surface_pressure' => 1010.5,
                ],
                'daily' => [
                    'time' => ['2026-10-16', '2026-10-17', '2026-10-18', '2026-10-19', '2026-10-20', '2026-10-21', '2026-10-22'],
                    'weather_code' => [1, 2, 3, 51, 61, 2, 1],
                    'temperature_2m_max' => [31.5, 30.8, 29.9, 28.5, 29.0, 31.0, 32.0],
                    'temperature_2m_min' => [23.1, 22.8, 23.5, 22.0, 21.9, 22.5, 23.0],
                    'apparent_temperature_max' => [34.0, 33.5, 32.0, 30.5, 31.0, 33.0, 34.5],
                    'apparent_temperature_min' => [24.0, 23.5, 24.0, 23.0, 22.5, 23.5, 24.0],
                    'precipitation_sum' => [0.0, 0.5, 2.8, 8.5, 4.2, 0.0, 0.0],
                    'precipitation_hours' => [0.0, 1.0, 3.0, 5.0, 2.0, 0.0, 0.0],
                    'precipitation_probability_max' => [10, 25, 60, 85, 50, 15, 10],
                    'wind_speed_10m_max' => [14.5, 12.0, 15.2, 18.0, 13.5, 11.0, 10.5],
                    'sunrise' => ['2026-10-16T05:50', '2026-10-17T05:50', '2026-10-18T05:49', '2026-10-19T05:49', '2026-10-20T05:48', '2026-10-21T05:48', '2026-10-22T05:48'],
                    'sunset' => ['2026-10-16T18:10', '2026-10-17T18:10', '2026-10-18T18:10', '2026-10-19T18:11', '2026-10-20T18:11', '2026-10-21T18:11', '2026-10-22T18:11'],
                    'uv_index_max' => [9.5, 8.0, 6.5, 4.0, 7.0, 9.0, 10.0],
                ],
            ], 200),
        ]);
    }

    /**
     * Test bahwa sentralisasi lokasi memuat tepat 21 kecamatan resmi + Anjani
     */
    public function test_centralized_locations_contains_all_21_districts_plus_anjani(): void
    {
        $service = app(OpenMeteoService::class);
        $locations = $service->getLocations();
        $official = $service->getOfficialDistricts();

        $this->assertCount(22, $locations);
        $this->assertCount(21, $official);

        foreach ($this->allDistricts as $districtKey) {
            $this->assertArrayHasKey($districtKey, $locations, "District {$districtKey} must exist in locations repository.");
            $this->assertNotEmpty($locations[$districtKey]['name']);
            $this->assertNotEmpty($locations[$districtKey]['verification_source']);
            $this->assertIsFloat($locations[$districtKey]['latitude']);
            $this->assertIsFloat($locations[$districtKey]['longitude']);
        }

        // Pastikan Anjani ditandai bukan kecamatan tersendiri, melainkan berada di bawah Suralaga
        $this->assertFalse($locations['anjani']['is_kecamatan']);
        $this->assertEquals('suralaga', $locations['anjani']['parent_kecamatan']);
    }

    /**
     * Test halaman utama dashboard TaniWeather
     */
    public function test_dashboard_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('TaniWeather');
        $response->assertSee('Dashboard Cuaca');
        $response->assertSee('Cuaca Saat Ini');
        $response->assertSee('Selong, Lombok Timur');
        $response->assertSee('Data dari Open-Meteo');
    }

    /**
     * Test kelima rute utama dengan lokasi spesifik
     */
    public function test_all_routes_work_with_location_persistence(): void
    {
        $responseForecast = $this->get('/prakiraan?location=sembalun');
        $responseForecast->assertStatus(200);
        $responseForecast->assertSee('Sembalun');

        $responseAnalytics = $this->get('/analitik?location=aikmel');
        $responseAnalytics->assertStatus(200);
        $responseAnalytics->assertSee('Aikmel');

        $responseAgri = $this->get('/potensi-pertanian?location=wanasaba');
        $responseAgri->assertStatus(200);
        $responseAgri->assertSee('Wanasaba');

        $responseAbout = $this->get('/tentang?location=jerowaru');
        $responseAbout->assertStatus(200);
        $responseAbout->assertSee('Jerowaru');
    }

    /**
     * Test fallback lokasi jika lokasi tidak valid
     */
    public function test_invalid_location_falls_back_to_selong(): void
    {
        $response = $this->get('/?location=unknown_district_xyz');

        $response->assertStatus(200);
        $response->assertSee('Selong');
    }

    /**
     * Test verifikasi pemanggilan Open-Meteo dengan koordinat akurat per kecamatan
     */
    public function test_open_meteo_receives_exact_verified_coordinates_for_districts(): void
    {
        Cache::flush();
        $service = app(OpenMeteoService::class);
        $locations = $service->getLocations();

        $testCases = ['sembalun', 'jerowaru', 'sambelia', 'aikmel'];

        foreach ($testCases as $key) {
            $loc = $locations[$key];
            $response = $this->get("/?location={$key}&refresh=1");
            $response->assertStatus(200);

            Http::assertSent(function ($request) use ($loc) {
                return str_contains($request->url(), 'api.open-meteo.com')
                    && abs($request['latitude'] - $loc['latitude']) < 0.001
                    && abs($request['longitude'] - $loc['longitude']) < 0.001;
            });
        }
    }

    /**
     * Test pemantauan cuaca untuk seluruh 21 kecamatan + Anjani di Dashboard
     */
    public function test_all_21_districts_load_successfully_on_dashboard(): void
    {
        $service = app(OpenMeteoService::class);
        $locations = $service->getLocations();

        foreach ($this->allDistricts as $distKey) {
            $loc = $locations[$distKey];
            $response = $this->get("/?location={$distKey}");

            $response->assertStatus(200);
            $response->assertSee($loc['name']);
            $response->assertSee('Lokasi Aktif');
            $response->assertSee('Cuaca Saat Ini');
            $response->assertSee('Data dari Open-Meteo');
        }
    }

    /**
     * Test API endpoint data cuaca untuk seluruh 21 kecamatan
     */
    public function test_weather_api_returns_valid_json_for_districts(): void
    {
        foreach (['sembalun', 'keruak', 'sambelia', 'terara', 'anjani'] as $sampleKey) {
            $response = $this->getJson("/api/weather?location={$sampleKey}");

            $response->assertStatus(200);
            $response->assertJsonStructure([
                'success',
                'locationKey',
                'data' => [
                    'current',
                    'daily',
                    'location',
                ],
                'weatherInfo',
                'advice',
            ]);
            $response->assertJson([
                'success' => true,
                'locationKey' => $sampleKey,
            ]);
        }
    }
}
