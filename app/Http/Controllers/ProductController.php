<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'subcategory', 'brand'])->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        $brands = Brand::all();
        return view('add-product', compact('categories', 'subcategories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'brand_id' => 'required|exists:brands,id',
            'description' => 'nullable|string',
            'hsn_sac_code' => 'nullable|string|max:255',
            'unit_of_measure' => 'required|in:kg,liter,piece,meter,dozen,gram,ml',
            'unit_price' => 'required|numeric|min:0',
            'tax_type' => 'required|in:inclusive,exclusive',
            'tax_category' => 'required|in:gst,sgst,cgst,igst',
            'tax_percentage' => 'required|in:0,5,18,40',
            'quantity' => 'required|numeric|min:0',
            'quantity_alert' => 'nullable|numeric|min:0',
            'barcode' => 'nullable|string|max:255',
            'manufactured_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:manufactured_date',
        ]);

        Product::create($validated);

        // return redirect()->route('home')->with('success', 'Product created successfully.');
        return redirect("/")->with('success', 'Product created successfully.');
    }
}