<div id="sidebar" class="fixed top-0 left-0 w-64 h-full bg-white shadow-lg z-[110] transform -translate-x-full transition-transform duration-300 ease-in-out border-r">
    <div class="p-4 border-b flex justify-between items-center h-16"> {{-- Samakan tinggi dgn navbar --}}
        <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-900">COMMORA</a>
        {{-- Tombol close di dalam sidebar? (optional) --}}
        {{-- <button onclick="closeSidebar()" class="text-gray-500 hover:text-gray-700">&times;</button> --}}
    </div>
    <nav class="mt-4 flex flex-col justify-between h-[calc(100vh-4rem)] pb-4"> {{-- Flex column, tinggi sisa --}}
        {{-- Menu Utama --}}
        <div class="px-2 space-y-1">
             <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm rounded-md">
                <i class="fas fa-home w-5 mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('home') ? 'text-blue-600' : '' }}"></i> Beranda
            </a>
             <a href="{{ route('communities.index') }}" class="{{ request()->routeIs('communities.index') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm rounded-md">
                <i class="fas fa-compass w-5 mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('communities.index') ? 'text-blue-600' : '' }}"></i> Eksplor Komunitas
            </a>
            @auth
             <a href="{{ route('profile.show', Auth::user()->name) }}" class="{{ request()->routeIs('profile.show') && request()->user->is(Auth::user()) ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm rounded-md">
                <i class="fas fa-user w-5 mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('profile.show') && request()->user->is(Auth::user()) ? 'text-blue-600' : '' }}"></i> Profil Saya
            </a>
             <a href="{{ route('posts.drafts') }}" class="{{ request()->routeIs('posts.drafts') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm rounded-md">
                 <i class="fas fa-edit w-5 mr-3 text-gray-400 group-hover:text-gray-500 {{ request()->routeIs('posts.drafts') ? 'text-blue-600' : '' }}"></i> Draf Postingan
            </a>
            @endauth
             {{-- Tambahkan link lain jika perlu --}}
        </div>

        {{-- Menu Bawah (Logout) --}}
        <div class="px-2">
             @auth
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();"
                   class="text-gray-600 hover:bg-red-50 hover:text-red-700 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                   <i class="fas fa-sign-out-alt w-5 mr-3 text-gray-400 group-hover:text-red-600"></i> Keluar
                </a>
                 <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
             @else
                 <a href="{{ route('login') }}" class="text-gray-600 hover:bg-gray-100 hover:text-gray-900 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                   <i class="fas fa-sign-in-alt w-5 mr-3 text-gray-400 group-hover:text-gray-500"></i> Login / Register
                </a>
             @endauth
        </div>
    </nav>
</div>
<div id="sidebar-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[105]" onclick="closeSidebar()"></div>