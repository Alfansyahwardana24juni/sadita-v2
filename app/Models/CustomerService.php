<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerService extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_service_category_id',
        'name',
        'photo',
        'title',
        'experience',
        'city',
        'whatsapp_number',
        'working_hours',
        'status',
        'sort_order',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(CustomerServiceCategory::class, 'customer_service_category_id');
    }
}
