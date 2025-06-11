<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('images', 'categories');
        
        if ($request->has('categories') && !empty($request->categories)) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->whereIn('categories.id', $request->categories);
            });
        }
        
        if ($request->has('min_price') && is_numeric($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && is_numeric($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }
        
        if ($request->has('sizes') && !empty($request->sizes)) {
            $query->whereHas('sizes', function($q) use ($request) {
                $q->whereIn('size', $request->sizes)
                  ->where('quantity', '>', 0);
            });
        }
        
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }
        
        $products = $query->paginate(12);
        $categories = Category::all();
        
        return view('store', compact('products', 'categories'));
    }
    
    public function show($id)
    {
        $product = Product::with('images', 'categories', 'sizes')
            ->findOrFail($id);
            
        $relatedProducts = Product::whereHas('categories', function($q) use ($product) {
                $q->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->where('id', '!=', $product->id)
            ->with('images')
            ->take(4)
            ->get();
            
        return view('product', compact('product', 'relatedProducts'));
    }
} 