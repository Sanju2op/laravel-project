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
            'file' => 'required|mimes:pdf,doc,docx,jpg,png|max:2048', // Accepts only certain files
        ]);

        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store('uploads', 'public');

        $file = File::create([
            'user_id' => auth()->id(),
            'name' => $uploadedFile->getClientOriginalName(),
            'path' => $path,
            'type' => $uploadedFile->getClientMimeType(),
        ]);

        return back()->with('success', 'File uploaded successfully!');
    }
}

