@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h1>{{ $post->title }}</h1>
            <small>By {{ $post->user->name }} on {{ $post->created_at->format('F d, Y') }}</small>
        </div>

        <div class="card-body">
            @if($post->image)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $post->image) }}" 
                         alt="Image for {{ $post->title }}" 
                         class="img-fluid rounded">
                </div>
            @endif
            <p>{{ $post->content }}</p>
        </div>
        
        @can('update', $post)
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            
            <form action="{{ route('posts.destroy', $post) }}" 
                  method="POST" 
                  style="display:inline-block;" 
                  onsubmit="return confirm('Are you sure you want to delete this post?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
        </div>
        @endcan
    </div>
</div>
@endsection
