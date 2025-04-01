<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Documents Summary -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Documents Summary</h3>
                        <div class="space-y-2">
                            <p class="text-gray-600">Total Documents: {{ \App\Models\Document::count() }}</p>
                            <p class="text-gray-600">Your Documents: {{ \App\Models\Document::where('user_id', auth()->id())->count() }}</p>
                            <a href="{{ route('documents.create') }}" class="inline-block mt-4 bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-300">
                                Upload New Document
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Categories Summary -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Categories Summary</h3>
                        <div class="space-y-2">
                            <p class="text-gray-600">Total Categories: {{ \App\Models\Category::count() ?? 0 }}</p>
                            <a href="{{ route('categories.create') }}" class="inline-block mt-4 bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-300">
                                Create New Category
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-2">
                            <a href="{{ route('documents.index') }}" class="block w-full text-center bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-300">
                                View All Documents
                            </a>
                            <a href="{{ route('categories.index') }}" class="block w-full text-center bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-300">
                                Manage Categories
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Documents -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Documents</h3>
                    @php
                        $recentDocuments = \App\Models\Document::where('user_id', auth()->id())
                            ->latest()
                            ->take(5)
                            ->get();
                    @endphp
                    
                    @if($recentDocuments->isEmpty())
                        <p class="text-gray-500">No recent documents.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentDocuments as $document)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <a href="{{ route('documents.show', $document) }}" class="hover:text-blue-600">
                                                        {{ $document->title }}
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ strtoupper($document->file_type) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $document->created_at->format('M d, Y') }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
