@props(['post'])

<div class="bg-white rounded-lg border mb-4 shadow-sm hover:shadow-md transition">
    <div class="p-4 flex items-center justify-between border-b">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-gray-500"></i>
            </div>
            <div>
                <p class="font-medium">{{ $post->community->name }}</p>
                <p class="text-xs text-gray-500">
                    {{ $post->user->name ?? 'Anonymous' }} • {{ $post->created_at->diffForHumans() }}
                </p>
            </div>
        </div>
        <button class="px-4 py-1 border rounded-full text-sm hover:bg-gray-50">
            Gabung
        </button>
    </div>

    @if($post->image)
    <div class="w-full">
        <img src="{{ asset('storage/' . $post->image) }}" 
             alt="{{ $post->title }}" 
             class="w-full h-auto max-h-96 object-cover">
    </div>
    @endif

    <div class="p-4">

        {{-- ====================================================== --}}
        {{-- ⚡️ LOGIKA PINTERNYA MULAI DI SINI ⚡️ --}}
        {{-- Cek KTP postingan --}}
        @if($post->type == 'listing')
            
            <div class="mb-2 flex items-center justify-between">
                <span class="text-xs font-bold uppercase py-1 px-2 rounded-full
                             {{ $post->listing_type == 'jual' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                    <i class="fas fa-tag"></i>
                    {{ $post->listing_type }}
                </span>
                
                @if($post->condition)
                    <span class="text-sm text-gray-600">
                        Kondisi: <span class="font-medium capitalize">{{ $post->condition }}</span>
                    </span>
                @endif
            </div>
            
            <h3 class="text-2xl font-bold text-red-600 mb-2">
                {{-- Format harga jadi 'Rp 1.000.000' --}}
                Rp {{ number_format($post->price, 0, ',', '.') }}
            </h3>

           @if($post->listing_type == 'jual' && $post->status != 'sold') {{-- Asumsi ada kolom status --}}
    <div class="mt-4">
        {{-- Ini adalah link, BUKAN form POST ke keranjang --}}
       <form action="{{ route('cart.add') }}" method="POST">
    @csrf
    <input type="hidden" name="post_id" value="{{ $post->id }}">
    <input type="hidden" name="quantity" value="1">
    <button type="submit" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors shadow hover:shadow-md text-sm">
        <i class="fas fa-cart-plus"></i>
        Tambah Keranjang
    </button>
</form>
    </div>
    @elseif($post->status == 'sold')
            <div class="mt-4">
                <span class="inline-flex items-center gap-2 px-5 py-2 bg-gray-300 text-gray-600 rounded-lg font-medium cursor-not-allowed">
                    <i class="fas fa-times-circle"></i>
                    Sudah Terjual
                </span>
            </div>
            @endif
            {{-- ⬆️ SELESAI TAMBAH TOMBOL BELI ⬆️ --}}

        
        @endif
        {{-- ⚡️ LOGIKA PINTERNYA SELESAI DI SINI ⚡️ --}}
        {{-- ====================================================== --}}


        <h2 class="text-xl font-bold mb-3">{{ $post->title }}</h2>
        
        <p class="text-gray-700 mb-4 whitespace-pre-line">{{ Str::limit($post->content, 300) }}</p>
        
        @if(strlen($post->content) > 300)
        <button class="text-blue-500 hover:underline text-sm">Selengkapnya</button>
        @endif
    </div>

    <div class="px-4 pb-4">
        <div class="flex items-center justify-between text-gray-600 pt-4 border-t">
            <button class="flex items-center space-x-2 hover:text-blue-600 transition">
                <i class="far fa-thumbs-up text-lg"></i>
                <span class="text-sm">{{ number_format($post->likes_count) }}</span>
            </button>
            <button class="flex items-center space-x-2 hover:text-blue-600 transition">
                <i class="far fa-comment text-lg"></i>
                <span class="text-sm">{{ number_format($post->comments_count) }}</span>
            </button>
            <button class="flex items-center space-x-2 hover:text-blue-600 transition">
                <i class="fas fa-share text-lg"></i>
                <span class="text-sm">{{ number_format($post->shares_count) }}</span>
            </button>
            <button class="flex items-center space-x-2 hover:text-gray-700 transition">
                <i class="fas fa-chart-bar text-lg"></i>
                <span class="text-sm">{{ number_format($post->views_count) }}</span>
            </button>
        </div>
    </div>
</div>