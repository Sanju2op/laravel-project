<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDocuments = Document::where('user_id', auth()->id())->count();
        $totalCategories = Category::where('user_id', auth()->id())->count();
        $totalDocumentsSize = Document::where('user_id', auth()->id())->sum('file_size');

        $totalFiles = \App\Models\File::whereHas('folder', function ($q) {
            $q->where('user_id', auth()->id());
        })->count();
        $totalFilesSize = \App\Models\File::whereHas('folder', function ($q) {
            $q->where('user_id', auth()->id());
        })->sum('file_size');

        $totalFolders = \App\Models\Folder::where('user_id', auth()->id())->count();

        $recentDocuments = Document::with(['category', 'user'])
            ->where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        $recentFiles = \App\Models\File::with(['folder', 'folder.user', 'folder.files'])
            ->where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        $categoryDistribution = Category::where('user_id', auth()->id())->withCount('documents')
            ->get()
            ->map(function ($category) {
                $folderCount = \App\Models\Folder::where('category_id', $category->id)->count();

                return [
                    'name' => $category->name,
                    'count' => $category->documents_count,
                    'folder_count' => $folderCount,
                ];
            });

        $recentFolders = \App\Models\Folder::with(['category', 'user', 'files'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(5);

        $stats = [
            'total_documents' => $totalDocuments,
            'total_categories' => $totalCategories,
            'total_size' => $totalDocumentsSize,
            'total_files' => $totalFiles,
            'total_files_size' => $totalFilesSize,
            'total_folders' => $totalFolders,
            'recent_documents' => $recentDocuments,
            'recent_files' => $recentFiles,
            'recent_folders' => $recentFolders,
            'category_distribution' => $categoryDistribution,
        ];

        return view('dashboard', compact('stats'));
    }
}
