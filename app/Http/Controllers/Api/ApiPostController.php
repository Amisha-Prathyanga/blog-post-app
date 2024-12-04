<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ApiPostController extends Controller
{
    use AuthorizesRequests;

    //create new post
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        $post = Auth::user()->posts()->create($validatedData);

        return response()->json($post, 201);
    }

    //list all posts
    public function index()
    {
        $posts = Auth::user()->posts()->latest()->paginate(10);

        return response()->json($posts);
    }

    //show post details
    public function show(Post $post)
    {
        
        $this->authorize('view', $post);

        return response()->json($post);
    }

    //update post 
    public function update(Request $request, Post $post)
    {
        
        $this->authorize('update', $post);

        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string'
        ]);

        $post->update($validatedData);

        return response()->json($post);
    }

    //delete post
    public function destroy(Post $post)
    {
        
        $this->authorize('delete', $post);

        $post->delete();

        return response()->json(null, 204);
    }

    //seach post
    public function search(Request $request)
    {
        $query = Auth::user()->posts();

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }

        $posts = $query->latest()->paginate(10);

        return response()->json($posts);
    }
}
