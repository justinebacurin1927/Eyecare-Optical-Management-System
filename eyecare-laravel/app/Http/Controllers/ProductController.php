<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Frame;
use App\Models\LensType;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Product::with('category')->orderBy('created_at', 'desc')->get();
        $frames = Frame::orderBy('created_at', 'desc')->get();
        $lensTypes = LensType::orderBy('created_at', 'desc')->get();
        return view('products.index', compact('categories', 'products', 'frames', 'lensTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'selling_price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'reorder_level' => 'required|integer|min:0',
            'reorder_quantity' => 'required|integer|min:0',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'selling_price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'reorder_level' => 'required|integer|min:0',
            'reorder_quantity' => 'required|integer|min:0',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function storeFrame(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'style' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
        ]);

        Frame::create($validated);

        return redirect()->route('products.index')->with('success', 'Frame added successfully.');
    }

    public function storeLensType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'material' => 'nullable|string|max:255',
            'coating' => 'nullable|string|max:255',
        ]);

        LensType::create($validated);

        return redirect()->route('products.index')->with('success', 'Lens type added successfully.');
    }
}
