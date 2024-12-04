<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostService
{
    /**
     * Create a new post
     * 
     * @param array $validatedData
     * @param array|null $imageData
     * @return Post
     */
    public function createPost(array $validatedData, ?array $imageData = null): Post
    {
        $imagePath = null;
        
        if ($imageData && isset($imageData['image'])) {
            $imagePath = $imageData['image']->store('images', 'public');
        }

        return Auth::user()->posts()->create([
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
            'image' => $imagePath,
        ]);
    }

    /**
     * Update an existing post
     * 
     * @param Post $post
     * @param array $validatedData
     * @param array|null $imageData
     * @return Post
     */
    public function updatePost(Post $post, array $validatedData, ?array $imageData = null): Post
    {
        $imagePath = $post->image;
        
        if ($imageData && isset($imageData['image'])) {
            
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            
            $imagePath = $imageData['image']->store('images', 'public');
        }

        $post->update([
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
            'image' => $imagePath,
        ]);

        return $post;
    }

    /**
     * Delete a post and its associated image
     * 
     * @param Post $post
     * @return void
     */
    public function deletePost(Post $post): void
    {
        
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();
    }

    /**
     * Retrieve posts for a user
     * 
     * @param string|null $searchTitle
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getUserPosts(?string $searchTitle = null, int $perPage = 10)
    {
        $query = Auth::user()->posts();

        if ($searchTitle) {
            $query->where('title', 'like', '%' . $searchTitle . '%');
        }

        return $query->latest()->paginate($perPage);
    }
}