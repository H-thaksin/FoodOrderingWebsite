<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // 1️⃣ List all categories
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    // 2️⃣ Show Add Category form
    public function create()
    {
        return view('categories.create');
    }

    // 3️⃣ Save new Category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255'
        ]);

        Category::create([
            'name' => $request->name
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    // 4️⃣ Show Edit Category form
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    // 5️⃣ Update Category in database
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|max:255'
        ]);

        $category->update([
            'name' => $request->name
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    // 6️⃣ Delete Category
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
