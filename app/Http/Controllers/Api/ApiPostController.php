<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ApiPostController extends Controller
{
    use AuthorizesRequests;

    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        $post = $this->postService->createPost($validatedData);

        return response()->json($post, 201);
    }

    public function index(Request $request)
    {
        $posts = $this->postService->getUserPosts(
            $request->input('title'), 
            $request->input('per_page', 10)
        );

        return response()->json($posts);
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);
        return response()->json($post);
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string'
        ]);

        $updatedPost = $this->postService->updatePost($post, $validatedData);

        return response()->json($updatedPost);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $this->postService->deletePost($post);
        return response()->json(null, 204);
    }
}