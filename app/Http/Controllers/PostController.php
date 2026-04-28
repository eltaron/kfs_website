<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        // Fetch published posts, order them by the newest first,
        // and paginate them, showing 9 posts per page.
        $posts = Post::where('is_published', true)
            ->whereHas('category', fn($q) => $q->where('slug', '!=', 'events')) // <--  التعديل هنا
            ->latest('published_at')
            ->paginate(9);

        return view('posts.index', [
            'posts' => $posts
        ]);
    }

    public function show(Post $post)
    {
        // Abort with a 404 error if the post is not published
        // You might want to allow admins to preview unpublished posts later
        if (!$post->is_published) {
            abort(404);
        }

        // Load the post with its category and approved comments
        $post->load(['category', 'comments' => function ($query) {
            $query->where('is_approved', true)->latest();
        }]);

        // Get some related posts (e.g., from the same category)
        $relatedPosts = Post::where('is_published', true)
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id) // Exclude the current post
            ->latest()
            ->take(3)
            ->get();

        return view('posts.show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
        ]);
    }
}
