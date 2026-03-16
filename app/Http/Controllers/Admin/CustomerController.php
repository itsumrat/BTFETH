<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_admin', false);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $customers = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.customers', compact('customers'));
    }

    public function show(User $user)
    {
        $transactions = $user->transactions()->orderByDesc('transaction_date')->paginate(15);
        return view('admin.customer-detail', compact('user', 'transactions'));
    }

    public function toggle(User $user)
    {
        $user->update(['is_active' => ! $user->is_active]);
        return back()->with('success', 'Customer status updated.');
    }
}
