@extends('components.layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<!-- Hero Section with Gradient Background -->
<div class="relative min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 via-white to-blue-100 dark:from-gray-900 dark:to-blue-900">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23007bff" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
    
    <div class="relative w-full max-w-7xl px-6 lg:px-8 py-12">
        <!-- Animated Logo -->
        <div class="text-center mb-16 animate-fade-in-down">
            <h1 class="text-5xl md:text-7xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-blue-900 dark:from-blue-400 dark:to-blue-700 animate-pulse">
                <span class="text-blue-600 dark:text-blue-400">Raafi</span>
                <span class="text-blue-900 dark:text-blue-700">blogs</span>
            </h1>
            <p class="mt-4 text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto animate-fade-in-up">
                Discover amazing content from passionate writers around the world
            </p>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16 animate-fade-in">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 text-center transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                <div class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ $categories->count() }}</div>
                <div class="text-gray-600 dark:text-gray-400 mt-2">Categories</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 text-center transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                <div class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ $posts->count() }}</div>
                <div class="text-gray-600 dark:text-gray-400 mt-2">Posts</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 text-center transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                <div class="text-4xl font-bold text-blue-600 dark:text-blue-400">100+</div>
                <div class="text-gray-600 dark:text-gray-400 mt-2">Active Readers</div>
            </div>
        </div>

        <!-- Categories Section -->
        <section class="mb-20 animate-fade-in-up">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Popular Categories
                </h2>
                <a href="{{ url('/categories') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium transition-all duration-300 hover:translate-x-1">
                    View All
                </a>
            </div>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($categories as $category)
                <a
                    href="{{ url('/categories/' . $category->slug) }}"
                    class="block group"
                >
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1 h-full">
                        @if ($category->image)
                        <div class="h-48 overflow-hidden">
                            <img
                                src="{{ asset('storage/' . $category->image) }}"
                                alt="{{ $category->name }}"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                loading="lazy"
                            />
                        </div>
                        @endif
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors">
                                {{ $category->name }}
                            </h3>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>

        <!-- Latest Posts Section -->
        <section class="mb-20 animate-fade-in-up-delay">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Latest Posts
                </h2>
                <a href="{{ url('/posts') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium transition-all duration-300 hover:translate-x-1">
                    View All
                </a>
            </div>
            <div class="grid gap-8 md:grid-cols-2">
                @foreach ($posts as $post)
                <a
                    href="{{ url('/posts/' . $post->slug) }}"
                    class="block group"
                >
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden h-full transform hover:-translate-y-1">
                        @if($post->featured_image)
                        <div class="h-60 overflow-hidden">
                            <img
                                src="{{ asset('storage/' . $post->featured_image) }}"
                                alt="{{ $post->title }}"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                loading="lazy"
                            />
                        </div>
                        @endif
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors mb-3">
                                {{ $post->title }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                {{ Str::limit(strip_tags($post->content), 150) }}
                            </p>
                            <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                <span>By {{ $post->author->name }}</span>
                                <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Not published' }}</span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>

        <!-- Call to Action -->
        <section class="text-center py-16 animate-fade-in-up">
            <div class="bg-gradient-to-r from-blue-500 to-blue-700 rounded-3xl p-8 md:p-12 shadow-xl transform transition-all duration-500 hover:scale-[1.02]">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Join Our Community
                </h2>
                <p class="text-blue-100 text-lg mb-8 max-w-2xl mx-auto">
                    Share your thoughts, discover new ideas, and connect with like-minded individuals
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a
                        href="{{ url('/posts') }}"
                        class="inline-flex items-center px-6 py-3 bg-white text-blue-600 font-bold rounded-full hover:bg-gray-100 transition duration-300 ease-in-out transform hover:scale-105 shadow-lg"
                    >
                        Explore Posts
                    </a>
                    @if (Route::has('filament.admin.auth.login'))
                        @auth
                            <a
                                href="{{ url('/admin') }}"
                                class="inline-flex items-center px-6 py-3 bg-transparent border-2 border-white text-white font-bold rounded-full hover:bg-white hover:text-blue-600 transition duration-300 ease-in-out shadow-lg"
                            >
                                Dashboard
                            </a>
                        @else
                            <a
                                href="{{ route('filament.admin.auth.login') }}"
                                class="inline-flex items-center px-6 py-3 bg-transparent border-2 border-white text-white font-bold rounded-full hover:bg-white hover:text-blue-600 transition duration-300 ease-in-out shadow-lg"
                            >
                                Get Started
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </section>
    </div>

    <footer class="relative py-8 text-center text-sm text-gray-600 dark:text-gray-400 mt-16 animate-fade-in">
        <p>&copy; {{ date("Y") }} Raafiblogs. All rights reserved.</p>
    </footer>
</div>

<style>
    @keyframes fade-in-down {
        0% {
            opacity: 0;
            transform: translateY(-20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fade-in-up {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fade-in {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }
    
    .animate-fade-in-down {
        animation: fade-in-down 0.8s ease-out;
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 0.8s ease-out;
    }
    
    .animate-fade-in-up-delay {
        animation: fade-in-up 0.8s ease-out 0.2s both;
    }
    
    .animate-fade-in {
        animation: fade-in 0.8s ease-out;
    }
</style>
@endsection
