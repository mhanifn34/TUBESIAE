<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Post;
use App\Models\CartItem;
use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
// --- TAMBAHAN UNTUK MIDTRANS ---
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman checkout untuk single item (dari post card).
     */
    public function checkout(Post $post)
    {
        // Validasi: Pastikan post ini adalah 'listing' (Jual Beli), tipenya 'jual',
        // dan statusnya masih 'published' (belum terjual).
        if ($post->type !== 'listing' || $post->listing_type !== 'jual' || $post->status !== 'published') {
            return redirect()->route('home')->with('error', 'Produk ini tidak tersedia untuk dibeli.');
        }

        // Ambil item sebagai koleksi untuk konsistensi view checkout
        $cartItems = collect([
            (object)['post' => $post, 'quantity' => 1, 'id' => null] // Buat objek sementara
        ]);

        $totalPrice = $post->price;
        // Asumsi post memiliki kolom weight_in_grams (default 1000g jika tidak ada)
        $totalWeight = $post->weight_in_grams ?? 1000;
        
        // Kirim data ke view
        return view('orders.checkout', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'totalWeight' => $totalWeight,
            'source' => 'single' // Menandai sumber
        ]);
    }
    
    /**
     * Menampilkan halaman checkout dari Keranjang (Multi-Item).
     */
    public function checkoutCart()
    {
        $userId = Auth::id();

        // 1. Ambil semua item di keranjang user
        $cartItems = CartItem::where('user_id', $userId)
                             ->with('post.user') // Load post dan user penjual
                             ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong!');
        }

        // 2. Hitung total harga dan total berat
        $totalPrice = $cartItems->sum(function($item) {
            return $item->quantity * ($item->post->price ?? 0);
        });

        // Asumsi berat setiap post di tabel posts (default 1000g jika tidak ada)
        $totalWeight = $cartItems->sum(function($item) {
            return $item->quantity * ($item->post->weight_in_grams ?? 1000); 
        });

        // 3. Kirim data ke view
        return view('orders.checkout', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'totalWeight' => $totalWeight,
            'source' => 'cart' // Menandai sumber
        ]);
    }
    
    /**
     * Memproses pesanan dari form checkout, membuat Order record, dan menghasilkan Snap Token.
     */
    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'item_ids' => 'required', // Bisa berupa JSON array CartItem IDs atau single Post ID
            'shipping_address_combined' => 'required|string|min:10',
            'shipping_option' => 'required|string',
            'total_amount' => 'required|numeric', // Total harga barang saja
            'total_weight' => 'required|numeric|min:1', // Total berat
        ]);

        $totalItemPrice = (float)$validated['total_amount'];
        list($courierService, $shippingCost) = explode('|', $validated['shipping_option']);
        $shippingCost = (float)$shippingCost;

        // Tentukan Item/Items yang dibeli
        $isCartCheckout = Str::startsWith($validated['item_ids'], '[');
        
        if ($isCartCheckout) {
             // Multi-Item Checkout: Ambil Cart Items berdasarkan ID
            $itemIds = json_decode($validated['item_ids'], true);
            $itemsToProcess = CartItem::whereIn('id', $itemIds)->with('post')->get();
        } else {
             // Single Item Checkout: Ambil Post Item
             $itemsToProcess = collect([(object)[
                 'post' => Post::findOrFail($validated['item_ids']),
                 'quantity' => 1
             ]]);
        }
        
        // 1. Buat Order
        $order = Order::create([
            'buyer_id' => Auth::id(),
            'order_number' => 'CMRA-' . strtoupper(Str::random(8)),
            'total_amount' => $totalItemPrice + $shippingCost,
            'shipping_cost' => $shippingCost,
            'status' => 'pending', 
            'shipping_address' => $validated['shipping_address_combined'],
            'shipping_service' => $courierService,
        ]);

        // 2. Buat Order Items (Asumsi Anda punya tabel 'order_items' dengan relasi)
        foreach ($itemsToProcess as $item) {
            $post = $item->post;
            $quantity = $item->quantity;
            
            // Logika menyimpan ke tabel order_items (Anda harus membuat OrderItem Model jika belum)
            /*
            $order->items()->create([
                'post_id' => $post->id, 
                'seller_id' => $post->user_id, 
                'quantity' => $quantity, 
                'price' => $post->price,
            ]);
            */

            // 3. Hapus item dari Keranjang jika itu berasal dari Cart
            if ($isCartCheckout) {
                // Hapus item dari tabel cart_items setelah diproses
                CartItem::find($item->id)->delete();
            } else {
                 // Tandai post single item sebagai 'sold'
                 $post->update(['status' => 'sold']);
            }
        }
        
        // 4. GENERATE MIDTRANS SNAP TOKEN (Menggantikan redirect)
        // Order sudah dibuat, sekarang proses pembayaran.
        return $this->generatePaymentToken($request, $order, $itemsToProcess);
        
        // 5. Redirect ke halaman sukses (DIHAPUS karena diganti dengan return JSON dari generatePaymentToken)
    }
    
    /**
     * Mengambil Order yang sudah dibuat, mengkonfigurasi Midtrans, dan menghasilkan Snap Token.
     * Dipanggil oleh placeOrder setelah Order record dibuat.
     */
    protected function generatePaymentToken(Request $request, Order $order, $itemsToProcess)
    {
        // 1. Konfigurasi Midtrans
        // Mengambil Server Key dari file konfigurasi (config/midtrans.php)
        Config::$serverKey = config('midtrans.server_key'); 
        Config::$isProduction = false; // Atur ke true untuk lingkungan produksi
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // 2. Persiapan Item Details
        $item_details = [];
        // Tambahkan item yang dibeli
        foreach ($itemsToProcess as $item) {
            $post = $item->post;
            $item_details[] = [
                'id'       => "PST-{$post->id}",
                'price'    => $post->price,
                'quantity' => $item->quantity,
                'name'     => Str::limit($post->title, 50),
            ];
        }
        
        // Tambahkan biaya pengiriman (Shipping Cost) sebagai item terpisah
        if ($order->shipping_cost > 0) {
            $item_details[] = [
                'id'       => "SHP-{$order->shipping_service}",
                'price'    => (int)$order->shipping_cost,
                'quantity' => 1,
                'name'     => "Biaya Kirim ({$order->shipping_service})",
            ];
        }
        

        // 3. Persiapan Parameter Transaksi Midtrans
        $params = [
            'transaction_details' => [
                // Gunakan order_number yang unik sebagai order_id Midtrans
                'order_id'     => $order->order_number, 
                'gross_amount' => (int)$order->total_amount, // Total harga sudah termasuk ongkir
            ],
            'item_details' => $item_details,
            'customer_details' => [
                // Menggunakan data dari user yang login dan alamat yang diinput
                'first_name' => Auth::user()->name ?? 'Guest', 
                'last_name'  => '', 
                'email'      => Auth::user()->email ?? 'guest@commora.com',
                'phone'      => Auth::user()->phone ?? '081234567890', 
                // Midtrans Snap akan menggunakan data ini
            ],
            // Optional: Notification URL, jika Anda sudah mengaturnya
            // 'callbacks' => [
            //     'finish' => route('order.success', $order->id),
            //     'error' => route('order.failed', $order->id),
            // ],
        ];

        // 4. Mendapatkan Snap Token
        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error("Midtrans Snap Token Error for Order #{$order->id}: " . $e->getMessage());
            // Update status order menjadi failed/cancel jika token gagal dibuat
            $order->update(['status' => 'payment_failed']); 
            return response()->json(['error' => 'Gagal mendapatkan Midtrans Snap Token'], 500);
        }

        // 5. Kembalikan Snap Token ke frontend
        return response()->json([
            'snap_token' => $snapToken,
            'order_id'   => $order->id,
            'order_number' => $order->order_number,
            'message'    => 'Snap Token berhasil dibuat.'
        ]);
    }
    
    /**
     * Method untuk menampilkan halaman sukses.
     */
    public function success(Order $order)
    {
        if (Auth::id() !== $order->buyer_id) {
            abort(403, 'Akses ditolak.'); 
        }
        // Load item (jika OrderItem Model sudah ada)
        //$order->load('items.post'); 
        
        // Pastikan Anda punya resources/views/orders/sucess.blade.php
        return view('orders.sucess', compact('order')); 
    }

    public function markAsPaid(Order $order)
    {
        // Ubah status pesanan
        $order->update([
            'status' => 'paid',
        ]);

        // Arahkan ke beranda
        return redirect()->route('home')->with('success', 'Pesanan berhasil dibayar.');
    }   

}
