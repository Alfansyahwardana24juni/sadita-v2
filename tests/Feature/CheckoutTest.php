<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_checkout_successfully()
    {
        // 1. Setup Data
        $warehouse = Warehouse::factory()->create([
            'name' => 'Gudang Pusat Makassar',
            'city' => 'Makassar',
            'is_active' => true,
        ]);

        $product = Product::factory()->create([
            'name' => 'Produk A',
            'price' => 100000,
            'status' => 'active',
            'weight' => 500,
        ]);

        ProductStock::factory()->create([
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'stock' => 100,
            'reserved_stock' => 0,
        ]);

        $province = Province::create(['id' => '73', 'name' => 'SULAWESI SELATAN']);
        $regency = Regency::create(['id' => '7371', 'province_id' => '73', 'name' => 'KOTA MAKASSAR', 'rajaongkir_city_id' => 278]);
        $district = District::create(['id' => '737101', 'regency_id' => '7371', 'name' => 'MARISO']);
        $village = Village::create(['id' => '7371011001', 'district_id' => '737101', 'name' => 'MARISO']);

        // 2. Add to Cart (using CartService implicitly via endpoint if possible, but let's just use session)
        $this->withSession([
            'cart.items' => [
                $product->id => [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => 2,
                    'subtotal' => 200000,
                ]
            ],
            'warehouse_id' => $warehouse->id,
        ]);

        // 3. Hit Checkout Store
        $payload = [
            'customer_name' => 'John Doe',
            'customer_phone' => '081234567890',
            'customer_province' => $province->id,
            'customer_regency_id' => $regency->id,
            'district_id' => $district->id,
            'village_id' => $village->id,
            'customer_city' => $regency->name,
            'postal_code' => '90111',
            'customer_address' => 'Jl. Merdeka No. 123',
            'shipping_method' => 'Kurir SADITA',
            'shipping_service' => 'kurir_sadita',
            'shipping_cost' => 50000,
            'payment_method' => 'transfer',
            'notes' => 'Tolong dibungkus rapi',
        ];

        $response = $this->post(route('checkout.store'), $payload);

        // 4. Assertions
        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'John Doe',
            'total' => 250000, // 200000 (subtotal) + 50000 (shipping)
            'status' => 'pending',
            'payment_method' => 'transfer',
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 100000,
            'subtotal' => 200000,
        ]);

        $this->assertDatabaseHas('product_stocks', [
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'reserved_stock' => 2, // Check if stock is reserved
        ]);
    }
}
