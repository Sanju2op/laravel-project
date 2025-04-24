<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Upload New Document') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-300">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                class="mt-1 block w-full bg-gray-700 border border-gray-600 text-gray-300    placeholder-gray-400 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Enter document title">
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

                        <!-- File Upload -->
                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-300">Document File</label>
                            <div class="mt-1">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-1">
                                        <input type="file" name="file" id="file" required accept=".pdf,.doc,.docx,.txt"
                                            class="block w-full text-sm text-gray-300
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-md file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-blue-600 file:text-white
                                            hover:file:bg-blue-700
                                            cursor-pointer"
                                            onchange="updateFileName(this)">
                                    </div>
                                    <div id="file-preview" class="hidden flex items-center space-x-2 bg-gray-700 px-4 py-2 rounded-md">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span id="selected-file" class="text-sm text-gray-300"></span>
                                        <button type="button" onclick="clearFile()" class="text-red-400 hover:text-red-300">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-gray-400">
                                    Supported formats: PDF, DOC, DOCX, TXT (max 10MB)
                                </p>
                            </div>
                            @error('file')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-300">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full bg-gray-700 border border-gray-600 text-gray-300 placeholder-gray-400 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Enter document description">{{ old('description') }}</textarea>
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
                                Upload Document
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const fileInput = document.querySelector('input[type="file"]');
        const filePreview = document.getElementById('file-preview');
        const selectedFileDisplay = document.getElementById('selected-file');

        function updateFileName(input) {
            if (input.files.length > 0) {
                selectedFileDisplay.textContent = input.files[0].name;
                filePreview.classList.remove('hidden');
            } else {
                clearFile();
            }
        }

        function clearFile() {
            fileInput.value = '';
            selectedFileDisplay.textContent = '';
            filePreview.classList.add('hidden');
        }
    </script>
    @endpush
</x-app-layout> 