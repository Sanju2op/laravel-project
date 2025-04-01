<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ $document->title }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('documents.edit', $document) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Edit Document
                </a>
                <a href="{{ route('documents.download', $document) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Download
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <!-- Document Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4">Document Information</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-400">Title</dt>
                                    <dd class="mt-1 text-sm text-white">{{ $document->title }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-400">Category</dt>
                                    <dd class="mt-1 text-sm text-white">{{ $document->category->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-400">File Type</dt>
                                    <dd class="mt-1 text-sm text-white">{{ $document->file_type }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-400">File Size</dt>
                                    <dd class="mt-1 text-sm text-white">{{ number_format($document->file_size / 1024, 2) }} KB</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-400">Uploaded By</dt>
                                    <dd class="mt-1 text-sm text-white">{{ $document->user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-400">Upload Date</dt>
                                    <dd class="mt-1 text-sm text-white">{{ $document->created_at->format('F j, Y, g:i a') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-white mb-4">Description</h3>
                            <p class="text-gray-400">{{ $document->description ?? 'No description provided.' }}</p>
                        </div>
                    </div>

                    <!-- Document Preview -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-white mb-4">Document Preview</h3>
                        <div class="bg-gray-700 rounded-lg p-6">
                            @if(str_contains($document->file_type, 'pdf'))
                                <iframe src="{{ Storage::url($document->file_path) }}" class="w-full h-96 rounded-lg"></iframe>
                            @elseif(str_contains($document->file_type, 'image'))
                                <img src="{{ Storage::url($document->file_path) }}" alt="{{ $document->title }}" class="max-w-full h-auto rounded-lg">
                            @else
                                <div class="flex items-center justify-center h-96">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="mt-2 text-gray-400">Preview not available for this file type.</p>
                                        <a href="{{ route('documents.download', $document) }}" class="mt-4 inline-flex items-center text-blue-400 hover:text-blue-300">
                                            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Download to view
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('documents.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Documents
                        </a>
                        <form action="{{ route('documents.destroy', $document) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to delete this document?')">
                                Delete Document
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 