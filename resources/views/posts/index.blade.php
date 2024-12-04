<x-app-layout>
@section('content')
<div class="container mt-5">

     <!-- Header -->
     <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-primary mb-3">Discover Posts</h1>
        <p class="lead text-muted">Explore, create, and share your stories</p>
    </div>

    <!-- Create Post CTA -->
    <div class="text-center mb-5">
        <a href="{{ route('posts.create') }}" class="btn btn-primary btn-lg shadow-sm">
            <i class="fas fa-plus-circle me-2"></i>Create New Post
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        @forelse($posts as $post)
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm h-100 border-0">
                    @if($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top rounded-top" alt="Post image">
                    @else
                        <img src="https://via.placeholder.com/350x200" class="card-img-top rounded-top" alt="Default image">
                    @endif

                    <div class="card-body">
                        <h5 class="card-title text-primary fw-semibold">{{ $post->title }}</h5>
                        <p class="card-text text-truncate" style="max-height: 3.5em; overflow: hidden;">
                            {{ Str::limit($post->content, 150) }}
                        </p>
                        <p class="small text-muted">By {{ $post->user->name }} on {{ $post->created_at->format('F d, Y') }}</p>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <a href="{{ route('posts.show', $post) }}" class="btn btn-info btn-sm shadow-sm">
                                <i class="fas fa-eye me-1"></i>Read More
                            </a>

                            @can('update', $post)
                                <div class="d-flex">
                                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning btn-sm me-2 shadow-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm shadow-sm">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5">
                <i class="fas fa-file-alt fa-3x mb-3"></i>
                <p class="fs-5">No posts found.</p>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $posts->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
</x-app-layout>
