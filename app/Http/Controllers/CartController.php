<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display the shopping cart page.
     */
    public function index(): View
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $id => $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $cart[$id]['subtotal'] = $subtotal;
            $total += $subtotal;
        }

        return view('cart.index', compact('cart', 'total'));
    }

    /**
     * Add a product to the session cart.
     */
    public function add(Request $request, Product $product): RedirectResponse
    {
        if (! $product->status) {
            return redirect()->back()->with('error', 'This product is currently unavailable.');
        }

        if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'This product is out of stock.');
        }

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $requestedQty = (int) $validated['quantity'];
        $cart = session()->get('cart', []);
        $productId = $product->id;
        $currentCartQty = isset($cart[$productId]) ? (int) $cart[$productId]['quantity'] : 0;
        $newQty = $currentCartQty + $requestedQty;

        if ($newQty > $product->stock) {
            $availableToAdd = max(0, $product->stock - $currentCartQty);
            if ($availableToAdd === 0) {
                return redirect()->route('cart.index')->with('error', 'You already have the maximum available stock for this product in your cart.');
            }

            return redirect()->back()->with('error', "Only {$availableToAdd} additional items can be added. Available stock: {$product->stock}.");
        }

        $effectivePrice = ($product->sale_price !== null && $product->sale_price < $product->price)
            ? (float) $product->sale_price
            : (float) $product->price;

        $cart[$productId] = [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => $effectivePrice,
            'original_price' => (float) $product->price,
            'image' => $product->image,
            'quantity' => $newQty,
            'stock' => (int) $product->stock,
        ];

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', "'{$product->name}' added to your shopping cart.");
    }

    /**
     * Update quantity of a cart item.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $quantity = (int) $validated['quantity'];
        $cart = session()->get('cart', []);
        $productId = $product->id;

        if (! isset($cart[$productId])) {
            return redirect()->route('cart.index')->with('error', 'Item not found in cart.');
        }

        if ($quantity > $product->stock) {
            return redirect()->route('cart.index')->with('error', "Cannot update quantity. Available stock for '{$product->name}' is {$product->stock}.");
        }

        $cart[$productId]['quantity'] = $quantity;
        $cart[$productId]['stock'] = (int) $product->stock;

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove a single product from the cart.
     */
    public function remove(Product $product): RedirectResponse
    {
        $cart = session()->get('cart', []);
        $productId = $product->id;

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from your cart.');
    }

    /**
     * Clear all items from the cart.
     */
    public function clear(): RedirectResponse
    {
        session()->forget('cart');

        return redirect()->route('cart.index')->with('success', 'Your cart has been cleared.');
    }
}
