<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Food; 

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::query()->latest()->get();

        return response()->json([
            'data' => $foods
        ]);
    }

    public function show($id)
    {
        $food = Food::findOrFail($id);

        return response()->json([
            'data' => $food
        ]);
    }
}