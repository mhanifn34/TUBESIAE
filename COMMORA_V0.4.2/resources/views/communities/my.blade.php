@extends('layouts.app')

@section('content')
<div class="flex gap-6">
    <div class="flex-1">
        <div class="bg-white rounded-lg mb-4 border">
            <div class="flex border-b">
                <a href="{{ route('home') }}" class="px-6 py-3 hover:bg-gray-50">Beranda</a>
                <a href="{{ route('communities.my') }}" class="px-6 py-3 border-b-2 border-blue-500 font-medium">Komunitas Anda</a>
            </div>
        </div>

        <div class="bg-white rounded-lg border p-4">
            <h2 class="text-lg font-semibold mb-3">Komunitas yang Anda Ikuti</h2>

            @if($communities->count())
                <div class="space-y-4">
                    @foreach($communities as $community)
                        @include('components.community-card', ['community' => $community])
                    @endforeach
                </div>

                
            @else
                <div class="text-gray-600">
                    Kamu belum bergabung dengan komunitas apapun.
                    <a href="{{ route('communities.index') }}" class="text-blue-600 underline">Jelajahi komunitas</a>
                </div>
            @endif
        </div>
    </div>

    <div class="w-80">
        <div class="bg-white rounded-lg border p-4 sticky top-20">
            <h3 class="font-semibold mb-4">Saran Komunitas</h3>
            {{-- contoh: tampilkan 3 komunitas populer --}}
            @foreach(\App\Models\Community::take(3)->get() as $c)
                @include('components.community-card', ['community' => $c])
            @endforeach
        </div>
    </div>
</div>
@endsection
