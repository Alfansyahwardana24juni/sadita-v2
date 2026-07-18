<?php

namespace Tests\Unit\Models;

use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Tests\TestCase;

class RegionModelIntegrationTest extends TestCase
{
    /**
     * Test that all region models can be created without errors
     */
    public function test_region_models_can_be_created()
    {
        $province = new Province();
        $regency = new Regency();
        $district = new District();
        $village = new Village();

        $this->assertInstanceOf(Province::class, $province);
        $this->assertInstanceOf(Regency::class, $regency);
        $this->assertInstanceOf(District::class, $district);
        $this->assertInstanceOf(Village::class, $village);
    }

    /**
     * Test that all models use string keys correctly
     */
    public function test_all_models_use_string_keys()
    {
        $models = [
            new Province(),
            new Regency(),
            new District(),
            new Village()
        ];

        foreach ($models as $model) {
            $this->assertFalse($model->getIncrementing(), 'Model should not auto-increment');
            $this->assertEquals('string', $model->getKeyType(), 'Key type should be string');
            $this->assertEquals('id', $model->getKeyName(), 'Primary key should be id');
        }
    }

    /**
     * Test that all models have timestamps disabled (region reference data)
     */
    public function test_all_models_support_timestamps()
    {
        $models = [
            new Province(),
            new Regency(),
            new District(),
            new Village()
        ];

        foreach ($models as $model) {
            $this->assertFalse($model->usesTimestamps(), 'Region model should not use timestamps');
        }
    }

    /**
     * Test fillable attributes for Province
     */
    public function test_province_fillable_attributes()
    {
        $data = [
            'id' => '01',
            'name' => 'Jawa Barat'
        ];

        $province = Province::make($data);
        
        foreach ($data as $key => $value) {
            $this->assertEquals($value, $province->getAttribute($key));
        }
    }

    /**
     * Test fillable attributes for Regency
     */
    public function test_regency_fillable_attributes()
    {
        $data = [
            'id' => '3201',
            'province_id' => '32',
            'name' => 'Kabupaten Bandung',
            'rajaongkir_city_id' => 34
        ];

        $regency = Regency::make($data);
        
        foreach ($data as $key => $value) {
            $this->assertEquals($value, $regency->getAttribute($key));
        }
    }

    /**
     * Test fillable attributes for District
     */
    public function test_district_fillable_attributes()
    {
        $data = [
            'id' => '3201010',
            'regency_id' => '3201',
            'name' => 'Kecamatan Bandung'
        ];

        $district = District::make($data);
        
        foreach ($data as $key => $value) {
            $this->assertEquals($value, $district->getAttribute($key));
        }
    }

    /**
     * Test fillable attributes for Village
     */
    public function test_village_fillable_attributes()
    {
        $data = [
            'id' => '3201010001',
            'district_id' => '3201010',
            'name' => 'Kelurahan Bandung'
        ];

        $village = Village::make($data);
        
        foreach ($data as $key => $value) {
            $this->assertEquals($value, $village->getAttribute($key));
        }
    }

    /**
     * Test that models can be instantiated with array and accessed as objects
     */
    public function test_model_property_access()
    {
        $province = new Province(['id' => '01', 'name' => 'Jawa Barat']);
        $this->assertEquals('01', $province->id);
        $this->assertEquals('Jawa Barat', $province->name);

        $regency = new Regency([
            'id' => '3201',
            'province_id' => '32',
            'name' => 'Kabupaten Bandung',
            'rajaongkir_city_id' => 34
        ]);
        $this->assertEquals('3201', $regency->id);
        $this->assertEquals('32', $regency->province_id);
        $this->assertEquals('Kabupaten Bandung', $regency->name);
        $this->assertEquals(34, $regency->rajaongkir_city_id);

        $district = new District([
            'id' => '3201010',
            'regency_id' => '3201',
            'name' => 'Kecamatan Bandung'
        ]);
        $this->assertEquals('3201010', $district->id);
        $this->assertEquals('3201', $district->regency_id);
        $this->assertEquals('Kecamatan Bandung', $district->name);

        $village = new Village([
            'id' => '3201010001',
            'district_id' => '3201010',
            'name' => 'Kelurahan Bandung'
        ]);
        $this->assertEquals('3201010001', $village->id);
        $this->assertEquals('3201010', $village->district_id);
        $this->assertEquals('Kelurahan Bandung', $village->name);
    }
}
