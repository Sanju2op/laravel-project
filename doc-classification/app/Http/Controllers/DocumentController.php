<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Document::with(['category', 'user'])
            ->where('user_id', auth()->id());

        // Search by title or file size or number of files/documents
        if ($request->has('search')) {
            $search = $request->search;
            $searchLower = strtolower($search);
            $query->where(function ($q) use ($search, $searchLower) {
                $q->whereRaw('LOWER(title) like ?', ['%' . $searchLower . '%']);
                if (is_numeric($search)) {
                    $sizeInBytes = $search * 1024;
                    $q->orWhere('file_size', $sizeInBytes);
                }
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Sort by file size ascending or descending
        if ($request->has('sort_size') && in_array($request->sort_size, ['asc', 'desc'])) {
            $query->orderBy('file_size', $request->sort_size);
        }

        // Sort by date
        if ($request->has('sort_date') && in_array($request->sort_date, ['asc', 'desc'])) {
            $query->orderBy('created_at', $request->sort_date);
        } else {
            // Default sort by latest
            $query->latest();
        }

        $documents = $query->paginate(10);
        $categories = Category::orderBy('name')->get();

        // Fetch files from folders with similar filters
        $filesQuery = \App\Models\File::with(['folder', 'folder.user', 'folder.category'])
            ->whereHas('folder', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $searchLower = strtolower($search);
            $filesQuery->where(function ($q) use ($search, $searchLower) {
                $q->whereRaw('LOWER(name) like ?', ['%' . $searchLower . '%']);
                if (is_numeric($search)) {
                    $sizeInBytes = $search * 1024;
                    $q->orWhere('file_size', $sizeInBytes);
                }
            });
            $filesQuery->whereHas('folder', function ($q) use ($search, $searchLower) {
                $q->whereRaw('LOWER(title) like ?', ['%' . $searchLower . '%']);
            });
        }

        if ($request->has('category') && $request->category != '') {
            $filesQuery->whereHas('folder', function ($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        // Fetch folders with their files applying filters
            $foldersQuery = \App\Models\Folder::with(['files', 'user', 'category'])
            ->where('folders.user_id', auth()->id());

        if ($request->has('search')) {
            $search = $request->search;
            $searchLower = strtolower($search);
            $foldersQuery->where(function ($q) use ($search, $searchLower) {
                $q->whereRaw('LOWER(title) like ?', [$searchLower . '%']);
                if (is_numeric($search)) {
                    $q->whereHas('files', function ($q2) use ($search) {
                        $q2->selectRaw('count(*)')
                            ->groupBy('folder_id')
                            ->havingRaw('count(*) = ?', [(int)$search]);
                    });
                }
            });
        }

        if ($request->has('category') && $request->category != '') {
            $foldersQuery->where('category_id', $request->category);
        }

        // Sort folders by total file size
        if ($request->has('folder_sort_size') && in_array($request->folder_sort_size, ['asc', 'desc'])) {
            $foldersQuery->leftJoin('files', 'folders.id', '=', 'files.folder_id')
                ->select('folders.id', 'folders.title', 'folders.category_id', 'folders.user_id', \DB::raw('COALESCE(SUM(files.file_size), 0) as total_file_size'), \DB::raw('MAX(files.created_at) as latest_upload'))
                ->groupBy('folders.id', 'folders.title', 'folders.category_id', 'folders.user_id')
                ->orderByRaw('SUM(files.file_size) ' . $request->folder_sort_size);
        } else {
            $foldersQuery->leftJoin('files', 'folders.id', '=', 'files.folder_id')
                ->select('folders.id', 'folders.title', 'folders.category_id', 'folders.user_id', \DB::raw('COALESCE(SUM(files.file_size), 0) as total_file_size'), \DB::raw('MAX(files.created_at) as latest_upload'))
                ->groupBy('folders.id', 'folders.title', 'folders.category_id', 'folders.user_id');
        }

        $folders = $foldersQuery->paginate(10);

        return view('documents.index', compact('documents', 'categories', 'folders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('user_id', auth()->id())->orderBy('name')->get();
        return view('documents.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'file' => 'required|file|mimes:pdf,doc,docx,txt|max:10240',
            'description' => 'nullable|string'
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'public');
        $fileType = $file->getClientOriginalExtension();
        $fileSize = $file->getSize();

        $document = Document::create([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'file_path' => $path,
            'file_type' => $fileType,
            'file_size' => $fileSize,
            'description' => $validated['description'],
            'user_id' => auth()->id()
        ]);

        // Send notification to user
        $user = auth()->user();
        $user->notify(new \App\Notifications\DocumentUploadedNotification($document, $user));

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        $document->load(['category', 'user']);
        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        $categories = Category::where('user_id', auth()->id())->orderBy('name')->get();
        return view('documents.edit', compact('document', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'file' => ' |file|mimes:pdf,doc,docx,txt,html,js,css,dart,java|max:10240',
            // 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
            'description' => 'nullable|string'
        ]);

        if ($request->hasFile('file')) {
            // Delete old file
            Storage::disk('public')->delete($document->file_path);

            // Store new file
            $file = $request->file('file');
            $path = $file->store('documents', 'public');
            $fileType = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();

            $document->update([
                'file_path' => $path,
                'file_type' => $fileType,
                'file_size' => $fileSize
            ]);
        }

        $document->update([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description']
        ]);

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    public function download(Document $document)
    {
        return Storage::disk('public')->download($document->file_path);
    }
}
