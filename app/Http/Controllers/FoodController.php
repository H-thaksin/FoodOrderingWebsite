<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Category;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::with('category')->get();
        return view('foods.index', compact('foods'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('foods.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'name'        => 'required',
            'price'       => 'required|numeric',
             'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,png,jpeg'
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->image->getClientOriginalName();
            $request->image->storeAs('foods', $imageName, 'public');
        }

        Food::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'image'       => $imageName
        ]);

        return redirect()->route('foods.index')
                         ->with('success', 'Food created successfully.');
    }

    public function edit(Food $food)
    {
        $categories = Category::all();
        return view('foods.edit', compact('food', 'categories'));
    }

    public function update(Request $request, Food $food)
    {
        $request->validate([
            'category_id' => 'required',
            'name'        => 'required',
            'price'       => 'required|numeric',
            'image'       => 'nullable|image|mimes:jpg,png,jpeg'
        ]);

        $imageName = $food->image;

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->image->getClientOriginalName();
            $request->image->storeAs('foods', $imageName, 'public');
        }

        $food->update([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'image'       => $imageName
        ]);

        return redirect()->route('foods.index')
                         ->with('success', 'Food updated successfully.');
    }

    public function destroy(Food $food)
    {
        $food->delete();

        return redirect()->route('foods.index')
                         ->with('success', 'Food removed successfully.');
    }
}
