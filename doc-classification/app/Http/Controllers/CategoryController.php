<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories for the authenticated user.
     */
    public function index()
    {
        $categories = DB::table('categories')
            ->leftJoin('documents', 'categories.id', '=', 'documents.category_id')
            ->select('categories.*', DB::raw('COUNT(documents.id) as documents_count'))
            ->where('categories.user_id', Auth::id())
            ->groupBy('categories.id', 'categories.name', 'categories.description', 'categories.user_id', 'categories.created_at', 'categories.updated_at')
            ->orderBy('categories.name')
            ->paginate(10);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->where(function ($query) {
                return $query->where('user_id', Auth::id());
            })],
            'description' => ['nullable', 'string'],
        ]);

        $categoryId = DB::table('categories')->insertGetId([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'user_id' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Fetch the inserted category as an object
        $category = DB::table('categories')->where('id', $categoryId)->first();

        // Optionally, you can do something with $category here

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit($id)
    {
        $category = DB::table('categories')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$category) {
            abort(404);
        }

        // Convert stdClass object to array to avoid view errors
        $category = (array) $category;

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        $category = DB::table('categories')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$category) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($id)->where(function ($query) {
                return $query->where('user_id', Auth::id());
            })],
            'description' => ['nullable', 'string'],
        ]);

        DB::table('categories')
            ->where('id', $id)
            ->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'updated_at' => now(),
            ]);

        // Update documents_count for the updated category
        $documentsCount = DB::table('documents')
            ->where('category_id', $id)
            ->count();

        // Optionally, you can store this count in a cache or a dedicated column if needed

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        $category = DB::table('categories')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$category) {
            abort(404);
        }

        DB::table('categories')->where('id', $id)->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
