<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource (Dashboard).
     */
    public function index(Request $request){

        $query = Product::with('category');

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                ->orWhere('sku', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('category_filter')) {
            $query->where('category_id', $request->category_filter);
        }

        // Fetch products with their categories for display
        $products = $query->latest()->get();
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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Optional photo upload
        ]);

        $data = $request->except('photo');
        
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('product-photos', 'public');
            $data['photo'] = $photoPath;
        }

        Product::create($data);

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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('photo');
        
        if ($request->hasFile('photo')) {
            if ($product->photo) {
                Storage::disk('public')->delete($product->photo);
            }

            $photoPath = $request->file('photo')->store('product-photos', 'public');
            $data['photo'] = $photoPath;
        }

        $product->update($data);

        return redirect()->route('dashboard')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {

        $product->delete();
        return redirect()->route('dashboard')->with('success', 'Product trashed successfully!');
    }


    public function trash()
    {
        $products = Product::onlyTrashed()->with('category')->latest('deleted_at')->get();
        $categories = Category::all();

        return view('trash', compact('products', 'categories'));
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('products.trash')->with('success', 'Product restored successfully.');
    }

    public function forceDelete($id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        if ($product->photo) {
            Storage::disk('public')->delete($product->photo);
        }

        $product->forceDelete();

        return redirect()->route('products.trash')->with('success', 'Product permanently deleted.');
    }

    public function export(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('sku', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('category_filter') && $request->category_filter != '') {
            $query->where('category_id', $request->category_filter);
        }

        $products = $query->latest()->get();

        $filename = 'products_export_' . date('Y-m-d_His') . '.pdf';

        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Products Export</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    padding: 20px;
                    background-color: #f5f5f5;
                }
                .container {
                    max-width: 1200px;
                    margin: 0 auto;
                    background-color: white;
                    padding: 30px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }
                h1 {
                    color: #333;
                    text-align: center;
                    margin-bottom: 10px;
                }
                .export-info {
                    text-align: center;
                    color: #666;
                    margin-bottom: 30px;
                    font-size: 14px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                th {
                    background-color: #4472C4;
                    color: white;
                    padding: 12px;
                    text-align: left;
                    font-weight: bold;
                    border: 1px solid #2e5c9a;
                }
                td {
                    padding: 10px 12px;
                    border: 1px solid #ddd;
                }
                tr:nth-child(even) {
                    background-color: #f9f9f9;
                }
                tr:hover {
                    background-color: #f0f0f0;
                }
                .footer {
                    margin-top: 20px;
                    padding: 15px;
                    background-color: #f0f0f0;
                    border-radius: 5px;
                    text-align: center;
                    font-weight: bold;
                    color: #333;
                }
                @media print {
                    body {
                        background-color: white;
                    }
                    .container {
                        box-shadow: none;
                    }
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Products Export Report</h1>
                <div class="export-info">
                    Exported on: ' . date('F d, Y \a\t h:i A') . '<br>
                    Total Records: ' . $products->count() . '
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Category</th>
                            <th>Created Date</th>
                        </tr>
                    </thead>
                    <tbody>';

                $number = 1;
                foreach ($products as $product) {
                    $html .= '<tr>
                    <td>' . $number++ . '</td>
                    <td>' . htmlspecialchars($product->name) . '</td>
                    <td>' . htmlspecialchars($product->sku) . '</td>
                    <td>$' . number_format($product->price, 2) . '</td>
                    <td>' . $product->stock . '</td>
                    <td>' . htmlspecialchars($product->category ? $product->category->name : 'N/A') . '</td>
                    <td>' . $product->created_at->format('Y-m-d H:i:s') . '</td>
                </tr>';
                }

                $html .= '</tbody>
                </table>

                <div class="footer">
                    Total Products: ' . $products->count() . '
                </div>
            </div>
        </body>
        </html>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->stream($filename, ['Attachment' => true]);
    }

}
