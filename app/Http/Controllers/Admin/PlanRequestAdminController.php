<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlanRequest;
use App\Models\InvestmentPlan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class PlanRequestAdminController extends Controller
{
    public function index()
    {
        // Mark all pending as seen when admin opens the page
        PlanRequest::where('seen_by_admin', false)->update(['seen_by_admin' => true]);

        $requests = PlanRequest::with('user')
            ->orderByRaw("FIELD(status,'pending','approved','rejected')")
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.plan-requests', compact('requests'));
    }

    public function approve(PlanRequest $planRequest)
    {
        if ($planRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $startDate = now();
        $endDate   = now()->addDays($planRequest->duration_days);

        // Create investment plan
        $plan = InvestmentPlan::create([
            'user_id'       => $planRequest->user_id,
            'plan_name'     => $planRequest->plan_name,
            'plan_level'    => $planRequest->plan_level,
            'max_cycles'    => $planRequest->max_cycles,
            'cycle_number'  => $planRequest->cycle_number,
            'amount'        => $planRequest->amount,
            'profit_rate'   => $planRequest->profit_rate,
            'duration_days' => $planRequest->duration_days,
            'start_date'    => $startDate,
            'end_date'      => $endDate,
            'status'        => 'active',
            'notes'         => "Approved from customer request #" . $planRequest->id,
        ]);

        // Auto-create deposit transaction
        Transaction::create([
            'user_id'          => $planRequest->user_id,
            'type'             => 'deposit',
            'amount'           => $planRequest->amount,
            'reference'        => "{$planRequest->plan_name} – Cycle {$planRequest->cycle_number}",
            'status'           => 'completed',
            'transaction_date' => now(),
        ]);

        // Deduct from wallet balance (not main balance)
        $user = User::find($planRequest->user_id);
        $user->decrement('wallet_balance', $planRequest->amount);
        $user->increment('total_deposited', $planRequest->amount);

        // Recalculate daily profit
        $activePlans = InvestmentPlan::where('user_id', $planRequest->user_id)->where('status', 'active')->get();
        $dailyProfit = $activePlans->sum(fn($p) => $p->dailyProfit());
        $user->update(['daily_profit' => $dailyProfit]);

        $planRequest->update(['status' => 'approved']);

        return back()->with('success', "Request approved. {$planRequest->plan_name} Cycle {$planRequest->cycle_number} activated for {$planRequest->user->name}.");
    }

    public function reject(Request $request, PlanRequest $planRequest)
    {
        if ($planRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $planRequest->update([
            'status'     => 'rejected',
            'admin_note' => $request->input('admin_note'),
        ]);

        return back()->with('success', "Request rejected for {$planRequest->user->name}.");
    }
}
