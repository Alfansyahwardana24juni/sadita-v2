<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * SHIPPING RULES (jangan diubah):
 *
 * RULE 1: Customer WAJIB pilih kota tujuan terlebih dahulu
 * RULE 2: Kota ∈ (Makassar, Maros) → tampilkan Kurir Sadita + Ambil Langsung
 *         Free ongkir jika berat ≥ 10 kg
 * RULE 3: Kota ∉ (Makassar, Maros) → tampilkan RajaOngkir + Ambil Langsung
 * RULE 4: Jika RajaOngkir → tampilkan daftar layanan dari API
 */
class ShippingService
{
    // Kota dalam kota (Kurir SADITA)
    private const SADITA_CITIES = ['makassar', 'maros'];

    // Free ongkir Kurir SADITA mulai berat ini (kg)
    private const FREE_SHIPPING_KG = 10;

    // Origin city ID RajaOngkir (Makassar)
    private const ORIGIN_CITY_ID = 300;

    private RajaOngkirService $rajaOngkir;

    public function __construct()
    {
        $this->rajaOngkir = app(RajaOngkirService::class);
    }

    /**
     * Get available shipping methods berdasarkan kota tujuan dan berat.
     *
     * @param string $cityName  Nama kota tujuan
     * @param int    $cityId    City ID RajaOngkir (digunakan jika luar kota)
     * @param float  $weightKg  Berat total dalam kg
     * @return array
     */
    public function getAvailableShippingMethods(string $cityName, int $cityId, float $weightKg): array
    {
        $methods     = [];
        $isLocalCity = $this->isSaditaCity($cityName);
        $weightGr    = (int) ($weightKg * 1000);

        if ($isLocalCity) {
            // RULE 2 — Kurir SADITA
            $isFree = $weightKg >= self::FREE_SHIPPING_KG;

            $methods[] = [
                'type'           => 'kurir_sadita',
                'name'           => 'Kurir SADITA',
                'service_code'   => 'kurir_sadita',
                'service_name'   => $isFree
                    ? 'Gratis ongkir pembelian ≥ ' . self::FREE_SHIPPING_KG . ' kg'
                    : 'Pengiriman dalam kota Makassar & Maros',
                'cost'           => $isFree ? 0 : 50000,
                'estimated_days' => '1 – 2 hari kerja',
                'is_free'        => $isFree,
            ];
        } else {
            // RULE 3 & 4 — RajaOngkir
            $rajaOngkirMethods = $this->getRajaOngkirMethods($cityId, $weightGr);
            $methods = array_merge($methods, $rajaOngkirMethods);
        }

        // Ambil Langsung — selalu tersedia
        $methods[] = [
            'type'           => 'pickup',
            'name'           => 'Ambil Langsung',
            'service_code'   => 'pickup',
            'service_name'   => 'Ambil sendiri di kantor SADITA, Makassar',
            'cost'           => 0,
            'estimated_days' => 'Sesuai jam kantor',
            'is_free'        => true,
        ];

        return $methods;
    }

    /**
     * Cek apakah nama kota termasuk area SADITA (Makassar / Maros)
     */
    public function isSaditaCity(string $cityName): bool
    {
        $normalized = strtolower(trim($cityName));

        foreach (self::SADITA_CITIES as $saditaCity) {
            if (str_contains($normalized, $saditaCity)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get RajaOngkir shipping methods (RULE 4)
     */
    private function getRajaOngkirMethods(int $destinationCityId, int $weightGr): array
    {
        $methods = [];

        foreach (['jne', 'tiki', 'pos'] as $courier) {
            try {
                $results = $this->rajaOngkir->calculateCost(
                    self::ORIGIN_CITY_ID,
                    $destinationCityId,
                    $weightGr,
                    $courier
                );

                foreach ($results as $result) {
                    foreach ($result['costs'] ?? [] as $service) {
                        $cost = $service['cost'][0] ?? null;
                        if (!$cost) continue;

                        $methods[] = [
                            'type'           => 'rajaongkir',
                            'courier'        => strtoupper($courier),
                            'name'           => strtoupper($courier) . ' ' . ($service['service'] ?? ''),
                            'service_code'   => $service['service'] ?? '',
                            'service_name'   => $service['description'] ?? '',
                            'cost'           => (int) ($cost['value'] ?? 0),
                            'estimated_days' => ($cost['etd'] ?? '-') . ' hari',
                            'is_free'        => false,
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::warning("ShippingService RajaOngkir [{$courier}]: " . $e->getMessage());
            }
        }

        if (empty($methods)) {
            $methods = $this->getMockRajaOngkirMethods();
        }

        usort($methods, fn($a, $b) => $a['cost'] <=> $b['cost']);

        return $methods;
    }

    // ─── Proxy ke RajaOngkirService ─────────────────────────────────────────

    public function getProvinces(): array
    {
        return $this->rajaOngkir->getProvinces();
    }

    public function getCitiesByProvince(int $provinceId): array
    {
        return $this->rajaOngkir->getCities($provinceId);
    }

    public function getAllCities(): array
    {
        return $this->rajaOngkir->getCities();
    }

    /**
     * Starter account tidak mendukung subdistrict (selalu false)
     */
    public function supportsSubdistrict(): bool
    {
        return false;
    }

    // ─── Mock fallback ───────────────────────────────────────────────────────

    private function getMockRajaOngkirMethods(): array
    {
        return [
            ['type' => 'rajaongkir', 'courier' => 'JNE',  'name' => 'JNE OKE',  'service_code' => 'OKE', 'service_name' => 'Ongkir Ekonomis',   'cost' => 28000, 'estimated_days' => '3-5 hari', 'is_free' => false],
            ['type' => 'rajaongkir', 'courier' => 'JNE',  'name' => 'JNE REG',  'service_code' => 'REG', 'service_name' => 'Layanan Reguler',    'cost' => 38000, 'estimated_days' => '2-3 hari', 'is_free' => false],
            ['type' => 'rajaongkir', 'courier' => 'TIKI', 'name' => 'TIKI REG', 'service_code' => 'REG', 'service_name' => 'Regular Service',    'cost' => 36000, 'estimated_days' => '2-3 hari', 'is_free' => false],
            ['type' => 'rajaongkir', 'courier' => 'POS',  'name' => 'POS PCP',  'service_code' => 'PCP', 'service_name' => 'Paket Kilat Khusus', 'cost' => 32000, 'estimated_days' => '2-4 hari', 'is_free' => false],
        ];
    }
}
