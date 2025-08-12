<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function index()
    {
        // For pagination, we need to handle caching differently
        $page = request('page', 1);
        $posts = Cache::remember("posts_page_{$page}", 3600, function () {
            return BlogPost::with('author')->latest()->paginate(10);
        });
        return view('posts.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = Cache::remember("post_{$slug}", 3600, function () use ($slug) {
            return BlogPost::where('slug', $slug)->firstOrFail();
        });
        return view('posts.show', compact('post'));
    }
}
