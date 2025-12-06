@extends('layouts.app')

@section('content')
<div class="flex gap-6">
    <!-- Left Side: Posts -->
    <div class="flex-1">
        <!-- Tabs -->
        <div class="bg-white rounded-lg mb-4 border">
            <div class="flex border-b">
                <button class="px-6 py-3 border-b-2 border-blue-500 font-medium text-blue-500">Beranda</button>
                <button class="px-6 py-3 text-gray-600 hover:bg-gray-50">Komunitas Anda</button>
            </div>
        </div>

        <!-- Posts -->
        @forelse($posts as $post)
            @include('components.post-card', ['post' => $post])
        @empty
            <div class="bg-white rounded-lg border p-8 text-center">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-inbox text-6xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Belum ada postingan</h3>
                <p class="text-gray-500 mb-4">Jadilah yang pertama membuat postingan!</p>
                <button onclick="openCreatePostModal()" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    <i class="fas fa-plus mr-2"></i>Buat Post
                </button>
            </div>
        @endforelse

        <!-- Pagination -->
        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </div>

    <!-- Right Sidebar: Communities -->
    <div class="w-80">
        <div class="bg-white rounded-lg border p-4 sticky top-20">
            <h3 class="font-semibold mb-4">Komunitas</h3>
            
            @forelse($communities as $community)
                <div class="flex items-center justify-between mb-4 pb-4 border-b last:border-0">
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 mb-1">Komunitas</p>
                        <h4 class="font-medium text-sm">{{ $community->name }}</h4>
                        <p class="text-xs text-gray-500">{{ number_format($community->members_count) }} anggota</p>
                    </div>
                    <form action="{{ route('communities.join', $community->id) }}" method="POST" class="inline">
                    @csrf
                     <button class="px-3 py-1 border rounded-full text-sm hover:bg-gray-50 transition">
                        Gabung
                    </button>
                </form>  
                </div>
            @empty
                <p class="text-gray-500 text-sm">Belum ada komunitas</p>
            @endforelse
        </div>
    </div>
</div>
@endsection