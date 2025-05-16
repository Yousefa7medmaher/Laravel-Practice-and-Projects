<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['user', 'product'])
            ->latest();

        // Apply status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('order_status', $request->status);
        }

        // Apply date filter
        if ($request->has('date') && $request->date !== '') {
            $query->whereDate('created_at', $request->date);
        }

        $purchases = $query->paginate(10);

        return view('admin.orders.index', compact('purchases'));
    }
} 