<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\MapsController;



// ===============================
// ROUTES UNTUK GUEST (BELUM LOGIN)
// ===============================
Route::middleware('guest')->group(function () {
    // Registrasi
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Google OAuth
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/auth-google-callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');
});

// ===============================
// ROUTES UNTUK USER LOGIN
// ===============================
Route::middleware('auth')->group(function () {

    // Home
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Komunitas saya
    Route::get('/komunitas/saya', [CommunityController::class, 'myCommunities'])->name('communities.my');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ===============================
    // POST ROUTES
    // ===============================
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/listings', [PostController::class, 'storeListing'])->name('listings.store');

    // ===============================
    // COMMUNITY ROUTES
    // ===============================
    Route::get('/communities', [CommunityController::class, 'index'])->name('communities.index');
    Route::get('/communities/create', [CommunityController::class, 'create'])->name('communities.create');
    Route::post('/communities', [CommunityController::class, 'store'])->name('communities.store');
    Route::get('/list-community', [CommunityController::class, 'listCommunity'])->name('communities.joined');
    Route::get('/my-communities', [CommunityController::class, 'listCommunity'])->name('communities.joined');

    // Dynamic routes (harus di bawah)
    Route::get('/communities/{community}', [CommunityController::class, 'show'])->name('communities.show');
    Route::get('/communities/{community}/edit', [CommunityController::class, 'edit'])->name('communities.edit');
    Route::put('/communities/{community}', [CommunityController::class, 'update'])->name('communities.update');
    Route::delete('/communities/{community}', [CommunityController::class, 'destroy'])->name('communities.destroy');
    Route::post('/communities/{community}/join', [CommunityController::class, 'join'])->name('communities.join');
    Route::post('/communities/{community}/leave', [CommunityController::class, 'leave'])->name('communities.leave');

    // ===============================
    // CART ROUTES
    // ===============================
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');

    // ===============================
    // PROFILE ROUTES
    // ===============================
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/profile/{username}', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // =======================================================
    // CHECKOUT & SHIPMENT ROUTES
    // =======================================================
    // 1️⃣ Checkout dari keranjang
    Route::get('/checkout/cart', [OrderController::class, 'checkoutCart'])->name('order.checkout.cart');

    // 2️⃣ Checkout single item (halaman form)
    Route::get('/checkout/{post}', [OrderController::class, 'showCheckoutForm'])->name('order.checkout.show');

    // 3️⃣ Proses pembayaran & generate Snap Token Midtrans
    Route::post('/order/process-payment', [OrderController::class, 'checkout'])->name('order.process.payment');

    // 4️⃣ Proses order & pengiriman
    Route::post('/order/place', [OrderController::class, 'placeOrder'])->name('order.place');
    Route::get('/order/success/{order}', [OrderController::class, 'success'])->name('order.success');
    Route::post('/maps/search-area', [MapsController::class, 'searchArea'])->name('maps.searchArea');
    Route::post('/shipping/calculate', [ShipmentController::class, 'calculate'])->name('shipping.calculate');

    // Perubahan status menjadi paid
    Route::get('/order/{order}/mark-paid', [OrderController::class, 'markAsPaid'])->name('order.markPaid');

    // Tombol Terima Pesanan cuyyy WAKAAKKAKAKAKAK
    Route::post('/orders/{order}/terima', [CartController::class, 'terimaPesanan'])->name('orders.terima');
    
    // ===============================
    // HALAMAN PESAN
    // ===============================
    Route::get('/messages', function () {
        return view('messages.index');
    })->name('messages');
});
