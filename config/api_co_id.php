<?php

return [
    'api_key' => env('API_CO_ID_API_KEY', ''),
    'base_url' => env('API_CO_ID_BASE_URL', 'https://use.api.co.id'),
    'origin_village_code' => env('API_CO_ID_ORIGIN_VILLAGE_CODE', ''),
    'origin_village_codes' => [
        'sadita-bogor-timur' => env('API_CO_ID_ORIGIN_VILLAGE_CODE_BOGOR_TIMUR', ''),
        'sadita-makassar' => env('API_CO_ID_ORIGIN_VILLAGE_CODE_MAKASSAR', ''),
    ],
    'item_weight_grams' => (int) env('API_CO_ID_ITEM_WEIGHT_GRAMS', 500),
];
