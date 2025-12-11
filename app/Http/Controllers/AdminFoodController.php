<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Category;

class AdminFoodController extends Controller
{
    public function index(Request $request)
    {
        $query = Food::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $foods = $query->orderBy('id', 'desc')->get();
        return view('admin.foods.index', compact('foods'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.foods.form', compact('categories')); // single reusable form
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
        ]);

        $food = new Food();
        $food->name = $request->name;
        $food->category_id = $request->category_id;
        $food->description = $request->description; // ← IMPORTANT!!!
        $food->price = $request->price;
        

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('foods', 'public');
            $food->image = basename($path);
        }

        $food->save();
        return redirect()->route('admin.foods.index')->with('success', 'Food added successfully.');
    }

    public function edit(Food $food)
    {
        $categories = Category::all();
        return view('admin.foods.form', compact('food', 'categories')); // same form
    }

    public function update(Request $request, Food $food)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
             'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);

        $food->name = $request->name;
        $food->category_id = $request->category_id;
        $food->price = $request->price;
         $food->description = $request->description; // ← FIXED!

        if ($request->hasFile('image')) {
            if ($food->image && file_exists(public_path('storage/foods/'.$food->image))) {
                unlink(public_path('storage/foods/'.$food->image));
            }
            $path = $request->file('image')->store('foods', 'public');
            $food->image = basename($path);
        }

        $food->save();
        return redirect()->route('admin.foods.index')->with('success', 'Food updated successfully.');
    }

    public function destroy(Food $food)
    {
        if ($food->image && file_exists(public_path('storage/foods/'.$food->image))) {
            unlink(public_path('storage/foods/'.$food->image));
        }

        $food->delete();
        return redirect()->route('admin.foods.index')->with('success', 'Food deleted successfully.');
    }
}
