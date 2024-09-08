@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Posts</h1>
    @foreach($posts as $post)
        <div class="card mb-3">
            <div class="card-header">
                <img src="{{ $post->user->profile_photo_url }}" alt="User photo" width="50" class="rounded-circle">
                <strong>{{ $post->user->name }}</strong> - {{ $post->user->role }}
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $post->title }}</h5>
                <p class="card-text">{{ $post->description }}</p>
            </div>
        </div>
    @endforeach
</div>
@endsection
