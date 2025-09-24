<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::with(['user', 'employee'])->get();
        return view('brands-list', compact('brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
        ], [
            'name.unique' => 'this brand already exist',
        ]);

        Brand::create([
            'name' => $validated['name'],
            'user_id' => auth()->check() ? auth()->id() : null,
            'employee_id' => null, // Set to null as no employee input in form
        ]);

        return redirect()->route('brands.index')->with('success', 'Brand created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $id,
        ], [
            'name.unique' => 'this brand already exist',
        ]);

        $brand = Brand::findOrFail($id);
        $brand->update($validated);

        return redirect()->route('brands.index')->with('success', 'Brand updated successfully.');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return redirect()->route('brands.index')->with('success', 'Brand deleted successfully.');
    }
}