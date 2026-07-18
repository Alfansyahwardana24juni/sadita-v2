<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;
use App\Services\ApiCoIdService;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
class ShippingController extends Controller
{
    // Kota dalam kota (Kurir SADITA, bukan RajaOngkir)
    private const SADITA_CITIES = ['makassar', 'maros'];

    // Free ongkir Kurir SADITA mulai berat ini (kg)
    private const FREE_SHIPPING_KG = 10;

    private RajaOngkirService $rajaOngkir;

    public function __construct(
        RajaOngkirService $rajaOngkir,
        private ApiCoIdService $apiCoId
    )
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    // ─── GET /api/shipping/provinces ────────────────────────────────────────

    public function getProvinces()
    {
        try {
            $provinces = Province::query()
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn (Province $province) => [
                    'province_id' => $province->id,
                    'province' => $province->name,
                ])
                ->values();

            return response()->json([
                'success' => true,
                'data'    => $provinces,
            ]);
        } catch (\Exception $e) {
            Log::error('ShippingController::getProvinces – ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memuat provinsi'], 500);
        }
    }

    // ─── GET /api/shipping/cities?province_id=xx ────────────────────────────

    public function getCities(Request $request)
    {
        $provinceId = trim((string) $request->query('province_id'));

        if (!$provinceId) {
            return response()->json(['success' => false, 'message' => 'province_id wajib diisi'], 422);
        }

        try {
            $cities = Regency::query()
                ->where('province_id', $provinceId)
                ->orderBy('name')
                ->get(['id', 'province_id', 'name', 'rajaongkir_city_id'])
                ->map(fn (Regency $regency) => [
                    'regency_id' => $regency->id,
                    'city_id' => $regency->rajaongkir_city_id,
                    'province_id' => $regency->province_id,
                    'type' => str_starts_with($regency->id, substr($regency->province_id, 0, 2) . '7') ? 'Kota' : 'Kabupaten',
                    'city_name' => $regency->name,
                    'rajaongkir_city_id' => $regency->rajaongkir_city_id,
                ])
                ->values();

            return response()->json([
                'success' => true,
                'data'    => $cities,
            ]);
        } catch (\Exception $e) {
            Log::error('ShippingController::getCities – ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memuat kota'], 500);
        }
    }

    // ─── POST /api/shipping/methods ─────────────────────────────────────────
    //
    // Body: { city_id (RajaOngkir), regency_id, city_name, postal_code, weight (gram), total (rupiah) }
    //
    // RULE 2: Makassar/Maros → Kurir SADITA + Ambil Langsung
    // RULE 3: Lainnya        → RajaOngkir   + Ambil Langsung

    public function getShippingMethods(Request $request)
    {
        $request->validate([
            'city_id'   => 'nullable|integer',
            'regency_id' => 'nullable|string',
            'city_name' => 'required|string',
            'postal_code' => 'nullable|string|max:10',
            'origin_village_code' => 'nullable|string|size:10',
            'destination_village_code' => 'nullable|string|size:10',
            'origin_city' => 'nullable|string|max:255',
            'weight'    => 'nullable|numeric|min:1',
            'total'     => 'nullable|numeric|min:0',
            'items' => 'nullable|array',
        ]);

        $cityId   = $request->filled('city_id') ? (int) $request->input('city_id') : null;
        $cityName = trim($request->input('city_name'));
        $postalCode = preg_replace('/\D+/', '', (string) $request->input('postal_code', ''));
        $originVillageCode = preg_replace('/\D+/', '', (string) $request->input('origin_village_code', ''));
        $destinationVillageCode = preg_replace('/\D+/', '', (string) $request->input('destination_village_code', ''));
        $weightGr = (int) ($request->input('weight', 1000));   // gram
        $weightKg = $weightGr / 1000;

        $methods = [];
        $warnings = [];
        $apiCoIdError = null;

        // ── Tentukan apakah dalam kota ──────────────────────────────────────
        $isLocalCity = $this->isLocalCity($cityName);

        if ($isLocalCity) {
            // RULE 2 — Kurir SADITA
            $isFreeShipping = $weightKg >= self::FREE_SHIPPING_KG;
            $saditaCost     = $isFreeShipping ? 0 : 50000; // Rp 50.000 flat rate

            $methods[] = [
                'type'           => 'kurir_sadita',
                'name'           => 'Kurir SADITA',
                'service_code'   => 'kurir_sadita',
                'service_name'   => $isFreeShipping
                    ? 'Gratis ongkir pembelian ≥ ' . self::FREE_SHIPPING_KG . ' kg'
                    : 'Pengiriman dalam kota Makassar & Maros',
                'cost'           => $saditaCost,
                'estimated_days' => '1 – 2 hari kerja',
                'is_free'        => $isFreeShipping,
            ];
        } else {
            if ($destinationVillageCode !== '') {
                $apiCoIdMethods = $this->apiCoId->getRates(
                    $destinationVillageCode,
                    $weightGr,
                    $originVillageCode ?: null
                );
                $apiCoIdError = $this->apiCoId->getLastError();
                $methods = array_merge($methods, $apiCoIdMethods);
            } else {
                $apiCoIdError = 'Pilih kelurahan/desa tujuan terlebih dahulu.';
            }

            // Ekspedisi nasional wajib berasal dari API.co.id agar preview checkout
            // dan validasi submit memakai sumber tarif yang sama.
        }

        if ($isLocalCity) {
            $methods[] = [
                'type'           => 'pickup',
                'name'           => 'Ambil Langsung',
                'service_code'   => 'pickup',
                'service_name'   => 'Ambil sendiri di kantor SADITA',
                'cost'           => 0,
                'estimated_days' => 'Sesuai jam kantor',
                'is_free'        => true,
            ];
        }

        if (! $isLocalCity && empty($methods)) {
            return response()->json([
                'success' => false,
                'is_local' => false,
                'city_name' => $cityName,
                'methods' => [],
                'message' => $apiCoIdError
                    ?: (config('api_co_id.api_key')
                        ? 'Tarif ekspedisi belum tersedia untuk alamat ini. Periksa kode pos atau coba kurir lain.'
                        : 'Tarif ekspedisi belum aktif karena API key ongkir belum dikonfigurasi.'),
            ]);
        }

        // Sort by cheapest, then fastest (keeping pickup at the very end)
        usort($methods, function ($a, $b) {
            $isPickupA = ($a['type'] ?? '') === 'pickup';
            $isPickupB = ($b['type'] ?? '') === 'pickup';
            if ($isPickupA !== $isPickupB) {
                return $isPickupA ? 1 : -1;
            }

            if ($a['cost'] !== $b['cost']) {
                return $a['cost'] <=> $b['cost'];
            }

            $daysA = $this->getMinEstimatedDays($a['estimated_days'] ?? '');
            $daysB = $this->getMinEstimatedDays($b['estimated_days'] ?? '');
            return $daysA <=> $daysB;
        });

        return response()->json([
            'success'    => true,
            'is_local'   => $isLocalCity,
            'city_name'  => $cityName,
            'methods'    => $methods,
            'warnings'   => $warnings,
        ]);
    }

    // ─── GET /api/shipping/subdistricts ─────────────────────────────────────

    public function getSubdistricts()
    {
        return response()->json([
            'success' => false,
            'message' => 'Gunakan endpoint /api/shipping/districts dan /api/shipping/villages.',
        ], 410);
    }

    // ─── GET /api/shipping/districts?regency_id=xx ─────────────────────────

    public function getDistricts(Request $request)
    {
        $regencyId = trim((string) $request->query('regency_id'));

        if (! $regencyId) {
            return response()->json(['success' => false, 'message' => 'regency_id wajib diisi'], 422);
        }

        try {
            $districts = District::query()
                ->where('regency_id', $regencyId)
                ->orderBy('name')
                ->get(['id', 'regency_id', 'name']);

            if ($districts->isEmpty()) {
                $remoteDistricts = collect($this->apiCoId->getDistricts($regencyId));

                if ($remoteDistricts->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => $this->apiCoId->getLastError() ?: 'Kecamatan belum tersedia.',
                    ]);
                }

                $districtRows = $remoteDistricts
                    ->map(fn (array $item) => [
                        'id' => (string) ($item['code'] ?? ''),
                        'regency_id' => (string) ($item['regency_code'] ?? $regencyId),
                        'name' => (string) ($item['name'] ?? ''),
                    ])
                    ->filter(fn (array $item) => $item['id'] !== '' && $item['name'] !== '')
                    ->values()
                    ->all();

                if ($districtRows === []) {
                    return response()->json(['success' => false, 'message' => 'Kecamatan belum tersedia.']);
                }

                District::upsert($districtRows, ['id'], ['regency_id', 'name']);

                $districts = District::query()
                    ->where('regency_id', $regencyId)
                    ->orderBy('name')
                    ->get(['id', 'regency_id', 'name']);
            }

            return response()->json([
                'success' => true,
                'data' => $districts
                    ->map(fn (District $district) => [
                        'district_id' => $district->id,
                        'regency_id' => $district->regency_id,
                        'district_name' => $district->name,
                    ])
                    ->values(),
            ]);
        } catch (\Exception $e) {
            Log::error('ShippingController::getDistricts - ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memuat kecamatan'], 500);
        }
    }

    // ─── GET /api/shipping/villages?district_id=xx ─────────────────────────

    public function getVillages(Request $request)
    {
        $districtId = trim((string) $request->query('district_id'));

        if (! $districtId) {
            return response()->json(['success' => false, 'message' => 'district_id wajib diisi'], 422);
        }

        try {
            $villages = Village::query()
                ->where('district_id', $districtId)
                ->orderBy('name')
                ->get(['id', 'district_id', 'name']);

            if ($villages->isEmpty()) {
                $remoteVillages = collect($this->apiCoId->getVillages($districtId));

                if ($remoteVillages->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => $this->apiCoId->getLastError() ?: 'Kelurahan/desa belum tersedia.',
                    ]);
                }

                $villageRows = $remoteVillages
                    ->map(fn (array $item) => [
                        'id' => (string) ($item['code'] ?? ''),
                        'district_id' => (string) ($item['district_code'] ?? $districtId),
                        'name' => (string) ($item['name'] ?? ''),
                    ])
                    ->filter(fn (array $item) => $item['id'] !== '' && $item['name'] !== '')
                    ->values()
                    ->all();

                if ($villageRows === []) {
                    return response()->json(['success' => false, 'message' => 'Kelurahan/desa belum tersedia.']);
                }

                Village::upsert($villageRows, ['id'], ['district_id', 'name']);

                $villages = Village::query()
                    ->where('district_id', $districtId)
                    ->orderBy('name')
                    ->get(['id', 'district_id', 'name']);
            }

            return response()->json([
                'success' => true,
                'data' => $villages
                    ->map(fn (Village $village) => [
                        'village_id' => $village->id,
                        'district_id' => $village->district_id,
                        'village_name' => $village->name,
                    ])
                    ->values(),
            ]);
        } catch (\Exception $e) {
            Log::error('ShippingController::getVillages - ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memuat kelurahan/desa'], 500);
        }
    }

    // ─── Helpers ────────────────────────────────────────────────────────────

    /**
     * Cek apakah nama kota termasuk kota SADITA (Makassar / Maros)
     */
    private function isLocalCity(string $cityName): bool
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
     * Ambil metode pengiriman dari RajaOngkir (RULE 4)
     */
    private function getRajaOngkirMethods(int $destinationCityId, int $weightGr): array
    {
        $originCityId = (int) config('rajaongkir.origin_city_id', 300);
        $couriers = config('rajaongkir.couriers', ['jne', 'tiki', 'pos']);
        $cacheKey = "shipping_methods_{$originCityId}_{$destinationCityId}_{$weightGr}";

        return Cache::remember($cacheKey, 3600, function () use ($originCityId, $destinationCityId, $weightGr, $couriers) {
            $methods = [];

            foreach ($couriers as $courier) {
                try {
                    $results = $this->rajaOngkir->calculateCost(
                        $originCityId,
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
                    Log::warning("RajaOngkir [{$courier}] error: " . $e->getMessage());
                }
            }

            usort($methods, fn($a, $b) => $a['cost'] <=> $b['cost']);

            return $methods;
        });
    }

    private function cartItemsFromRequest(Request $request, int $weightGr): array
    {
        $items = $request->input('items', []);

        if (is_array($items) && ! empty($items)) {
            return $items;
        }

        return [[
            'name' => 'Produk SADITA',
            'price' => max(1000, (int) $request->input('total', 1000)),
            'quantity' => max(1, (int) ceil($weightGr / max(1, (int) config('api_co_id.item_weight_grams', 500)))),
        ]];
    }

    private function getMinEstimatedDays(string $etd): int
    {
        if (preg_match('/(\d+)/', $etd, $matches)) {
            return (int) $matches[1];
        }
        return 999;
    }
}
