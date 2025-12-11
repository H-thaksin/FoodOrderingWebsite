<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Food;

class MenuController extends Controller
{
    // Display all foods with optional search and category filter
    public function index(Request $request)
    {
        $categories = Category::all();

        $query = Food::with('category');

        // Filter by category if provided
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Search by food name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $foods = $query->get();

        return view('menu.index', compact('categories', 'foods'));
    }

    // Optional: List all categories (can be used in a separate page)
    public function categories()
    {
        $categories = Category::all();
        return view('menu.categories', compact('categories'));
    }

    // Show foods for a specific category (if you want a dedicated category page)
    public function foods($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $foods = $category->foods()->with('category')->get();
        $categories = Category::all(); // for menu/filter display

        return view('menu.index', compact('categories', 'foods', 'category'));
    }

    // Show single food details
    public function details($id)
    {
        $food = Food::with('category')->findOrFail($id);
        return view('menu.details', compact('food'));
    }

    // Show all foods (optional, can use same as index)
    public function allFoods()
    {
        $foods = Food::with('category')->get();
        $categories = Category::all();
        return view('menu.index', compact('categories', 'foods'));
    }
}
