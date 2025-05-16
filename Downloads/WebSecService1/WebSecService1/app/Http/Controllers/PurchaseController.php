<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Auth::user()->purchases()->with('product')->get();
        return view('purchases.index', compact('purchases'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'delivery_method' => 'required|in:home,pickup',
            'color' => 'required|in:red,blue,black',
            'payment_method' => 'required|in:cod,card',
            'delivery_address' => 'required_if:delivery_method,home',
            'pickup_branch' => 'required_if:delivery_method,pickup',
        ]);

        $quantity = $request->input('quantity');
        $user = Auth::user();

        if (!$user->hasRole('customer')) {
            return redirect()->back()->with('error', 'Only customers can make purchases.');
        }

        if (!$product->isInStock() || $quantity > $product->stock_quantity) {
            return redirect()->back()->with('error', 'Product is out of stock or not enough quantity.');
        }

        $totalPrice = $product->price * $quantity;
        if ($user->getCreditBalance() < $totalPrice && $request->payment_method === 'card') {
            return redirect()->back()->with('error', 'Insufficient credit balance for card payment.');
        }

        // Decrease stock
        $product->decreaseStock($quantity);

        // Deduct credit if payment method is card
        if ($request->payment_method === 'card') {
            $user->deductCredit($totalPrice);
        }

        $purchase = Purchase::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
            'delivery_method' => $request->delivery_method,
            'delivery_address' => $request->delivery_method === 'home' ? $request->delivery_address : null,
            'pickup_branch' => $request->delivery_method === 'pickup' ? $request->pickup_branch : null,
            'color' => $request->color,
            'payment_method' => $request->payment_method,
            'card_last4' => $request->payment_method === 'card' ? ($request->card_last4 ?? '0000') : null,
        ]);

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase completed successfully!');
    }

    public function updateStatus(Request $request, Purchase $purchase)
    {
        $request->validate([
            'order_status' => 'required|in:Pending,On the way,Delivered',
            'estimated_delivery_time' => 'nullable|date',
        ]);
        $purchase->order_status = $request->order_status;
        if ($request->order_status === 'Delivered') {
            $purchase->delivered_at = now();
        }
        if ($request->filled('estimated_delivery_time')) {
            $purchase->estimated_delivery_time = $request->estimated_delivery_time;
        }
        $purchase->save();
        // TODO: Notify customer
        return back()->with('success', 'Order status updated.');
    }

    public function tracking(Purchase $purchase)
    {
        // Only allow the customer who owns the order to view
        if (auth()->id() !== $purchase->user_id && !auth()->user()->hasRole('admin')) {
            abort(403);
        }
        return view('purchases.tracking', compact('purchase'));
    }
} 