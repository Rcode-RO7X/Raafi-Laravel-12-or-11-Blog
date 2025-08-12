<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ open: false }">
    <head>
        <meta charset="utf-8" />

        <meta name="application-name" content="{{ config('app.name') }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>{{ config("app.name") }}</title>

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        @filamentStyles @vite('resources/css/app.css')
    </head>

    <body class="antialiased">
        <!-- Navbar -->
        <nav class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <div class="relative flex items-center justify-between h-16">
                    <div
                        class="absolute inset-y-0 left-0 flex items-center sm:hidden"
                    >
                        <!-- Mobile menu button-->
                        <button
                            type="button"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                            aria-controls="mobile-menu"
                            aria-expanded="false"
                            @click="open = !open"
                        >
                            <span class="sr-only">Open main menu</span>
                            <!-- Icon when menu is closed. -->
                            <!-- Heroicon name: menu -->
                            <svg
                                class="block h-6 w-6"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16m-7 6h7"
                                />
                            </svg>
                            <!-- Icon when menu is open. -->
                            <svg
                                class="hidden h-6 w-6"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 24 24"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                    <div
                        class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start"
                    >
                        <div class="flex-shrink-0">
                            <a
                                href="{{ url('/') }}"
                                class="text-xl font-bold"
                            >
                                <span class="text-blue-600 dark:text-blue-400">Raafi</span>
                                <span class="text-blue-900 dark:text-blue-700">blogs</span>
                            </a>
                        </div>
                        <div class="hidden sm:block sm:ml-6">
                            <div class="flex space-x-4">
                                <a
                                    href="{{ url('/') }}"
                                    class="text-gray-900 dark:text-white px-3 py-2 rounded-md text-sm font-medium"
                                    >Home</a
                                >
                                <a
                                    href="{{ url('/categories') }}"
                                    class="text-gray-900 dark:text-white px-3 py-2 rounded-md text-sm font-medium"
                                    >Categories</a
                                >
                                <a
                                    href="{{ url('/posts') }}"
                                    class="text-gray-900 dark:text-white px-3 py-2 rounded-md text-sm font-medium"
                                    >Posts</a
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Desktop only auth routes links -->
                    <div class="hidden md:flex sm:ml-6 gap-10">

                        @if (Route::has('filament.admin.auth.login'))
                            @auth
                                <a
                                    href="{{ url('/admin') }}"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex gap-3"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                    </svg>
                                    Dashboard
                                </a>
                            @else
                                <a
                                    href="{{ route('filament.admin.auth.login') }}"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex gap-3"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                                    </svg>

                                    Log in
                                </a>
                            @endauth
                        @endif
                    </div>
                    <!-- Dark mode toggle button -->
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                        <button id="dark-mode-toggle" type="button" class="bg-gray-200 dark:bg-gray-700 rounded-full p-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white focus:outline-none">
                            <svg id="sun-icon" class="hidden h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                            </svg>
                            <svg id="moon-icon" class="hidden h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <div class="container mx-auto px-4 my-5">@yield('content')</div>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 shadow mt-10">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <p class="text-gray-900 dark:text-white">
                            © {{ date("Y") }} {{ config("app.name") }}. All
                            rights reserved.
                        </p>
                    </div>
                    <div class="flex items-center">
                        <a
                            href="https://github.com/ManiruzzamanAkash"
                            class="text-gray-900 dark:text-white"
                        >
                            Made by Raafi Hafidz Ramadhan
                        </a>
                    </div>
                </div>
            </div>
        </footer>

        @filamentScripts @vite('resources/js/app.js')
    </body>
</html>
