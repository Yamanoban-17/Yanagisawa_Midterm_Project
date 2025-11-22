<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource (Dashboard).
     */
    public function index()
    {
        // Fetch products with their categories for display
        $products = Product::with('category')->latest()->get();
        // Fetch categories for the 'Add New' form dropdown
        $categories = Category::all();

        // Required statistics for the dashboard cards
        $totalProducts = Product::count();
        $inStock = Product::where('stock', '>', 0)->count();
        $totalCategories = Category::count(); // Third dynamic card

        return view('dashboard', compact('products', 'categories', 'totalProducts', 'inStock', 'totalCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Form validation (Required Feature)
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|unique:products,sku|max:50',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id', // Can be null
        ]);

        Product::create($request->all());

        return redirect()->route('dashboard')->with('success', 'Product added successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Form validation (Required Feature)
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|unique:products,sku,' . $product->id . '|max:50',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $product->update($request->all());

        return redirect()->route('dashboard')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('dashboard')->with('success', 'Product deleted successfully!');
    }
}
