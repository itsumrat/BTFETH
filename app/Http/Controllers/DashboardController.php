<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\{DepositInfo, WithdrawalInfo, InvestmentPlan};

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $depositInfo = DepositInfo::where('user_id', $user->id)->first()
            ?? DepositInfo::whereNull('user_id')->first();

        $withdrawalInfo = WithdrawalInfo::where('user_id', $user->id)->first()
            ?? WithdrawalInfo::whereNull('user_id')->first();

        $transactions = $user->transactions()
            ->orderBy('transaction_date', 'desc')
            ->paginate(10);

        $activePlans = InvestmentPlan::where('user_id', $user->id)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', compact(
            'user', 'depositInfo', 'withdrawalInfo', 'transactions', 'activePlans'
        ));
    }
}
