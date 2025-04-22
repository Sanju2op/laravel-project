<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Document Classifier') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ENjdO4Dr2bkBIFxQpeoYz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-900 text-gray-100">
        <div class="min-h-screen">
            <!-- Navigation -->
            <nav class="bg-gray-800 border-b border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex-shrink-0 flex items-center">
                                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-white">
                                    {{ config('app.name', 'Document Classifier') }}
                                </a>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-blue-500 text-white' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('documents.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('documents.*') ? 'border-blue-500 text-white' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300' }}">
                                    Documents
                                </a>
                                <a href="{{ route('categories.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('categories.*') ? 'border-blue-500 text-white' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300' }}">
                                    Categories
                                </a>
                                <!-- <a href="{{ route('categories.create') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('categories.create') ? 'border-blue-500 text-white' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300' }}">
                                    New Category
                                </a> -->
                            </div>
                        </div>

                        <!-- Settings Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-300 hover:text-white focus:outline-none transition ease-in-out duration-150">
                                        <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('images/default-profile.png') }}" alt="Profile Photo" class="rounded-full border-4 border-blue-500 shadow-lg me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        <div>{{ Auth::user()->name }}</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')" class="text-gray-400 hover:text-gray-300">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                            this.closest('form').submit();"
                                                class="text-gray-400 hover:text-gray-300">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </div>
            </nav>

            <header class="bg-gray-900 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 bg-gray-800">
                    {{ $header ?? '' }}
                </div>
            </header>

            <!-- Page Content -->
            <main class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        @php
                            $errorMessage = session('error');
                            // Remove port number pattern like :587 or :anynumber
                            $errorMessage = preg_replace('/:\d+/', '', $errorMessage);
                        @endphp
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {!! nl2br(e($errorMessage)) !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>

        <!-- Bootstrap JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    </body>
</html>
