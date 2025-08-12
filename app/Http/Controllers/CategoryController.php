<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Cache::remember('all_categories', 3600, function () {
            return BlogCategory::all();
        });
        return view('categories.index', compact('categories'));
    }

    public function show($slug)
    {
        $category = Cache::remember("category_{$slug}", 3600, function () use ($slug) {
            return BlogCategory::where('slug', $slug)->firstOrFail();
        });
        
        // For pagination, we need to handle caching differently
        $page = request('page', 1);
        $posts = Cache::remember("category_{$slug}_posts_page_{$page}", 3600, function () use ($category) {
            return $category->posts()->with('author')->latest()->paginate(10);
        });
        
        return view('categories.show', compact('category', 'posts'));
    }
}
