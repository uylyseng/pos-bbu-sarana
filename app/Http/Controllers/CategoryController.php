<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index(Request $request)
{
    // Get the 'perPage' parameter from the query string, or set a default value of 10
    $perPage = $request->get('perPage', 10);
    
    // Validate the 'perPage' parameter to allow only specific values (2, 4, 5, 10, 20)
    $perPage = in_array($perPage, [2, 4, 5, 10, 20]) ? $perPage : 10;
    
    // Paginate categories based on the 'perPage' value
    $categories = Category::paginate($perPage);
    
    // Return the view with the categories and the 'perPage' value
    return view('categories.index', compact('categories', 'perPage'));
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
        $request->validate([
            'name'        => 'required|string|max:100',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,inactive',
        ]);

        $data = $request->only(['name', 'description', 'status']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'Created category successfully.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,inactive',
        ]);

        $data = $request->only(['name', 'description', 'status']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Updated category successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Deleted category successfully.');
    }
}
