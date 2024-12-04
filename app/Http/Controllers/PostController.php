<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{

    use AuthorizesRequests;
    
    //display posts
    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(10);
        return view('posts.index', ['posts' => $posts]);
    }

    //create posts
    public function create()
    {
        return view('posts.create');
    }

    //create posts
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public'); 
        }

        // Create the post with the authenticated user's ID
        $post = Auth::user()->posts()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image' => $imagePath,
        ]);

        flash()->success('Post has been added successfully.');

        return redirect()->route('posts.index', $post);
    }

    //display post details
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    //edit post
    public function edit(Post $post)
    {
        
        $this->authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    //update post
    public function update(Request $request, Post $post)
    {
        
        $this->authorize('update', $post);

        // Validate the request
    $validated = $request->validate([
        'title' => 'required|max:255',
        'content' => 'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    
    $imagePath = $post->image; 
    if ($request->hasFile('image')) {
       
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $imagePath = $request->file('image')->store('images', 'public');
    }

    
    $post->update([
        'title' => $validated['title'],
        'content' => $validated['content'],
        'image' => $imagePath, 
    ]);

    flash()->success('Post has been updated successfully.');
    
        return redirect()->route('posts.index', $post);
            
    }

    //delete post
    public function destroy(Post $post)
    {
        
        $this->authorize('delete', $post);

        // Delete the image if it exists
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        flash()->success('The post has been successfully deleted.');

        return redirect()->route('posts.index');
            
    }
}
