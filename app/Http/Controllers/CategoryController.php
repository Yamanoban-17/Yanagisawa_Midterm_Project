<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource (Categories Page).
     */
    public function index()
    {
        // Fetch categories and count their related products (Required Feature)
        $categories = Category::withCount('products')->latest()->get();
        return view('categories', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Form validation (Required Feature: unique for category name)
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create($request->all());

        return redirect()->route('categories')->with('success', 'Category added successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        // Form validation (Required Feature)
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id, // unique rule ignores current category id
            'description' => 'nullable|string',
        ]);

        $category->update($request->all());

        return redirect()->route('categories')->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Because of onDelete('set null') in the migration, products in this category will just have their category_id set to NULL.

        $category->delete();
        return redirect()->route('categories')->with('success', 'Category deleted successfully!');
    }
}
