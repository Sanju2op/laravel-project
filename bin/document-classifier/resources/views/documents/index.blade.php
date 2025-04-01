<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Documents') }}
            </h2>
            <a href="{{ route('documents.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Upload New Document
            </a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Search and Filter Section -->
            <div class="mb-4 flex space-x-4">
                <input type="text" id="searchInput" placeholder="Search documents..." class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <select id="categoryFilter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Categories</option>
                    @foreach($documents->pluck('category')->unique() as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <select id="dateFilter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Dates</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="documentsTable">
                        @foreach($documents as $document)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $document->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $document->category->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ strtoupper($document->file_type) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $document->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $document->created_at->format('Y-m-d H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('documents.download', $document) }}" class="text-green-600 hover:text-green-900 mr-3">Download</a>
                                    <a href="{{ route('documents.edit', $document) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <form action="{{ route('documents.destroy', $document) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this document?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');
            const dateFilter = document.getElementById('dateFilter');
            const documentsTable = document.getElementById('documentsTable');

            function filterDocuments() {
                const searchTerm = searchInput.value.toLowerCase();
                const categoryValue = categoryFilter.value;
                const dateValue = dateFilter.value;
                const rows = documentsTable.getElementsByTagName('tr');

                for (let row of rows) {
                    const title = row.cells[0].textContent.toLowerCase();
                    const category = row.cells[1].textContent;
                    const date = new Date(row.cells[4].textContent);
                    const today = new Date();
                    const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                    const monthAgo = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);

                    let showRow = true;

                    if (searchTerm && !title.includes(searchTerm)) {
                        showRow = false;
                    }

                    if (categoryValue && category !== document.querySelector(`#categoryFilter option[value="${categoryValue}"]`).textContent) {
                        showRow = false;
                    }

                    if (dateValue) {
                        switch(dateValue) {
                            case 'today':
                                if (date.toDateString() !== today.toDateString()) showRow = false;
                                break;
                            case 'week':
                                if (date < weekAgo) showRow = false;
                                break;
                            case 'month':
                                if (date < monthAgo) showRow = false;
                                break;
                        }
                    }

                    row.style.display = showRow ? '' : 'none';
                }
            }

            searchInput.addEventListener('input', filterDocuments);
            categoryFilter.addEventListener('change', filterDocuments);
            dateFilter.addEventListener('change', filterDocuments);
        });
    </script>
    @endpush
</x-app-layout> 