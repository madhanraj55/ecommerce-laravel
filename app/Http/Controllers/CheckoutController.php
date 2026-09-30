<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function index(): View|RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (empty($cart) || !is_array($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your shopping cart is empty. Please add items before checking out.');
        }

        // Verify and calculate current total
        $total = 0;
        foreach ($cart as $item) {
            $total += (float) ($item['price'] * $item['quantity']);
        }

        return view('checkout.index', [
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    /**
     * Store a newly created order and its items in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart) || !is_array($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your shopping cart is empty.');
        }

        // Database transaction for atomic order creation
        try {
            $order = DB::transaction(function () use ($validated, $cart) {
                $orderTotal = 0;
                $itemsToCreate = [];

                // Re-validate every cart item against the live database
                foreach ($cart as $productId => $cartItem) {
                    /** @var Product|null $product */
                    $product = Product::lockForUpdate()->find($productId);

                    if (!$product) {
                        throw new \Exception("A product in your cart no longer exists. Please review your cart.");
                    }

                    if (!$product->status) {
                        throw new \Exception("Product '{$product->name}' is currently unavailable. Please remove it from your cart.");
                    }

                    $requestedQty = (int) ($cartItem['quantity'] ?? 1);
                    if ($requestedQty < 1) {
                        throw new \Exception("Invalid quantity for product '{$product->name}'.");
                    }

                    if ($requestedQty > $product->stock) {
                        throw new \Exception("Insufficient stock for '{$product->name}'. Only {$product->stock} unit(s) available.");
                    }

                    // Server-side authoritative price calculation
                    $effectivePrice = ($product->sale_price !== null && $product->sale_price > 0 && $product->sale_price < $product->price)
                        ? (float) $product->sale_price
                        : (float) $product->price;

                    $subtotal = $effectivePrice * $requestedQty;
                    $orderTotal += $subtotal;

                    $itemsToCreate[] = [
                        'product' => $product,
                        'product_id' => $product->id,
                        'quantity' => $requestedQty,
                        'price' => $effectivePrice,
                        'subtotal' => $subtotal,
                    ];
                }

                // Generate unique order number
                do {
                    $orderNumber = 'ORD-' . strtoupper(Str::random(8));
                } while (Order::where('order_number', $orderNumber)->exists());

                // Create Order record
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'order_number' => $orderNumber,
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'total' => $orderTotal,
                    'status' => 'pending',
                ]);

                // Create OrderItems and decrease inventory
                foreach ($itemsToCreate as $itemData) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $itemData['product_id'],
                        'quantity' => $itemData['quantity'],
                        'price' => $itemData['price'],
                        'subtotal' => $itemData['subtotal'],
                    ]);

                    // Deduct stock safely
                    /** @var Product $product */
                    $product = $itemData['product'];
                    $product->stock = max(0, $product->stock - $itemData['quantity']);
                    $product->save();
                }

                return $order;
            });

            // Clear cart from session
            session()->forget('cart');

            // Redirect to order success page
            return redirect()->route('orders.success', $order->order_number)
                ->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            return redirect()->route('checkout.index')
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the order success confirmation page.
     */
    public function success(string $order_number): View|RedirectResponse
    {
        $order = Order::with('items.product')->where('order_number', $order_number)->firstOrFail();

        return view('orders.success', [
            'order' => $order,
        ]);
    }
}
