<?php

namespace Tests\Unit\Models;

use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Tests\TestCase;

class RegionMassAssignmentTest extends TestCase
{
    /**
     * Test Province $fillable array contains all required attributes
     */
    public function test_province_fillable_array_complete()
    {
        $expected = ['id', 'name'];
        $province = new Province();
        $this->assertEquals($expected, $province->getFillable());
    }

    /**
     * Test Regency $fillable array contains all required attributes
     */
    public function test_regency_fillable_array_complete()
    {
        $expected = ['id', 'province_id', 'name', 'rajaongkir_city_id'];
        $regency = new Regency();
        $this->assertEquals($expected, $regency->getFillable());
    }

    /**
     * Test District $fillable array contains all required attributes
     */
    public function test_district_fillable_array_complete()
    {
        $expected = ['id', 'regency_id', 'name'];
        $district = new District();
        $this->assertEquals($expected, $district->getFillable());
    }

    /**
     * Test Village $fillable array contains all required attributes
     */
    public function test_village_fillable_array_complete()
    {
        $expected = ['id', 'district_id', 'name'];
        $village = new Village();
        $this->assertEquals($expected, $village->getFillable());
    }

    /**
     * Test Province string primary keys configuration
     */
    public function test_province_string_primary_key_config()
    {
        $province = new Province();
        $this->assertFalse($province->incrementing);
        $this->assertEquals('id', $province->getKeyName());
    }

    /**
     * Test Regency string primary keys configuration
     */
    public function test_regency_string_primary_key_config()
    {
        $regency = new Regency();
        $this->assertFalse($regency->incrementing);
        $this->assertEquals('id', $regency->getKeyName());
    }

    /**
     * Test District string primary keys configuration
     */
    public function test_district_string_primary_key_config()
    {
        $district = new District();
        $this->assertFalse($district->incrementing);
        $this->assertEquals('id', $district->getKeyName());
    }

    /**
     * Test Village string primary keys configuration
     */
    public function test_village_string_primary_key_config()
    {
        $village = new Village();
        $this->assertFalse($village->incrementing);
        $this->assertEquals('id', $village->getKeyName());
    }

    /**
     * Test Province timestamps disabled
     */
    public function test_province_timestamps_disabled()
    {
        $province = new Province();
        $this->assertFalse($province->timestamps);
    }

    /**
     * Test Regency timestamps disabled
     */
    public function test_regency_timestamps_disabled()
    {
        $regency = new Regency();
        $this->assertFalse($regency->timestamps);
    }

    /**
     * Test District timestamps disabled
     */
    public function test_district_timestamps_disabled()
    {
        $district = new District();
        $this->assertFalse($district->timestamps);
    }

    /**
     * Test Village timestamps disabled
     */
    public function test_village_timestamps_disabled()
    {
        $village = new Village();
        $this->assertFalse($village->timestamps);
    }

    /**
     * Test Province mass assignment without errors
     */
    public function test_province_mass_assignment_without_errors()
    {
        $data = ['id' => '01', 'name' => 'Jawa Barat'];
        $province = new Province($data);
        
        $this->assertEquals('01', $province->id);
        $this->assertEquals('Jawa Barat', $province->name);
        $this->assertTrue($province->wasRecentlyCreated === false);
    }

    /**
     * Test Regency mass assignment without errors
     */
    public function test_regency_mass_assignment_without_errors()
    {
        $data = [
            'id' => '3201',
            'province_id' => '32',
            'name' => 'Kabupaten Bandung',
            'rajaongkir_city_id' => 34
        ];
        $regency = new Regency($data);
        
        $this->assertEquals('3201', $regency->id);
        $this->assertEquals('32', $regency->province_id);
        $this->assertEquals('Kabupaten Bandung', $regency->name);
        $this->assertEquals(34, $regency->rajaongkir_city_id);
    }

    /**
     * Test District mass assignment without errors
     */
    public function test_district_mass_assignment_without_errors()
    {
        $data = [
            'id' => '3201010',
            'regency_id' => '3201',
            'name' => 'Kecamatan Bandung'
        ];
        $district = new District($data);
        
        $this->assertEquals('3201010', $district->id);
        $this->assertEquals('3201', $district->regency_id);
        $this->assertEquals('Kecamatan Bandung', $district->name);
    }

    /**
     * Test Village mass assignment without errors
     */
    public function test_village_mass_assignment_without_errors()
    {
        $data = [
            'id' => '3201010001',
            'district_id' => '3201010',
            'name' => 'Kelurahan Bandung'
        ];
        $village = new Village($data);
        
        $this->assertEquals('3201010001', $village->id);
        $this->assertEquals('3201010', $village->district_id);
        $this->assertEquals('Kelurahan Bandung', $village->name);
    }

    /**
     * Test Province bulk insert preparation - multiple instances
     */
    public function test_province_bulk_insert_preparation()
    {
        $provinces = [
            new Province(['id' => '01', 'name' => 'Jawa Barat']),
            new Province(['id' => '32', 'name' => 'Jawa Timur']),
            new Province(['id' => '31', 'name' => 'DKI Jakarta']),
        ];

        foreach ($provinces as $province) {
            $this->assertNotEmpty($province->id);
            $this->assertNotEmpty($province->name);
        }
    }

    /**
     * Test Regency bulk insert preparation - multiple instances
     */
    public function test_regency_bulk_insert_preparation()
    {
        $regencies = [
            new Regency(['id' => '3201', 'province_id' => '32', 'name' => 'Kabupaten Bandung', 'rajaongkir_city_id' => 34]),
            new Regency(['id' => '3202', 'province_id' => '32', 'name' => 'Kota Bandung', 'rajaongkir_city_id' => 35]),
        ];

        foreach ($regencies as $regency) {
            $this->assertNotEmpty($regency->id);
            $this->assertNotEmpty($regency->province_id);
            $this->assertNotEmpty($regency->name);
        }
    }

    /**
     * Test District bulk insert preparation - multiple instances
     */
    public function test_district_bulk_insert_preparation()
    {
        $districts = [
            new District(['id' => '3201010', 'regency_id' => '3201', 'name' => 'Kecamatan Bandung']),
            new District(['id' => '3201020', 'regency_id' => '3201', 'name' => 'Kecamatan Rancamulya']),
        ];

        foreach ($districts as $district) {
            $this->assertNotEmpty($district->id);
            $this->assertNotEmpty($district->regency_id);
            $this->assertNotEmpty($district->name);
        }
    }

    /**
     * Test Village bulk insert preparation - multiple instances
     */
    public function test_village_bulk_insert_preparation()
    {
        $villages = [
            new Village(['id' => '3201010001', 'district_id' => '3201010', 'name' => 'Kelurahan Bandung']),
            new Village(['id' => '3201010002', 'district_id' => '3201010', 'name' => 'Kelurahan Hegarmanah']),
        ];

        foreach ($villages as $village) {
            $this->assertNotEmpty($village->id);
            $this->assertNotEmpty($village->district_id);
            $this->assertNotEmpty($village->name);
        }
    }

    /**
     * Test models ready for bulk insert - all attributes accessible via array access
     */
    public function test_province_bulk_insert_array_access()
    {
        $province = new Province(['id' => '01', 'name' => 'Jawa Barat']);
        
        // Verify array-like access works
        $this->assertEquals('01', $province['id']);
        $this->assertEquals('Jawa Barat', $province['name']);
    }

    /**
     * Test Regency bulk insert array access with all fields
     */
    public function test_regency_bulk_insert_array_access()
    {
        $regency = new Regency([
            'id' => '3201',
            'province_id' => '32',
            'name' => 'Kabupaten Bandung',
            'rajaongkir_city_id' => 34
        ]);
        
        $this->assertEquals('3201', $regency['id']);
        $this->assertEquals('32', $regency['province_id']);
        $this->assertEquals('Kabupaten Bandung', $regency['name']);
        $this->assertEquals(34, $regency['rajaongkir_city_id']);
    }
}
