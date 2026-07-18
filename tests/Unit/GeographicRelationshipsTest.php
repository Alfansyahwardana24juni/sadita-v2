<?php

namespace Tests\Unit;

use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeographicRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Province hasMany Regencies relationship
     */
    public function test_province_has_many_regencies()
    {
        // Create a province
        $province = Province::create([
            'id' => 'prov-1',
            'name' => 'Jawa Barat'
        ]);

        // Create multiple regencies
        $regency1 = Regency::create([
            'id' => 'reg-1',
            'province_id' => 'prov-1',
            'name' => 'Bandung',
            'rajaongkir_city_id' => 101
        ]);

        $regency2 = Regency::create([
            'id' => 'reg-2',
            'province_id' => 'prov-1',
            'name' => 'Bogor',
            'rajaongkir_city_id' => 102
        ]);

        // Test that province has regencies
        $this->assertCount(2, $province->regencies);
        $this->assertTrue($province->regencies->contains($regency1));
        $this->assertTrue($province->regencies->contains($regency2));
    }

    /**
     * Test Regency belongsTo Province relationship
     */
    public function test_regency_belongs_to_province()
    {
        // Create province and regency
        $province = Province::create([
            'id' => 'prov-1',
            'name' => 'Jawa Barat'
        ]);

        $regency = Regency::create([
            'id' => 'reg-1',
            'province_id' => 'prov-1',
            'name' => 'Bandung',
            'rajaongkir_city_id' => 101
        ]);

        // Test belongsTo relationship
        $this->assertInstanceOf(Province::class, $regency->province);
        $this->assertEquals('prov-1', $regency->province->id);
        $this->assertEquals('Jawa Barat', $regency->province->name);
    }

    /**
     * Test Regency hasMany Districts relationship
     */
    public function test_regency_has_many_districts()
    {
        // Create province first (FK parent)
        Province::create(['id' => 'prov-1', 'name' => 'Jawa Barat']);

        // Create regency
        $regency = Regency::create([
            'id' => 'reg-1',
            'province_id' => 'prov-1',
            'name' => 'Bandung',
            'rajaongkir_city_id' => 101
        ]);

        // Create multiple districts
        $district1 = District::create([
            'id' => 'dist-1',
            'regency_id' => 'reg-1',
            'name' => 'Bandung Utara'
        ]);

        $district2 = District::create([
            'id' => 'dist-2',
            'regency_id' => 'reg-1',
            'name' => 'Bandung Selatan'
        ]);

        // Test that regency has districts
        $this->assertCount(2, $regency->districts);
        $this->assertTrue($regency->districts->contains($district1));
        $this->assertTrue($regency->districts->contains($district2));
    }

    /**
     * Test District belongsTo Regency relationship
     */
    public function test_district_belongs_to_regency()
    {
        // Create province first (FK parent)
        Province::create(['id' => 'prov-1', 'name' => 'Jawa Barat']);

        // Create regency and district
        $regency = Regency::create([
            'id' => 'reg-1',
            'province_id' => 'prov-1',
            'name' => 'Bandung',
            'rajaongkir_city_id' => 101
        ]);

        $district = District::create([
            'id' => 'dist-1',
            'regency_id' => 'reg-1',
            'name' => 'Bandung Utara'
        ]);

        // Test belongsTo relationship
        $this->assertInstanceOf(Regency::class, $district->regency);
        $this->assertEquals('reg-1', $district->regency->id);
        $this->assertEquals('Bandung', $district->regency->name);
    }

    /**
     * Test District hasMany Villages relationship
     */
    public function test_district_has_many_villages()
    {
        // Create parent records first (FK parents)
        Province::create(['id' => 'prov-1', 'name' => 'Jawa Barat']);
        Regency::create(['id' => 'reg-1', 'province_id' => 'prov-1', 'name' => 'Bandung', 'rajaongkir_city_id' => 101]);

        // Create district
        $district = District::create([
            'id' => 'dist-1',
            'regency_id' => 'reg-1',
            'name' => 'Bandung Utara'
        ]);

        // Create multiple villages
        $village1 = Village::create([
            'id' => 'vil-1',
            'district_id' => 'dist-1',
            'name' => 'Kelurahan A'
        ]);

        $village2 = Village::create([
            'id' => 'vil-2',
            'district_id' => 'dist-1',
            'name' => 'Kelurahan B'
        ]);

        // Test that district has villages
        $this->assertCount(2, $district->villages);
        $this->assertTrue($district->villages->contains($village1));
        $this->assertTrue($district->villages->contains($village2));
    }

    /**
     * Test Village belongsTo District relationship
     */
    public function test_village_belongs_to_district()
    {
        // Create parent records first (FK parents)
        Province::create(['id' => 'prov-1', 'name' => 'Jawa Barat']);
        Regency::create(['id' => 'reg-1', 'province_id' => 'prov-1', 'name' => 'Bandung', 'rajaongkir_city_id' => 101]);

        // Create district and village
        $district = District::create([
            'id' => 'dist-1',
            'regency_id' => 'reg-1',
            'name' => 'Bandung Utara'
        ]);

        $village = Village::create([
            'id' => 'vil-1',
            'district_id' => 'dist-1',
            'name' => 'Kelurahan A'
        ]);

        // Test belongsTo relationship
        $this->assertInstanceOf(District::class, $village->district);
        $this->assertEquals('dist-1', $village->district->id);
        $this->assertEquals('Bandung Utara', $village->district->name);
    }

    /**
     * Test Order belongsTo Province relationship
     */
    public function test_order_belongs_to_province()
    {
        // Create full hierarchy (FK parents)
        $province = Province::create(['id' => 'prov-1', 'name' => 'Jawa Barat']);
        Regency::create(['id' => 'reg-1', 'province_id' => 'prov-1', 'name' => 'Bandung', 'rajaongkir_city_id' => 101]);
        District::create(['id' => 'dist-1', 'regency_id' => 'reg-1', 'name' => 'Bandung Utara']);
        Village::create(['id' => 'vil-1', 'district_id' => 'dist-1', 'name' => 'Kelurahan A']);

        // Create order
        $order = Order::create([
            'order_number' => 'SDT240101001',
            'province_id' => 'prov-1',
            'regency_id' => 'reg-1',
            'district_id' => 'dist-1',
            'village_id' => 'vil-1',
            'customer_name' => 'John Doe',
            'customer_phone' => '08123456789',
            'customer_address' => 'Jl. Test No. 1',
            'subtotal' => 100000,
            'total' => 120000,
        ]);

        // Test belongsTo relationship
        $this->assertInstanceOf(Province::class, $order->province);
        $this->assertEquals('prov-1', $order->province->id);
        $this->assertEquals('Jawa Barat', $order->province->name);
    }

    /**
     * Test Order belongsTo Regency relationship
     */
    public function test_order_belongs_to_regency()
    {
        // Create full hierarchy (FK parents)
        Province::create(['id' => 'prov-1', 'name' => 'Jawa Barat']);
        $regency = Regency::create(['id' => 'reg-1', 'province_id' => 'prov-1', 'name' => 'Bandung', 'rajaongkir_city_id' => 101]);
        District::create(['id' => 'dist-1', 'regency_id' => 'reg-1', 'name' => 'Bandung Utara']);
        Village::create(['id' => 'vil-1', 'district_id' => 'dist-1', 'name' => 'Kelurahan A']);

        // Create order
        $order = Order::create([
            'order_number' => 'SDT240101001',
            'province_id' => 'prov-1',
            'regency_id' => 'reg-1',
            'district_id' => 'dist-1',
            'village_id' => 'vil-1',
            'customer_name' => 'John Doe',
            'customer_phone' => '08123456789',
            'customer_address' => 'Jl. Test No. 1',
            'subtotal' => 100000,
            'total' => 120000,
        ]);

        // Test belongsTo relationship
        $this->assertInstanceOf(Regency::class, $order->regency);
        $this->assertEquals('reg-1', $order->regency->id);
        $this->assertEquals('Bandung', $order->regency->name);
    }

    /**
     * Test Order belongsTo District relationship
     */
    public function test_order_belongs_to_district()
    {
        // Create full hierarchy (FK parents)
        Province::create(['id' => 'prov-1', 'name' => 'Jawa Barat']);
        Regency::create(['id' => 'reg-1', 'province_id' => 'prov-1', 'name' => 'Bandung', 'rajaongkir_city_id' => 101]);
        $district = District::create(['id' => 'dist-1', 'regency_id' => 'reg-1', 'name' => 'Bandung Utara']);
        Village::create(['id' => 'vil-1', 'district_id' => 'dist-1', 'name' => 'Kelurahan A']);

        // Create order
        $order = Order::create([
            'order_number' => 'SDT240101001',
            'province_id' => 'prov-1',
            'regency_id' => 'reg-1',
            'district_id' => 'dist-1',
            'village_id' => 'vil-1',
            'customer_name' => 'John Doe',
            'customer_phone' => '08123456789',
            'customer_address' => 'Jl. Test No. 1',
            'subtotal' => 100000,
            'total' => 120000,
        ]);

        // Test belongsTo relationship
        $this->assertInstanceOf(District::class, $order->district);
        $this->assertEquals('dist-1', $order->district->id);
        $this->assertEquals('Bandung Utara', $order->district->name);
    }

    /**
     * Test Order belongsTo Village relationship
     */
    public function test_order_belongs_to_village()
    {
        // Create full hierarchy (FK parents)
        Province::create(['id' => 'prov-1', 'name' => 'Jawa Barat']);
        Regency::create(['id' => 'reg-1', 'province_id' => 'prov-1', 'name' => 'Bandung', 'rajaongkir_city_id' => 101]);
        District::create(['id' => 'dist-1', 'regency_id' => 'reg-1', 'name' => 'Bandung Utara']);
        $village = Village::create(['id' => 'vil-1', 'district_id' => 'dist-1', 'name' => 'Kelurahan A']);

        // Create order
        $order = Order::create([
            'order_number' => 'SDT240101001',
            'province_id' => 'prov-1',
            'regency_id' => 'reg-1',
            'district_id' => 'dist-1',
            'village_id' => 'vil-1',
            'customer_name' => 'John Doe',
            'customer_phone' => '08123456789',
            'customer_address' => 'Jl. Test No. 1',
            'subtotal' => 100000,
            'total' => 120000,
        ]);

        // Test belongsTo relationship
        $this->assertInstanceOf(Village::class, $order->village);
        $this->assertEquals('vil-1', $order->village->id);
        $this->assertEquals('Kelurahan A', $order->village->name);
    }

    /**
     * Test complete hierarchy: Province -> Regency -> District -> Village
     */
    public function test_complete_geographic_hierarchy()
    {
        // Create complete hierarchy
        $province = Province::create([
            'id' => 'prov-1',
            'name' => 'Jawa Barat'
        ]);

        $regency = Regency::create([
            'id' => 'reg-1',
            'province_id' => 'prov-1',
            'name' => 'Bandung',
            'rajaongkir_city_id' => 101
        ]);

        $district = District::create([
            'id' => 'dist-1',
            'regency_id' => 'reg-1',
            'name' => 'Bandung Utara'
        ]);

        $village = Village::create([
            'id' => 'vil-1',
            'district_id' => 'dist-1',
            'name' => 'Kelurahan Braga'
        ]);

        // Verify complete chain
        $this->assertEquals('prov-1', $village->district->regency->province->id);
        $this->assertEquals('Jawa Barat', $village->district->regency->province->name);
        $this->assertEquals('reg-1', $village->district->regency->id);
        $this->assertEquals('dist-1', $village->district->id);
        $this->assertEquals('vil-1', $village->id);
    }

    /**
     * Test Order with complete geographic hierarchy
     */
    public function test_order_with_complete_geographic_hierarchy()
    {
        // Create complete hierarchy
        $province = Province::create([
            'id' => 'prov-1',
            'name' => 'Jawa Barat'
        ]);

        $regency = Regency::create([
            'id' => 'reg-1',
            'province_id' => 'prov-1',
            'name' => 'Bandung',
            'rajaongkir_city_id' => 101
        ]);

        $district = District::create([
            'id' => 'dist-1',
            'regency_id' => 'reg-1',
            'name' => 'Bandung Utara'
        ]);

        $village = Village::create([
            'id' => 'vil-1',
            'district_id' => 'dist-1',
            'name' => 'Kelurahan Braga'
        ]);

        // Create order with all relationships
        $order = Order::create([
            'order_number' => 'SDT240101001',
            'province_id' => 'prov-1',
            'regency_id' => 'reg-1',
            'district_id' => 'dist-1',
            'village_id' => 'vil-1',
            'customer_name' => 'John Doe',
            'customer_phone' => '08123456789',
            'customer_address' => 'Jl. Braga No. 1',
            'subtotal' => 100000,
            'total' => 120000,
        ]);

        // Verify all relationships are accessible
        $this->assertEquals('prov-1', $order->province->id);
        $this->assertEquals('reg-1', $order->regency->id);
        $this->assertEquals('dist-1', $order->district->id);
        $this->assertEquals('vil-1', $order->village->id);

        // Verify complete chain through relationships
        $this->assertEquals('Jawa Barat', $order->province->name);
        $this->assertEquals('Bandung', $order->regency->name);
        $this->assertEquals('Bandung Utara', $order->district->name);
        $this->assertEquals('Kelurahan Braga', $order->village->name);
    }
}
