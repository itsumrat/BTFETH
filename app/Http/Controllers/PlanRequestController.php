<?php

namespace App\Http\Controllers;

use App\Models\PlanRequest;
use App\Models\InvestmentPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanRequestController extends Controller
{
    // Plan config (must match InvestmentPlanController)
    private static array $plans = [
        'Plan 1' => ['level'=>1,'rate_min'=>1.5,'rate_max'=>1.5,'min'=>500,  'max'=>1000, 'days_min'=>7, 'days_max'=>7,  'max_cycles'=>2],
        'Plan 2' => ['level'=>2,'rate_min'=>3.0,'rate_max'=>3.5,'min'=>10000,'max'=>15000,'days_min'=>15,'days_max'=>15, 'max_cycles'=>3],
        'VIP 1'  => ['level'=>3,'rate_min'=>5.0,'rate_max'=>5.0,'min'=>25000,'max'=>50000,'days_min'=>28,'days_max'=>28, 'max_cycles'=>6],
        'VIP 2'  => ['level'=>4,'rate_min'=>7.0,'rate_max'=>7.0,'min'=>50000,'max'=>999999,'days_min'=>28,'days_max'=>28,'max_cycles'=>5],
    ];

    public function store(Request $request)
    {
        $data = $request->validate([
            'plan_name' => 'required|string',
            'amount'    => 'required|numeric|min:1',
            'notes'     => 'nullable|string|max:500',
        ]);

        $plan = self::$plans[$data['plan_name']] ?? null;
        if (!$plan) return back()->with('error', 'Invalid plan selected.');

        $amount = (float)$data['amount'];
        if ($amount < $plan['min'] || $amount > $plan['max']) {
            return back()->with('error', "Amount must be between \${$plan['min']} and \${$plan['max']} for {$data['plan_name']}.");
        }

        // Check wallet balance is sufficient
        $user = \App\Models\User::find(Auth::id());
        if ($user->wallet_balance < $amount) {
            $shortfall = number_format($amount - $user->wallet_balance, 2);
            return back()->with('error', "Insufficient wallet balance. You need \${$shortfall} more. Please contact support to recharge your wallet.");
        }

        // Block if there's already an ACTIVE cycle for this plan
        $hasActive = InvestmentPlan::where('user_id', Auth::id())
            ->where('plan_name', $data['plan_name'])
            ->where('status', 'active')
            ->exists();
        if ($hasActive) {
            return back()->with('error', "You already have an active {$data['plan_name']} cycle running. Please wait for it to complete.");
        }

        // Check no pending request already exists for this plan
        $existing = PlanRequest::where('user_id', Auth::id())
            ->where('plan_name', $data['plan_name'])
            ->where('status', 'pending')
            ->exists();
        if ($existing) {
            return back()->with('error', "You already have a pending request for {$data['plan_name']}. Please wait for admin approval.");
        }

        // Cycle number = actual investment cycles (active+completed) + 1
        $cyclesUsed  = InvestmentPlan::cyclesUsed(Auth::id(), $data['plan_name']);
        $cycleNumber = $cyclesUsed + 1;

        PlanRequest::create([
            'user_id'      => Auth::id(),
            'plan_name'    => $data['plan_name'],
            'plan_level'   => $plan['level'],
            'amount'       => $amount,
            'profit_rate'  => $plan['rate_min'],
            'duration_days'=> $plan['days_min'],
            'cycle_number' => $cycleNumber,
            'max_cycles'   => $plan['max_cycles'],
            'notes'        => $data['notes'] ?? null,
            'status'       => 'pending',
            'seen_by_admin'=> false,
        ]);

        return back()->with('success', "Your request for {$data['plan_name']} has been submitted. Admin will review and activate it shortly.");
    }
}
