@extends('layouts.app')

@section('content')
<div class="flex gap-6">
    <!-- Left Side: Posts -->
    <div class="flex-1">
        <!-- Tabs (UI sesuai layout) -->
        <div class="bg-white rounded-lg mb-4 border">
            <div class="flex">
                <a href="{{ route('home') }}"
                   class="px-6 py-3 flex-1 text-center {{ request()->routeIs('home') ? 'border-b-2 border-blue-500 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                    Beranda
                </a>

                @auth
                    <a href="{{ route('communities.my') }}"
                       class="px-6 py-3 flex-1 text-center {{ request()->routeIs('communities.my') ? 'border-b-2 border-blue-500 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                        Komunitas Saya
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-6 py-3 flex-1 text-center text-gray-600 hover:bg-gray-50">
                        Komunitas (Masuk)
                    </a>
                @endauth
            </div>
        </div>

        <!-- Posts -->
        @foreach($posts as $post)
            @include('components.post-card', ['post' => $post])
        @endforeach
    </div>

    <!-- Right Sidebar: Communities -->
    <div class="w-80">
        <div class="bg-white rounded-lg border p-4 sticky top-20">
            <h3 class="font-semibold mb-4">Komunitas</h3>
            
            {{-- ✅ GANTI JADI INI --}}
            @foreach($sidebarCommunities as $community)
                @include('components.community-card', ['community' => $community])
            @endforeach
            
            {{-- (Opsional) Kalo mau nampilin link "Lihat Semua" --}}
            <a href="{{ route('communities.index') }}" class="text-blue-500 text-sm mt-4 inline-block">
                Lihat semua komunitas...
            </a>
        </div>
    </div>
</div>
@endsection
