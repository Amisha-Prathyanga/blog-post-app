<x-app-layout>
@section('content')
<div class="container mt-4">
    <!-- Authentication -->
    <form method="POST" action="{{ route('logout') }}" class="mb-4">
        @csrf
        <button type="submit" class="btn btn-danger btn-block">Logout</button>
    </form>

    <h1 class="mb-4 text-center text-primary">All Posts</h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('posts.create') }}" class="btn btn-success btn-lg">Create New Post</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @forelse($posts as $post)
        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title text-primary">{{ $post->title }}</h5>
                    <small class="text-muted">By {{ $post->user->name }} on {{ $post->created_at->format('F d, Y') }}</small>
                </div>
                
                @can('update', $post)
                <div class="post-actions">
                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-warning mr-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('posts.destroy', $post) }}" 
                          method="POST" 
                          style="display:inline-block;"
                          onsubmit="return confirm('Are you sure you want to delete this post?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
                @endcan
            </div>
            <div class="card-body">
                <p class="card-text">{{ Str::limit($post->content, 200) }}</p>
                <a href="{{ route('posts.show', $post) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-eye"></i> Read More
                </a>
            </div>
        </div>
    @empty
        <p class="text-center text-muted">No posts found.</p>
    @endforelse

    <div class="d-flex justify-content-center">
        {{ $posts->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
</x-app-layout>
