@extends('layouts.app')

@section('title', 'Profil ' . $user->username)

@section('content')
<div class="container mt-5">
    <div class="card p-4 shadow-sm">
        <div class="text-center">
            <img src="{{ $user->avatar_url ?? 'https://via.placeholder.com/150' }}" 
                 alt="Avatar" class="rounded-circle mb-3" width="120" height="120">
            <h4>{{ $user->display_name ?? $user->name }}</h4>
            <p class="text-muted">{{ '@' . $user->username }}</p>
        </div>

        <hr>

        <p><strong>About Me:</strong> {{ $user->about_me ?? '-' }}</p>
        <p><strong>Location:</strong> {{ $user->location ?? '-' }}</p>
        <p><strong>Website:</strong>
            @if($user->website)
                <a href="{{ $user->website }}" target="_blank">{{ $user->website }}</a>
            @else
                -
            @endif
        </p>
        <p><strong>Signature:</strong> {{ $user->signature ?? '-' }}</p>

        @if($isOwnProfile)
            <div class="mt-4">
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profil</a>
            </div>
        @endif
    </div>
</div>
@endsection
