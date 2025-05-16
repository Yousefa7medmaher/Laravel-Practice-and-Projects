<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductHoldController extends Controller
{
    public function toggleHold(Product $product)
    {
        if (!auth()->user()->can('hold_products')) {
            return back()->with('error', 'You do not have permission to hold products.');
        }

        $product->update(['hold' => !$product->hold]);

        $action = $product->hold ? 'held' : 'unheld';
        return back()->with('success', "Product has been {$action} successfully.");
    }
} 