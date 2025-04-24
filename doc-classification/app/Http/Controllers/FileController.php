<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx,jpg,png,dart,js,html,css|max:2048', // Accepts only certain files
            'folder_id' => 'required|integer|exists:folders,id',
        ]);

        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store('uploads', 'public');

        $file = File::create([
            'user_id' => auth()->id(),
            'folder_id' => $request->input('folder_id'),
            'file_path' => $path,
            'file_type' => $uploadedFile->getClientOriginalExtension(),
            'file_size' => $uploadedFile->getSize(),
            'name' => $uploadedFile->getClientOriginalName(),
        ]);

        return back()->with('success', 'File uploaded successfully!');
    }

    public function show(File $file)
    {
        $file->load('folder');
        return view('files.show', compact('file'));
    }

    public function edit(File $file)
    {
        $file->load('folder');
        return view('files.edit', compact('file'));
    }

    public function update(Request $request, File $file)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'nullable|mimes:pdf,doc,docx,jpg,png,dart,js,html,css|max:2048',
        ]);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($file->file_path);

            $uploadedFile = $request->file('file');
            $path = $uploadedFile->store('uploads', 'public');

            $file->update([
                'file_path' => $path,
                'file_type' => $uploadedFile->getClientOriginalExtension(),
                'file_size' => $uploadedFile->getSize(),
                'name' => $validated['name'],
            ]);
        } else {
            $file->update([
                'name' => $validated['name'],
            ]);
        }

        return redirect()->route('files.show', $file)->with('success', 'File updated successfully.');
    }

    public function destroy(File $file)
    {
        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return redirect()->route('documents.index')->with('success', 'File deleted successfully.');
    }
}

