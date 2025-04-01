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
        $query = Document::with(['category', 'user'])->latest();

        // Search by title
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        $documents = $query->paginate(10);
        $categories = Category::orderBy('name')->get();

        return view('documents.index', compact('documents', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
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
        $categories = Category::orderBy('name')->get();
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
