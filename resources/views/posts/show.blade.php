@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h1>{{ $post->title }}</h1>
            <small>By {{ $post->user->name }} on {{ $post->created_at->format('F d, Y') }}</small>
        </div>
        <div class="card-body">
            <p>{{ $post->content }}</p>
        </div>
        
        @can('update', $post)
        <div class="card-footer">
            <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning">Edit</a>
            
            <form action="{{ route('posts.destroy', $post) }}" 
                  method="POST" 
                  style="display:inline-block;"
                  onsubmit="return confirm('Are you sure you want to delete this post?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
        @endcan
    </div>
</div>
@endsection