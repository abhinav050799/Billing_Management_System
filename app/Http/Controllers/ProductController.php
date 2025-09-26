<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'subcategory', 'brand', 'user', 'employee'])->get();
        $categories = Category::all();
        $brands = Brand::all();
        return view('product-list', compact('products', 'categories', 'brands'));
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

        // Convert dates if needed (fallback for invalid format)
        if ($request->filled('manufactured_date')) {
            try {
                $validated['manufactured_date'] = Carbon::createFromFormat('d-m-Y', $request->manufactured_date)->format('Y-m-d');
            } catch (\Exception $e) {
                // Already in Y-m-d format or invalid; rely on validation
            }
        }

        if ($request->filled('expiry_date')) {
            try {
                $validated['expiry_date'] = Carbon::createFromFormat('d-m-Y', $request->expiry_date)->format('Y-m-d');
            } catch (\Exception $e) {
                // Already in Y-m-d format or invalid; rely on validation
            }
        }

        $userId = null;
        $employeeId = null;

        if (auth()->guard('employee')->check()) {
            $employeeId = auth()->guard('employee')->id();
            $employee = \App\Models\Employee::find($employeeId);
            $userId = $employee ? $employee->user_id : null; // Get user_id from employees table
        } elseif (auth()->check()) {
            $userId = auth()->id();
        }

        $validated['user_id'] = $userId;
        $validated['employee_id'] = $employeeId;

        //  dd(auth()->user(), auth()->guard('employee')->user());

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show($id)
    {
        $product = Product::with(['category', 'subcategory', 'brand', 'user', 'employee'])->findOrFail($id);
        return view('product-details', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $subcategories = Subcategory::all();
        $brands = Brand::all();
        return view('edit-product', compact('product', 'categories', 'subcategories', 'brands'));
    }

    public function update(Request $request, $id)
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

        if ($request->filled('manufactured_date')) {
            try {
                $validated['manufactured_date'] = Carbon::createFromFormat('d-m-Y', $request->manufactured_date)->format('Y-m-d');
            } catch (\Exception $e) {
                // Already in Y-m-d format or invalid; rely on validation
            }
        }

        if ($request->filled('expiry_date')) {
            try {
                $validated['expiry_date'] = Carbon::createFromFormat('d-m-Y', $request->expiry_date)->format('Y-m-d');
            } catch (\Exception $e) {
                // Already in Y-m-d format or invalid; rely on validation
            }
        }

        $userId = null;
        $employeeId = null;

        if (auth()->guard('employee')->check()) {
            $employeeId = auth()->guard('employee')->id();
            $employee = \App\Models\Employee::find($employeeId);
            $userId = $employee ? $employee->user_id : null; // Get user_id from employees table
        } elseif (auth()->check()) {
            $userId = auth()->id();
        }

        $validated['user_id'] = $userId;
        $validated['employee_id'] = $employeeId;

        $product = Product::findOrFail($id);
        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index')->with('danger', 'Product deleted successfully.');
    }
}