<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderTrackingController extends Controller
{
    public function index(Request $request): View
    {
        $order = null;
        $error = null;
        $waPhone = \App\Models\DisplaySetting::active()->company_whatsapp;

        if ($request->isMethod('post') || $request->filled('order_number')) {
            $request->validate([
                'order_number' => 'required|string',
                'customer_phone' => 'required|string',
            ]);

            $order = Order::with(['items', 'warehouse'])
                ->where('order_number', $request->order_number)
                ->where('customer_phone', $request->customer_phone)
                ->first();

            if (!$order) {
                $error = 'Pesanan tidak ditemukan. Pastikan Nomor Pesanan dan Nomor HP sudah benar.';
            }
        }

        return view('toko.track-order', compact('order', 'error', 'waPhone'));
    }
}
