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
        'store_whatsapp_number',
        'store_whatsapp_message',
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
            [
                'name' => 'Default',
                'store_whatsapp_number' => '6285161018507',
                'store_whatsapp_message' => 'Halo admin, saya ingin konsultasi',
            ]
        );
    }

    public function getWhatsappNumberAttribute(): string
    {
        $num = preg_replace('/\D/', '', $this->store_whatsapp_number ?? '');

        if (empty($num)) {
            return '6285161018507';
        }

        if (str_starts_with($num, '0')) {
            $num = '62' . substr($num, 1);
        }

        return $num;
    }

    public function getWhatsappMessageAttribute(): string
    {
        return $this->store_whatsapp_message ?: 'Halo admin, saya ingin konsultasi';
    }

    public function getWhatsappChatUrlAttribute(): string
    {
        return 'https://wa.me/' . $this->whatsapp_number . '?text=' . rawurlencode($this->whatsapp_message);
    }

    /**
     * Generate a WhatsApp URL with pre-filled message.
     *
     * @param  string|null  $productName  Product name to include in the message.
     */
    public function whatsappUrl(?string $productName = null): string
    {
        $number = $this->whatsapp_number;
        $baseMessage = $this->whatsapp_message;

        if ($productName) {
            if (str_contains($baseMessage, '{product}')) {
                $message = str_replace('{product}', $productName, $baseMessage);
            } else {
                $message = $baseMessage . ' mengenai produk ' . $productName;
            }
        } else {
            $message = $baseMessage;
        }

        return 'https://wa.me/' . $number . '?text=' . rawurlencode($message);
    }
}

