<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RajaOngkirService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.rajaongkir.com/starter/';
    protected int $cacheDuration = 86400; // 24 jam

    public function __construct()
    {
        $this->apiKey = env('RAJAONGKIR_API_KEY', '');
    }

    /**
     * Get all provinces
     */
    public function getProvinces(): array
    {
        return Cache::remember('rajaongkir_provinces', $this->cacheDuration, function () {
            try {
                $response = Http::timeout(10)->withHeaders([
                    'key' => $this->apiKey
                ])->get($this->baseUrl . 'province');

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['rajaongkir']['results'] ?? [];
                }

                Log::warning('RajaOngkir getProvinces non-200', ['status' => $response->status()]);
                return $this->getMockProvinces();
            } catch (\Exception $e) {
                Log::error('RajaOngkir getProvinces error: ' . $e->getMessage());
                return $this->getMockProvinces();
            }
        });
    }

    /**
     * Get cities, optionally filtered by province
     */
    public function getCities(?int $provinceId = null): array
    {
        $cacheKey = $provinceId ? "rajaongkir_cities_{$provinceId}" : 'rajaongkir_cities_all';

        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($provinceId) {
            try {
                $params = $provinceId ? ['province' => $provinceId] : [];

                $response = Http::timeout(10)->withHeaders([
                    'key' => $this->apiKey
                ])->get($this->baseUrl . 'city', $params);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['rajaongkir']['results'] ?? [];
                }

                Log::warning('RajaOngkir getCities non-200', ['status' => $response->status()]);
                return $this->getMockCities($provinceId);
            } catch (\Exception $e) {
                Log::error('RajaOngkir getCities error: ' . $e->getMessage());
                return $this->getMockCities($provinceId);
            }
        });
    }

    /**
     * Calculate shipping cost
     *
     * @param int $originCityId
     * @param int $destinationCityId
     * @param int $weightGrams Weight in grams
     * @param string $courier jne|tiki|pos
     */
    public function calculateCost(int $originCityId, int $destinationCityId, int $weightGrams, string $courier): array
    {
        $cacheKey = "rajaongkir_cost_{$originCityId}_{$destinationCityId}_{$weightGrams}_{$courier}";

        return Cache::remember($cacheKey, 3600, function () use ($originCityId, $destinationCityId, $weightGrams, $courier) {
            try {
                $response = Http::timeout(10)->withHeaders([
                    'key' => $this->apiKey
                ])->post($this->baseUrl . 'cost', [
                    'origin'      => $originCityId,
                    'destination' => $destinationCityId,
                    'weight'      => $weightGrams,
                    'courier'     => $courier,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['rajaongkir']['results'] ?? [];
                }

                Log::warning('RajaOngkir calculateCost non-200', ['status' => $response->status()]);
                return [];
            } catch (\Exception $e) {
                Log::error('RajaOngkir calculateCost error: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Get shipping cost to a destination city from all couriers
     * Returns flat list of shipping methods
     */
    public function getShippingCost(int $destinationCityId, int $weightGrams, ?int $originCityId = null): array
    {
        $origin = $originCityId ?? (int) env('RAJAONGKIR_ORIGIN_CITY_ID', 153);
        $couriers = ['jne', 'tiki', 'pos'];
        $methods = [];

        foreach ($couriers as $courier) {
            $results = $this->calculateCost($origin, $destinationCityId, $weightGrams, $courier);

            foreach ($results as $result) {
                foreach ($result['costs'] ?? [] as $service) {
                    $cost = $service['cost'][0] ?? null;
                    if (!$cost) continue;

                    $methods[] = [
                        'courier'        => strtoupper($courier),
                        'service_code'   => $service['service'] ?? '',
                        'name'           => strtoupper($courier) . ' ' . ($service['service'] ?? ''),
                        'service_name'   => $service['description'] ?? '',
                        'cost'           => (int) ($cost['value'] ?? 0),
                        'estimated_days' => $cost['etd'] ?? '-',
                        'is_free'        => false,
                    ];
                }
            }
        }

        if (empty($methods)) {
            $methods = $this->getMockShippingMethods();
        }

        usort($methods, fn($a, $b) => $a['cost'] <=> $b['cost']);

        return $methods;
    }

    /**
     * Search city by name
     */
    public function searchCity(string $cityName): ?array
    {
        $cities = $this->getCities();

        foreach ($cities as $city) {
            if (stripos($city['city_name'] ?? '', $cityName) !== false) {
                return $city;
            }
        }

        return null;
    }

    /**
     * Get available couriers (Starter account only supports these)
     */
    public function getAvailableCouriers(): array
    {
        return ['jne', 'tiki', 'pos'];
    }

    /**
     * Starter account does not support subdistrict
     */
    public function supportsSubdistrict(): bool
    {
        return false;
    }

    // ─── Mock / Fallback Data ───────────────────────────────────────────────

    protected function getMockProvinces(): array
    {
        return [
            ['province_id' => '6',  'province' => 'DKI Jakarta'],
            ['province_id' => '9',  'province' => 'Jawa Barat'],
            ['province_id' => '10', 'province' => 'Jawa Tengah'],
            ['province_id' => '11', 'province' => 'Jawa Timur'],
            ['province_id' => '26', 'province' => 'Sulawesi Selatan'],
            ['province_id' => '32', 'province' => 'Sumatera Utara'],
            ['province_id' => '3',  'province' => 'Bali'],
        ];
    }

    protected function getMockCities(?int $provinceId = null): array
    {
        $all = [
            '6'  => [
                ['city_id' => '151', 'province_id' => '6',  'province' => 'DKI Jakarta',      'type' => 'Kota',       'city_name' => 'Jakarta Pusat',  'postal_code' => '10540'],
                ['city_id' => '152', 'province_id' => '6',  'province' => 'DKI Jakarta',      'type' => 'Kota',       'city_name' => 'Jakarta Selatan','postal_code' => '12230'],
                ['city_id' => '154', 'province_id' => '6',  'province' => 'DKI Jakarta',      'type' => 'Kota',       'city_name' => 'Jakarta Timur',  'postal_code' => '13330'],
                ['city_id' => '155', 'province_id' => '6',  'province' => 'DKI Jakarta',      'type' => 'Kota',       'city_name' => 'Jakarta Utara',  'postal_code' => '14140'],
                ['city_id' => '153', 'province_id' => '6',  'province' => 'DKI Jakarta',      'type' => 'Kota',       'city_name' => 'Jakarta Barat',  'postal_code' => '11220'],
            ],
            '9'  => [
                ['city_id' => '23',  'province_id' => '9',  'province' => 'Jawa Barat',       'type' => 'Kota',       'city_name' => 'Bandung',        'postal_code' => '40111'],
                ['city_id' => '39',  'province_id' => '9',  'province' => 'Jawa Barat',       'type' => 'Kota',       'city_name' => 'Bekasi',         'postal_code' => '17121'],
                ['city_id' => '69',  'province_id' => '9',  'province' => 'Jawa Barat',       'type' => 'Kota',       'city_name' => 'Bogor',          'postal_code' => '16119'],
                ['city_id' => '321', 'province_id' => '9',  'province' => 'Jawa Barat',       'type' => 'Kota',       'city_name' => 'Depok',          'postal_code' => '16412'],
            ],
            '10' => [
                ['city_id' => '234', 'province_id' => '10', 'province' => 'Jawa Tengah',      'type' => 'Kota',       'city_name' => 'Semarang',       'postal_code' => '50131'],
                ['city_id' => '375', 'province_id' => '10', 'province' => 'Jawa Tengah',      'type' => 'Kota',       'city_name' => 'Surakarta',      'postal_code' => '57111'],
            ],
            '11' => [
                ['city_id' => '444', 'province_id' => '11', 'province' => 'Jawa Timur',       'type' => 'Kota',       'city_name' => 'Surabaya',       'postal_code' => '60119'],
                ['city_id' => '196', 'province_id' => '11', 'province' => 'Jawa Timur',       'type' => 'Kota',       'city_name' => 'Malang',         'postal_code' => '65101'],
            ],
            '26' => [
                ['city_id' => '300', 'province_id' => '26', 'province' => 'Sulawesi Selatan', 'type' => 'Kota',       'city_name' => 'Makassar',       'postal_code' => '90111'],
                ['city_id' => '130', 'province_id' => '26', 'province' => 'Sulawesi Selatan', 'type' => 'Kabupaten',  'city_name' => 'Gowa',           'postal_code' => '92111'],
                ['city_id' => '294', 'province_id' => '26', 'province' => 'Sulawesi Selatan', 'type' => 'Kabupaten',  'city_name' => 'Maros',          'postal_code' => '90511'],
            ],
        ];

        if ($provinceId !== null) {
            return $all[(string) $provinceId] ?? [];
        }

        return array_merge(...array_values($all));
    }

    protected function getMockShippingMethods(): array
    {
        return [
            ['courier' => 'JNE',  'service_code' => 'REG', 'name' => 'JNE REG',  'service_name' => 'Layanan Reguler',      'cost' => 38000, 'estimated_days' => '2-3', 'is_free' => false],
            ['courier' => 'JNE',  'service_code' => 'OKE', 'name' => 'JNE OKE',  'service_name' => 'Ongkir Ekonomis',      'cost' => 28000, 'estimated_days' => '3-5', 'is_free' => false],
            ['courier' => 'TIKI', 'service_code' => 'REG', 'name' => 'TIKI REG', 'service_name' => 'Regular Service',      'cost' => 36000, 'estimated_days' => '2-3', 'is_free' => false],
            ['courier' => 'POS',  'service_code' => 'PCP', 'name' => 'POS PCP',  'service_name' => 'Paket Kilat Khusus',   'cost' => 32000, 'estimated_days' => '2-4', 'is_free' => false],
        ];
    }
}
