<?php

use App\Http\Controllers\Api\ShippingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Shipping API
Route::prefix('shipping')->group(function () {
    Route::post('/methods', [ShippingController::class, 'getShippingMethods']);
    Route::get('/provinces', [ShippingController::class, 'getProvinces']);
    Route::get('/cities', [ShippingController::class, 'getCities']);
    Route::get('/districts', [ShippingController::class, 'getDistricts']);
    Route::get('/villages', [ShippingController::class, 'getVillages']);
    Route::get('/subdistricts', [ShippingController::class, 'getSubdistricts']);
    
    // Test endpoint
    Route::get('/test', function () {
        $apiKey = config('api_co_id.api_key');
        return response()->json([
            'api_key_set' => !empty($apiKey),
            'api_key_length' => strlen($apiKey ?? ''),
            'base_url' => config('api_co_id.base_url'),
            'origin_village_code_set' => ! empty(config('api_co_id.origin_village_code')),
        ]);
    });
});

Route::prefix('vouchers')->group(function () {
    Route::post('/check', [\App\Http\Controllers\Api\VoucherController::class, 'check']);
});
