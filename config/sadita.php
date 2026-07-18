<?php

return [
    'whatsapp_number' => env('SADITA_WHATSAPP_NUMBER', '6281234567890'),

    // Manual payment info (Step 1 - without payment gateway)
    'bank_name' => env('SADITA_BANK_NAME'),
    'bank_account' => env('SADITA_BANK_ACCOUNT'),
    'bank_holder' => env('SADITA_BANK_HOLDER'),
    'qris_image_url' => env('SADITA_QRIS_IMAGE_URL'),

    // ===== Checkout V3 Configuration =====

    /**
     * Payment methods available in Checkout V3
     * Each method has: key (unique identifier), icon (Material Symbols), label, sublabel
     * Rendered as cards in the payment method selection section
     * 
     * Requirements: 6.2, 6.4
     */
    'payment_methods' => [
        [
            'key' => 'transfer',
            'icon' => 'account_balance',
            'label' => 'Transfer Bank',
            'sublabel' => 'Transfer Manual',
        ],
        [
            'key' => 'qris',
            'icon' => 'qr_code_2',
            'label' => 'QRIS',
            'sublabel' => 'Semua E-Wallet',
        ],
        [
            'key' => 'cod',
            'icon' => 'payments',
            'label' => 'COD',
            'sublabel' => 'Bayar di Tempat',
        ],
    ],

    /**
     * Voucher feature flag
     * Set to false for V3.0, will be enabled in V3.1
     * When false, voucher section is displayed but disabled with "Segera Hadir" badge
     * 
     * Requirement: 5.2
     */
    'voucher_enabled' => env('SADITA_VOUCHER_ENABLED', false),

    /**
     * SADITA Courier Configuration
     * Internal courier available for specific cities (Makassar and Maros)
     * 
     * Requirements: 3.5, 3.6, 5.2
     */
    'sadita_courier' => [
        /**
         * Cities where SADITA courier is available
         * City name matching is case-insensitive and uses substring matching
         */
        'cities' => ['makassar', 'maros'],

        /**
         * Free shipping weight threshold in grams
         * Orders with total weight >= this value get free SADITA courier shipping
         * Default: 10,000 grams (10 kg)
         * 
         * Requirement: 3.6
         */
        'free_weight_threshold' => env('SADITA_COURIER_FREE_WEIGHT_THRESHOLD', 10000),

        /**
         * Base shipping cost for SADITA courier in Rupiah
         * Applied when total weight < free_weight_threshold
         * Default: Rp 50,000
         */
        'base_cost' => env('SADITA_COURIER_BASE_COST', 50000),
    ],
];

