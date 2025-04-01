<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $document->title }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('documents.edit', $document) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('documents.download', $document) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Download
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Document Details</h3>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Title</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $document->title }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">File Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ strtoupper($document->file_type) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">File Size</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ number_format($document->file_size / 1024, 2) }} KB</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Uploaded By</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $document->user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Upload Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $document->created_at->format('M d, Y H:i:s') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $document->updated_at->format('M d, Y H:i:s') }}</dd>
                                </div>
                            </dl>
                        </div>

                        @if($document->description)
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                                <div class="prose max-w-none">
                                    {{ $document->description }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('documents.index') }}" class="text-blue-600 hover:text-blue-900">
                            &larr; Back to Documents
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 