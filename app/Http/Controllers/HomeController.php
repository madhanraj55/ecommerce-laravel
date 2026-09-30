<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Display the storefront home page with active categories and products.
     */
    public function index(): View
    {
        $categories = Category::query()
            ->where('status', true)
            ->latest()
            ->get();

        $products = Product::query()
            ->where('status', true)
            ->where('stock', '>', 0)
            ->with('category')
            ->latest()
            ->limit(8)
            ->get();

        return view('home', compact('categories', 'products'));
    }
}
