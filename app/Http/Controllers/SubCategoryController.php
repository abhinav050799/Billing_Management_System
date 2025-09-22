<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subcategories = SubCategory::with('category')->get();
        $categories = Category::all();
        return view('sub-categories', compact('subcategories', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subcategories,name',
            'category_id' => 'required|exists:categories,id',
        ], [
            'name.unique' => 'Yeh sub category name pehle se exist karta hai.',
            'category_id.required' => 'Category select karna zaroori hai.',
            'category_id.exists' => 'Selected category valid nahi hai.',
        ]);

        SubCategory::create($validated);

        return redirect()->route('subcategories.index')->with('success', 'Sub category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subcategories,name,' . $id,
            'category_id' => 'required|exists:categories,id',
        ], [
            'name.unique' => 'Yeh sub category name pehle se exist karta hai.',
            'category_id.required' => 'Category select karna zaroori hai.',
            'category_id.exists' => 'Selected category valid nahi hai.',
        ]);

        $subcategory = SubCategory::findOrFail($id);
        $subcategory->update($validated);

        return redirect()->route('subcategories.index')->with('success', 'Sub category updated successfully.');
    }

    public function destroy($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $subcategory->delete();

        return redirect()->route('subcategories.index')->with('success', 'Sub category deleted successfully.');
    }
}