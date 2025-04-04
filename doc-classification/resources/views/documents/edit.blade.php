<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Edit Document') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form action="{{ route('documents.update', $document) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-300">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $document->title) }}" required
                                class="mt-1 block w-full bg-gray-700 border border-gray-600 text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Enter document title">
                            @error('title')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-300">Category</label>
                            <select name="category_id" id="category_id" required
                                class="mt-1 block w-full bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $document->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current File -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Current File</label>
                            <div class="mt-1 flex items-center space-x-4">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-400">{{ $document->file_path }}</p>
                                    <p class="text-xs text-gray-500">{{ number_format($document->file_size / 1024, 2) }} KB</p>
                                </div>
                            </div>
                        </div>

                        <!-- New File -->
                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-300">New File (Optional)</label>
                            <input id="file" name="file" type="file"
                                class="mt-1 block w-full text-sm text-blue-300 bg-blue-700 border border-gray-600 rounded-md shadow-sm file:bg-blue-600 file:border-0 file:text-white file:font-semibold file:px-4 file:py-2 hover:file:bg-blue-500">
                            <p class="mt-1 text-xs text-blue-400">PDF, DOC, DOCX, TXT up to 10MB</p>
                            @error('file')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-300">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full bg-gray-700 border border-gray-600 text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Enter document description">{{ old('description', $document->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('documents.show', $document) }}"
                                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Document
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
