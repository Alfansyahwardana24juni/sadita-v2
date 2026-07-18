<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ApiCoIdService
{
    private ?string $lastError = null;

    public function getDistricts(string $regencyCode): array
    {
        return $this->getRegionalList("/regional/indonesia/regencies/{$regencyCode}/districts");
    }

    public function getVillages(string $districtCode): array
    {
        return $this->getRegionalList("/regional/indonesia/districts/{$districtCode}/villages");
    }

    public function getRates(string $destinationVillageCode, int $weightGrams, ?string $originVillageCode = null): array
    {
        $this->lastError = null;

        $apiKey = (string) config('api_co_id.api_key');
        $origin = $originVillageCode ?: (string) config('api_co_id.origin_village_code');

        if ($apiKey === '') {
            $this->lastError = 'API key API.co.id belum dikonfigurasi.';
            return [];
        }

        if ($origin === '') {
            $this->lastError = 'Kode kelurahan asal API.co.id belum dikonfigurasi.';
            return [];
        }

        if ($destinationVillageCode === '') {
            $this->lastError = 'Pilih kelurahan/desa tujuan terlebih dahulu.';
            return [];
        }

        $weightKg = max(1, (int) ceil($weightGrams / 1000));
        $cacheKey = "api_co_id_rates:{$origin}:{$destinationVillageCode}:{$weightKg}";

        if (Cache::has($cacheKey)) {
            $cached = Cache::get($cacheKey);
            if (is_array($cached) && !empty($cached)) {
                return $cached;
            }
        }

        try {
            $response = Http::timeout(15)
                ->acceptJson()
                ->withHeaders(['x-api-co-id' => $apiKey])
                ->get(rtrim((string) config('api_co_id.base_url'), '/') . '/expedition/shipping-cost', [
                    'origin_village_code' => $origin,
                    'destination_village_code' => $destinationVillageCode,
                    'weight' => $weightKg,
                ]);

            $data = $response->json();

            if (! $response->successful() || ! (bool) ($data['is_success'] ?? false)) {
                $this->lastError = $this->translateErrorMessage((string) ($data['message'] ?? 'API.co.id menolak permintaan tarif.'));

                Log::warning('API.co.id shipping cost non-success', [
                    'status' => $response->status(),
                    'message' => $data['message'] ?? null,
                ]);

                return [];
            }

            $rates = collect($data['data']['couriers'] ?? [])
                ->map(fn (array $rate) => $this->mapRate($rate))
                ->filter()
                ->sortBy('cost')
                ->values()
                ->all();

            if (!empty($rates)) {
                Cache::put($cacheKey, $rates, 600); // 10 minutes TTL
            }

            return $rates;
        } catch (\Throwable $e) {
            $this->lastError = 'Gagal menghubungi API.co.id. Coba lagi beberapa saat.';
            Log::error('API.co.id shipping cost error: ' . $e->getMessage());
            return [];
        }
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    private function getRegionalList(string $path): array
    {
        $this->lastError = null;

        $apiKey = (string) config('api_co_id.api_key');

        if ($apiKey === '') {
            $this->lastError = 'API key API.co.id belum dikonfigurasi.';
            return [];
        }

        try {
            $response = Http::timeout(15)
                ->acceptJson()
                ->withHeaders(['x-api-co-id' => $apiKey])
                ->get(rtrim((string) config('api_co_id.base_url'), '/') . $path);

            $data = $response->json();

            if (! $response->successful() || ! (bool) ($data['is_success'] ?? false)) {
                $this->lastError = $this->translateErrorMessage((string) ($data['message'] ?? 'API.co.id menolak permintaan data wilayah.'));

                Log::warning('API.co.id regional non-success', [
                    'status' => $response->status(),
                    'path' => $path,
                    'message' => $data['message'] ?? null,
                ]);

                return [];
            }

            return is_array($data['data'] ?? null) ? $data['data'] : [];
        } catch (\Throwable $e) {
            $this->lastError = 'Gagal menghubungi API.co.id. Coba lagi beberapa saat.';
            Log::error('API.co.id regional error: ' . $e->getMessage());
            return [];
        }
    }

    private function mapRate(array $rate): ?array
    {
        $price = $rate['price'] ?? null;

        if ($price === null || (int) $price <= 0) {
            return null;
        }

        $courierCode = (string) ($rate['courier_code'] ?? '');
        $courierName = (string) ($rate['courier_name'] ?? $courierCode);

        if ($courierCode === '' || str_contains(strtolower($courierName), 'unknown')) {
            return null;
        }

        return [
            'type' => 'api_co_id',
            'courier' => strtoupper($courierCode),
            'name' => trim($courierName),
            'service_code' => $courierCode,
            'service_name' => '',
            'cost' => (int) $price,
            'estimated_days' => (string) ($rate['estimation'] ?? '-'),
            'is_free' => false,
        ];
    }

    private function translateErrorMessage(string $message): string
    {
        $normalized = strtolower($message);

        if (str_contains($normalized, 'api key') || str_contains($normalized, 'unauthorized')) {
            return 'API key API.co.id tidak valid atau belum aktif.';
        }

        if (str_contains($normalized, 'from village not found')) {
            return 'Kode kelurahan asal tidak ditemukan di API.co.id.';
        }

        if (str_contains($normalized, 'village not found')) {
            return 'Kode kelurahan tujuan tidak ditemukan di API.co.id.';
        }

        if (str_contains($normalized, 'not supported')) {
            return 'Kelurahan/desa ini belum didukung API.co.id untuk ongkir.';
        }

        if (str_contains($normalized, 'weight')) {
            return 'Berat kiriman tidak valid untuk API.co.id.';
        }

        return $message ?: 'Tarif ekspedisi belum tersedia dari API.co.id.';
    }
}
