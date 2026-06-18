<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\{DepositInfo, WithdrawalInfo, InvestmentPlan};
use App\Models\Message;

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

        $messages = Message::where('user_id', $user->id)->latest()->get();
        // DO NOT mark as seen here — only mark seen when customer clicks Messages tab

        return view('dashboard', compact(
            'user', 'depositInfo', 'withdrawalInfo', 'transactions', 'activePlans', 'messages'
        ));
    }

    // Called via AJAX when customer opens Messages tab
    public function markMessagesSeen()
    {
        $user = Auth::user();
        Message::where('user_id', $user->id)->where('seen', false)->update(['seen' => true]);
        return response()->json(['ok' => true]);
    }
}
