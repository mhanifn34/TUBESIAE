<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Commora - Find Your People! - @yield('title', 'Beranda')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    @include('components.navbar')
    
    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>

    <!-- Sidebar (tersembunyi default) -->
    <div id="sidebar"
        class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 z-50">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="font-bold text-lg">Menu</h2>
            <button onclick="toggleSidebar(false)" class="text-gray-500 hover:text-gray-700">✕</button>
        </div>

        <nav class="p-6 space-y-2">
            <a href="/messages" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 group">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <span class="font-medium">Obrolan</span>
            </a>
            <a href="/list" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 group">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span class="font-medium">List Community</span>
            </a>
            <a href="/bookmark" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 group">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                </svg>
                <span class="font-medium">Bookmark</span>
            </a>
            <a href="/ads" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 group">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                <span class="font-medium">Promosi</span>
            </a>

            <a href="/settings" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 group">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="font-medium">Pengaturan</span>
            </a>
            
            <a href="/settings" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 group">
            <div class="pt-2 mt-2 border-t border-gray-200">
                <a href="/logout" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg transition-all duration-200 hover:bg-red-50 hover:text-red-600 group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="font-medium">Keluar</span>
                </a>
            </div>
        </nav>
    </div>

    <!-- Overlay (latar belakang gelap saat sidebar muncul) -->
    <div id="overlay"
        class="fixed inset-0 bg-black bg-opacity-40 hidden z-40"
        onclick="toggleSidebar(false)">
    </div>

    {{-- Create Post --}}
    @include('components.create-post-modal')

    <script>
        function openCreatePostModal() {
            document.getElementById('createPostModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeCreatePostModal() {
            document.getElementById('createPostModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });
            
            // Remove active state from all tab buttons
            document.querySelectorAll('[id^="tab"]').forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-500');
                btn.classList.add('text-gray-600');
            });
            
            // Show selected tab
            const tabMap = {
                'teks': 'contentTeks',
                'gambar': 'contentGambar',
                'video': 'contentVideo',
                'jualbeli': 'contentJualBeli',
                'draf': 'contentDraf'
            };
            
            document.getElementById(tabMap[tabName]).classList.remove('hidden');
            
            // Add active state to clicked tab button
            const btnId = 'tab' + tabName.charAt(0).toUpperCase() + tabName.slice(1).replace('beli', 'Beli');
            const activeBtn = document.getElementById(btnId);
            if (activeBtn) {
                activeBtn.classList.add('border-blue-500', 'text-blue-500');
                activeBtn.classList.remove('text-gray-600');
            }
        }

        // Close modal when clicking outside
        document.getElementById('createPostModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeCreatePostModal();
            }
        });
    </script>

    {{-- Modal Keranjang --}}
    @include('components.cart-modal')

    <script>
    function toggleCartModal(show) {
      const modal = document.getElementById('cartModal');
      modal.classList.toggle('hidden', !show);
      if (show) modal.classList.add('flex');
      else modal.classList.remove('flex');
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

    <script>
    function toggleSidebar(show) {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        if (show) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    }
    </script>

<!-- Tambahkan di paling bawah sebelum </body> -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@stack('scripts')

</body>
</html>
