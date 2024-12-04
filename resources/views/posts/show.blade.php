@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <article class="card border-0 shadow-lg overflow-hidden">
                {{-- Header with Title and Author Info --}}
                <div class="card-header bg-gradient-primary text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h1 class="mb-2 display-6 fw-bold">{{ $post->title }}</h1>
                            <div class="d-flex align-items-center text-white-50">
                                <img src="{{ $post->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) }}" 
                                     alt="{{ $post->user->name }}" 
                                     class="rounded-circle me-3" 
                                     style="width: 40px; height: 40px; object-fit: cover;">
                                <div>
                                    <span class="fw-semibold text-white">{{ $post->user->name }}</span>
                                    <small class="d-block">{{ $post->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Post Content --}}
                <div class="card-body p-4 p-md-5">
                    @if($post->image)
                        <div class="mb-4 rounded-3 overflow-hidden shadow-sm">
                            <img src="{{ asset('storage/' . $post->image) }}" 
                                 alt="Image for {{ $post->title }}" 
                                 class="img-fluid w-100" 
                                 style="max-height: 500px; object-fit: cover;">
                        </div>
                    @endif

                    <div class="post-content">
                        <p class="lead text-muted mb-4">{{ $post->excerpt }}</p>
                        <p class="fs-5">{{ $post->content }}</p>
                    </div>
                </div>
                
                {{-- Admin Actions --}}
                @can('update', $post)
                <div class="card-footer bg-light p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning btn-sm me-2">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            
                            <form action="{{ route('posts.destroy', $post) }}" 
                                  method="POST" 
                                  style="display:inline-block;" 
                                  onsubmit="return confirm('Are you sure you want to delete this post?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash me-1"></i>Delete
                                </button>
                            </form>
                        </div>
                        <small class="text-muted">Last updated {{ $post->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
                @endcan
            </article>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(to right, #6a11cb 0%, #2575fc 100%);
    }
</style>
@endpush