<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{InvestmentPlan, Transaction, User};
use Illuminate\Http\Request;
use Carbon\Carbon;

class InvestmentPlanController extends Controller
{
    public function index(Request $request)
    {
        $customers = User::where('is_admin', false)->orderBy('name')->get();

        // Cycle usage summary per customer per plan
        $customerPlanSummary = [];
        foreach ($customers as $c) {
            foreach (array_keys(InvestmentPlan::$planConfig) as $planName) {
                $used = InvestmentPlan::cyclesUsed($c->id, $planName);
                $max  = InvestmentPlan::$planConfig[$planName]['max_cycles'];
                $customerPlanSummary[$c->id][$planName] = ['used' => $used, 'max' => $max];
            }
        }

        $plans = InvestmentPlan::with('user')->latest()->paginate(20, ['*'], 'plans_page');

        // Transaction history (withdrawals + auto deposits)
        $txnQuery = Transaction::with('user');
        if ($request->filled('txn_customer')) $txnQuery->where('user_id', $request->txn_customer);
        if ($request->filled('txn_type'))     $txnQuery->where('type', $request->txn_type);
        $transactions = $txnQuery->orderByDesc('transaction_date')->paginate(15, ['*'], 'txn_page')->withQueryString();

        // Stats
        $stats = [
            'total'     => Transaction::count(),
            'deposits'  => Transaction::where('type','deposit')->where('status','completed')->sum('amount'),
            'withdraws' => Transaction::where('type','withdraw')->where('status','completed')->sum('amount'),
            'pending'   => Transaction::where('status','pending')->count(),
        ];

        return view('admin.investment-plans', compact(
            'customers','plans','customerPlanSummary','transactions','stats'
        ));
    }

    public function checkCycle(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id', 'plan_name' => 'required|string']);
        $result = InvestmentPlan::canStartCycle($request->user_id, $request->plan_name);
        $config = InvestmentPlan::$planConfig[$request->plan_name] ?? [];
        return response()->json(array_merge($result, ['config' => $config]));
    }

    // ── Assign investment → auto-creates deposit transaction ──
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'       => 'required|exists:users,id',
            'plan_name'     => 'required|string',
            'amount'        => 'required|numeric|min:1',
            'profit_rate'   => 'required|numeric|min:0.01|max:100',
            'duration_days' => 'required|integer|min:1',
            'start_date'    => 'required|date',
            'notes'         => 'nullable|string|max:500',
        ]);

        // Cycle limit check
        $check = InvestmentPlan::canStartCycle($data['user_id'], $data['plan_name']);
        if (!$check['allowed']) {
            return back()->withErrors(['cycle' => $check['reason']])->withInput();
        }

        $config      = InvestmentPlan::$planConfig[$data['plan_name']];
        $cycleNumber = InvestmentPlan::cyclesUsed($data['user_id'], $data['plan_name']) + 1;
        $start       = Carbon::parse($data['start_date']);
        $end         = $start->copy()->addDays($data['duration_days']);

        // 1. Create the investment plan
        $plan = InvestmentPlan::create([
            'user_id'       => $data['user_id'],
            'plan_name'     => $data['plan_name'],
            'plan_level'    => $config['level'],
            'max_cycles'    => $config['max_cycles'],
            'cycle_number'  => $cycleNumber,
            'amount'        => $data['amount'],
            'profit_rate'   => $data['profit_rate'],
            'duration_days' => $data['duration_days'],
            'start_date'    => $start,
            'end_date'      => $end,
            'status'        => 'active',
            'notes'         => $data['notes'] ?? null,
        ]);

        // 2. Auto-create deposit transaction
        Transaction::create([
            'user_id'          => $data['user_id'],
            'type'             => 'deposit',
            'amount'           => $data['amount'],
            'reference'        => "{$data['plan_name']} – Cycle {$cycleNumber}",
            'status'           => 'completed',
            'transaction_date' => now(),
        ]);

        // 3. Update user balance + totals
        $user = User::find($data['user_id']);
        $user->increment('balance', $data['amount']);
        $user->increment('total_deposited', $data['amount']);

        // 4. Recalculate daily profit
        $this->recalculateUserProfit($data['user_id']);

        return back()->with('success', "✅ {$data['plan_name']} Cycle {$cycleNumber} assigned to {$user->name}. Deposit of \${$data['amount']} recorded automatically.");
    }

    // ── Manual withdrawal entry ──
    public function storeWithdraw(Request $request)
    {
        $data = $request->validate([
            'user_id'          => 'required|exists:users,id',
            'amount'           => 'required|numeric|min:0.01',
            'reference'        => 'nullable|string|max:255',
            'transaction_date' => 'nullable|date',
            'status'           => 'required|in:completed,pending,failed',
        ]);

        $data['type']             = 'withdraw';
        $data['transaction_date'] = $data['transaction_date'] ?? now();

        Transaction::create($data);

        if ($data['status'] === 'completed') {
            $user = User::find($data['user_id']);
            $user->decrement('balance', $data['amount']);
            $user->increment('total_withdrawn', $data['amount']);
        }

        return back()->with('success', 'Withdrawal recorded successfully.');
    }

    // ── Mark cycle complete (goal reached) ──
    // Customer always receives FULL cycle profit (duration_days × daily_profit)
    public function complete(InvestmentPlan $plan)
    {
        // Full profit = daily profit × full duration days (regardless of when completed)
        $totalProfit = round($plan->dailyProfit() * $plan->duration_days, 2);

        // Credit full profit to customer balance
        $user = User::find($plan->user_id);
        $user->increment('balance', $totalProfit);

        // Record profit transaction
        Transaction::create([
            'user_id'          => $plan->user_id,
            'type'             => 'deposit',
            'amount'           => $totalProfit,
            'reference'        => "{$plan->plan_name} Cycle {$plan->cycle_number} — Full profit ({$plan->duration_days} days × {$plan->profit_rate}%)",
            'status'           => 'completed',
            'transaction_date' => now(),
        ]);

        $plan->update(['status' => 'completed']);
        $this->recalculateUserProfit($plan->user_id);

        $remaining = $plan->max_cycles - InvestmentPlan::cyclesUsed($plan->user_id, $plan->plan_name);
        $msg  = "Cycle {$plan->cycle_number} of {$plan->plan_name} completed for {$plan->user->name}. ";
        $msg .= "Full profit of \${$totalProfit} credited to balance. ";
        $msg .= $remaining <= 0
            ? "All cycles exhausted — customer must upgrade to next plan."
            : "{$remaining} cycle(s) remaining in {$plan->plan_name}.";

        return back()->with('success', $msg);
    }

    // ── Edit plan (rate, duration, status) ──
    public function update(Request $request, InvestmentPlan $plan)
    {
        $data = $request->validate([
            'profit_rate'   => 'required|numeric|min:0.01|max:100',
            'duration_days' => 'required|integer|min:1',
            'start_date'    => 'required|date',
            'status'        => 'required|in:active,completed,cancelled',
            'notes'         => 'nullable|string|max:500',
        ]);

        $start          = Carbon::parse($data['start_date']);
        $data['end_date'] = $start->copy()->addDays($data['duration_days']);
        $plan->update($data);
        $this->recalculateUserProfit($plan->user_id);

        return back()->with('success', 'Investment plan updated.');
    }

    // ── Delete plan ──
    // Active cycle  → reverse deposit amount from balance + total_deposited
    // Completed cycle → already paid out, only delete record (no balance change)
    public function destroy(InvestmentPlan $plan)
    {
        $userId = $plan->user_id;
        $amount = (float) $plan->amount;
        $isActive = $plan->status === 'active';

        // Find the original deposit transaction for this cycle
        $depositTxn = Transaction::where('user_id', $userId)
            ->where('type', 'deposit')
            ->where('amount', $amount)
            ->where('reference', 'like', "{$plan->plan_name} – Cycle {$plan->cycle_number}%")
            ->first();

        if ($isActive) {
            // Reverse deposit: remove from balance and total_deposited
            if ($depositTxn) {
                $depositTxn->delete();
            }
            $user = User::find($userId);
            $user->decrement('balance', $amount);
            $user->decrement('total_deposited', $amount);

            $msg = "Active cycle deleted — deposit of \${$amount} reversed from balance.";
        } else {
            // Completed: cycle already paid out — just delete the deposit record silently
            // (profit transaction stays in history)
            if ($depositTxn) {
                $depositTxn->delete();
            }
            $msg = "Completed cycle record deleted. Balance unchanged.";
        }

        $plan->delete();
        $this->recalculateUserProfit($userId);

        return back()->with('success', $msg);
    }

    // ── Update/delete standalone transactions ──
    public function updateTxn(Request $request, Transaction $transaction)
    {
        $old = $transaction->status;
        $new = $request->validate(['status' => 'required|in:completed,pending,failed'])['status'];
        $transaction->update(['status' => $new]);

        if ($old !== 'completed' && $new === 'completed') {
            $this->applyBalance($transaction->user_id, $transaction->type, $transaction->amount);
        }
        if ($old === 'completed' && $new !== 'completed') {
            $reverse = $transaction->type === 'deposit' ? 'withdraw' : 'deposit';
            $this->applyBalance($transaction->user_id, $reverse, $transaction->amount);
        }

        return back()->with('success', 'Transaction status updated.');
    }

    public function destroyTxn(Transaction $transaction)
    {
        if ($transaction->status === 'completed') {
            $reverse = $transaction->type === 'deposit' ? 'withdraw' : 'deposit';
            $this->applyBalance($transaction->user_id, $reverse, $transaction->amount);
        }
        $transaction->delete();
        return back()->with('success', 'Transaction deleted.');
    }

    private function recalculateUserProfit(int $userId): void
    {
        $user  = User::find($userId);
        $daily = InvestmentPlan::where('user_id', $userId)->where('status', 'active')
            ->get()->sum(fn($p) => $p->dailyProfit());
        $user->update(['daily_profit' => $daily]);
    }

    private function applyBalance(int $userId, string $type, float $amount): void
    {
        $user = User::find($userId);
        if (!$user) return;
        if ($type === 'deposit') {
            $user->increment('balance', $amount);
            $user->increment('total_deposited', $amount);
        } else {
            $user->decrement('balance', $amount);
            $user->increment('total_withdrawn', $amount);
        }
    }
}
