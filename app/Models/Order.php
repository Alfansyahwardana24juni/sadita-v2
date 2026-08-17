<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'success_token',
        'warehouse_id',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'customer_postal_code',
        'customer_city',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'subtotal',
        'shipping_cost',
        'shipping_method',
        'shipping_courier',
        'shipping_service',
        'shipping_etd',
        'discount_amount',
        'voucher_code',
        'cashback_amount',
        'total',
        'status',
        'payment_method',
        'payment_status',
        'notes',
        'admin_notes',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'shipping_cost' => 'integer',
        'total' => 'integer',
    ];

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'transfer' => 'Transfer Bank',
            'qris' => 'QRIS',
            'cod' => 'COD',
            default => $this->payment_method ?: '-',
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'unpaid' => 'Belum Bayar',
            'paid_pending_verify' => 'Menunggu Verifikasi',
            'paid_confirmed' => 'Lunas',
            'expired' => 'Kadaluarsa',
            default => $this->payment_status ?: '-',
        };
    }

    public static function generateOrderNumber(): string
    {
        $prefix = 'SDT';
        $date = now()->format('ymd');
        $latest = static::whereDate('created_at', today())->count() + 1;
        return $prefix . $date . str_pad($latest, 4, '0', STR_PAD_LEFT);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'delivered' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'processing' => 'primary',
            'shipped' => 'success',
            'delivered' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function toWhatsAppMessage(): string
    {
        $lines = ["*Pesanan SADITA - {$this->order_number}*", ''];

        if ($this->warehouse?->name) {
            $lines[] = "📦 Gudang: {$this->warehouse->name}";
        }

        $lines[] = "👤 Nama: {$this->customer_name}";
        $lines[] = "📱 HP: {$this->customer_phone}";

        if ($this->customer_address) {
            $lines[] = "📍 Alamat: {$this->customer_address}";
        }

        $lines[] = '';
        $lines[] = '*Detail Pesanan:*';

        foreach ($this->items as $item) {
            $subtotal = 'Rp ' . number_format($item->subtotal, 0, ',', '.');
            $lines[] = "- {$item->product_name} x{$item->quantity} = {$subtotal}";
        }

        $lines[] = '';
        $lines[] = '*Total: Rp ' . number_format($this->total, 0, ',', '.') . '*';

        if ($this->payment_method) {
            $lines[] = '';
            $lines[] = "💳 Pembayaran: {$this->payment_method_label}";
        }

        if ($this->notes) {
            $lines[] = '';
            $lines[] = "📝 Catatan: {$this->notes}";
        }

        return implode("\n", $lines);
    }
}
