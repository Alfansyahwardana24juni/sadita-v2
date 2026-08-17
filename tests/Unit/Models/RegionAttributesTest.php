<?php

namespace Tests\Unit\Models;

use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Tests\TestCase;

class RegionAttributesTest extends TestCase
{
    /**
     * Test Province model attributes and configuration
     */
    public function test_province_attributes_and_configuration()
    {
        // Test that model has correct configuration
        $province = new Province();
        
        // Test non-incrementing string key
        $this->assertFalse($province->getIncrementing());
        $this->assertEquals('string', $province->getKeyType());
        
        // Test that timestamps are disabled (region reference data)
        $this->assertFalse($province->usesTimestamps());
        
        // Test fillable attributes
        $fillable = $province->getFillable();
        $this->assertContains('id', $fillable);
        $this->assertContains('name', $fillable);
    }

    /**
     * Test Regency model attributes and configuration
     */
    public function test_regency_attributes_and_configuration()
    {
        $regency = new Regency();
        
        // Test non-incrementing string key
        $this->assertFalse($regency->getIncrementing());
        $this->assertEquals('string', $regency->getKeyType());
        
        // Test that timestamps are disabled
        $this->assertFalse($regency->usesTimestamps());
        
        // Test fillable attributes
        $fillable = $regency->getFillable();
        $this->assertContains('id', $fillable);
        $this->assertContains('province_id', $fillable);
        $this->assertContains('name', $fillable);
        $this->assertContains('rajaongkir_city_id', $fillable);
    }

    /**
     * Test District model attributes and configuration
     */
    public function test_district_attributes_and_configuration()
    {
        $district = new District();
        
        // Test non-incrementing string key
        $this->assertFalse($district->getIncrementing());
        $this->assertEquals('string', $district->getKeyType());
        
        // Test that timestamps are disabled
        $this->assertFalse($district->usesTimestamps());
        
        // Test fillable attributes
        $fillable = $district->getFillable();
        $this->assertContains('id', $fillable);
        $this->assertContains('regency_id', $fillable);
        $this->assertContains('name', $fillable);
    }

    /**
     * Test Village model attributes and configuration
     */
    public function test_village_attributes_and_configuration()
    {
        $village = new Village();
        
        // Test non-incrementing string key
        $this->assertFalse($village->getIncrementing());
        $this->assertEquals('string', $village->getKeyType());
        
        // Test that timestamps are disabled
        $this->assertFalse($village->usesTimestamps());
        
        // Test fillable attributes
        $fillable = $village->getFillable();
        $this->assertContains('id', $fillable);
        $this->assertContains('district_id', $fillable);
        $this->assertContains('name', $fillable);
    }

    /**
     * Test that no mass assignment warnings occur
     */
    public function test_mass_assignment_without_warnings()
    {
        // Create Province
        $province = Province::make([
            'id' => '01',
            'name' => 'Jawa Barat'
        ]);
        $this->assertEquals('01', $province->id);
        $this->assertEquals('Jawa Barat', $province->name);

        // Create Regency
        $regency = Regency::make([
            'id' => '3201',
            'province_id' => '32',
            'name' => 'Kabupaten Bandung',
            'rajaongkir_city_id' => 34
        ]);
        $this->assertEquals('3201', $regency->id);
        $this->assertEquals('32', $regency->province_id);
        $this->assertEquals('Kabupaten Bandung', $regency->name);
        $this->assertEquals(34, $regency->rajaongkir_city_id);

        // Create District
        $district = District::make([
            'id' => '3201010',
            'regency_id' => '3201',
            'name' => 'Kecamatan Bandung'
        ]);
        $this->assertEquals('3201010', $district->id);
        $this->assertEquals('3201', $district->regency_id);
        $this->assertEquals('Kecamatan Bandung', $district->name);

        // Create Village
        $village = Village::make([
            'id' => '3201010001',
            'district_id' => '3201010',
            'name' => 'Kelurahan Bandung'
        ]);
        $this->assertEquals('3201010001', $village->id);
        $this->assertEquals('3201010', $village->district_id);
        $this->assertEquals('Kelurahan Bandung', $village->name);
    }
}
