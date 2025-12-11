<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Food;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->category;
        $search = $request->search;

        $categories = Category::all();

        $foods = Food::when($categoryId && $categoryId !== 'all', function ($query) use ($categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->take(6)
            ->get();

        return view('home', compact('categories', 'foods', 'categoryId', 'search'));
    }
}
