<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds fields required for Checkout V3:
     * - Detailed shipping information (courier, service, ETD)
     * - Discount and voucher support
     * - Customer postal code
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Customer postal code (after customer_address)
            $table->string('customer_postal_code', 10)->nullable()->after('customer_address');
            
            // Detailed shipping information (after shipping_method)
            $table->string('shipping_courier')->nullable()->after('shipping_method')
                ->comment('Nama kurir: JNE, TIKI, SADITA, dll');
            $table->string('shipping_service')->nullable()->after('shipping_courier')
                ->comment('Nama layanan: REG, OKE, YES, dll');
            $table->string('shipping_etd')->nullable()->after('shipping_service')
                ->comment('Estimasi pengiriman: "2-3 hari"');
            
            // Discount and voucher fields (after shipping_cost, before total)
            $table->integer('discount_amount')->default(0)->after('shipping_cost')
                ->comment('Jumlah diskon dari voucher atau promo');
            $table->string('voucher_code')->nullable()->after('discount_amount')
                ->comment('Kode voucher yang digunakan');
            $table->integer('cashback_amount')->default(0)->after('voucher_code')
                ->comment('Jumlah cashback untuk customer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'customer_postal_code',
                'shipping_courier',
                'shipping_service',
                'shipping_etd',
                'discount_amount',
                'voucher_code',
                'cashback_amount',
            ]);
        });
    }
};
