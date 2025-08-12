<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Cache::remember('homepage_categories', 3600, function () {
            return BlogCategory::take(6)->get();
        });
        
        $posts = Cache::remember('homepage_posts', 3600, function () {
            return BlogPost::with('author')->latest()->take(6)->get();
        });

        return view('welcome', compact('categories', 'posts'));
    }
}
