<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\StoreSetting;
use Illuminate\Http\Request;

class OrderActionController extends Controller
{
    public function edit($orderNumber, $token)
    {
        $order = Order::where('order_number', $orderNumber)->where('success_token', $token)->firstOrFail();
        
        if ($order->status !== 'pending') {
            return redirect()->route('toko.orders')->with('error', 'Pesanan yang sudah diproses tidak bisa diedit.');
        }

        $storeSetting = StoreSetting::active();
        $paymentMethods = is_array($storeSetting->payment_methods) ? $storeSetting->payment_methods : [];

        return view('toko.edit-order', compact('order', 'paymentMethods'));
    }

    public function update(Request $request, $orderNumber, $token)
    {
        $order = Order::where('order_number', $orderNumber)->where('success_token', $token)->firstOrFail();
        
        if ($order->status !== 'pending') {
            return redirect()->route('toko.orders')->with('error', 'Pesanan yang sudah diproses tidak bisa diedit.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $order->update($validated);

        return redirect()->route('checkout.success', ['orderNumber' => $order->order_number, 'token' => $order->success_token])
            ->with('success', 'Data pesanan berhasil diperbarui!');
    }

    public function cancel(Request $request, $orderNumber, $token)
    {
        $order = Order::where('order_number', $orderNumber)->where('success_token', $token)->firstOrFail();
        
        if (in_array($order->status, ['shipped', 'delivered', 'cancelled'])) {
            return back()->with('error', 'Pesanan ini tidak bisa dibatalkan.');
        }

        $request->validate([
            'cancel_reason' => 'required|string|max:1000'
        ]);

        $order->update([
            'cancel_requested' => true,
            'cancel_reason' => $request->cancel_reason
        ]);

        return back()->with('success', 'Pengajuan pembatalan berhasil dikirim. Menunggu persetujuan Admin.');
    }
}
