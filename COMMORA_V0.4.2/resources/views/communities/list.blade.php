@extends('layouts.app')

@section('title', 'Komunitas Saya - Commora')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-5xl mx-auto px-4">
        <!-- Judul Halaman -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Komunitas yang Kamu Ikuti</h1>
            <a href="{{ route('community.list') }}" 
               class="text-sm font-medium text-blue-600 hover:underline">
                + Gabung Komunitas Lain
            </a>
        </div>

        <!-- Notifikasi -->
        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded-md">
                {{ session('success') }}
            </div>
        @elseif (session('info'))
            <div class="mb-4 bg-blue-100 border border-blue-300 text-blue-800 px-4 py-2 rounded-md">
                {{ session('info') }}
            </div>
        @elseif (session('error'))
            <div class="mb-4 bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        <!-- List Komunitas -->
        @if ($joinedCommunities->count() > 0)
            <div class="grid md:grid-cols-2 gap-6">
                @foreach ($joinedCommunities as $community)
                    <div class="bg-white border rounded-xl shadow-sm hover:shadow-md transition-all duration-200 p-5">
                        <h2 class="text-lg font-semibold text-gray-800 mb-2">
                            {{ $community->name }}
                        </h2>
                        <p class="text-sm text-gray-600 mb-4">
                            {{ $community->description ?? 'Tidak ada deskripsi.' }}
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">
                                Bergabung sejak {{ $community->pivot->created_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 bg-white border rounded-xl">
                <i data-lucide="users" class="w-12 h-12 mx-auto mb-4 text-gray-400"></i>
                <p class="text-lg font-medium text-gray-700 mb-2">Belum bergabung dengan komunitas mana pun</p>
                <a href="{{ route('community.list') }}" 
                   class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                   Jelajahi Komunitas
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function leaveCommunity(id) {
    if (confirm("Apakah kamu yakin ingin keluar dari komunitas ini?")) {
        window.location.href = /community/leave/${id};
    }
}

document.addEventListener("DOMContentLoaded", function () {
    if (window.lucide) lucide.createIcons();
});
</script>
@endsection