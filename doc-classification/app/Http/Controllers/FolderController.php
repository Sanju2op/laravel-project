<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Folder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Notifications\FolderUploadedNotification;
use ZipArchive;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;

class FolderController extends Controller
{
    /**
     * Show the form for creating a new folder upload.
     */
    public function create()
    {
        $categories = Category::where('user_id', auth()->id())->orderBy('name')->get();
        return view('folders.create', compact('categories'));
    }

    /**
     * Store a newly created folder upload in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => ['required', Rule::exists('categories', 'id')->where('user_id', auth()->id())],
            'folder' => 'required|array|min:1',
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
                    'user_id' => $user->id,
                    'name' => $file->getClientOriginalName(),
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

    /**
     * Download all files in a folder as a zip archive.
     */
    public function download($folderId)
    {
        $folder = Folder::with('files')->findOrFail($folderId);

        $zipFileName = 'folder_' . $folder->id . '.zip';
        $zipFilePath = storage_path('app/public/' . $zipFileName);

        $zip = new ZipArchive;

        // Delete existing zip if exists to avoid stale files
        if (file_exists($zipFilePath)) {
            unlink($zipFilePath);
        }

        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $addedFileNames = [];
            foreach ($folder->files as $file) {
                $filePath = storage_path('app/public/' . $file->file_path);
                if (file_exists($filePath)) {
                    $fileName = $file->name;
                    // Ensure unique file name in zip
                    $originalName = pathinfo($fileName, PATHINFO_FILENAME);
                    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                    $counter = 1;
                    while (in_array($fileName, $addedFileNames)) {
                        $fileName = $originalName . '_' . $counter . '.' . $extension;
                        $counter++;
                    }
                    $addedFileNames[] = $fileName;
                    $zip->addFile($filePath, $fileName);
                }
            }
            $zip->close();
        } else {
            return back()->with('error', 'Failed to create zip file.');
        }

        return Response::download($zipFilePath)->deleteFileAfterSend(true);
    }

    /**
     * Display the specified folder.
     */
    public function show(Folder $folder)
    {
        $folder->load('files', 'category', 'user');
        return view('folders.show', compact('folder'));
    }

    /**
     * Show the form for editing the specified folder.
     */
    public function edit(Folder $folder)
    {
        $categories = Category::where('user_id', auth()->id())->orderBy('name')->get();
        return view('folders.edit', compact('folder', 'categories'));
    }

    /**
     * Update the specified folder in storage.
     */
    public function update(Request $request, Folder $folder)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:folders,title,' . $folder->id,
            'category_id' => ['required', Rule::exists('categories', 'id')->where('user_id', auth()->id())],
            'description' => 'nullable|string',
        ]);

        $folder->update($validated);

        return redirect()->route('folders.show', $folder)->with('success', 'Folder updated successfully.');
    }

    /**
     * Remove the specified folder from storage.
     */
    public function destroy(Folder $folder)
    {
        // Delete all files in the folder from storage
        foreach ($folder->files as $file) {
            Storage::disk('public')->delete($file->file_path);
        }

        // Delete folder and its files from database
        $folder->files()->delete();
        $folder->delete();

        return redirect()->route('documents.index')->with('success', 'Folder deleted successfully.');
    }
}
