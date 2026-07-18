<?php

use App\Http\Controllers\AiController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

// Halaman Informational
Route::get('/tentang', [PageController::class, 'tentang'])->name('tentang');
Route::get('/produk', [PageController::class, 'produk'])->name('produk');
Route::get('/produk/{category:slug}', [PageController::class, 'produkKategori'])->name('produk.category');
Route::get('/produk/{category:slug}/{product:slug}', [PageController::class, 'produkDetail'])->name('produk.detail');
Route::get('/artikel', [PageController::class, 'artikel'])->name('artikel');
Route::get('/artikel/{article:slug}', [PageController::class, 'artikelShow'])->name('artikel.show');
Route::get('/chat', [PageController::class, 'chat'])->name('chat');
Route::get('/saditacare', [PageController::class, 'saditacare'])->name('saditacare');

// Toko Routes
Route::get('/toko', [StoreController::class, 'index'])->name('toko.home');
Route::post('/toko/select-warehouse/{warehouse:slug}', [StoreController::class, 'select'])->name('toko.select-warehouse');
Route::get('/toko/katalog', [ProductController::class, 'index'])->name('toko.katalog');
Route::get('/toko/produk/{product:slug}', [ProductController::class, 'show'])->name('toko.produk.show');

// Cart Routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::get('/count', [CartController::class, 'count'])->name('count');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
    Route::post('/add-by-slug/{product:slug}', [CartController::class, 'add'])->name('add.slug');
    Route::patch('/update/{productId}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{productId}', [CartController::class, 'remove'])->name('remove');
});

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{orderNumber}/{token}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/toko/order', [CheckoutController::class, 'orders'])->name('toko.orders');
Route::get('/toko/lacak-pesanan', [App\Http\Controllers\OrderTrackingController::class, 'index'])->name('toko.track-order');
Route::post('/toko/lacak-pesanan', [App\Http\Controllers\OrderTrackingController::class, 'index']);

// AI Routes
Route::post('/ai/chat', [AiController::class, 'chat'])->middleware('throttle:ai-chat')->name('ai.chat');

// Locale Route
Route::get('/lang/{locale}', function (string $locale) {
    \Illuminate\Support\Facades\Log::info('Locale switch hit', ['locale' => $locale]);
    if (in_array($locale, ['id', 'en'])) {
        Illuminate\Support\Facades\Session::put('locale', $locale);
        Illuminate\Support\Facades\Session::save();
        \Illuminate\Support\Facades\Log::info('Locale saved in session', ['locale' => $locale]);
    }
    return back();
})->name('locale.switch');

