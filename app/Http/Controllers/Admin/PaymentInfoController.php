<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{DepositInfo, WithdrawalInfo, User};
use Illuminate\Http\Request;

class PaymentInfoController extends Controller
{
    public function index()
    {
        $globalDeposit    = DepositInfo::whereNull('user_id')->first();
        $globalWithdrawal = WithdrawalInfo::whereNull('user_id')->first();

        // All non-admin customers for the override selector
        $customers = User::where('is_admin', false)->orderBy('name')->get();

        // Existing overrides keyed by user_id for easy JS lookup
        $depOverrides = DepositInfo::whereNotNull('user_id')->get()->keyBy('user_id');
        $wdOverrides  = WithdrawalInfo::whereNotNull('user_id')->get()->keyBy('user_id');

        return view('admin.payment-info', compact(
            'globalDeposit', 'globalWithdrawal',
            'customers', 'depOverrides', 'wdOverrides'
        ));
    }

    public function saveDeposit(Request $request)
    {
        $data = $request->validate([
            'user_id'              => 'nullable|exists:users,id',
            'binance_link'         => 'nullable|string|max:500',
            'wallet_address'       => 'nullable|string|max:255',
            'trust_wallet_address' => 'nullable|string|max:255',
            'trust_network'        => 'nullable|string|max:100',
            'account_name'         => 'nullable|string|max:255',
            'account_number'       => 'nullable|string|max:255',
            'bank_name'            => 'nullable|string|max:255',
            'swift'                => 'nullable|string|max:100',
            'reference'            => 'nullable|string|max:255',
        ]);

        $userId = $data['user_id'] ?? null;
        unset($data['user_id']);

        DepositInfo::updateOrCreate(['user_id' => $userId], $data);

        return back()->with('success', 'Deposit info saved successfully.');
    }

    public function saveWithdraw(Request $request)
    {
        $data = $request->validate([
            'user_id'               => 'nullable|exists:users,id',
            'withdraw_link'         => 'nullable|string|max:500',
            'withdraw_id'           => 'nullable|string|max:255',
            'trust_withdraw_address'=> 'nullable|string|max:255',
            'trust_network'         => 'nullable|string|max:100',
            'min_withdrawal'        => 'nullable|string|max:100',
            'processing_time'       => 'nullable|string|max:100',
            'fee'                   => 'nullable|string|max:100',
            'note'                  => 'nullable|string|max:500',
        ]);

        $userId = $data['user_id'] ?? null;
        unset($data['user_id']);

        WithdrawalInfo::updateOrCreate(['user_id' => $userId], $data);

        return back()->with('success', 'Withdrawal info saved successfully.');
    }

    public function removeDepositOverride(int $userId)
    {
        DepositInfo::where('user_id', $userId)->delete();
        return back()->with('success', 'Deposit override removed.');
    }

    public function removeWithdrawOverride(int $userId)
    {
        WithdrawalInfo::where('user_id', $userId)->delete();
        return back()->with('success', 'Withdrawal override removed.');
    }
}
