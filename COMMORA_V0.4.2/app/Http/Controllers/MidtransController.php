<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Gunakan Log untuk debugging

class MidtransController extends Controller
{
    /**
     * Menangani notifikasi HTTP (webhook) dari Midtrans.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request)
    {
        // 1. Validasi Signature Key (Keamanan Wajib)
        // ----------------------------------------------
        // Dapatkan Server Key dari file config
        // Kita akan atur ini di Langkah 4
        $serverKey = config('services.midtrans.server_key');

        // Ambil data payload dari Midtrans
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $signatureKey = $request->signature_key;

        // Buat hash SHA512 dari (order_id + status_code + gross_amount + server_key)
        $hashed = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        // Bandingkan hash buatan kita dengan signature_key dari Midtrans
        if ($hashed !== $signatureKey) {
            // Jika tidak cocok, ini adalah request palsu atau error.
            Log::warning('Midtrans Notification: Invalid signature.', [
                'order_id' => $orderId,
                'client_hash' => $signatureKey,
                'server_hash' => $hashed
            ]);
            return response()->json(['message' => 'Invalid signature.'], 403);
        }

        // 2. Dapatkan Status Transaksi
        // ----------------------------------------------
        $transactionStatus = $request->transaction_status;
        
        // 3. Cari Order di Database Anda
        // ----------------------------------------------
        // Anda menggunakan 'order_number' sebagai 'order_id' saat placeOrder, jadi kita cari berdasarkan itu.
        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            Log::error('Midtrans Notification: Order not found.', ['order_id' => $orderId]);
            return response()->json(['message' => 'Order not found.'], 404);
        }

        // 4. Update Status Order (Handle idempotency)
        // ----------------------------------------------
        // Cek apakah order sudah dalam status final (selesai, gagal, dibatalkan)
        // Ini untuk mencegah notifikasi ganda/terlambat meng-update status yang sudah final.
        if (in_array($order->status, ['paid', 'failed', 'cancelled', 'refunded'])) {
            Log::info('Midtrans Notification: Order already in final state.', [
                'order_id' => $orderId, 
                'status' => $order->status
            ]);
            return response()->json(['message' => 'Order already processed.'], 200);
        }

        // Handle status berdasarkan 'transaction_status' dari Midtrans
        switch ($transactionStatus) {
            case 'settlement':
            case 'capture': // 'capture' biasanya untuk kartu kredit
                // --- PEMBAYARAN BERHASIL ---
                $order->update(['status' => 'paid']);
                
                // (Opsional) Kirim notifikasi ke user, email, dll.
                // ...
                
                Log::info('Midtrans Notification: Payment successful (paid).', ['order_id' => $orderId]);
                break;
            
            case 'pending':
                // --- PEMBAYARAN TERTUNDA ---
                // Status di DB kita sudah 'pending' dari awal, tapi kita bisa update jika perlu.
                $order->update(['status' => 'pending']);
                Log::info('Midtrans Notification: Payment pending.', ['order_id' => $orderId]);
                break;
            
            case 'deny':
            case 'expire':
            case 'cancel':
                // --- PEMBAYARAN GAGAL / DIBATALKAN / KADALUARSA ---
                $order->update(['status' => 'failed']);
                
                // --- PENTING: Kembalikan status Post ---
                // Di OrderController, Anda set post jadi 'sold'.
                // Jika pembayaran gagal, kita harus mengembalikannya ke 'published'.
                
                // Load relasi 'items' dan 'post' di dalam 'items'
                $order->load('items.post'); 
                
                foreach ($order->items as $item) {
                    if ($item->post) { // Pastikan post masih ada
                        $item->post->update(['status' => 'published']);
                    }
                }
                
                Log::info('Midtrans Notification: Payment failed/expired/denied.', ['order_id' => $orderId]);
                break;

            case 'refund':
            case 'partial_refund':
                $order->update(['status' => 'refunded']);
                Log::info('Midtrans Notification: Payment refunded.', ['order_id' => $orderId]);
                break;

            default:
                // Status lain yang mungkin belum ter-handle
                Log::warning('Midtrans Notification: Unhandled status.', [
                    'status' => $transactionStatus, 
                    'order_id' => $orderId
                ]);
                break;
        }

        // 5. Kirim respon 200 OK ke Midtrans
        // ----------------------------------------------
        // Ini WAJIB agar Midtrans tahu notifikasi berhasil diterima.
        // Jika Anda tidak mengirim 200, Midtrans akan terus mengirim notifikasi ulang.
        return response()->json(['message' => 'Notification processed successfully.'], 200);
    }
}