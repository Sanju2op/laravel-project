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
        $stats = [
            'total_documents' => Document::count(),
            'total_categories' => Category::count(),
            'total_size' => Document::sum('file_size'),
            'recent_documents' => Document::with(['category', 'user'])
                ->latest()
                ->take(5)
                ->get(),
            'category_distribution' => Category::withCount('documents')
                ->get()
                ->map(function ($category) {
                    return [
                        'name' => $category->name,
                        'count' => $category->documents_count
                    ];
                })
        ];

        return view('dashboard', compact('stats'));
    }
}
