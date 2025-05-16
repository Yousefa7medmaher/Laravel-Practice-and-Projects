<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        // Here you would fetch the cart for the logged-in user
        // For now, pass an empty array or mock data
        $cart = session('cart', []); // Or fetch from DB
        $user = Auth::user();
        return view('checkout.show', compact('cart', 'user'));
    }

    public function process(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.show')->with('error', 'Your cart is empty.');
        }

        $user = Auth::user();
        
        try {
            DB::beginTransaction();
            
            foreach ($cart as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                // Verify stock
                if (!$product->isInStock() || $item['quantity'] > $product->stock_quantity) {
                    throw new \Exception("Product {$product->name} is out of stock or not enough quantity available.");
                }

                // Create purchase record
                $purchase = Purchase::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'total_price' => $product->price * $item['quantity'],
                    'delivery_method' => $request->delivery_method ?? 'home',
                    'delivery_address' => $request->delivery_address,
                    'pickup_branch' => $request->pickup_branch,
                    'color' => $request->color ?? 'default',
                    'payment_method' => $request->payment_method ?? 'cod',
                    'order_status' => 'Pending'
                ]);

                // Decrease stock
                $product->decreaseStock($item['quantity']);

                // If payment method is card, deduct from user's credit
                if ($request->payment_method === 'card') {
                    $totalPrice = $product->price * $item['quantity'];
                    if ($user->getCreditBalance() < $totalPrice) {
                        throw new \Exception('Insufficient credit balance.');
                    }
                    $user->deductCredit($totalPrice);
                }
            }

            DB::commit();
            session()->forget('cart');
            
            return redirect()->route('purchases.index')
                ->with('success', 'Order placed successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error processing order: ' . $e->getMessage());
        }
    }
} 