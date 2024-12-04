<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    use AuthorizesRequests;
    
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }
    
    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(10);
        return view('posts.index', ['posts' => $posts]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageData = $request->hasFile('image') ? ['image' => $request->file('image')] : null;
        
        $post = $this->postService->createPost($validated, $imageData);

        flash()->success('Post has been added successfully.');

        return redirect()->route('posts.index', $post);
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageData = $request->hasFile('image') ? ['image' => $request->file('image')] : null;
        
        $updatedPost = $this->postService->updatePost($post, $validated, $imageData);

        flash()->success('Post has been updated successfully.');
        
        return redirect()->route('posts.index', $updatedPost);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $this->postService->deletePost($post);

        flash()->success('The post has been successfully deleted.');

        return redirect()->route('posts.index');
    }
}