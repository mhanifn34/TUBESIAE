@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-white py-8">
    <div class="max-w-7xl mx-auto px-4">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 flex items-center gap-3">
                    <i class="fas fa-users text-blue-500"></i>
                    Semua Komunitas
                </h1>
                <p class="text-gray-600 mt-2">Temukan dan bergabung dengan komunitas yang kamu minati</p>
            </div>
            <a href="{{ route('communities.create') }}" 
               class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-all flex items-center gap-2 shadow-lg hover:shadow-xl">
                <i class="fas fa-plus"></i>
                Buat Komunitas
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Communities Grid -->
        @if($communities->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($communities as $community)
                    <a href="{{ route('communities.show', $community->slug) }}" 
                       class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                        <!-- Community Image -->
                        <div class="h-48 bg-gradient-to-br from-blue-400 to-blue-600 relative overflow-hidden">
                            @if($community->image)
                                <img src="{{ asset('storage/' . $community->image) }}" 
                                     alt="{{ $community->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-users text-6xl text-white opacity-50"></i>
                                </div>
                            @endif
                            
                            <!-- Members Badge -->
                            <div class="absolute top-4 right-4 bg-white bg-opacity-90 px-3 py-1 rounded-full text-sm font-semibold text-gray-700">
                                <i class="fas fa-user-friends text-blue-500 mr-1"></i>
                                {{ number_format($community->members_count) }}
                            </div>
                        </div>

                        <!-- Community Info -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-blue-500 transition-colors">
                                {{ $community->name }}
                            </h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ Str::limit($community->description, 100) }}
                            </p>
                            
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <div class="flex items-center gap-2">
                                    <i class="far fa-file-alt text-blue-500"></i>
                                    <span>{{ $community->posts_count ?? 0 }} Post</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="far fa-clock text-blue-500"></i>
                                    <span>{{ $community->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $communities->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-md p-12 text-center">
                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">Belum Ada Komunitas</h3>
                <p class="text-gray-500 mb-6">Jadilah yang pertama membuat komunitas!</p>
                <a href="{{ route('communities.create') }}" 
                   class="inline-flex items-center gap-2 bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-all">
                    <i class="fas fa-plus"></i>
                    Buat Komunitas Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection