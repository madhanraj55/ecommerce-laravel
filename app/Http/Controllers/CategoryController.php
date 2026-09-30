<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of all active categories.
     */
    public function index(): View
    {
        $categories = Category::query()
            ->where('status', true)
            ->withCount(['products' => function ($query) {
                $query->where('status', true);
            }])
            ->latest()
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Display products belonging to a category.
     */
    public function show(string $slug): View
    {
        $category = Category::query()
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $products = $category->products()
            ->where('status', true)
            ->with('category')
            ->latest()
            ->paginate(12);

        return view('categories.show', compact('category', 'products'));
    }
}
