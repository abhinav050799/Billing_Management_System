<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Employee;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function productpage_store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ], [
            'name.unique' => 'This category is already Present'
        ]);

        $userId = null;
        $employeeId = null;

        if (auth()->guard('employee')->check()) {
            $employeeId = auth()->guard('employee')->id();
            $employee = Employee::find($employeeId);
            $userId = $employee ? $employee->user_id : null; // Get user_id from employees table
        } elseif (auth()->check()) {
            $userId = auth()->id();
        }

        Category::create([
            'name' => $validated['name'],
            'user_id' => $userId,
            'employee_id' => $employeeId,
        ]);


        return redirect()->back()->with('success', 'Category created successfully.');
    }

    public function index()
    {
        $categories = Category::with(['user', 'employee'])->get();
        return view('category-list', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ], [
            'name.unique' => 'This category is already Present'
        ]);

        $userId = null;
        $employeeId = null;

        if (auth()->guard('employee')->check()) {
            $employeeId = auth()->guard('employee')->id();
            $employee = Employee::find($employeeId);
            $userId = $employee ? $employee->user_id : null; // Get user_id from employees table
        } elseif (auth()->check()) {
            $userId = auth()->id();
        }

        Category::create([
            'name' => $validated['name'],
            'user_id' => $userId,
            'employee_id' => $employeeId,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ], [
            'name.unique' => 'This category is already Present'
        ]);

        $category = Category::findOrFail($id);
        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}