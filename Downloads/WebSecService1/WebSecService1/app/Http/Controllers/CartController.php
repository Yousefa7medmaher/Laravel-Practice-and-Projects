<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function buyNow(Request $request, Product $product)
    {
        // Only for authenticated users
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        // Store product in session cart (single product for Buy Now)
        $cart = [
            [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => 1,
            ]
        ];
        session(['cart' => $cart]);
        return redirect()->route('checkout.show');
    }

    public function add(Request $request, Product $product)
    {
        $cart = session('cart', []);
        $found = false;
        foreach ($cart as &$item) {
            if ($item['product_id'] == $product->id) {
                $item['quantity'] += 1;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $cart[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => 1,
            ];
        }
        session(['cart' => $cart]);
        return back()->with('success', 'Product added to cart!');
    }

    public function show()
    {
        $cart = session('cart', []);
        $subtotal = collect($cart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });
        $shipping = 65;
        $total = $subtotal + $shipping;
        return view('cart.show', compact('cart', 'subtotal', 'shipping', 'total'));
    }

    public function remove(Product $product)
    {
        $cart = session('cart', []);
        $cart = array_filter($cart, function($item) use ($product) {
            return $item['product_id'] != $product->id;
        });
        session(['cart' => array_values($cart)]);
        return back()->with('success', 'Product removed from cart.');
    }

    public function updateQty(Request $request, Product $product)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cart = session('cart', []);
        foreach ($cart as &$item) {
            if ($item['product_id'] == $product->id) {
                $item['quantity'] = $request->quantity;
                break;
            }
        }
        session(['cart' => $cart]);
        return back()->with('success', 'Cart updated.');
    }
} 