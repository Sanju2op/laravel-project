<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ __('Documents') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-800 border border-green-700 text-white px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Upload Section -->
            <div class="bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <div class="text-center">
                        <h3 class="text-lg font-medium text-gray-100 mb-4">Upload New Document</h3>
                        <div class="flex justify-center space-x-4">
                            <a href="{{ route('documents.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Upload Document
                            </a>
                            <a href="{{ route('folders.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M16 3h-4a2 2 0 00-2 2v2h8V5a2 2 0 00-2-2z" />
                                </svg>
                                Upload Folder
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <form action="{{ route('documents.index') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Search Input -->
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-300">Search Documents</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="mt-1 block w-full bg-gray-700 border border-gray-600 text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Search by title...">
                            </div>

                            <!-- Category Filter -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-300">Filter by Category</label>
                                <select name="category" id="category"
                                    class="mt-1 block w-full bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sort by File Size -->
                            <div>
                                <label for="sort_size" class="block text-sm font-medium text-gray-300">Sort by File Size</label>
                                <select name="sort_size" id="sort_size"
                                    class="mt-1 block w-full bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">None</option>
                                    <option value="desc" {{ request('sort_size') == 'desc' ? 'selected' : '' }}>High to Low</option>
                                </select>
                            </div>

                            <!-- Sort by Date -->
                            <div>
                                <label for="sort_date" class="block text-sm font-medium text-gray-300">Sort by Date</label>
                                <select name="sort_date" id="sort_date"
                                    class="mt-1 block w-full bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">None</option>
                                    <option value="desc" {{ request('sort_date') == 'desc' ? 'selected' : '' }}>Newest First</option>
                                    <option value="asc" {{ request('sort_date') == 'asc' ? 'selected' : '' }}>Oldest First</option>
                                </select>
                            </div>

                        </div>

                        <!-- Filter Buttons -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('documents.index') }}"
                                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Clear Filters
                            </a>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Apply Filters
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Documents Table -->
            <div class="bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-white mb-4">Documents</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Title
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Category
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Size
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Uploaded By
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800 divide-y divide-gray-700">
                                @forelse ($documents as $document)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-white">
                                                {{ $document->title }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-300">
                                                {{ $document->category->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-300">
                                                {{ number_format($document->file_size / 1024, 2) }} KB
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-300">
                                                {{ $document->user->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-300">
                                                {{ $document->created_at->format('M d, Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('documents.show', $document) }}" class="text-blue-400 hover:text-blue-300">View</a>
                                            <a href="{{ route('documents.download', $document) }}" class="text-green-400 hover:text-green-300">Download</a>
                                            <a href="{{ route('documents.edit', $document) }}" class="text-yellow-400 hover:text-yellow-300">Edit</a>
                                            <form action="{{ route('documents.destroy', $document) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('Are you sure you want to delete this document?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-400">
                                            No documents found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $documents->links() }}
                    </div>
                </div>
            </div>

            <!-- Files Table -->
            <div class="bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-white mb-4">Folders</h3>
                    @forelse ($folders as $folder)
                        <div class="bg-gray-700 rounded-lg p-4 mb-4">
                            <div class="flex justify-between items-center cursor-pointer" onclick="toggleFolderFiles('folder-{{ $folder->id }}')">
                                <div>
                                    <h4 class="text-white font-semibold">{{ $folder->title }}</h4>
                                    <p class="text-gray-400 text-sm">{{ $folder->category->name }} - Uploaded by {{ $folder->user->name }}</p>
                                    <p class="text-gray-400 text-sm">{{ $folder->files->count() }} files</p>
                                    <p class="text-gray-400 text-sm">Total Size: {{ number_format($folder->total_file_size / 1024, 2) }} KB</p>
                                    <p class="text-gray-400 text-sm">Latest Upload: {{ $folder->latest_upload ? \Carbon\Carbon::parse($folder->latest_upload)->format('M d, Y h:i A') : 'N/A' }}</p>
                                </div>
                                <div class="space-x-2">
                                    <a href="{{ route('folders.download', $folder) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Download Folder</a>
                                    <a href="{{ route('folders.show', $folder) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">View</a>
                                    <a href="{{ route('folders.edit', $folder) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                                    <form action="{{ route('folders.destroy', $folder) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this folder?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                                    </form>
                                </div>
                            </div>
                            <div id="folder-{{ $folder->id }}" class="hidden mt-4">
                                <table class="min-w-full divide-y divide-gray-700">
                                    <thead class="bg-gray-600">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">File Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Size</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Uploaded At</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                                        @foreach ($folder->files as $file)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $file->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ number_format($file->file_size / 1024, 2) }} KB</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $file->created_at->format('M d, Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400">No folders found.</p>
                    @endforelse
                    <div class="mt-4">
                        {{ $folders->links() }}
                    </div>
                </div>
            </div>

            <script>
                function toggleFolderFiles(id) {
                    const element = document.getElementById(id);
                    if (element.classList.contains('hidden')) {
                        element.classList.remove('hidden');
                    } else {
                        element.classList.add('hidden');
                    }
                }
            </script>
        </div>
    </div>
</x-app-layout> 