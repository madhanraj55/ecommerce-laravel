<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Display the customer's account dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();
        $recentOrders = $user->orders()->with('items.product')->latest()->take(3)->get();
        $ordersCount = $user->orders()->count();

        return view('account.index', compact('user', 'recentOrders', 'ordersCount'));
    }

    /**
     * Display all orders belonging to the authenticated customer.
     */
    public function orders(): View
    {
        $orders = Auth::user()
            ->orders()
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }
}
