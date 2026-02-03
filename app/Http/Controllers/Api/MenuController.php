<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Category;

class MenuController extends Controller
{
    // -------------------------
    // List all foods (with optional search & category filter)
    // -------------------------
    public function index(Request $request)
    {
        $query = Food::with('category');

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Search by food name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $foods = $query->get();

        return response()->json([
            'status' => 'success',
            'foods'  => $foods
        ]);
    }

    // -------------------------
    // Show single food details
    // -------------------------
    public function details($id)
    {
        $food = Food::with('category')->find($id);

        if (!$food) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Food not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'food'   => $food
        ]);
    }

    // -------------------------
    // List all categories
    // -------------------------
    public function categories()
    {
        $categories = Category::all();

        return response()->json([
            'status'     => 'success',
            'categories' => $categories
        ]);
    }

    // -------------------------
    // Show foods by category
    // -------------------------
    public function foodsByCategory($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Category not found'
            ], 404);
        }

        $foods = $category->foods()->with('category')->get();

        return response()->json([
            'status'   => 'success',
            'category' => $category,
            'foods'    => $foods
        ]);
    }

    // -------------------------
    // Optional: list all foods (same as index, no filters)
    // -------------------------
    public function allFoods()
    {
        $foods = Food::with('category')->get();

        return response()->json([
            'status' => 'success',
            'foods'  => $foods
        ]);
    }
}