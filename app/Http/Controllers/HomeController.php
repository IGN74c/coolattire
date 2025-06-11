<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('images', 'categories')
            ->latest()
            ->take(8)
            ->get();
            
        $categories = Category::all();
        
        return view('home', compact('featuredProducts', 'categories'));
    }
} 