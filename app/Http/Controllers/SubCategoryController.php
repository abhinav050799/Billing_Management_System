<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::with(['category', 'user', 'employee'])->get();
        $categories = Category::all();
        return view('sub-categories', compact('subcategories', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subcategories,name',
            'category_id' => 'required|exists:categories,id',
        ], [
            'name.unique' => 'this sub-category already exist',
            'category_id.required' => 'Category is required',
            'category_id.exists' => 'Selected category is not valid',
        ]);

        $userId = null;
        $employeeId = null;

        if (auth()->guard('employee')->check()) {
            $employeeId = auth()->guard('employee')->id();
            $employee = \App\Models\Employee::find($employeeId);
            $userId = $employee ? $employee->user_id : null; // Get user_id from employees table
        } elseif (auth()->check()) {
            $userId = auth()->id();
        }

        SubCategory::create([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'user_id' => $userId,
            'employee_id' => $employeeId,
        ]);

        return redirect()->route('subcategories.index')->with('success', 'Sub category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subcategories,name,' . $id,
            'category_id' => 'required|exists:categories,id',
        ], [
            'name.unique' => 'this sub-category already exist',
            'category_id.required' => 'Category is required',
            'category_id.exists' => 'Selected category is not valid',
        ]);

        $subcategory = Subcategory::findOrFail($id);
        $subcategory->update($validated);

        return redirect()->route('subcategories.index')->with('success', 'Sub category updated successfully.');
    }

    public function destroy($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $subcategory->delete();

        return redirect()->route('subcategories.index')->with('success', 'Sub category deleted successfully.');
    }
}