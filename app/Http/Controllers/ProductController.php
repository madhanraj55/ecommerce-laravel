<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of all active products with optional filtering.
     */
    public function index(Request $request): View
    {
        $categorySlug = $request->query('category');
        $search = $request->query('search');

        $query = Product::query()
            ->where('status', true)
            ->with('category');

        if ($categorySlug) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        $categories = Category::query()
            ->where('status', true)
            ->withCount(['products' => function ($q) {
                $q->where('status', true);
            }])
            ->get();

        return view('products.index', compact('products', 'categories', 'categorySlug', 'search'));
    }

    /**
     * Display the specified active product along with related/remaining products.
     */
    public function show(string $slug): View
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('status', true)
            ->with('category')
            ->firstOrFail();

        // Fetch remaining active products (excluding the current one)
        $remainingProducts = Product::query()
            ->where('status', true)
            ->where('id', '!=', $product->id)
            ->with('category')
            ->latest()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'remainingProducts'));
    }
}
