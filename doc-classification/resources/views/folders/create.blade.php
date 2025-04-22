<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Upload New Folder') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form action="{{ route('folders.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-300">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                class="mt-1 block w-full bg-gray-700 border border-gray-600 text-gray-300 placeholder-gray-400 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Enter folder title">
                            @error('title')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-300">Category</label>
                            <select name="category_id" id="category_id" required
                                class="mt-1 block w-full bg-gray-700 border border-gray-600 text-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Folder Upload -->
                        <div>
                            <label for="folder" class="block text-sm font-medium text-gray-300">Choose Folder</label>
                            <input type="file" name="folder[]" id="folder" multiple webkitdirectory directory required
                                class="mt-1 block w-full text-sm text-gray-300
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-600 file:text-white
                                hover:file:bg-blue-700
                                cursor-pointer">
                            @error('folder')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            @error('folder.*')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-300">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full bg-gray-700 border border-gray-600 text-gray-300 placeholder-gray-400 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Enter folder description">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('documents.index') }}"
                                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Upload Folder
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
