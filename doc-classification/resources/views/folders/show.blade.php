<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Folder: {{ $folder->title }}</h1>

        <div class="bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6 text-white">
            <p class="mb-2"><strong>Category:</strong> {{ $folder->category->name }}</p>
            <p class="mb-2"><strong>Uploaded by:</strong> {{ $folder->user->name }}</p>
            <p class="mb-4"><strong>Description:</strong> {{ $folder->description ?? 'N/A' }}</p>

            <h3 class="text-lg font-semibold mb-2">Files in this folder:</h3>
            @if($folder->files->count() > 0)
                <ul class="list-disc list-inside space-y-2">
                    @foreach($folder->files as $file)
                        <li>
                            <a href="{{ route('files.show', $file) }}" class="text-blue-400 hover:underline">
                                {{ $file->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No files found in this folder.</p>
            @endif

            <div class="mt-6">
                <a href="{{ route('documents.index') }}" class="text-blue-400 hover:underline">Back to Documents</a>
            </div>
        </div>
    </div>
</x-app-layout>
