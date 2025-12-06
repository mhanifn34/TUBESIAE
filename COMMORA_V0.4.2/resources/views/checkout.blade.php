@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 md:p-8 rounded-lg shadow-xl">
    <h1 class="text-2xl md:text-3xl font-bold mb-6 border-b pb-4 text-gray-800">Checkout</h1>

    @if(isset($post))
        <div class="border rounded-lg p-4 mb-8 bg-gray-50 shadow-sm">
            <p class="text-sm font-semibold text-gray-700 mb-3">Produk yang Dipesan</p>
            <div class="flex items-start space-x-4">
                {{-- PERBAIKAN GAMBAR: Pastikan $post->image ada --}}
                @if($post->image)
                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-20 h-20 object-cover rounded-md border flex-shrink-0">
                @else
                    {{-- Placeholder jika tidak ada gambar --}}
                    <div class="w-20 h-20 bg-gray-200 rounded-md flex items-center justify-center text-gray-400 flex-shrink-0">
                        <i class="fas fa-image fa-2x"></i>
                    </div>
                @endif
                <div class="flex-1 min-w-0"> {{-- min-w-0 untuk text wrap --}}
                    <h2 class="text-lg font-semibold text-gray-900 truncate">{{ $post->title }}</h2> {{-- Truncate jika panjang --}}
                    <p class="text-sm text-gray-600">
                        Penjual: <span class="font-medium">{{ $post->user->name ?? 'User' }}</span>
                    </p>
                    <p class="text-sm text-gray-600">
                        Kondisi: <span class="font-medium">{{ ucfirst($post->condition) }}</span>
                    </p>
                    <p class="text-xl font-bold text-green-700 mt-2">Rp {{ number_format($post->price, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        {{-- Akhir Perbaikan UI Atas --}}


        <form action="{{ route('order.place') }}" method="POST" id="checkout-form">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <input type="hidden" name="origin_area_id" id="origin_area_id" value="IDN.JB.BA.BANDUNG.KOTA BANDUNG.BANDUNG KIDUL"> {{-- GANTI DENGAN ID AREA ASAL VALID ANDA --}}
            <input type="hidden" name="destination_area_id" id="destination_area_id">
            <input type="hidden" name="shipping_address_combined" id="shipping_address_combined"> {{-- Hidden input alamat gabungan --}}
            <input type="hidden" name="shipping_option" id="shipping_option_hidden">

            <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
                <div class="md:col-span-3">
                    <section class="mb-8">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800 border-l-4 border-indigo-500 pl-3">1. Alamat Asal Pengiriman</h3>
                         <div class="space-y-4 p-5 rounded-lg border bg-indigo-50 border-indigo-100">
                            <div class="relative">
                                <label for="origin_search" class="block text-sm font-medium text-gray-700">Cari Kecamatan/Kelurahan Asal</label>
                                <input type="text" id="origin_search" placeholder="Ketik min. 3 huruf..." class="w-full mt-1 p-2 border rounded-md" autocomplete="off" required>
                                <div id="origin_results" class="absolute z-30 w-full bg-white border rounded-md mt-1 max-h-48 overflow-y-auto hidden shadow-lg"></div>
                                <p id="selected_origin_area" class="text-sm text-indigo-700 mt-1 font-medium hidden"></p>
                            </div>
                        </div>
                    </section>

                    <section class="mb-8">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800 border-l-4 border-blue-500 pl-3">2. Alamat Tujuan Pengiriman</h3>
                        <div class="space-y-4 p-5 rounded-lg border bg-blue-50 border-blue-100">
                           <div class="relative">
                                <label for="destination_search" class="block text-sm font-medium text-gray-700">Cari Kecamatan/Kelurahan Tujuan</label>
                                <input type="text" id="destination_search" placeholder="Ketik min. 3 huruf..." class="w-full mt-1 p-2 border rounded-md" autocomplete="off" required>
                                <div id="destination_results" class="absolute z-20 w-full bg-white border rounded-md mt-1 max-h-48 overflow-y-auto hidden shadow-lg"></div>
                                <p id="selected_destination_area" class="text-sm text-blue-700 mt-1 font-medium hidden"></p>
                            </div>
                             <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700">Kode Pos</label>
                                <input type="number" name="postal_code_display" id="postal_code" placeholder="Kode Pos (otomatis)" class="w-full mt-1 p-2 border rounded-md bg-gray-100" required readonly>
                            </div>
                            <div>
                                <label for="shipping_address_detail" class="block text-sm font-medium text-gray-700">Detail Alamat (Nama Jalan, No Rumah, dll.)</label>
                                <textarea name="shipping_address_detail" id="shipping_address_detail" rows="3" placeholder="Nama Jalan, Nomor Rumah, RT/RW..." class="w-full mt-1 p-2 border rounded-md" required></textarea>
                            </div>
                        </div>
                    </section>

                    <section>
                         <h3 class="text-xl font-semibold mb-4 text-gray-800 border-l-4 border-green-500 pl-3">3. Opsi Pengiriman</h3>
                         <div class="space-y-3 p-5 rounded-lg border bg-green-50 border-green-100">
                             <label class="block text-sm font-medium text-gray-700 mb-3">Pilih Kurir:</label>
                             <div id="courier-selection" class="grid grid-cols-3 gap-3">
                                {{-- Tombol Kurir --}}
                                <button type="button" data-courier="jne" class="courier-btn p-2 border rounded-md text-sm">JNE</button>
                                <button type="button" data-courier="tiki" class="courier-btn p-2 border rounded-md text-sm">TIKI</button>
                                <button type="button" data-courier="pos" class="courier-btn p-2 border rounded-md text-sm">POS</button>
                                <button type="button" data-courier="sicepat" class="courier-btn p-2 border rounded-md text-sm">SiCepat</button>
                                <button type="button" data-courier="jnt" class="courier-btn p-2 border rounded-md text-sm">J&T</button>
                                <button type="button" data-courier="anteraja" class="courier-btn p-2 border rounded-md text-sm">AnterAja</button>
                             </div>
                              <div id="courier-alert" class="hidden mt-3 text-sm text-yellow-700 bg-yellow-100 p-3 rounded-md">
                                 Pilih area asal dan tujuan terlebih dahulu.
                             </div>
                            <div id="shipping-options" class="mt-5 border-t border-green-200 pt-4">
                                <p class="text-sm text-gray-500 text-center py-4">Pilih area & kurir untuk lihat layanan.</p>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="md:col-span-2">
                    <div class="sticky top-24">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800 border-l-4 border-gray-500 pl-3">4. Ringkasan Pesanan</h3>
                        <div class="space-y-3 p-5 bg-gray-100 rounded-lg border border-gray-200">
                             <div class="flex justify-between text-gray-700"><span>Subtotal</span><span id="subtotal-summary">Rp {{ number_format($post->price, 0, ',', '.') }}</span></div>
                            <div class="flex justify-between text-gray-700"><span>Ongkir</span><span id="shipping-cost-summary">Rp 0</span></div>
                            <div class="border-t border-gray-300 my-3"></div>
                            <div class="flex justify-between font-bold text-lg text-gray-900"><span>Total</span><span id="total-cost-summary">Rp {{ number_format($post->price, 0, ',', '.') }}</span></div>
                        </div>
                         {{-- Tombol submit form (diaktifkan setelah ongkir dipilih) --}}
                        <button type="button" onclick="prepareAndSubmitForm()" class="w-full bg-blue-600 text-white font-bold py-3 mt-6 rounded-md hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition duration-200" id="payment-button" disabled>
                            Proses Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </form>

    @else
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">Error! Data produk tidak ditemukan.</div>
    @endif
</div>

@push('scripts')
@if(isset($post))
<script>
    // --- State Variables ---
    let selectedShippingCost = 0;
    const itemPrice = {{ $post->price }};
    let selectedOriginAreaId = document.getElementById('origin_area_id').value; // Ambil nilai awal jika ada
    let selectedDestinationAreaId = null;
    let selectedAreaNameOrigin = '';
    let selectedAreaNameDestination = '';
    let searchTimeoutOrigin;
    let searchTimeoutDestination;

    // --- DOM Elements ---
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

     // --- Autocomplete Function (Reusable) ---
    function setupAutocomplete(searchInput, resultsDiv, areaIdInput, selectedAreaP, areaNameVarSetter, isOrigin = false) {
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
                                resultsHtml += `<div class="p-2 hover:bg-gray-100 cursor-pointer text-sm"
                                                     data-area-id="${area.id}"
                                                     data-area-name="${safeName}"
                                                     data-postal-code="${area.postal_code ?? ''}"
                                                     onclick="selectArea(this, '${searchInput.id}')">
                                                    ${safeName}
                                                </div>`;
                            });
                        } else {
                            resultsHtml = '<div class="p-2 text-sm text-gray-500">Area tidak ditemukan</div>';
                        }
                        resultsDiv.innerHTML = resultsHtml;
                    })
                    .catch(error => { console.error('Error searching area:', error); resultsDiv.innerHTML = '<div class="p-2 text-sm text-red-500">Gagal mencari area</div>'; });
            }, 300); // Kurangi delay

            if (isOrigin) searchTimeoutOrigin = timeoutVar; else searchTimeoutDestination = timeoutVar;
        });
    }

    // --- Initialize Autocomplete ---
    setupAutocomplete(originSearchInput, originResultsDiv, originAreaIdInput, selectedOriginAreaP, (name) => selectedAreaNameOrigin = name, true); // Tandai sebagai origin
    setupAutocomplete(destinationSearchInput, destinationResultsDiv, destinationAreaIdInput, selectedDestinationAreaP, (name) => selectedAreaNameDestination = name, false); // Tandai bukan origin

    // --- Select Area Function ---
    function selectArea(element, inputId) {
        const areaId = element.dataset.areaId;
        const areaName = element.dataset.areaName;
        const postalCode = element.dataset.postalCode;

        if (inputId === 'origin_search') {
            originSearchInput.value = areaName;
            originAreaIdInput.value = areaId; // Simpan ID Area Origin
            selectedOriginAreaP.textContent = `Asal: ${areaName}`;
            selectedOriginAreaP.classList.remove('hidden');
            originResultsDiv.classList.add('hidden');
            selectedAreaNameOrigin = areaName;
            selectedOriginAreaId = areaId; // Simpan ke variabel JS juga
        } else {
            destinationSearchInput.value = areaName;
            destinationAreaIdInput.value = areaId; // Simpan ID Area Tujuan
            postalCodeInput.value = postalCode; // Isi kode pos
            selectedDestinationAreaP.textContent = `Tujuan: ${areaName}`;
            selectedDestinationAreaP.classList.remove('hidden');
            destinationResultsDiv.classList.add('hidden');
            selectedAreaNameDestination = areaName;
             selectedDestinationAreaId = areaId; // Simpan ke variabel JS juga
        }

        resetShippingSelection();
        courierAlert.classList.add('hidden');
    }

     // --- Reset Shipping Selection ---
     function resetShippingSelection() {
         shippingOptionsDiv.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">Pilih kurir untuk melihat layanan.</p>';
         paymentButton.disabled = true;
         resetSummary();
         document.querySelectorAll('.courier-btn').forEach(btn => {
             btn.classList.remove('bg-blue-500', 'text-white', 'ring-2', 'ring-blue-300');
             btn.classList.add('hover:bg-gray-100');
         });
         shippingOptionHiddenInput.value = '';
     }


    // --- Courier Button Listener ---
    document.querySelectorAll('.courier-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            const courierType = this.dataset.courier;
            // PERBAIKAN: Gunakan variabel JS untuk cek
            if (!selectedOriginAreaId || !selectedDestinationAreaId) {
                courierAlert.classList.remove('hidden'); return;
            }
            courierAlert.classList.add('hidden');
            // UI Feedback
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
        // PERBAIKAN: Ambil ID Area dari variabel JS
        const originId = selectedOriginAreaId;
        const destinationId = selectedDestinationAreaId;
        const weight = {{ $post->weight_in_grams ?? 1000 }};

        shippingOptionsDiv.innerHTML = '<p class="text-center py-4 text-gray-600 animate-pulse">Menghitung ongkir...</p>';
        paymentButton.disabled = true;
        resetSummary();

        axios.post('{{ route("shipping.calculate") }}', {
            origin_area_id: originId,       // Kirim ID Area Asal
            destination_area_id: destinationId, // Kirim ID Area Tujuan
            weight: weight,
            courier_type: courierType
        })
        .then(response => {
             let html = '';
            // PERBAIKAN: Validasi response.data lebih ketat
            if (response.data && Array.isArray(response.data) && response.data.length > 0) {
                 html += '<h4 class="font-semibold text-gray-700 mb-2 mt-4 text-sm">Pilih Layanan:</h4>';
                 response.data.forEach(option => {
                    if (option.service && option.cost && Array.isArray(option.cost) && option.cost[0] && option.courier) {
                        const costDetail = option.cost[0];
                        const serviceValue = `${option.courier.toUpperCase()} - ${option.service}|${costDetail.value}`;
                        const etd = costDetail.etd ? `Estimasi: ${costDetail.etd}` : '';
                        html += `
                            <label class="block border rounded-md mb-2 hover:bg-gray-50 cursor-pointer transition duration-150 has-[:checked]:bg-blue-50 has-[:checked]:border-blue-300">
                                <div class="flex items-center justify-between p-3">
                                    <div><span class="font-semibold text-sm">${option.courier.toUpperCase()} - ${option.service}</span><p class="text-xs text-gray-500">${etd}</p></div>
                                    <div class="text-right flex items-center"><span class="font-semibold text-sm mr-3">Rp ${costDetail.value.toLocaleString('id-ID')}</span><input type="radio" name="shipping_option_radio" value="${serviceValue}" onchange="updateTotal(this.value)" class="form-radio h-4 w-4 text-blue-600 focus:ring-blue-500" required></div>
                                </div>
                            </label>`;
                    } else { console.warn("Skipping invalid shipping option:", option); }
                });
                 if (html === '<h4 class="font-semibold text-gray-700 mb-2 mt-4 text-sm">Pilih Layanan:</h4>') {
                     html = '<p class="text-yellow-600 text-center py-4 text-sm">Tidak ada layanan yang cocok dari kurir ini.</p>';
                 }
            } else {
                 html = '<p class="text-yellow-600 text-center py-4 text-sm">Tidak ada layanan ditemukan untuk kurir/area ini.</p>';
            }
            shippingOptionsDiv.innerHTML = html;
        })
        .catch(error => { /* ... error handling ... */ });
    }

    // --- Reset Summary ---
    function resetSummary() { /* ... kode resetSummary ... */ }

    // --- Update Total ---
    function updateTotal(shippingOptionValue) {
        // ... (Kode updateTotal tetap sama, pastikan radio button name="shipping_option_radio") ...
        const parts = shippingOptionValue.split('|');
        if (parts.length === 2 && !isNaN(parseInt(parts[1]))) {
            selectedShippingCost = parseInt(parts[1]);
            const total = itemPrice + selectedShippingCost;
            document.getElementById('shipping-cost-summary').innerText = `Rp ${selectedShippingCost.toLocaleString('id-ID')}`;
            document.getElementById('total-cost-summary').innerText = `Rp ${total.toLocaleString('id-ID')}`;
            shippingOptionHiddenInput.value = shippingOptionValue; // Simpan pilihan ke hidden input 'shipping_option'
            paymentButton.disabled = false;
        } else {
             console.error("Invalid shipping option value:", shippingOptionValue);
             paymentButton.disabled = true;
             shippingOptionHiddenInput.value = '';
        }
    }

     // --- Prepare & Submit Form ---
     function prepareAndSubmitForm() {
        const detailAddress = shippingAddressDetailTextarea.value.trim();
        const areaOriginName = selectedAreaNameOrigin; // Ambil nama area
        const areaDestinationName = selectedAreaNameDestination; // Ambil nama area
        const postal = postalCodeInput.value;
        const selectedOption = shippingOptionHiddenInput.value;

        // Validasi Akhir
        if (!originAreaIdInput.value) { alert('Silakan pilih area asal pengiriman.'); return; }
        if (!destinationAreaIdInput.value) { alert('Silakan pilih area tujuan pengiriman.'); return; }
        if (!detailAddress) { alert('Silakan isi detail alamat Anda.'); shippingAddressDetailTextarea.focus(); return; }
        if (!selectedOption) { alert('Silakan pilih layanan pengiriman.'); return; }

        // Gabungkan alamat lengkap untuk backend
        shippingAddressCombinedInput.value = `${detailAddress}\n${areaDestinationName}\nKode Pos: ${postal}`;

        // Submit form
        document.getElementById('checkout-form').submit();
    }

    // --- Close Dropdown on Click Outside ---
    document.addEventListener('click', function(event) {
        // ... (kode tutup dropdown tetap sama) ...
    });

</script>
@endif
@endpush
@endsection