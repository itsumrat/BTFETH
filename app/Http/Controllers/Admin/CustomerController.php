<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\InvestmentPlan;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('is_admin', false)->latest()->paginate(20);
        return view('admin.customers', compact('customers'));
    }

    public function show(User $user)
    {
        $transactions = $user->transactions()->orderByDesc('transaction_date')->paginate(15);
        $activePlans  = InvestmentPlan::where('user_id', $user->id)->where('status', 'active')->get();
        return view('admin.customer-detail', compact('user', 'transactions', 'activePlans'));
    }

    public function toggle(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', "Customer {$user->name} has been " . ($user->is_active ? 'deactivated' : 'activated') . ".");
    }

    public function destroy(User $user)
    {
        // Soft delete — transactions remain in DB with user_id intact
        $user->delete();
        return redirect()->route('admin.customers')
            ->with('success', "Customer {$user->name} has been deleted. Their transaction history is preserved.");
    }
}
