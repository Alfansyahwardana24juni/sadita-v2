<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisplaySetting extends Model
{
    protected $fillable = [
        'name',
        'home_hero_badge',
        'home_hero_title',
        'home_hero_description',
        'home_primary_cta_label',
        'home_primary_cta_url',
        'home_secondary_cta_label',
        'home_secondary_cta_url',
        'home_warehouse_cta_label',
        'home_warehouse_cta_url',
        'home_about_title',
        'home_about_description',
        'home_stat_experience_value',
        'home_stat_experience_label',
        'home_stat_product_value',
        'home_stat_product_label',
        'home_stat_partner_value',
        'home_stat_partner_label',
        'home_stat_volume_value',
        'home_stat_volume_label',
        'store_home_title',
        'store_home_description',
        'store_home_info_title',
        'store_home_info_description',
        'store_katalog_title',
        'store_katalog_subtitle',
        'about_company_title',
        'about_company_description',
        'about_vision',
        'about_mission_points',
        'about_certification_points',
    ];

    public static function active(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            ['name' => 'Default']
        );
    }
}

