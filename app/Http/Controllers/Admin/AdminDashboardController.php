<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Transaction};

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalCustomers  = User::where('is_admin', false)->count();
        $totalDeposited  = Transaction::where('type', 'deposit')->where('status', 'completed')->sum('amount');
        $totalWithdrawn  = Transaction::where('type', 'withdraw')->where('status', 'completed')->sum('amount');
        $pendingCount    = Transaction::where('status', 'pending')->count();

        $recentTxns = Transaction::with('user')
            ->orderByDesc('transaction_date')
            ->take(10)
            ->get();

        $newCustomers = User::where('is_admin', false)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalCustomers', 'totalDeposited', 'totalWithdrawn',
            'pendingCount', 'recentTxns', 'newCustomers'
        ));
    }
}
