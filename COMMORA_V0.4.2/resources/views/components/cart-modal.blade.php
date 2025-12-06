<!-- Modal Cart (Slide dari kanan) -->
<div id="cartModal" class="fixed inset-0 bg-black bg-opacity-40 hidden z-50" onclick="handleOverlayClick(event)">
  <!-- Panel kanan -->
  <div id="cartPanel" class="absolute right-0 top-0 h-full w-full max-w-sm bg-white shadow-xl flex flex-col transform translate-x-full transition-transform duration-300 ease-in-out">
    
    <!-- Header -->
    <div class="flex justify-between items-center border-b p-4">
      <h2 class="text-lg font-semibold">Keranjang Belanja</h2>
      <button onclick="toggleCartModal(false)" class="text-gray-500 hover:text-gray-700 text-xl">✕</button>
    </div>

    <!-- Tabs -->
    <div class="flex border-b">
      <button id="tabCart" onclick="showTab('cart')" class="w-1/2 py-2 text-blue-600 border-b-2 border-blue-600 font-medium">Keranjang</button>
      <button id="tabOrder" onclick="showTab('order')" class="w-1/2 py-2 text-gray-500 hover:text-blue-600">Pesanan</button>
    </div>

    <!-- Isi Konten -->
    <div class="flex-1 overflow-y-auto">
      <!-- Tab Keranjang -->
      <div id="cartContent" class="p-4">
        <p class="text-gray-600 text-center py-10">Tidak ada item di keranjang.</p>
      </div>

      <!-- Tab Pesanan -->
      <div id="orderContent" class="p-4 hidden">
        <p class="text-gray-600 text-center py-10">Belum ada pesanan.</p>
      </div>
    </div>
  </div>
</div>

<script>
function toggleCartModal(show) {
  const modal = document.getElementById('cartModal');
  const panel = document.getElementById('cartPanel');

  if (show) {
    modal.classList.remove('hidden');
    setTimeout(() => panel.classList.remove('translate-x-full'), 10);
  } else {
    panel.classList.add('translate-x-full');
    setTimeout(() => modal.classList.add('hidden'), 300);
  }
}

function handleOverlayClick(e) {
  const panel = document.getElementById('cartPanel');
  if (!panel.contains(e.target)) {
    toggleCartModal(false);
  }
}

function showTab(tab) {
  const tabCart = document.getElementById('tabCart');
  const tabOrder = document.getElementById('tabOrder');
  const cartContent = document.getElementById('cartContent');
  const orderContent = document.getElementById('orderContent');

  if (tab === 'cart') {
    tabCart.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
    tabOrder.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
    cartContent.classList.remove('hidden');
    orderContent.classList.add('hidden');
  } else {
    tabOrder.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
    tabCart.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
    orderContent.classList.remove('hidden');
    cartContent.classList.add('hidden');
  }
}
</script>
