<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Ditambahkan
use App\Models\Post;                   // <-- Ditambahkan
use App\Models\CartItem;               // <-- Ditambahkan
use App\Models\Order;                  // <-- Ditambahkan (untuk tab Pesanan)

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja.
     */
    public function index()
    {
        // Ambil semua item keranjang milik user yang sedang login
        // Beserta relasi 'post' untuk menampilkan detail barang
        $cartItems = CartItem::where('user_id', Auth::id())
                                ->with(['post.user']) // Eager load post dan user penjual
                                ->latest() // Urutkan dari yg terbaru ditambahkan
                                ->get();

        // Ambil data pesanan user (untuk tab Pesanan)
        $orders = Order::where('buyer_id', Auth::id())
                       ->latest() // Urutkan dari yg terbaru
                       ->take(10) // Batasi jumlah pesanan yg ditampilkan (opsional)
                       ->get();

        // Kirim data ke view
        return view('cart.index', compact('cartItems', 'orders'));
    }

    /**
     * Menambahkan item ke keranjang.
     */
    public function add(Request $request)
{
    // dd('Reached add method'); // Debug 1: Pastikan method dipanggil

    $validated = $request->validate([
        'post_id' => 'required|exists:posts,id',
        'quantity' => 'required|integer|min:1'
    ]);

    // dd('Validation passed', $validated); // Debug 2: Cek hasil validasi

    $post = Post::find($validated['post_id']);
    $user = Auth::user();

    // Validasi tambahan
    if ($post->user_id == $user->id) {
        // dd('Validation failed: Cannot buy own product'); // Debug 3a
        return back()->with('error', 'Anda tidak dapat membeli produk Anda sendiri.');
    }
    if ($post->type !== 'listing' || $post->listing_type !== 'jual' || $post->status !== 'published') {
        // dd('Validation failed: Product not available'); // Debug 3b
         return back()->with('error', 'Produk tidak tersedia.');
    }

    // Cek item di keranjang
    $cartItem = CartItem::where('user_id', $user->id)
                        ->where('post_id', $post->id)
                        ->first();

    try { // Tambahkan try-catch untuk menangkap error database
        if ($cartItem) {
            // dd('Item exists, incrementing quantity'); // Debug 4a
            $newQuantity = $cartItem->quantity + $validated['quantity'];
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // dd('Item does not exist, creating new item'); // Debug 4b
            CartItem::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
                'quantity' => $validated['quantity'],
            ]);
        }
    } catch (\Exception $e) {
        // Jika ada error saat menyimpan ke DB, tampilkan errornya
        // dd('Database error:', $e->getMessage()); // Debug 5
    }

    

    // dd('Successfully added/updated item, attempting redirect'); // Debug 6: Cek sebelum redirect

    return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    // return redirect('https://www.google.com'); // Tetap pakai redirect Google untuk tes
}

    public function update(Request $request, CartItem $cartItem)
{
    // Pastikan item ini milik user yang login (Keamanan)
    if ($cartItem->user_id !== Auth::id()) {
        abort(403, 'Akses ditolak.');
    }

    $validated = $request->validate([
        'quantity' => 'required|integer|min:1' // Hanya butuh quantity baru
    ]);

    $cartItem->update(['quantity' => $validated['quantity']]);

    // Kirim response JSON (jika pakai AJAX) atau redirect back (jika pakai form biasa)
    // Untuk sekarang, kita redirect back saja
    return back()->with('success', 'Jumlah item diperbarui.');}

    // Jika ingin pakai AJAX nanti:
    // return response()->json(['message' => 'Jumlah item diperbarui.', 'newQuantity' => $validated['quantity']]);

    public function terimaPesanan(Order $order)
    {
        // Pastikan pesanan milik user yang sedang login
        if ($order->buyer_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk pesanan ini.');
        }

        // Ubah status jadi "terima_barang"
        $order->update([
            'status' => 'terima_barang',
        ]);

        return back()->with('success', 'Pesanan berhasil diterima.');
    }

}

{}