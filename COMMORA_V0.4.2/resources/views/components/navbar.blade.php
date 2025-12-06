<nav class="bg-white border-b sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <!-- Left: Menu & Logo -->
            <div class="flex items-center space-x-4">
                <!-- Tombol Sidebar -->
                <button onclick="toggleSidebar(true)" class="text-gray-600 hover:text-gray-900">
    <i class="fas fa-bars text-xl"></i>
</button>

                <a href="/" class="text-2xl font-bold text-gray-900">COMMORA</a>
            </div>

            <!-- Center: Search -->
            <div class="flex-1 max-w-md mx-8">
                <div class="relative">
                    <input 
                        type="text" 
                        placeholder="Cari" 
                        class="w-full px-4 py-2 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-search absolute right-4 top-3 text-gray-400"></i>
                </div>
            </div>

            <!-- Right: Actions -->
            <div class="flex items-center space-x-4">
                <button onclick="openCreatePostModal()" class="px-4 py-2 border rounded-md hover:bg-gray-50">
                    <i class="fas fa-plus mr-2"></i>Post
                </button>

                <!-- Buat Komunitas Button -->
                <a href="{{ route('communities.create') }}" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                    <i class="fas fa-users mr-2"></i>
                    <span>Buat Komunitas</span>
                </a>

                <a href="{{ route('cart.index') }}" class="p-2 text-gray-600 hover:text-gray-900">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </a>

                <button class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-bell text-xl"></i>
                </button>

                <button onclick="window.location.href='/profile'" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-user-circle text-xl"></i>
                </button>
            </div>
        </div>
    </div>
</nav>
