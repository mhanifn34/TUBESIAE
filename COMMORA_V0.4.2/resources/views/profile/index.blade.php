
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 p-4 rounded-xl shadow-sm animate-slide-down">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 text-red-800 p-4 rounded-xl shadow-sm animate-slide-down">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Sidebar - Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-20">
                    <!-- Cover -->
                    <div class="h-32 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 relative">
                        <div class="absolute inset-0 bg-pattern opacity-20"></div>
                    </div>
                    
                    <!-- Avatar -->
                    <div class="px-6 pb-6">
                        <div class="flex justify-center -mt-16 mb-4">
                            <div class="relative">
                                <div class="w-28 h-28 rounded-full border-4 border-white shadow-xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center overflow-hidden">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-user text-4xl text-blue-500"></i>
                                    @endif
                                </div>
                                <div class="absolute bottom-1 right-1 w-7 h-7 bg-green-500 rounded-full border-3 border-white shadow-lg pulse-ring"></div>
                            </div>
                        </div>

                        <!-- User Info -->
                        <div class="text-center mb-6">
                            <div class="flex items-center justify-center gap-2 mb-2">
                                <h2 class="text-2xl font-bold text-gray-800">{{ Auth::user()->name }}</h2>
                                <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">NEWBIE</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-3">@<span class="font-medium">{{ Auth::user()->username ?? 'user' }}</span></p>
                            <p class="text-sm text-gray-600 leading-relaxed px-4">
                                {{ Auth::user()->bio ?? 'Belum ada bio. Ceritakan tentang dirimu!' }}
                            </p>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-2 mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-600">{{ $stats['pengikut'] ?? 0 }}</p>
                                <p class="text-xs text-gray-600 font-medium">Pengikut</p>
                            </div>
                            <div class="text-center border-x border-blue-200">
                                <p class="text-2xl font-bold text-blue-600">{{ $stats['mengikuti'] ?? 0 }}</p>
                                <p class="text-xs text-gray-600 font-medium">Mengikuti</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-600">{{ $stats['posts'] ?? 0 }}</p>
                                <p class="text-xs text-gray-600 font-medium">Postingan</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2">
                            <a href="{{ route('profile.edit') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl font-medium hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-user-edit"></i>
                                <span>Edit Profil</span>
                            </a>
                            
                            <button onclick="toggleSettingsModal()" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white border-2 border-gray-200 text-gray-700 rounded-xl font-medium hover:bg-gray-50 hover:border-gray-300 transition-all duration-300">
                                <i class="fas fa-cog"></i>
                                <span>Pengaturan</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content - Posts & Activity -->
            <div class="lg:col-span-2">
                <!-- Detail Info Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Informasi Detail</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                <i class="fas fa-id-card text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">ID Pengguna</p>
                                <p class="text-sm font-bold text-gray-800">{{ Auth::user()->id }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                <i class="fas fa-calendar-alt text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Tanggal Bergabung</p>
                                <p class="text-sm font-bold text-gray-800">{{ Auth::user()->created_at ? Auth::user()->created_at->format('d M Y') : 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg md:col-span-2">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                <i class="fas fa-envelope text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Email</p>
                                <p class="text-sm font-bold text-gray-800">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="flex border-b border-gray-200 overflow-x-auto">
                        <button onclick="showTab('terbaru')" class="tab-btn flex-1 min-w-fit px-6 py-4 text-center font-medium transition-all duration-300" id="tab-terbaru">
                            <i class="fas fa-clock mr-2"></i>
                            <span>Terbaru</span>
                        </button>
                        <button onclick="showTab('terlama')" class="tab-btn flex-1 min-w-fit px-6 py-4 text-center font-medium transition-all duration-300" id="tab-terlama">
                            <i class="fas fa-history mr-2"></i>
                            <span>Terlama</span>
                        </button>
                        <button onclick="showTab('populer')" class="tab-btn flex-1 min-w-fit px-6 py-4 text-center font-medium transition-all duration-300" id="tab-populer">
                            <i class="fas fa-fire mr-2"></i>
                            <span>Populer</span>
                        </button>
                    </div>
                    
                    <!-- Tab Content -->
                    <div class="p-6">
                        <!-- Terbaru Tab -->
                        <div id="content-terbaru" class="tab-content">
                            @forelse($posts ?? [] as $post)
                            <div class="bg-gradient-to-r from-white to-blue-50 rounded-xl p-5 mb-4 border border-gray-100 hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-newspaper text-white"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-xs text-gray-500 font-medium">
                                                <i class="far fa-clock mr-1"></i>
                                                {{ $post->created_at->diffForHumans() }}
                                            </span>
                                            <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full font-semibold">
                                                {{ $post->category ?? 'Post' }}
                                            </span>
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-800 mb-2 hover:text-blue-600 cursor-pointer">
                                            {{ $post->title ?? 'Post tanpa judul' }}
                                        </h4>
                                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                            {{ Str::limit($post->content, 150) }}
                                        </p>
                                        <div class="flex items-center gap-6 text-sm">
                                            <button class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition-colors">
                                                <i class="far fa-thumbs-up"></i>
                                                <span class="font-medium">{{ $post->likes_count ?? 0 }}</span>
                                            </button>
                                            <button class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition-colors">
                                                <i class="far fa-comment"></i>
                                                <span class="font-medium">{{ $post->comments_count ?? 0 }}</span>
                                            </button>
                                            <button class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition-colors">
                                                <i class="fas fa-share-alt"></i>
                                                <span class="font-medium">Bagikan</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-16">
                                <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-inbox text-5xl text-blue-400"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Postingan</h3>
                                <p class="text-gray-500 mb-6">Mulai berbagi cerita dan pengalaman Anda!</p>
                                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl font-medium hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <i class="fas fa-plus"></i>
                                    <span>Buat Postingan Pertama</span>
                                </a>
                            </div>
                            @endforelse
                        </div>
                        
                        <!-- Terlama Tab -->
                        <div id="content-terlama" class="tab-content hidden">
                            <div class="text-center py-16">
                                <i class="fas fa-history text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">Postingan terlama akan ditampilkan di sini</p>
                            </div>
                        </div>
                        
                        <!-- Populer Tab -->
                        <div id="content-populer" class="tab-content hidden">
                            <div class="text-center py-16">
                                <i class="fas fa-fire text-6xl text-orange-300 mb-4"></i>
                                <p class="text-gray-500">Postingan populer akan ditampilkan di sini</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Settings Modal -->
<div id="settingsModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 animate-fade-in">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 animate-scale-in">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-cog text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Pengaturan Akun</h3>
            </div>
            <button onclick="toggleSettingsModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 space-y-3">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 p-4 rounded-xl hover:bg-blue-50 transition-all duration-300 group">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                    <i class="fas fa-user-edit text-blue-600 text-lg"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">Edit Profil</p>
                    <p class="text-sm text-gray-500">Ubah foto dan informasi profil</p>
                </div>
                <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-600 transition-colors"></i>
            </a>

            <button onclick="showChangePassword()" class="w-full flex items-center gap-4 p-4 rounded-xl hover:bg-blue-50 transition-all duration-300 group">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                    <i class="fas fa-key text-green-600 text-lg"></i>
                </div>
                <div class="flex-1 text-left">
                    <p class="font-semibold text-gray-800 group-hover:text-green-600 transition-colors">Ganti Password</p>
                    <p class="text-sm text-gray-500">Perbarui kata sandi Anda</p>
                </div>
                <i class="fas fa-chevron-right text-gray-400 group-hover:text-green-600 transition-colors"></i>
            </button>

            <button onclick="showPrivacySettings()" class="w-full flex items-center gap-4 p-4 rounded-xl hover:bg-blue-50 transition-all duration-300 group">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                    <i class="fas fa-shield-alt text-purple-600 text-lg"></i>
                </div>
                <div class="flex-1 text-left">
                    <p class="font-semibold text-gray-800 group-hover:text-purple-600 transition-colors">Privasi & Keamanan</p>
                    <p class="text-sm text-gray-500">Kelola pengaturan privasi</p>
                </div>
                <i class="fas fa-chevron-right text-gray-400 group-hover:text-purple-600 transition-colors"></i>
            </button>

            <div class="border-t border-gray-200 pt-3 mt-3">
                <form action="{{ route('logout') }}" method="POST" onsubmit="return confirmLogout()">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-4 p-4 rounded-xl hover:bg-red-50 transition-all duration-300 group">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition-colors">
                            <i class="fas fa-sign-out-alt text-red-600 text-lg"></i>
                        </div>
                        <div class="flex-1 text-left">
                            <p class="font-semibold text-red-600 group-hover:text-red-700 transition-colors">Keluar</p>
                            <p class="text-sm text-gray-500">Logout dari akun Anda</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-red-600 transition-colors"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.bg-pattern {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.6'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.pulse-ring {
    animation: pulse-ring 2s ease-out infinite;
}

@keyframes pulse-ring {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
    }
    50% {
        box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
    }
}

.tab-btn {
    color: #6b7280;
    border-bottom: 3px solid transparent;
    background: transparent;
}

.tab-btn.active {
    color: #3b82f6;
    border-bottom-color: #3b82f6;
    background: linear-gradient(to bottom, #eff6ff, transparent);
}

.tab-btn:hover:not(.active) {
    background: #f9fafb;
    color: #3b82f6;
}

@keyframes slide-down {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes scale-in {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes fade-in {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.animate-slide-down {
    animation: slide-down 0.5s ease-out;
}

.animate-scale-in {
    animation: scale-in 0.3s ease-out;
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active class to selected tab
    document.getElementById('tab-' + tabName).classList.add('active');
}

function toggleSettingsModal() {
    const modal = document.getElementById('settingsModal');
    modal.classList.toggle('hidden');
}

function confirmLogout() {
    return confirm('Apakah Anda yakin ingin keluar?');
}

function showChangePassword() {
    alert('Fitur ganti password akan segera hadir!');
    toggleSettingsModal();
}

function showPrivacySettings() {
    alert('Pengaturan privasi akan segera hadir!');
    toggleSettingsModal();
}

// Close modal when clicking outside
document.getElementById('settingsModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        toggleSettingsModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('settingsModal');
        if (!modal.classList.contains('hidden')) {
            toggleSettingsModal();
        }
    }
});

// Initialize first tab as active
document.addEventListener('DOMContentLoaded', function() {
    showTab('terbaru');
});
</script>
@endsection