<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $code = strtoupper(trim($request->input('code')));
        $subtotal = (int) $request->input('subtotal');

        // Mock logic for Voucher V3.1
        if ($code === 'SADITA2026') {
            $discount = 20000;
            if ($subtotal < 100000) {
                return response()->json([
                    'success' => false,
                    'message' => 'Minimal belanja Rp 100.000 untuk menggunakan voucher ini.',
                ]);
            }
            return response()->json([
                'success' => true,
                'discount' => $discount,
                'message' => "Voucher berhasil! Anda hemat Rp " . number_format($discount, 0, ',', '.'),
            ]);
        }

        if ($code === 'DISKON10') {
            $discount = (int) ($subtotal * 0.1);
            $discount = min($discount, 50000); // Max 50rb
            return response()->json([
                'success' => true,
                'discount' => $discount,
                'message' => "Diskon 10% berhasil! Anda hemat Rp " . number_format($discount, 0, ',', '.'),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kode voucher tidak valid atau sudah kedaluwarsa.',
        ]);
    }
}
