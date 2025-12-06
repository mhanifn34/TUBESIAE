@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
@php
// PENTING: Ambil data dari OrderController
$itemsToDisplay = $cartItems ?? collect(); 
$finalPrice = $totalPrice ?? 0;
$finalWeight = $totalWeight ?? 1000;
$isMultiItem = $source === 'cart'; // Flag untuk mengetahui apakah dari keranjang

// Ambil semua ID CartItem dalam bentuk JSON string untuk dikirim ke backend
$itemIdsForBackend = $isMultiItem ? json_encode($itemsToDisplay->pluck('id')) : ($itemsToDisplay->first()->post->id ?? null);
@endphp

<div class="py-6 sm:py-8 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-6 md:p-8 rounded-lg shadow-xl border border-gray-200">
            <h1 class="text-2xl md:text-3xl font-bold mb-6 border-b pb-4 text-gray-800">Finalisasi Checkout</h1>

            {{-- Notifikasi Modal --}}
            <div id="notification-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center p-4 hidden">
                <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-sm">
                    <h4 id="notification-title" class="text-lg font-semibold text-gray-800 mb-3">Notifikasi</h4>
                    <p id="notification-message" class="text-sm text-gray-600 mb-5">Pesan notifikasi.</p>
                    <button onclick="hideNotification()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Tutup
                    </button>
                </div>
            </div>
            {{-- End Notifikasi Modal --}}


            @if($itemsToDisplay->isNotEmpty())
                {{-- Bagian Ringkasan Produk --}}
                <div class="border rounded-lg p-4 mb-8 bg-gray-50 shadow-sm border-gray-200">
                    <p class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">
                        Produk Dipesan ({{ $itemsToDisplay->count() }} Jenis Item)
                    </p>
                    
                    {{-- Loop Item Dipesan --}}
                    @foreach($itemsToDisplay as $item)
@php
$product = $item->post; 
$quantity = $item->quantity;
@endphp
                        <div class="flex items-start space-x-4 mb-3 pb-3 @if(!$loop->last) border-b border-gray-100 @endif">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="w-16 h-16 object-cover rounded-md border flex-shrink-0">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded-md flex items-center justify-center text-gray-400 flex-shrink-0"><i class="fas fa-image text-xl"></i></div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h2 class="text-md font-semibold text-gray-900 truncate">{{ $product->title }}</h2>
                                <p class="text-sm text-gray-600">Qty: {{ $quantity }} | Penjual: {{ $product->user->username ?? 'User' }}</p>
                                <p class="text-md font-bold text-green-700 mt-1">Rp {{ number_format($product->price * $quantity, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="flex justify-between font-bold text-md text-gray-800 pt-3">
                        <span>Subtotal Barang:</span>
                        <span>Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                {{-- FORM CHECKOUT LANJUTAN (Alamat, Kurir, Total) --}}
                <form action="{{ route('order.place') }}" method="POST" id="checkout-form">
                    @csrf
                    
                    {{-- Hidden Inputs Wajib --}}
                    <input type="hidden" name="item_ids" value="{{ $itemIdsForBackend }}"> {{-- ID Cart Items atau Post ID --}}
                    <input type="hidden" name="total_amount" value="{{ $finalPrice }}">
                    <input type="hidden" name="total_weight" value="{{ $finalWeight }}">
                    <input type="hidden" name="origin_area_id" id="origin_area_id" value="IDN.JB.BA.BANDUNG.KOTA BANDUNG.BANDUNG KIDUL"> 
                    <input type="hidden" name="destination_area_id" id="destination_area_id">
                    <input type="hidden" name="shipping_address_combined" id="shipping_address_combined">
                    <input type="hidden" name="shipping_option" id="shipping_option_hidden">

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
                        <div class="md:col-span-3">
                            {{-- 1. Alamat Asal --}}
                            <section class="mb-8">
                                <h3 class="text-xl font-semibold mb-4 text-gray-800 border-l-4 border-indigo-500 pl-3">1. Alamat Asal Pengiriman</h3>
                                <div class="space-y-4 p-5 rounded-lg border bg-indigo-50 border-indigo-100">
                                    <div class="relative">
                                        <label for="origin_search" class="block text-sm font-medium text-gray-700">Cari Kecamatan/Kelurahan Asal</label>
                                        <input type="text" id="origin_search" placeholder="Ketik min. 3 huruf..." class="w-full mt-1 p-2 border rounded-md focus:ring-indigo-500" autocomplete="off" required>
                                        <div id="origin_results" class="absolute z-30 w-full bg-white border rounded-md mt-1 max-h-48 overflow-y-auto hidden shadow-lg"></div>
                                    </div>
                                </div>
                            </section>

                            {{-- 2. Alamat Tujuan --}}
                            <section class="mb-8">
                                <h3 class="text-xl font-semibold mb-4 text-gray-800 border-l-4 border-blue-500 pl-3">2. Alamat Tujuan Pengiriman</h3>
                                <div class="space-y-4 p-5 rounded-lg border bg-blue-50 border-blue-100">
                                   <div class="relative">
                                        <label for="destination_search" class="block text-sm font-medium text-gray-700">Cari Kecamatan/Kelurahan Tujuan</label>
                                        <input type="text" id="destination_search" placeholder="Ketik min. 3 huruf..." class="w-full mt-1 p-2 border rounded-md focus:ring-blue-500" autocomplete="off" required>
                                        <div id="destination_results" class="absolute z-20 w-full bg-white border rounded-md mt-1 max-h-48 overflow-y-auto hidden shadow-lg"></div>
                                        <p id="selected_destination_area" class="text-sm text-blue-700 mt-1 font-medium hidden"></p>
                                    </div>
                                    <div>
                                        <label for="postal_code" class="block text-sm font-medium text-gray-700">Kode Pos</label>
                                        <input type="number" name="postal_code_display" id="postal_code" placeholder="Kode Pos (otomatis)" class="w-full mt-1 p-2 border rounded-md bg-gray-100" required readonly>
                                    </div>
                                    <div>
                                        <label for="shipping_address_detail" class="block text-sm font-medium text-gray-700">Detail Alamat (Nama Jalan, No Rumah, dll.)</label>
                                        <textarea name="shipping_address_detail" id="shipping_address_detail" rows="3" placeholder="Nama Jalan, Nomor Rumah, RT/RW..." class="w-full mt-1 p-2 border rounded-md focus:ring-blue-500" required></textarea>
                                    </div>
                                </div>
                            </section>

                            {{-- 3. Opsi Pengiriman --}}
                            <section>
                                   <h3 class="text-xl font-semibold mb-4 text-gray-800 border-l-4 border-green-500 pl-3">3. Opsi Pengiriman</h3>
                                   <div class="space-y-3 p-5 rounded-lg border bg-green-50 border-green-100">
                                       <label class="block text-sm font-medium text-gray-700 mb-3">Pilih Kurir:</label>
                                       <div id="courier-selection" class="grid grid-cols-3 gap-3">
                                            <button type="button" data-courier="jne" class="courier-btn p-2 border rounded-md text-sm bg-white hover:bg-gray-100 transition-colors">JNE</button>
                                            <button type="button" data-courier="tiki" class="courier-btn p-2 border rounded-md text-sm bg-white hover:bg-gray-100 transition-colors">TIKI</button>
                                            <button type="button" data-courier="pos" class="courier-btn p-2 border rounded-md text-sm bg-white hover:bg-gray-100 transition-colors">POS</button>
                                            <button type="button" data-courier="sicepat" class="courier-btn p-2 border rounded-md text-sm bg-white hover:bg-gray-100 transition-colors">SiCepat</button>
                                            <button type="button" data-courier="jnt" class="courier-btn p-2 border rounded-md text-sm bg-white hover:bg-gray-100 transition-colors">J&T</button>
                                            <button type="button" data-courier="anteraja" class="courier-btn p-2 border rounded-md text-sm bg-white hover:bg-gray-100 transition-colors">AnterAja</button>
                                        </div>
                                        <div id="courier-alert" class="hidden mt-3 text-sm text-yellow-700 bg-yellow-100 p-3 rounded-md">
                                            Pilih area asal dan tujuan terlebih dahulu.
                                        </div>
                                       <div id="shipping-options" class="mt-5 border-t border-green-200 pt-4">
                                            <p class="text-sm text-gray-500 text-center py-4">Pilih area & kurir untuk lihat layanan.</p>
                                        </div>
                                        <div id="loading-spinner" class="hidden text-center py-4 text-sm text-blue-600">
                                            <i class="fas fa-spinner fa-spin mr-2"></i> Memproses pembayaran...
                                        </div>
                                   </div>
                            </section>
                        </div>

                        {{-- 4. Ringkasan Akhir --}}
                        <div class="md:col-span-2">
                            <div class="sticky top-24">
                                <h3 class="text-xl font-semibold mb-4 text-gray-800 border-l-4 border-gray-500 pl-3">4. Total Pesanan</h3>
                                <div class="space-y-3 p-5 bg-gray-100 rounded-lg border border-gray-200">
                                    <div class="flex justify-between text-gray-700"><span>Subtotal Produk</span><span id="subtotal-summary">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span></div>
                                    <div class="flex justify-between text-gray-700"><span>Ongkir</span><span id="shipping-cost-summary">Rp 0</span></div>
                                    <div class="border-t border-gray-300 my-3"></div>
                                    <div class="flex justify-between font-bold text-lg text-gray-900"><span>Total</span><span id="total-cost-summary">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span></div>
                                </div>
                                {{-- Tombol submit form (diaktifkan setelah ongkir dipilih) --}}
                                <button type="button" class="w-full bg-blue-600 text-white font-bold py-3 mt-6 rounded-md hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition duration-200 focus:outline-none" id="payment-button" disabled>
                                    Proses Pembayaran
                                </button>
                                <p class="text-xs text-gray-500 mt-2 text-center">Total berat yang dihitung: {{ number_format($finalWeight / 1000, 2) }} kg</p>
                            </div>
                        </div>
                    </div>
                </form>

            @else
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">Keranjang kosong. Silakan kembali ke halaman utama untuk berbelanja.</div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
{{-- 1. Midtrans Snap Script (Sandbox) --}}
{{-- TODO: Ganti data-client-key dengan client key Anda dari config. Untuk production, ganti src menjadi https://app.midtrans.com/snap/snap.js --}}
<script type="text/javascript"
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="SET_YOUR_CLIENT_KEY_HERE">
</script>

{{-- PENTING: Pastikan Anda sudah mengimpor Axios di layouts/app.blade.php Anda --}}
<script>
    // --- Notifikasi Modal Functions ---
    const notificationModal = document.getElementById('notification-modal');
    const notificationTitle = document.getElementById('notification-title');
    const notificationMessage = document.getElementById('notification-message');

    function showNotification(message, title = 'Validasi Gagal') {
        notificationTitle.textContent = title;
        notificationMessage.textContent = message;
        notificationModal.classList.remove('hidden');
    }

    function hideNotification() {
        notificationModal.classList.add('hidden');
    }
    // --- End Notifikasi Modal Functions ---

    // --- State Variables ---
    let selectedShippingCost = 0;
    const totalItemPrice = {{ $finalPrice ?? 0 }};
    const totalItemWeight = {{ $finalWeight ?? 1000 }};

    let selectedOriginAreaId = document.getElementById('origin_area_id').value;
    let selectedDestinationAreaId = null;
    let selectedAreaNameOrigin = 'Bandung Kidul, Bandung';
    let selectedAreaNameDestination = '';
    let searchTimeoutOrigin;
    let searchTimeoutDestination;

    // --- DOM Elements ---
    const checkoutForm = document.getElementById('checkout-form');
    const paymentButton = document.getElementById('payment-button');
    const originSearchInput = document.getElementById('origin_search');
    const originResultsDiv = document.getElementById('origin_results');
    const originAreaIdInput = document.getElementById('origin_area_id');
    const selectedOriginAreaP = document.getElementById('selected_origin_area');
    const destinationSearchInput = document.getElementById('destination_search');
    const destinationResultsDiv = document.getElementById('destination_results');
    const destinationAreaIdInput = document.getElementById('destination_area_id');
    const postalCodeInput = document.getElementById('postal_code');
    const selectedDestinationAreaP = document.getElementById('selected_destination_area');
    const courierAlert = document.getElementById('courier-alert');
    const shippingOptionsDiv = document.getElementById('shipping-options');
    const shippingAddressDetailTextarea = document.getElementById('shipping_address_detail');
    const shippingAddressCombinedInput = document.getElementById('shipping_address_combined');
    const shippingOptionHiddenInput = document.getElementById('shipping_option_hidden');
    const loadingSpinner = document.getElementById('loading-spinner');
    
    // --- Autocomplete Function ---
    function setupAutocomplete(searchInput, resultsDiv, isOrigin = false) {
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        let timeoutVar = isOrigin ? searchTimeoutOrigin : searchTimeoutDestination;
        clearTimeout(timeoutVar);
        resultsDiv.classList.add('hidden');

        if (query.length < 3) return;

        resultsDiv.innerHTML = '<div class="p-2 text-sm text-gray-500 animate-pulse">Mencari...</div>';
        resultsDiv.classList.remove('hidden');

        timeoutVar = setTimeout(() => {
        axios.post('{{ route("maps.searchArea") }}', { query: query })
            .then(response => {
            let resultsHtml = '';
            if (response.data && response.data.length > 0) {
                response.data.forEach(area => {
                const safeName = String(area.name).replace(/</g, "&lt;").replace(/>/g, "&gt;");
                resultsHtml += `<div class="p-2 hover:bg-gray-100 cursor-pointer text-sm" data-area-id="${area.id}" data-area-name="${safeName}" data-postal-code="${area.postal_code ?? ''}" onclick="selectArea(this, '${searchInput.id}')">${safeName} (Kodepos: ${area.postal_code ?? 'N/A'})</div>`;
                });
            } else {
                resultsHtml = '<div class="p-2 text-sm text-gray-500">Area tidak ditemukan</div>';
            }
            resultsDiv.innerHTML = resultsHtml;
            })
            .catch(error => {
            console.error('Error searching area:', error);
            resultsDiv.innerHTML = '<div class="p-2 text-sm text-red-500">Gagal mencari area</div>';
            });
        }, 300);

        if (isOrigin) searchTimeoutOrigin = timeoutVar; else searchTimeoutDestination = timeoutVar;
    });
    }

    // --- Select Area Function ---
    function selectArea(element, inputId) {
    const areaId = element.dataset.areaId;
    const areaName = element.dataset.areaName;
    const postalCode = element.dataset.postalCode;

    if (inputId === 'origin_search') {
        originSearchInput.value = areaName;
        originAreaIdInput.value = areaId;
        selectedOriginAreaP.textContent = `Asal: ${areaName}`;
        selectedOriginAreaP.classList.remove('hidden');
        originResultsDiv.classList.add('hidden');
        selectedOriginAreaId = areaId;
    } else {
        destinationSearchInput.value = areaName;
        destinationAreaIdInput.value = areaId;
        selectedDestinationAreaP.textContent = `Tujuan: ${areaName}`;
        selectedDestinationAreaP.classList.remove('hidden');
        destinationResultsDiv.classList.add('hidden');
        selectedDestinationAreaId = areaId;

        postalCodeInput.value = postalCode && postalCode !== 'null' ? postalCode : '';
    }

    resetShippingSelection();
    courierAlert.classList.add('hidden');
    }

    // --- Initialize Autocomplete ---
    setupAutocomplete(originSearchInput, originResultsDiv, true);
    setupAutocomplete(destinationSearchInput, destinationResultsDiv, false);

    // --- Reset Shipping Selection ---
    function resetShippingSelection() {
    shippingOptionsDiv.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">Pilih kurir untuk melihat layanan.</p>';
    paymentButton.disabled = true;
    document.getElementById('shipping-cost-summary').innerText = `Rp 0`;
    document.getElementById('total-cost-summary').innerText = `Rp ${totalItemPrice.toLocaleString('id-ID')}`;
    document.querySelectorAll('.courier-btn').forEach(btn => {
        btn.classList.remove('bg-blue-500', 'text-white', 'ring-2', 'ring-blue-300');
        btn.classList.add('hover:bg-gray-100');
    });
    shippingOptionHiddenInput.value = '';
    }

    // --- Courier Button Listener ---
    document.querySelectorAll('.courier-btn').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault();
        const courierType = this.dataset.courier;

        if (!selectedOriginAreaId || !selectedDestinationAreaId) {
        courierAlert.classList.remove('hidden'); return;
        }
        courierAlert.classList.add('hidden');

        document.querySelectorAll('.courier-btn').forEach(btn => {
        btn.classList.remove('bg-blue-500', 'text-white', 'ring-2', 'ring-blue-300');
        btn.classList.add('hover:bg-gray-100');
        });
        event.target.classList.add('bg-blue-500', 'text-white', 'ring-2', 'ring-blue-300');
        event.target.classList.remove('hover:bg-gray-100');

        getShippingCost(courierType);
    });
    });

    // --- Get Shipping Cost Function ---
    function getShippingCost(courierType) {
    const originId = selectedOriginAreaId;
    const destinationId = selectedDestinationAreaId;
    const weight = totalItemWeight;

    shippingOptionsDiv.innerHTML = '<p class="text-center py-4 text-gray-600 animate-pulse"><i class="fas fa-shipping-fast mr-2"></i> Menghitung ongkir...</p>';
    paymentButton.disabled = true;

    document.getElementById('shipping-cost-summary').innerText = `Rp 0`;
    document.getElementById('total-cost-summary').innerText = `Rp ${totalItemPrice.toLocaleString('id-ID')}`;

    axios.post('{{ route("shipping.calculate") }}', {
        origin_area_id: originId,
        destination_area_id: destinationId,
        weight: weight,
        courier_type: courierType
    })
    .then(response => {
        let html = '';

        if (response.data && Array.isArray(response.data)) {
        html += '<h4 class="font-semibold text-gray-700 mb-2 mt-4 text-sm">Pilih Layanan:</h4>';

        if (response.data.length === 0) {
            html = '<p class="text-yellow-600 text-center py-4 text-sm"><i class="fas fa-exclamation-triangle mr-2"></i> Tidak ada layanan ditemukan dari kurir ini.</p>';
        } else {
            response.data.forEach(option => {
            if (option.service && option.cost && Array.isArray(option.cost) && option.cost[0] && option.courier) {
                const costDetail = option.cost[0];
                // Format: COURIER - SERVICE|COST
                const serviceValue = `${option.courier.toUpperCase()} - ${option.service}|${costDetail.value}`;
                const etd = costDetail.etd ? `Estimasi: ${costDetail.etd}` : '';

                html += `<label class="block border rounded-md mb-2 hover:bg-gray-50 cursor-pointer transition duration-150 has-[:checked]:bg-blue-50 has-[:checked]:border-blue-300">
                <div class="flex items-center justify-between p-3">
                    <div><span class="font-semibold text-sm">${option.courier.toUpperCase()} - ${option.service}</span><p class="text-xs text-gray-500">${etd}</p></div>
                    <div class="text-right flex items-center">
                    <span class="font-semibold text-sm mr-3">Rp ${costDetail.value.toLocaleString('id-ID')}</span>
                    <input type="radio" name="shipping_option_radio" value="${serviceValue}" onchange="updateTotal(this.value)" class="form-radio h-4 w-4 text-blue-600 focus:ring-blue-500" required>
                    </div>
                </div>
                </label>`;
            }
            });
        }
        } else {
        html = '<p class="text-red-600 text-center py-4 text-sm"><i class="fas fa-times-circle mr-2"></i> Gagal memuat layanan pengiriman. Coba lagi.</p>';
        }
        shippingOptionsDiv.innerHTML = html;
    })
    .catch(error => {
        console.error('Error calculating shipping:', error);
        let errorMessage = 'Gagal menghitung ongkir.';
        if (error.response && error.response.data && error.response.data.error) {
        errorMessage = 'Error API: ' + error.response.data.error;
        }
        shippingOptionsDiv.innerHTML = `<p class="text-red-600 text-center py-4 text-sm"><i class="fas fa-times-circle mr-2"></i> ${errorMessage}</p>`;
    });
    }

    // --- Update Total ---
    function updateTotal(shippingOptionValue) {
    const parts = shippingOptionValue.split('|');
    if (parts.length === 2 && !isNaN(parseInt(parts[1]))) {
        selectedShippingCost = parseInt(parts[1]);
        const total = totalItemPrice + selectedShippingCost;

        document.getElementById('shipping-cost-summary').innerText = `Rp ${selectedShippingCost.toLocaleString('id-ID')}`;
        document.getElementById('total-cost-summary').innerText = `Rp ${total.toLocaleString('id-ID')}`;

        shippingOptionHiddenInput.value = shippingOptionValue;
        paymentButton.disabled = false;
    } else {
        console.error("Invalid shipping option value:", shippingOptionValue);
        paymentButton.disabled = true;
        shippingOptionHiddenInput.value = '';
    }
    }

    // --- Prepare & Initiate Payment (AJAX) ---
    async function prepareAndSubmitForm(event) {
        
        const detailAddress = shippingAddressDetailTextarea.value.trim();
        const areaDestinationName = destinationSearchInput.value;
        const postal = postalCodeInput.value;
        const selectedOption = shippingOptionHiddenInput.value;
        
        // 1. Validasi
        if (!document.getElementById('destination_area_id').value) { showNotification('Silakan pilih area tujuan pengiriman.', 'Validasi Gagal'); return; }
        if (!detailAddress) { showNotification('Silakan isi detail alamat Anda.', 'Validasi Gagal'); shippingAddressDetailTextarea.focus(); return; }
        if (!selectedOption) { showNotification('Silakan pilih layanan pengiriman.', 'Validasi Gagal'); return; }
        
        // Gabungkan alamat
        shippingAddressCombinedInput.value = `${detailAddress}, ${areaDestinationName} (${postal})`;
        
        // 2. Tampilkan loading dan disable tombol
        paymentButton.disabled = true;
        paymentButton.textContent = 'Memproses...';
        loadingSpinner.classList.remove('hidden');
        
        // Mengambil data form
        const formData = new FormData(checkoutForm);
        const data = Object.fromEntries(formData.entries());

        // 3. Panggil API untuk mendapatkan Snap Token
        try {
            const response = await axios.post('{{ route("order.place") }}', data, {
                headers: { 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                }
            });

            const snapToken = response.data.snap_token;
            const orderId = response.data.order_id; // ID Order Laravel
            
            // 4. Sembunyikan loading dan Tampilkan Midtrans Snap Pop-up
            loadingSpinner.classList.add('hidden');
            
            if (typeof snap !== 'undefined' && snapToken) {
                window.snap.pay(snapToken, {
                    onSuccess: function(result) {
                        console.log('Payment Success:', result);
                        window.location.href = `{{ url('/order/success') }}/${orderId}`;
                    },
                    onPending: function(result) {
                        console.log('Payment Pending:', result);
                        window.location.href = `{{ url('/order/success') }}/${orderId}`; 
                    },
                    onError: function(result) {
                        console.error('Payment Error:', result);
                        showNotification('Pembayaran gagal! Silakan coba lagi.', 'Pembayaran Gagal');
                        paymentButton.disabled = false;
                        paymentButton.textContent = 'Proses Pembayaran';
                    },
                    onClose: function() {
                        console.log('Payment popup closed without finishing the payment.');
                        paymentButton.disabled = false;
                        paymentButton.textContent = 'Proses Pembayaran';
                    }
                });
            } else {
                showNotification('Gagal memuat Midtrans Snap. Pastikan script dan Client Key sudah benar.', 'Error Konfigurasi');
                paymentButton.disabled = false;
                paymentButton.textContent = 'Proses Pembayaran';
            }

        } catch (error) {
            console.error('Error getting Snap Token:', error.response ? error.response.data : error.message);
            showNotification('Terjadi kesalahan saat memulai pembayaran. Cek log konsol untuk detail.', 'Error API Server');
            
            paymentButton.disabled = false;
            paymentButton.textContent = 'Proses Pembayaran';
            loadingSpinner.classList.add('hidden');
        }
    }

    // --- Event Listener untuk Tombol Pembayaran ---
    paymentButton.addEventListener('click', function(event) {
        prepareAndSubmitForm(event);
    });
    
    // --- DOM Loaded ---
    document.addEventListener('DOMContentLoaded', () => {
        // Tampilkan alamat asal default saat load
        document.getElementById('selected_origin_area').classList.remove('hidden');
    });
</script>
@endpush
@endsection
