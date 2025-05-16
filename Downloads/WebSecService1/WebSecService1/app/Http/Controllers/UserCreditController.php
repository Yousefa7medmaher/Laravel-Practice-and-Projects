<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserCreditController extends Controller
{
    public function show(User $user)
    {
        Gate::authorize('manage-customer-credits');
        return view('credits.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        Gate::authorize('manage-customer-credits');

        $request->validate([
            'amount' => 'required|numeric|min:0'
        ]);

        $amount = $request->input('amount');
        $user->addCredit($amount);

        return redirect()->route('credits.show', $user)
            ->with('success', 'Credit added successfully.');
    }
} 