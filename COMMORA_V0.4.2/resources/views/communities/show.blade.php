@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header Community -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            @if($community->image)
                <img src="{{ asset('storage/' . $community->image) }}" 
                     alt="{{ $community->name }}" 
                     class="w-full h-48 object-cover rounded-lg mb-4">
            @endif
            
            <h1 class="text-3xl font-bold mb-2">{{ $community->name }}</h1>
            <p class="text-gray-600 mb-4">{{ $community->description }}</p>
            
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500">{{ number_format($community->members_count) }} Anggota</span>
                
                @if($community->isMember())
                    <form action="{{ route('communities.leave', $community->slug) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            Keluar dari Komunitas
                        </button>
                    </form>
                @else
                    <form action="{{ route('communities.join', $community->slug) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Gabung Komunitas
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Form Posting (hanya untuk member) -->
        @if($community->isMember())
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form action="{{ route('posts.store') }}" method="POST">
                @csrf
                <input type="hidden" name="community_id" value="{{ $community->id }}">
                <textarea name="content" rows="3" 
                    class="w-full border rounded-lg p-3 mb-3" 
                    placeholder="Bagikan sesuatu dengan komunitas ini..." 
                    required></textarea>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Posting
                </button>
            </form>
        </div>
        @endif

        <!-- Posts -->
        <div class="space-y-4">
            <h2 class="text-xl font-bold mb-4">Postingan</h2>
            
            @forelse($posts as $post)
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-start gap-3">
                    <img src="{{ $post->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) }}" 
                         alt="{{ $post->user->name }}" 
                         class="w-10 h-10 rounded-full">
                    <div class="flex-1">
                        <h3 class="font-semibold">{{ $post->user->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                        <p class="mt-2">{{ $post->content }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
                Belum ada postingan. Jadilah yang pertama posting!
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection