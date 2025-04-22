<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Folder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Notifications\FolderUploadedNotification;

class FolderController extends Controller
{
    /**
     * Show the form for creating a new folder upload.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('folders.create', compact('categories'));
    }

    /**
     * Store a newly created folder upload in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'folder' => 'required',
            'folder.*' => 'file|mimes:pdf,doc,docx,txt|max:10240',
            'description' => 'nullable|string'
        ]);

        $user = Auth::user();

        // Create folder record
        $folder = Folder::create([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'],
            'user_id' => $user->id,
        ]);

        // Store each file in the folder
        if ($request->hasFile('folder')) {
            foreach ($request->file('folder') as $file) {
                $path = $file->store('folders/' . $folder->id, 'public');
                $folder->files()->create([
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        // Send notification to user
        $user->notify(new FolderUploadedNotification($folder, $user));

        return redirect()->route('documents.index')
            ->with('success', 'Folder uploaded successfully.');
    }
}
