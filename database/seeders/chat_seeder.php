<?php
use App\Models\CustomerServiceCategory;
use App\Models\CustomerService;

CustomerService::query()->delete();
CustomerServiceCategory::query()->delete();

$cat1 = CustomerServiceCategory::create(['name' => '🩺 Dokter Hewan', 'sort_order' => 1]);
$cat2 = CustomerServiceCategory::create(['name' => '🏢 Admin Kantor', 'sort_order' => 2]);

CustomerService::create([
    'customer_service_category_id' => $cat1->id, 
    'name' => 'drh. Ilsan Arvan Nurgas', 
    'title' => 'Dokter Hewan', 
    'experience' => '15 Tahun Pengalaman', 
    'city' => 'Makassar', 
    'whatsapp_number' => '081234567890', 
    'working_hours' => '08.00 - 17.00 WITA', 
    'status' => 'online', 
    'sort_order' => 1
]);

CustomerService::create([
    'customer_service_category_id' => $cat2->id, 
    'name' => 'Ibu Eka', 
    'title' => 'Admin Customer Service', 
    'experience' => '5 Tahun Pengalaman', 
    'city' => 'Sidrap', 
    'whatsapp_number' => '081234567891', 
    'working_hours' => '08.00 - 17.00 WITA', 
    'status' => 'online', 
    'sort_order' => 1
]);

echo "Seeding completed!\n";
