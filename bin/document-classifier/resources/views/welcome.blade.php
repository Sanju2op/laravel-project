<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Document-Classifier') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        @if (Route::has('login'))
            <div class="sm:fixed sm:top-0 sm:right-0 p-4 text-right z-10">
                @auth
                    <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                    @endif
                @endauth
            </div>
        @endif

        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="flex justify-center">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Document-Classifier</h1>
            </div>

            <div class="mt-16">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                    <div class="flex items-center justify-center">
                        <img src="{{ asset('images/hero-image.png') }}" alt="Document Classification" class="w-full h-auto rounded-lg shadow-lg">
                    </div>
                    <div class="flex flex-col justify-center">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Smart Document Classification System</h2>
                        <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">
                            Organize and manage your documents efficiently with our intelligent classification system. Upload, categorize, and find your documents with ease.
                        </p>
                        <div class="flex space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Get Started
                                </a>
                                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Register
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-16">
                <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white mb-8">Features</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Document Upload</h3>
                        <p class="text-gray-600 dark:text-gray-300">Upload various document types with ease and organize them efficiently.</p>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Smart Classification</h3>
                        <p class="text-gray-600 dark:text-gray-300">Automatically categorize documents based on their content and type.</p>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Easy Search</h3>
                        <p class="text-gray-600 dark:text-gray-300">Quickly find documents using advanced search and filter options.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
