@extends('layouts.app')

@section('title', 'Pesan - Commora')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 py-8">
        <!-- Judul dan Tombol Pesan Baru -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Obrolan</h1>
            <button onclick="openNewMessageModal()" 
                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium text-white transition">
                <i data-lucide="message-square-plus" class="w-4 h-4"></i>
                <span>Pesan Baru</span>
            </button>
        </div>

    <!-- Search Bar -->
    <div class="px-6 py-3 border-b border-gray-200 bg-gray-50">
        <div class="flex items-center bg-white border rounded-lg px-3 py-2">
            <i data-lucide="search" class="w-4 h-4 text-gray-400 mr-2"></i>
            <input type="text" placeholder="Cari Direct Message" 
                   class="bg-transparent w-full focus:outline-none text-sm placeholder-gray-400">
        </div>
    </div>

    <!-- Tabs -->
     <div class="px-6 py-3 border-b border-gray-200 flex gap-6 bg-white">
        <button id="tab-permintaan" 
                class="font-medium text-gray-500 hover:text-blue-600 flex items-center gap-1 transition" 
                onclick="showTab('permintaan')">
            Permintaan Pesan 
            <span class="text-xs bg-blue-600 text-white px-2 py-0.5 rounded-full ml-1">2</span>
        </button> 
    </div>
    <div class="px-6 py-3 border-b border-gray-200 flex gap-6 bg-white">
        <button id="tab-obrolan" 
                class="font-medium text-blue-600 border-b-2 border-blue-600 pb-2 transition" 
                onclick="showTab('obrolan')">Pesan</button>
    </div>

    <!-- Konten Obrolan -->
    <div id="content-obrolan" class="px-6 py-10 text-center text-gray-500 bg-white">
        <i data-lucide="message-circle" class="w-12 h-12 mx-auto mb-3 text-gray-400"></i>
        <p class="text-lg font-medium">Belum ada obrolan</p>
        <p class="text-sm text-gray-400 mt-1">Mulai percakapan baru dengan teman kamu!</p>
    </div>

    <!-- Konten Permintaan Pesan -->
    <div id="content-permintaan" class="hidden px-6 py-6 space-y-4 bg-white">
        <div class="bg-gray-50 border rounded-lg p-4 hover:bg-gray-100 transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="font-medium text-gray-800">Rani Putri</p>
                    <p class="text-sm text-gray-500">“Hai, boleh kenalan?”</p>
                </div>
                <span class="text-xs bg-blue-600 text-white px-2 py-0.5 rounded-full">Baru</span>
            </div>
        </div>

        <div class="bg-gray-50 border rounded-lg p-4 hover:bg-gray-100 transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="font-medium text-gray-800">Bagas Dwi</p>
                    <p class="text-sm text-gray-500">“Halo, aku lihat kamu di event kemarin...”</p>
                </div>
                <span class="text-xs bg-blue-600 text-white px-2 py-0.5 rounded-full">Baru</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pesan Baru -->
<div id="newMessageModal" 
     class="fixed inset-0 bg-black bg-opacity-40 hidden z-50 flex items-center justify-center"
     onclick="closeOnOutsideClick(event)">
    <div class="bg-white rounded-lg w-full max-w-md p-6 shadow-lg border"
         onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Pesan Baru</h2>
            <button onclick="closeNewMessageModal()" class="text-gray-500 hover:text-gray-700">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <input type="text" placeholder="Cari pengguna..." 
               class="w-full border rounded-md px-3 py-2 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4">
        
        <button class="w-full py-2 bg-blue-600 hover:bg-blue-700 rounded-md font-medium text-white transition">
            Mulai Obrolan
        </button>
    </div>
</div>

<script>
function openNewMessageModal() {
  document.getElementById('newMessageModal').classList.remove('hidden');
}

function closeNewMessageModal() {
  document.getElementById('newMessageModal').classList.add('hidden');
}

// Tutup modal kalau klik area luar
function closeOnOutsideClick(event) {
  const modal = document.getElementById('newMessageModal');
  if (event.target === modal) {
    closeNewMessageModal();
  }
}
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    if (window.lucide) lucide.createIcons();
});

function openNewMessageModal() {
    document.getElementById('newMessageModal').classList.remove('hidden');
}
function closeNewMessageModal() {
    document.getElementById('newMessageModal').classList.add('hidden');
}

function showTab(tab) {
    const obrolanTab = document.getElementById('tab-obrolan');
    const permintaanTab = document.getElementById('tab-permintaan');
    const obrolanContent = document.getElementById('content-obrolan');
    const permintaanContent = document.getElementById('content-permintaan');

    if (tab === 'obrolan') {
        obrolanTab.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
        permintaanTab.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
        permintaanTab.classList.add('text-gray-500');
        obrolanContent.classList.remove('hidden');
        permintaanContent.classList.add('hidden');
    } else {
        permintaanTab.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
        obrolanTab.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
        obrolanTab.classList.add('text-gray-500');
        obrolanContent.classList.add('hidden');
        permintaanContent.classList.remove('hidden');
    }
}
</script>

{{-- ✅ Tambahan: perbaikan fungsional tanpa ubah tampilan --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  const btnPermintaan = document.getElementById('tab-permintaan');
  const btnObrolan = document.getElementById('tab-obrolan');
  const contentPermintaan = document.getElementById('content-permintaan');
  const contentObrolan = document.getElementById('content-obrolan');

  function activateTab(tab) {
    if (!btnPermintaan || !btnObrolan || !contentPermintaan || !contentObrolan) return;

    if (tab === 'permintaan') {
      contentPermintaan.classList.remove('hidden');
      contentObrolan.classList.add('hidden');

      btnPermintaan.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
      btnPermintaan.classList.remove('text-gray-500');

      btnObrolan.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
      btnObrolan.classList.add('text-gray-500');
    } else {
      contentObrolan.classList.remove('hidden');
      contentPermintaan.classList.add('hidden');

      btnObrolan.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
      btnObrolan.classList.remove('text-gray-500');

      btnPermintaan.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
      btnPermintaan.classList.add('text-gray-500');
    }
  }

  if (btnPermintaan) btnPermintaan.addEventListener('click', () => activateTab('permintaan'));
  if (btnObrolan) btnObrolan.addEventListener('click', () => activateTab('obrolan'));
});
</script>
@endsection
