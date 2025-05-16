<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductLikeController extends Controller
{
    public function toggleLike(Product $product)
    {
        if (!Auth::user()->hasRole('customer')) {
            return back()->with('error', 'Only customers can like products.');
        }

        // Check if user has purchased the product
        if (!$product->hasBeenPurchasedBy(Auth::user())) {
            return back()->with('error', 'You can only like products you have purchased.');
        }

        // Check if user has already liked the product
        if ($product->isLikedBy(Auth::user())) {
            $product->likes()->where('user_id', Auth::id())->delete();
            return back()->with('success', 'Product unliked successfully.');
        }

        // Create new like
        ProductLike::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id
        ]);

        return back()->with('success', 'Product liked successfully.');
    }
} 