<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">File Preview: {{ $file->name }}</h1>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
            @php
                $ext = strtolower(pathinfo($file->file_path, PATHINFO_EXTENSION));
            @endphp

            @if(in_array($ext, ['pdf']))
                <embed src="{{ asset('storage/' . $file->file_path) }}" type="application/pdf" width="100%" height="800px" />
            @elseif(in_array($ext, ['doc', 'docx']))
                <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('storage/' . $file->file_path)) }}" width="100%" height="800px" frameborder="0"></iframe>
            @elseif(in_array($ext, ['xls', 'xlsx']))
                <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('storage/' . $file->file_path)) }}" width="100%" height="800px" frameborder="0"></iframe>
            @elseif(in_array($ext, ['txt']))
                <pre class="whitespace-pre-wrap bg-gray-100 p-4 rounded max-h-96 overflow-auto">{{ \Illuminate\Support\Facades\Storage::disk('public')->get($file->file_path) }}</pre>
            @else
                <p>Preview not available for this file type.</p>
                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="text-blue-600 hover:underline">Download</a>
            @endif

            <div class="mt-6">
                <a href="{{ route('folders.show', $file->folder) }}" class="text-blue-600 hover:text-blue-900">Back to Folder</a>
            </div>
        </div>
    </div>
</x-app-layout>
