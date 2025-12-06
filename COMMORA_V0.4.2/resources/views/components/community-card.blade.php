<div class="flex items-center justify-between mb-4 pb-4 border-b last:border-0">
    <div class="flex-1">
        <p class="text-xs text-gray-500 mb-1">Komunitas</p>
        <a href="{{ route('communities.show', $community->slug) }}" 
           class="font-medium text-sm hover:text-blue-500">
            {{ $community->name }}
        </a>
        <p class="text-xs text-gray-500">{{ number_format($community->members_count) }} anggota</p>
    </div>
    
    @auth
        @if($community->isMember())
            <form action="{{ route('communities.leave', $community->slug) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm hover:bg-gray-300 transition">
                    Keluar
                </button>
            </form>
        @else
            <form action="{{ route('communities.join', $community->slug) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-3 py-1 border rounded-full text-sm hover:bg-gray-50 transition">
                    Gabung
                </button>
            </form>
        @endif
    @endauth
</div>