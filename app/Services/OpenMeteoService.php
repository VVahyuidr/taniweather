<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenMeteoService
{
    private const BASE_URL = 'https://api.open-meteo.com/v1/forecast';

    private const CACHE_TTL = 600; // 10 minutes

    /**
     * Sumber data terpusat seluruh 21 kecamatan di Kabupaten Lombok Timur + Kawasan Anjani
     * Koordinat representatif (pusat pemerintahan / ibukota kecamatan) terverifikasi
     * Sumber: OpenStreetMap, BPS Kabupaten Lombok Timur, & Badan Informasi Geospasial (BIG)
     */
    public const LOCATIONS = [
        // 1. AIKMEL
        'aikmel' => [
            'key' => 'aikmel',
            'name' => 'Aikmel',
            'label' => 'Kecamatan Aikmel',
            'latitude' => -8.5678,
            'longitude' => 116.5305,
            'elevation' => '380 m dpl',
            'region' => 'Tengah / Lereng Selatan Rinjani',
            'zone' => 'Sentra Hortikultura & Sayuran',
            'verification_source' => 'Kantor Camat Aikmel (OpenStreetMap & BPS)',
            'description' => 'Sentra budidaya aneka sayuran dataran menengah, cabai, dan tembakau rakyat.',
            'is_kecamatan' => true,
            'parent_kecamatan' => null,
        ],
        // 2. JEROWARU
        'jerowaru' => [
            'key' => 'jerowaru',
            'name' => 'Jerowaru',
            'label' => 'Kecamatan Jerowaru',
            'latitude' => -8.7831,
            'longitude' => 116.4727,
            'elevation' => '35 m dpl',
            'region' => 'Selatan / Pesisir',
            'zone' => 'Pertanian Lahan Kering & Palawija',
            'verification_source' => 'Pusat Pemerintahan Jerowaru (OpenStreetMap & BPS)',
            'description' => 'Wilayah selatan bertopografi perbukitan kapur dan lahan kering untuk jagung, tembakau, dan rumput laut.',
            'is_kecamatan' => true,
            'parent_kecamatan' => null,
        ],
        // 3. KERUAK
        'keruak' => [
            'key' => 'keruak',
            'name' => 'Keruak',
            'label' => 'Kecamatan Keruak',
            'latitude' => -8.7509,
            'longitude' => 116.5063,
            'elevation' => '25 m dpl',
            'region' => 'Selatan / Pesisir',
            'zone' => 'Pesisir Pantai & Lahan Tadah Hujan',
            'verification_source' => 'Kantor Camat Keruak (OpenStreetMap & BPS)',
            'description' => 'Wilayah pesisir selatan dengan tambak garam, perikanan budidaya, serta padi sawah tadah hujan.',
            'is_kecamatan' => true,
            'parent_kecamatan' => null,
        ],
        // 4. LABUHAN HAJI
        'labuhan-haji' => [
            'key' => 'labuhan-haji',
            'name' => 'Labuhan Haji',
            'label' => 'Kecamatan Labuhan Haji',
            'latitude' => -8.6584,
            'longitude' => 116.5853,
            'elevation' => '15 m dpl',
            'region' => 'Timur / Pesisir Selat Alas',
            'zone' => 'Dataran Rendah & Pesisir Selat Alas',
            'verification_source' => 'Pusat Labuhan Haji (OpenStreetMap & BPS)',
            'description' => 'Pintu gerbang laut timur Lombok, didominasi kelapa, perkebunan pesisir, dan persawahan irigasi hilir.',
            'is_kecamatan' => true,
            'parent_kecamatan' => null,
        ],
        // 5. LENEK
        'lenek' => [
            'key' => 'lenek',
            'name' => 'Lenek',
            'label' => 'Kecamatan Lenek',
            'latitude' => -8.5845,
            'longitude' => 116.5104,
            'elevation' => '310 m dpl',
            'region' => 'Tengah / Lereng Rinjani',
            'zone' => 'Sentra Padi & Mata Air Alami',
            'verification_source' => 'Kantor Camat Lenek (OpenStreetMap & BPS)',
            'description' => 'Kecamatan pemekaran kaya sumber mata air alami, sawah subur untuk padi organik dan sayur-mayur.',
            'is_kecamatan' => true,
            'parent_kecamatan' => null,
        ],
        // 6. MASBAGIK
        'masbagik' => [
            'key' => 'masbagik',
            'name' => 'Masbagik',
            'label' => 'Kecamatan Masbagik',
            'latitude' => -8.6214,
            'longitude' => 116.4772,
            'elevation' => '240 m dpl',
            'region' => 'Tengah',
            'zone' => 'Kawasan Agribisnis & Padi Sawah',
            'verification_source' => 'Kantor Camat Masbagik (OpenStreetMap & BPS)',
            'description' => 'Sentra agribisnis dan perdagangan sentral Lombok Timur, penghasil padi, kedelai, dan palawija.',
            'is_kecamatan' => true,
            'parent_kecamatan' => null,
        ],
        // 7. MONTONG GADING
        'montong-gading' => [
            'key' => 'montong-gading',
            'name' => 'Montong Gading',
            'label' => 'Kecamatan Montong Gading',
            'latitude' => -8.5556,
            'longitude' => 116.3950,
            'elevation' => '480 m dpl',
            'region' => 'Barat / Lereng Rinjani',
            'zone' => 'Perkebunan Kopi & Sayuran Dataran Tinggi',
            'verification_source' => 'Pusat Montong Gading (OpenStreetMap & BPS)',
            'description' => 'Kawasan sejuk di lereng barat daya Rinjani, sentra budidaya kopi robusta, kakao, pisang, dan sayuran.',
            'is_kecamatan' => true,
            'parent_kecamatan' => null,
        ],
        // 8. PRINGGABAYA
        'pringgabaya' => [
            'key' => 'pringgabaya',
            'name' => 'Pringgabaya',
            'label' => 'Kecamatan Pringgabaya',
            'latitude' => -8.4942,
            'longitude' => 116.6126,
            'elevation' => '50 m dpl',
            'region' => 'Timur / Pesisir',
            'zone' => 'Lahan Kering Pesisir & Palawija',
            'verification_source' => 'Kantor Camat Pringgabaya (OpenStreetMap & BPS)',
            'description' => 'Kawasan pesisir timur yang luas untuk budidaya kelapa, jagung hibrida, semangka, dan palawija.',
            'is_kecamatan' => true,
            'parent_kecamatan' => null,
        ],
        // 9. PRINGGASELA
        'pringgasela' => [
            'key' => 'pringgasela',
            'name' => 'Pringgasela',
            'label' => 'Kecamatan Pringgasela',
            'latitude' => -8.5725,
            'longitude' => 116.4814,
            'elevation' => '350 m dpl',
            'region' => 'Tengah / Lereng Rinjani',
            'zone' => 'Sentra Padi Subak & Hortikultura',
            'verification_source' => 'Kantor Camat Pringgasela (OpenStreetMap & BPS)',
            'description' => 'Kawasan agraris berhawa sejuk dengan sistem subak tertata, sentra padi, sayuran, dan agrowisata tenun.',
            'is_kecamatan' => true,
            'parent_kecamatan' => null,
        ],
        // 10. SAKRA
        'sakra' => [
            'key' => 'sakra',
            'name' => 'Sakra',
            'label' => 'Kecamatan Sakra',
            'latitude' => -8.6800,
            'longitude' => 116.4800,
            'elevation' => '110 m dpl',
            'region' => 'Selatan',
            'zone' => 'Lumbung Padi Sawah & Palawija',
            'verification_source' => 'Kantor Camat Sakra (OpenStreetMap & BPS)',
            'description' => 'Wilayah dataran rendah selatan penghasil padi sawah teknis, kedelai, kacang tanah, dan tembakau.',
            'is_kecamatan' => true,
            'parent_kecamatan' => null,
        ],
        // 11. SAKRA BARAT
        'sakra-barat' => [
            'key' => 'sakra-barat',
            'name' => 'Sakra Barat',
            'label' => 'Kecamatan Sakra Barat',
            'latitude' => -8.7093,
            'longitude' => 116.4607,
            'elevation' => '90 m dpl',
            'region' => 'Selatan',
            'zone' => 'Lahan Tadah Hujan & Tembakau',
            'verification_source' => 'Kantor Camat Sakra Barat (OpenStreetMap & BPS)',
            'description' => 'Kawasan pertanian lahan sawah dan tegalan untuk tembakau Virginia, cabai, dan jagung.',
            'is_kecamatan' => true,
            'parent_kecamatan' => null,
        ],
        // 12. SAKRA TIMUR
        'sakra-timur' => [
            'key' => 'sakra-timur',
            'name' => 'Sakra Timur',
            'label' => 'Kecamatan Sakra Timur',
            'latitude' => -8.7048,
            'longitude' => 116.5005,
            'elevation' => '75 m dpl',
            'region' => 'Selatan',
            'zone' => 'Padi Sawah & Palawija',
            'verification_source' => 'Pusat Pemerintahan Lepak (OpenStreetMap & BPS)',
            'description' => 'Berpusat di Desa Lepak, didominasi persawahan irigasi semi-teknis dan sentra penanaman palawija.',
            'is_kecamatan' => true,
            'parent_kecamatan' => null,
        ],
        // 13. SAMBELIA
        'sambelia' => [
            'key' => 'sambelia',
            'name' => 'Sambelia',
            'label' => 'Kecamatan Sambelia',
            'latitude' => -8.3791,
            'longitude' => 116.6869,
            'elevation' => '20 m dpl',
            'region' => 'Utara / Pesisir Timur',
            'zone' => 'Perkebunan Mete & Pertanian Pesisir',
            'verification_source' => 'Kantor Camat Sambelia (OpenStreetMap & BPS)',
            'description' => 'Kecamatan paling utara di tepi Selat Alas, sentra perkebunan jambu mete, jagung, dan kelapa.',
            'is_kecamatan' => true,
            'parent_kecamatan' => null,
        ],
        // 14. SELONG
        'selong' => [
            'key' => 'selong',
            'name' => 'Selong',
            'label' => 'Kecamatan Selong (Ibu Kota)',
            'latitude' => -8.6500,
            'longitude' => 116.5333,
            'elevation' => '120 m dpl',
            'region' => 'Tengah / Pusat',
            'zone' => 'Pusat Pemerintahan & Dataran Rendah',
            'verification_source' => 'Pusat Kota Selong (BPS & Badan Informasi Geospasial)',
            'description' => 'Ibu kota Kabupaten Lombok Timur, pusat perdagangan hasil bumi dan persawahan irigasi teknis.',
            'is_kecamatan' => true,
            'parent_kecamatan' => null,
        ],
        // 15. SEMBALUN
        'sembalun' => [
            'key' => 'sembalun',
            'name' => 'Sembalun',
            'label' => 'Kecamatan Sembalun',
            'latitude' => -8.3592,
            'longitude' => 116.5247,
            'elevation' => '1.180 m dpl',
            'region' => 'Utara / Kaldera Rinjani',
            'zone' => 'Dataran Tinggi Hortikultura Sub-Tropis',
            'verification_source' => 'Kantor Camat Sembalun Lawang (OpenStreetMap & BPS)',
            'description' => 'Lembah kaldera Gunung Rinjani berhawa dingin, sentra bawang putih nasional, kentang, wortel, dan stroberi.',
            'is_kecamatan' => true,
            'parent_kecamatan' => null,
        ],
        // 16. SIKUR
        'sikur' => [
            'key' => 'sikur',
            'name' => 'Sikur',
            'label' => 'Kecamatan Sikur',
            'latitude' => -8.6331,
            'longitude' => 116.4463,
            'elevation' => '260 m dpl',
            'region' => 'Tengah',
            'zone' => 'Sentra Pembibitan & Hortikultura',
            'verification_source' => 'Kantor Camat Sikur (OpenStreetMap & BPS)',
            'description' => 'Sentra penangkaran benih padi unggul, bibit buah-buahan, aneka hortikultura, dan perkebunan tembakau.',
            'is_kecamatan' => true,
            'parent_kecamatan' => null,
        ],
        // 17. SUELA
        'suela' => [
            'key' => 'suela',
            'name' => 'Suela',
            'label' => 'Kecamatan Suela',
            'latitude' => -8.5234,
            'longitude' => 116.5738,
            'elevation' => '450 m dpl',
            'region' => 'Utara / Lereng Rinjani',
            'zone' => 'Agroforestri & Perkebunan Lereng',
            'verification_source' => 'Kantor Camat Suela (OpenStreetMap & BPS)',
            'description' => 'Pintu gerbang pendakian lereng timur Rinjani, didominasi hutan kemasyarakatan, kemiri, kopi, dan jahe.',
            'is_kecamatan' => true,
            'parent_kecamatan' => null,
        ],
        // 18. SUKAMULIA
        'sukamulia' => [
            'key' => 'sukamulia',
            'name' => 'Sukamulia',
            'label' => 'Kecamatan Sukamulia',
            'latitude' => -8.6286,
            'longitude' => 116.5125,
            'elevation' => '160 m dpl',
            'region' => 'Tengah',
            'zone' => 'Tanaman Pangan & Aneka Palawija',
            'verification_source' => 'Kantor Camat Sukamulia (OpenStreetMap & BPS)',
            'description' => 'Kawasan persawahan subur di sekitar jalur lingkar Selong, penghasil padi, cabai rawit, dan jagung manis.',
            'is_kecamatan' => true,
            'parent_kecamatan' => null,
        ],
        // 19. SURALAGA
        'suralaga' => [
            'key' => 'suralaga',
            'name' => 'Suralaga',
            'label' => 'Kecamatan Suralaga',
            'latitude' => -8.6214,
            'longitude' => 116.5236,
            'elevation' => '190 m dpl',
            'region' => 'Tengah',
            'zone' => 'Persawahan Produktif & Sayuran',
            'verification_source' => 'Pusat Kecamatan Tebaban (OpenStreetMap & BPS)',
            'description' => 'Berpusat di Tebaban, memiliki potensi pertanian sawah beririgasi teknis untuk padi, cabai, dan tembakau.',
            'is_kecamatan' => true,
            'parent_kecamatan' => null,
        ],
        // 20. TERARA
        'terara' => [
            'key' => 'terara',
            'name' => 'Terara',
            'label' => 'Kecamatan Terara',
            'latitude' => -8.6394,
            'longitude' => 116.4133,
            'elevation' => '210 m dpl',
            'region' => 'Barat',
            'zone' => 'Lumbung Padi Barat & Minapolitan',
            'verification_source' => 'Kantor Camat Terara (OpenStreetMap & BPS)',
            'description' => 'Pintu masuk barat Lombok Timur, terkenal dengan sistem irigasi subak melimpah dan perikanan air tawar.',
            'is_kecamatan' => true,
            'parent_kecamatan' => null,
        ],
        // 21. WANASABA
        'wanasaba' => [
            'key' => 'wanasaba',
            'name' => 'Wanasaba',
            'label' => 'Kecamatan Wanasaba',
            'latitude' => -8.5678,
            'longitude' => 116.5672,
            'elevation' => '320 m dpl',
            'region' => 'Tengah / Lereng Tenggara Rinjani',
            'zone' => 'Lumbung Padi & Palawija',
            'verification_source' => 'Kantor Camat Wanasaba (OpenStreetMap & BPS)',
            'description' => 'Kecamatan agraris produktif dengan sistem subak teratur untuk tanaman pangan, jagung, dan tembakau Virginia.',
            'is_kecamatan' => true,
            'parent_kecamatan' => null,
        ],
        // LOKASI TAMBAHAN (Kawasan Khusus non-kecamatan di dalam Kec. Suralaga)
        'anjani' => [
            'key' => 'anjani',
            'name' => 'Anjani',
            'label' => 'Kawasan Agribisnis Anjani (Kec. Suralaga)',
            'latitude' => -8.6030,
            'longitude' => 116.5080,
            'elevation' => '220 m dpl',
            'region' => 'Tengah (Kawasan Khusus)',
            'zone' => 'Kawasan Agribisnis Suralaga',
            'verification_source' => 'Desa Anjani, Kec. Suralaga (OpenStreetMap & BPS)',
            'description' => 'Kawasan agribisnis dan sentra ekonomi strategis di Kecamatan Suralaga, penghasil padi dan cabai rawit.',
            'is_kecamatan' => false,
            'parent_kecamatan' => 'suralaga',
        ],
    ];

    /**
     * Mengambil data cuaca terkini dan prakiraan 7 hari dari Open-Meteo API
     */

    /**
     * Mengambil data cuaca terkini dan prakiraan 7 hari dari Open-Meteo API.
     * Menggunakan data cadangan jika API sedang tidak tersedia.
     */
    public function getWeather(string $locationKey, bool $forceRefresh = false): array
    {
        $location = self::LOCATIONS[$locationKey] ?? self::LOCATIONS['selong'];
        $cacheKey = "weather:{$locationKey}";
        $fallbackDir = '/tmp/taniweather-cache';
        $fallbackFile = $fallbackDir . '/' . preg_replace('/[^a-zA-Z0-9_-]/', '', $locationKey) . '.json';

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        try {
            return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($location, $locationKey, $fallbackDir, $fallbackFile) {
                $response = Http::retry(3, 1000, throw: false)
                    ->timeout(15)
                    ->get(self::BASE_URL, [
                        'latitude' => $location['latitude'],
                        'longitude' => $location['longitude'],
                        'current' => 'temperature_2m,relative_humidity_2m,apparent_temperature,precipitation,rain,weather_code,wind_speed_10m,wind_direction_10m,surface_pressure',
                        'daily' => 'temperature_2m_max,temperature_2m_min,apparent_temperature_max,apparent_temperature_min,precipitation_sum,precipitation_hours,precipitation_probability_max,wind_speed_10m_max,weather_code,sunrise,sunset,uv_index_max',
                        'timezone' => 'Asia/Makassar',
                        'forecast_days' => 7,
                    ]);

                if (! $response->successful()) {
                    Log::error('Open-Meteo API error', [
                        'status' => $response->status(),
                        'location' => $locationKey,
                    ]);

                    throw new \RuntimeException('Open-Meteo mengembalikan HTTP ' . $response->status());
                }

                $data = $response->json();

                if (! is_array($data) || ! isset($data['current'], $data['daily'])) {
                    throw new \RuntimeException('Respons Open-Meteo tidak valid');
                }

                $data['location'] = $location;

                if (! is_dir($fallbackDir)) {
                    @mkdir($fallbackDir, 0700, true);
                }

                $saved = [
                    'saved_at' => time(),
                    'data' => $data,
                ];

                if (is_dir($fallbackDir) && is_writable($fallbackDir)) {
                    @file_put_contents($fallbackFile, json_encode($saved), LOCK_EX);
                }

                return $data;
            });
        } catch (\Throwable $e) {
            Log::warning('Menggunakan fallback cuaca jika tersedia', [
                'location' => $locationKey,
                'message' => $e->getMessage(),
            ]);

            if (is_file($fallbackFile) && is_readable($fallbackFile)) {
                $saved = json_decode((string) file_get_contents($fallbackFile), true);

                if (
                    is_array($saved)
                    && isset($saved['saved_at'], $saved['data']['current'], $saved['data']['daily'])
                ) {
                    $data = $saved['data'];
                    $data['location'] = $location;
                    $data['fallback'] = true;
                    $data['fallback_saved_at'] = $saved['saved_at'];

                    return $data;
                }
            }

            throw $e;
        }
    }


    /**
     * Mendapatkan daftar seluruh lokasi yang didukung (21 kecamatan + Anjani)
     */
    public function getLocations(): array
    {
        return self::LOCATIONS;
    }

    /**
     * Mendapatkan daftar hanya 21 kecamatan administratif resmi
     */
    public function getOfficialDistricts(): array
    {
        return array_filter(self::LOCATIONS, fn ($loc) => $loc['is_kecamatan'] === true);
    }

    /**
     * Konversi kode WMO ke informasi Bahasa Indonesia dan tipe visual
     */
    public function getWeatherInfo(int $code): array
    {
        $weatherCodes = [
            0 => [
                'label' => 'Cerah',
                'type' => 'cerah',
                'description' => 'Langit cerah tanpa awan penghalang sinar matahari.',
                'badge' => 'bg-amber-100 text-amber-800 border-amber-300',
            ],
            1 => [
                'label' => 'Cerah Berawan',
                'type' => 'cerah-berawan',
                'description' => 'Sebagian besar cerah dengan sedikit gumpalan awan putih.',
                'badge' => 'bg-sky-100 text-sky-800 border-sky-300',
            ],
            2 => [
                'label' => 'Sebagian Berawan',
                'type' => 'cerah-berawan',
                'description' => 'Awan tersebar di langit, sinar matahari sesekali terhalang.',
                'badge' => 'bg-blue-100 text-blue-800 border-blue-300',
            ],
            3 => [
                'label' => 'Mendung / Berawan Tebal',
                'type' => 'berawan',
                'description' => 'Langit tertutup lapisan awan tebal secara menyeluruh.',
                'badge' => 'bg-slate-200 text-slate-800 border-slate-300',
            ],
            45 => [
                'label' => 'Berkabut',
                'type' => 'kabut',
                'description' => 'Kabut tebal membatasi jarak pandang permukaan.',
                'badge' => 'bg-zinc-200 text-zinc-800 border-zinc-300',
            ],
            48 => [
                'label' => 'Kabut Dingin Berembun',
                'type' => 'kabut',
                'description' => 'Kabut embun dingin terutama di dataran tinggi Sembalun & Suela.',
                'badge' => 'bg-zinc-200 text-zinc-800 border-zinc-300',
            ],
            51 => [
                'label' => 'Gerimis Ringan',
                'type' => 'hujan-ringan',
                'description' => 'Tetesan air halus dengan intensitas presipitasi rendah.',
                'badge' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
            ],
            53 => [
                'label' => 'Gerimis Sedang',
                'type' => 'hujan-ringan',
                'description' => 'Gerimis berkesinambungan yang membasahi permukaan daun.',
                'badge' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
            ],
            55 => [
                'label' => 'Gerimis Lebat',
                'type' => 'hujan-ringan',
                'description' => 'Gerimis rapat dengan akumulasi air cepat di tanah.',
                'badge' => 'bg-blue-100 text-blue-800 border-blue-300',
            ],
            56 => [
                'label' => 'Gerimis Dingin',
                'type' => 'hujan-ringan',
                'description' => 'Gerimis bertemperatur rendah khas dataran tinggi.',
                'badge' => 'bg-blue-100 text-blue-800 border-blue-300',
            ],
            57 => [
                'label' => 'Gerimis Dingin Lebat',
                'type' => 'hujan-ringan',
                'description' => 'Gerimis dingin padat.',
                'badge' => 'bg-blue-200 text-blue-900 border-blue-400',
            ],
            61 => [
                'label' => 'Hujan Ringan',
                'type' => 'hujan-ringan',
                'description' => 'Hujan berintensitas ringan, tanah mulai basah merata.',
                'badge' => 'bg-blue-100 text-blue-800 border-blue-300',
            ],
            63 => [
                'label' => 'Hujan Sedang',
                'type' => 'hujan-lebat',
                'description' => 'Hujan stabil berintensitas sedang berpotensi membasahi lahan secara intensif.',
                'badge' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            ],
            65 => [
                'label' => 'Hujan Lebat',
                'type' => 'hujan-lebat',
                'description' => 'Curah hujan deras berisiko memicu genangan air di saluran irigasi.',
                'badge' => 'bg-indigo-200 text-indigo-900 border-indigo-400',
            ],
            66 => [
                'label' => 'Hujan Dingin Ringan',
                'type' => 'hujan-ringan',
                'description' => 'Hujan bersuhu dingin di area pegunungan.',
                'badge' => 'bg-blue-200 text-blue-900 border-blue-400',
            ],
            67 => [
                'label' => 'Hujan Dingin Lebat',
                'type' => 'hujan-lebat',
                'description' => 'Hujan dingin lebat di lereng Rinjani.',
                'badge' => 'bg-indigo-200 text-indigo-900 border-indigo-400',
            ],
            71 => [
                'label' => 'Presipitasi Ringan',
                'type' => 'hujan-ringan',
                'description' => 'Presipitasi atmosferik ringan.',
                'badge' => 'bg-slate-200 text-slate-800 border-slate-300',
            ],
            73 => [
                'label' => 'Presipitasi Sedang',
                'type' => 'hujan-ringan',
                'description' => 'Presipitasi atmosferik sedang.',
                'badge' => 'bg-slate-200 text-slate-800 border-slate-300',
            ],
            75 => [
                'label' => 'Presipitasi Lebat',
                'type' => 'hujan-lebat',
                'description' => 'Presipitasi atmosferik lebat.',
                'badge' => 'bg-slate-300 text-slate-900 border-slate-400',
            ],
            80 => [
                'label' => 'Hujan Lokal Ringan',
                'type' => 'hujan-ringan',
                'description' => 'Hujan kilat lokal berdurasi singkat.',
                'badge' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
            ],
            81 => [
                'label' => 'Hujan Lokal Sedang',
                'type' => 'hujan-lebat',
                'description' => 'Hujan lokal yang cukup deras.',
                'badge' => 'bg-blue-200 text-blue-900 border-blue-400',
            ],
            82 => [
                'label' => 'Hujan Badai Lokal Lebat',
                'type' => 'hujan-lebat',
                'description' => 'Hujan lebat sporadis dengan fluktuasi angin kencang.',
                'badge' => 'bg-indigo-200 text-indigo-900 border-indigo-400',
            ],
            95 => [
                'label' => 'Badai Petir',
                'type' => 'badai-petir',
                'description' => 'Hujan lebat disertai kilatan petir dan hembusan angin kencang. Bahaya untuk aktivitas lapangan.',
                'badge' => 'bg-rose-100 text-rose-800 border-rose-300',
            ],
            96 => [
                'label' => 'Badai Petir & Hujan Es Ringan',
                'type' => 'badai-petir',
                'description' => 'Kondisi badai ekstrem disertai butiran es.',
                'badge' => 'bg-rose-200 text-rose-900 border-rose-400',
            ],
            99 => [
                'label' => 'Badai Petir & Hujan Es Lebat',
                'type' => 'badai-petir',
                'description' => 'Badai petir lebat ekstrem. Hindari seluruh kegiatan terbuka.',
                'badge' => 'bg-rose-300 text-rose-950 border-rose-500',
            ],
        ];

        return $weatherCodes[$code] ?? [
            'label' => 'Kondisi Beragam',
            'type' => 'cerah-berawan',
            'description' => 'Kondisi cuaca teramati oleh instrumen satelit/model.',
            'badge' => 'bg-gray-100 text-gray-800 border-gray-300',
        ];
    }

    /**
     * Menghasilkan rekomendasi aktivitas pertanian berdasarkan parameter cuaca aktual & prakiraan.
     */
    public function getAgriculturalAdvice(array $current, array $daily): array
    {
        $advice = [];

        $temp = (float) ($current['temperature_2m'] ?? 0);
        $humidity = (float) ($current['relative_humidity_2m'] ?? 0);
        $windSpeed = (float) ($current['wind_speed_10m'] ?? 0);
        $weatherCode = (int) ($current['weather_code'] ?? 0);

        $todayRain = (float) ($daily['precipitation_sum'][0] ?? 0);
        $tomorrowRain = (float) ($daily['precipitation_sum'][1] ?? 0);
        $rainProbToday = (int) ($daily['precipitation_probability_max'][0] ?? 0);
        $maxTemp = (float) ($daily['temperature_2m_max'][0] ?? 0);

        // 1. REKOMENDASI PENYEMPROTAN
        if ($todayRain > 3 || ($weatherCode >= 51 && $weatherCode <= 67) || $rainProbToday > 65) {
            $advice[] = [
                'category' => 'Penyemprotan',
                'type' => 'danger',
                'status' => 'Tidak Disarankan',
                'title' => 'Tunda Penyemprotan Pestisida / Pupuk Daun',
                'reason' => "Prediksi curah hujan {$todayRain} mm dengan probabilitas presipitasi {$rainProbToday}%.",
                'message' => 'Bahan aktif berisiko tinggi tercuci air hujan sebelum terserap sempurna oleh kutikula daun, menyebabkan pemborosan biaya input tani dan pencemaran tanah.',
                'action' => 'Jadwalkan ulang penyemprotan setelah curah hujan reda dan daun tanaman kembali kering.',
            ];
        } elseif ($windSpeed > 15) {
            $advice[] = [
                'category' => 'Penyemprotan',
                'type' => 'warning',
                'status' => 'Waspada Drift',
                'title' => 'Waspada Hembusan Angin Kencang saat Menyemprot',
                'reason' => "Kecepatan angin terukur {$windSpeed} km/jam (ambang batas aman: 15 km/jam).",
                'message' => 'Angin kencang memicu spray drift (butiran semprot terbawa angin menjauhi sasaran) dan penguapan cepat cairan insektisida/fungisida.',
                'action' => 'Gunakan nosel bertekanan rendah dengan ukuran droplet lebih besar, atau tunda hingga sore hari saat angin mereda.',
            ];
        } else {
            $advice[] = [
                'category' => 'Penyemprotan',
                'type' => 'success',
                'status' => 'Sangat Direkomendasikan',
                'title' => 'Kondisi Optimal untuk Aplikasi Semprot',
                'reason' => "Angin tenang ({$windSpeed} km/jam) dan potensi hujan sangat rendah ({$rainProbToday}%).",
                'message' => 'Kondisi cuaca sangat mendukung efektivitas penyerapan pestisida kontak/sistemik maupun pupuk daun.',
                'action' => 'Waktu terbaik penyemprotan adalah pagi hari pukul 06.30 - 09.30 WITA ketika stomata daun terbuka penuh.',
            ];
        }

        // 2. REKOMENDASI IRIGASI & PENYIRAMAN LAHAN
        if ($todayRain > 8 || $tomorrowRain > 12) {
            $advice[] = [
                'category' => 'Irigasi',
                'type' => 'info',
                'status' => 'Hemat Air',
                'title' => 'Irigasi Buatan Tidak Diperlukan',
                'reason' => "Presipitasi diperkirakan mencapai {$todayRain} mm hari ini dan {$tomorrowRain} mm besok.",
                'message' => 'Cadangan air tanah terisi secara alami oleh hujan. Pemberian air tambahan justru berisiko memicu waterlogging (tanah jenuh air).',
                'action' => 'Fokuskan tenaga kerja pada pengecekan saluran pembuangan (drainase) agar bedengan cabai, tembakau, atau bawang tidak tergenang.',
            ];
        } elseif ($todayRain < 1.5 && $humidity < 55 && $maxTemp > 30) {
            $advice[] = [
                'category' => 'Irigasi',
                'type' => 'warning',
                'status' => 'Prioritas Penyiraman',
                'title' => 'Tingkatkan Pasokan Air / Irigasi Lahan',
                'reason' => "Curah hujan nihil ({$todayRain} mm), kelembapan rendah ({$humidity}%), dan suhu siang mencapai {$maxTemp}°C.",
                'message' => 'Laju evapotranspirasi tinggi dapat memicu stres kekeringan pada tanaman hortikultura Sembalun & Aikmel serta tanaman palawija.',
                'action' => 'Lakukan pengairan secara berkala pada pagi atau menjelang senja untuk meminimalkan penguapan langsung.',
            ];
        } else {
            $advice[] = [
                'category' => 'Irigasi',
                'type' => 'success',
                'status' => 'Irigasi Rutin',
                'title' => 'Jadwal Irigasi Berjalan Normal',
                'reason' => "Kelembapan udara berada pada tingkat seimbang ({$humidity}%).",
                'message' => 'Kadar air tanah berada pada kapasitas lapang yang cukup stabil untuk fase vegetatif maupun generatif.',
                'action' => 'Lanjutkan giliran air irigasi subak sesuai kalender pembagian air setempat.',
            ];
        }

        // 3. REKOMENDASI PEMUPUKAN TANAH
        if ($tomorrowRain > 12 || $todayRain > 10) {
            $advice[] = [
                'category' => 'Pemupukan',
                'type' => 'danger',
                'status' => 'Tunda Pemupukan',
                'title' => 'Tunda Penaburan Pupuk Butir / Granul',
                'reason' => "Hujan lebat diprediksi terjadi (estimasi {$todayRain} - {$tomorrowRain} mm).",
                'message' => 'Aliran air permukaan (run-off) yang deras akan menghanyutkan butiran pupuk sebelum sempat larut dan diserap oleh perakaran.',
                'action' => 'Tunda pemupukan dasar maupun susulan sampai intensitas curah hujan mereda.',
            ];
        } elseif ($todayRain <= 5 && $humidity >= 50) {
            $advice[] = [
                'category' => 'Pemupukan',
                'type' => 'success',
                'status' => 'Waktu Tepat',
                'title' => 'Waktu Efektif Pemupukan Akar',
                'reason' => 'Tanah dalam kondisi cukup lembap tanpa ancaman banjir limpasan.',
                'message' => 'Kelembapan tanah moderat mempercepat proses disolusi butiran pupuk NPK/Urea ke zona rizosfer tanaman.',
                'action' => 'Benamkan pupuk di samping perakaran tanaman lalu tutup tipis dengan tanah untuk efisiensi penyerapan nitrogen.',
            ];
        }

        // 4. REKOMENDASI PANEN & PASCA-PANEN
        if ($todayRain < 1 && $weatherCode <= 2 && $rainProbToday < 30) {
            $advice[] = [
                'category' => 'Panen & Jemur',
                'type' => 'success',
                'status' => 'Sangat Baik',
                'title' => 'Kondisi Ideal untuk Panen & Penjemuran Komoditas',
                'reason' => 'Langit cerah/cerah berawan dengan paparan radiasi surya yang kuat.',
                'message' => 'Sangat menguntungkan untuk panen tembakau Virginia, cabai merah, serta penjemuran gabah padi dan jagung di lantai jemur terbuka.',
                'action' => 'Maksimalkan pemanfaatan sinar matahari penuh antara pukul 09.00 hingga 15.00 WITA.',
            ];
        } else {
            $advice[] = [
                'category' => 'Panen & Jemur',
                'type' => 'warning',
                'status' => 'Waspada Lembap',
                'title' => 'Waspada Penjemuran Hasil Panen',
                'reason' => "Terdapat indikasi tutupan awan atau potensi presipitasi {$todayRain} mm.",
                'message' => 'Kelembapan udara dapat memperlambat penurunan kadar air komoditas simpan dan memicu tumbuhnya jamur gudang (Aspergillus).',
                'action' => 'Siapkan terpal pelindung cepat gelar atau gunakan rumah pengeringan berventilasi terkontrol.',
            ];
        }

        // 5. DETEKSI DINI RISIKO HAMA & PENYAKIT
        if ($humidity >= 80 && $temp >= 24 && $temp <= 31) {
            $advice[] = [
                'category' => 'Kesehatan Tanaman',
                'type' => 'warning',
                'status' => 'Waspada Jamur',
                'title' => 'Peningkatan Risiko Penyakit Jamur & Bakteri',
                'reason' => "Kelembapan relatif tinggi ({$humidity}%) berpadu suhu hangat ({$temp}°C).",
                'message' => 'Kombinasi iklim mikro ini sangat mendukung spora antraknosa (patek) pada cabai rawit dan busuk daun (Phytophthora) pada tanaman hortikultura.',
                'action' => 'Lakukan perompesan daun tua yang rapat untuk memperbaiki sirkulasi udara mikro di tajuk tanaman.',
            ];
        }

        return $advice;
    }
}
