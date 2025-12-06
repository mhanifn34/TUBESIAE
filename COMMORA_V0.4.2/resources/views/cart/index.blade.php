@extends('layouts.app') {{-- Menggunakan layout utama --}}

@section('title', 'Keranjang & Pesanan - Commora') {{-- Mengisi @yield('title') di layout --}}

@section('content') {{-- Memulai section konten utama --}}

<div class="py-6 sm:py-8 md:py-12 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 md:p-8 text-gray-900">

                {{-- Judul Halaman --}}
                <h2 class="font-semibold text-2xl md:text-3xl text-gray-800 leading-tight mb-6 pb-4 border-b border-gray-200">
                    {{ __('Keranjang & Pesanan') }}
                </h2>

                {{-- Tabs Navigation --}}
                <div class="mb-6 border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        {{-- Tab Keranjang --}}
                        <button class="tab-btn border-indigo-500 text-indigo-600 whitespace-nowrap py-3 px-1 border-b-2 font-medium text-base focus:outline-none" data-tab="cart" id="tab-cart">
                            Keranjang (<span id="cart-count-display">{{ $cartItems->count() ?? 0 }}</span>)
                        </button>
                        {{-- Tab Pesanan --}}
                        <button class="tab-btn border-transparent text-gray-500 hover:text-indigo-600 hover:border-indigo-300 whitespace-nowrap py-3 px-1 border-b-2 font-medium text-base focus:outline-none" data-tab="orders" id="tab-orders">
                            Pesanan (<span id="orders-count-display">{{ $orders->count() ?? 0 }}</span>)
                        </button>
                    </nav>
                </div>

                {{-- Pesan Sukses/Error --}}
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                     <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif


                {{-- Konten Tab Keranjang --}}
                <div id="cart-content" class="tab-content transition-all duration-500">
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                        {{-- Kolom Kiri (3/5): Item Keranjang --}}
                        <div class="lg:col-span-3 space-y-4" id="cart-items-container">
                            <h3 class="text-xl font-semibold text-gray-700 mb-3">Item di Keranjang Anda</h3>
                            
                            {{-- === MULAI LOOP ITEM KERANJANG === --}}
                            @forelse ($cartItems as $item)
                                @if($item->post)
                                    <div class="cart-item flex items-start space-x-4 bg-white p-4 rounded-lg shadow border border-gray-100 transition-shadow duration-150 ease-in-out hover:shadow-md"
                                         data-price="{{ $item->post->price ?? 0 }}"
                                         data-id="{{ $item->id }}">

                                        {{-- Gambar --}}
                                        @if ($item->post->image)
                                            <img src="{{ asset('storage/' . $item->post->image) }}" alt="{{ $item->post->title }}" class="w-20 h-20 object-cover rounded-md border border-gray-200 flex-shrink-0">
                                        @else
                                            <div class="w-20 h-20 bg-gray-100 rounded-md flex items-center justify-center text-gray-400 flex-shrink-0">
                                                <i class="fas fa-image text-3xl"></i>
                                            </div>
                                        @endif

                                        {{-- Detail Item --}}
                                        <div class="flex-1 min-w-0 pr-4">
                                            <h3 class="text-lg font-semibold text-gray-800">{{ $item->post->title }}</h3>
                                            <p class="text-sm text-gray-500 mt-0.5">Penjual: {{ $item->post->user->username ?? 'User' }}</p>
                                            <p class="text-lg font-bold text-green-700 mt-2">Rp {{ number_format($item->post->price, 0, ',', '.') }}</p>
                                        </div>

                                        {{-- Aksi Item (Hapus & Jumlah) --}}
                                        <div class="flex flex-col items-end space-y-2 flex-shrink-0">
                                            {{-- Tombol Hapus (BELUM BERFUNGSI) --}}
                                            <form action="#" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium transition-colors duration-150 ease-in-out focus:outline-none">Hapus</button>
                                            </form>

                                            {{-- Pengaturan Jumlah --}}
                                            <div class="flex items-center border border-gray-300 rounded">
                                                <button type="button" class="quantity-decrease px-2 py-1.5 text-gray-600 bg-gray-100 hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed rounded-l transition-colors duration-150 ease-in-out focus:outline-none" @if($item->quantity <= 1) disabled @endif> - </button>
                                                <input type="number" value="{{ $item->quantity }}" class="item-quantity w-10 text-center border-l border-r border-gray-300 py-1.5 text-sm focus:outline-none" readonly min="1">
                                                <button type="button" class="quantity-increase px-2 py-1.5 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-r transition-colors duration-150 ease-in-out focus:outline-none"> + </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="text-center py-16 bg-white rounded-lg shadow border border-gray-200 lg:col-span-3">
                                    <i class="fas fa-shopping-cart text-5xl mx-auto text-gray-300 mb-4"></i>
                                    <h2 class="text-lg font-semibold mb-2">Tidak ada item di keranjang</h2>
                                    <p class="text-gray-600 mb-4">Mulai berbelanja dan tambahkan produk ke keranjangmu!</p>
                                    <a href="{{ route('home') }}" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors duration-150 ease-in-out">Mulai Belanja</a>
                                </div>
                            @endforelse
                            {{-- === AKHIR LOOP ITEM KERANJANG === --}}
                        </div>

                        {{-- Kolom Kanan (2/5): Ringkasan & Checkout - STICKY DIHAPUS --}}
                        <div class="lg:col-span-2">
                            <div class="bg-white p-6 rounded-lg shadow-xl border border-gray-200">
                                <h3 class="text-xl font-semibold mb-4 border-b pb-2 text-gray-800">Ringkasan Belanja</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Total Harga (<span id="total-items-count">0</span> item)</span>
                                        <span id="subtotal-price" class="font-medium text-gray-700">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Ongkos Kirim</span>
                                        <span id="shipping-price" class="font-medium text-gray-700">Rp 0</span>
                                    </div>
                                </div>
                                <div class="border-t border-gray-200 my-4"></div>
                                <div class="flex justify-between font-bold text-lg text-gray-900">
                                    <span>Total Belanja</span>
                                    <span id="total-price">Rp 0</span>
                                </div>

                                {{-- Tombol Checkout --}}
                                <form id="checkout-form" action="{{ route('order.checkout.cart') }}" method="GET">
                                    <button type="submit" class="mt-6 w-full bg-green-600 text-white py-3 rounded-md font-semibold hover:bg-green-700 disabled:bg-gray-400 transition-colors duration-150 ease-in-out focus:outline-none" id="checkout-button" disabled>
                                        Checkout (<span id="checkout-items-count">0</span>)
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Orders Content --}}
                <div id="orders-content" class="tab-content hidden opacity-0 -translate-x-4 transition-all duration-500">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Riwayat Pesanan</h3>
                    @if($orders->isEmpty())
                         <div class="text-center py-16 bg-white rounded-lg shadow-sm border border-gray-200">
                             <i class="fas fa-box-open text-5xl mx-auto text-gray-300 mb-4"></i>
                            <h2 class="text-lg font-semibold mb-2">Belum ada pesanan</h2>
                            <p class="text-gray-600 mb-4">Pesananmu akan tampil di sini setelah checkout berhasil.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($orders as $order)
                                <div class="border border-gray-200 p-4 rounded-lg bg-white shadow-sm hover:shadow-md transition-shadow duration-150 ease-in-out">
                                    <div class="flex flex-col sm:flex-row justify-between sm:items-start mb-2 gap-3">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-sm font-semibold text-gray-800">Order #{{ $order->order_number }}</span>
                                                <span class="text-xs px-2 py-0.5 rounded-full
                                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800 border border-yellow-200
                                                    @elseif($order->status == 'processing') bg-blue-100 text-blue-800 border border-blue-200
                                                    @elseif($order->status == 'paid') bg-purple-100 text-purple-800 border border-purple-200
                                                    @elseif($order->status == 'completed') bg-green-100 text-green-800 border border-green-200
                                                    @elseif($order->status == 'cancelled') bg-red-100 text-red-800 border border-red-200
                                                    @else bg-gray-100 text-gray-800 border border-gray-200 @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
                                            <p class="text-md font-semibold text-gray-900 mt-1">Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                        </div>
                                        
                                        {{-- Tombol Terima Pesanan hanya muncul jika statusnya "paid" --}}
                                        @if($order->status === 'paid')
                                            <form action="{{ route('orders.terima', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menandai pesanan ini sebagai diterima?');" class="flex-shrink-0">
                                                @csrf
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-sm px-4 py-2 rounded-md transition-colors duration-150 ease-in-out whitespace-nowrap">
                                                    Terima Pesanan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>


            </div> {{-- End p-6 --}}
        </div> {{-- End bg-white --}}
    </div> {{-- End max-w-6xl --}}
</div> {{-- End py-12 --}}

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabButtons = document.querySelectorAll('.tab-btn');
        const cartContent = document.getElementById('cart-content');
        const ordersContent = document.getElementById('orders-content');
        const cartItemsContainer = document.getElementById('cart-items-container');

        // --- Fungsi Kalkulasi Total Harga ---
        function calculateTotal() {
            let subtotal = 0;
            let totalQuantityCount = 0;
            const items = cartItemsContainer ? cartItemsContainer.querySelectorAll('.cart-item') : [];

            items.forEach(item => {
                const price = parseFloat(item.dataset.price) || 0;
                const quantityInput = item.querySelector('.item-quantity');
                const quantity = parseInt(quantityInput ? (quantityInput.value || 0) : 0);

                if (!isNaN(price) && price > 0 && !isNaN(quantity) && quantity > 0) {
                    subtotal += price * quantity;
                    totalQuantityCount += quantity;
                }
            });

            const totalItemsEl = document.getElementById('total-items-count');
            const subtotalPriceEl = document.getElementById('subtotal-price');
            const totalPriceEl = document.getElementById('total-price');
            const checkoutButton = document.getElementById('checkout-button');
            const checkoutItemsCountSpan = document.getElementById('checkout-items-count');
            const cartCountSpan = document.getElementById('cart-count-display');

            if (totalItemsEl) totalItemsEl.innerText = totalQuantityCount;
            if (subtotalPriceEl) subtotalPriceEl.innerText = `Rp ${subtotal.toLocaleString('id-ID')}`;
            if (totalPriceEl) totalPriceEl.innerText = `Rp ${subtotal.toLocaleString('id-ID')}`;

            if (checkoutButton && checkoutItemsCountSpan) {
                checkoutButton.disabled = totalQuantityCount <= 0;
                checkoutItemsCountSpan.innerText = totalQuantityCount;
            }
             if (cartCountSpan) {
                cartCountSpan.innerText = items.length;
             }
        }

        // --- Fungsi Update Quantity ke Server ---
        function updateQuantity(itemId, newQuantity, itemElement) {
            console.log(`Updating item ${itemId} to quantity ${newQuantity}`);

            itemElement.style.opacity = '0.7';
            itemElement.style.pointerEvents = 'none';

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                alert('Terjadi kesalahan (CSRF). Silakan refresh halaman.');
                itemElement.style.opacity = '1';
                itemElement.style.pointerEvents = 'auto';
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/cart/update/${itemId}`; 
            form.style.display = 'none';

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PATCH';
            form.appendChild(methodInput);

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            const quantityInputHidden = document.createElement('input');
            quantityInputHidden.type = 'hidden';
            quantityInputHidden.name = 'quantity';
            quantityInputHidden.value = newQuantity;
            form.appendChild(quantityInputHidden);

            document.body.appendChild(form);
            form.submit();
        }

        // --- Event Listener untuk Tombol +/- ---
        if (cartItemsContainer) {
            cartItemsContainer.addEventListener('click', function(event) {
                const target = event.target;
                const cartItemElement = target.closest('.cart-item');

                if (!cartItemElement) return; 

                const quantityInput = cartItemElement.querySelector('.item-quantity');
                const decreaseButton = cartItemElement.querySelector('.quantity-decrease');
                const increaseButton = cartItemElement.querySelector('.quantity-increase');
                const cartItemId = cartItemElement.dataset.id;

                if (!quantityInput || !decreaseButton || !increaseButton || !cartItemId) return;

                let currentQuantity = parseInt(quantityInput.value);

                if (target === increaseButton) {
                    const newQuantity = currentQuantity + 1;
                    quantityInput.value = newQuantity;
                    decreaseButton.disabled = false;
                    calculateTotal();
                    updateQuantity(cartItemId, newQuantity, cartItemElement);
                }
                else if (target === decreaseButton) {
                    if (currentQuantity > 1) {
                        const newQuantity = currentQuantity - 1;
                        quantityInput.value = newQuantity;
                        if (newQuantity === 1) {
                            decreaseButton.disabled = true;
                        }
                        calculateTotal();
                        updateQuantity(cartItemId, newQuantity, cartItemElement);
                    }
                }
            });
        }

        // --- Event Listener Tab ---
        tabButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.dataset.tab;

                tabButtons.forEach(b => {
                    b.classList.remove('border-b-2', 'border-blue-600', 'text-blue-600');
                    b.classList.add('text-gray-600');
                });

                [cartContent, ordersContent].forEach(content => {
                    content.classList.remove('opacity-100', 'translate-x-0');
                    content.classList.add('opacity-0', 'hidden', '-translate-x-4');
                });

                btn.classList.add('border-b-2', 'border-blue-600', 'text-blue-600');
                btn.classList.remove('text-gray-600');

                const activeContent = (target === 'cart') ? cartContent : ordersContent;
                
                setTimeout(() => {
                    activeContent.classList.remove('hidden');
                    setTimeout(() => {
                        activeContent.classList.remove('opacity-0', '-translate-x-4');
                        activeContent.classList.add('opacity-100', 'translate-x-0');
                    }, 10);
                }, 10);
            });
            
             if (btn.dataset.tab === 'cart') {
                btn.click();
             }
        });

        calculateTotal();
    });
</script>
@endpush

@endsection