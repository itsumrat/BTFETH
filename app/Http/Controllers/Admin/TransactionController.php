<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Transaction, User};
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user');

        if ($request->filled('customer')) {
            $query->where('user_id', $request->customer);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $transactions = $query->orderByDesc('transaction_date')->paginate(20)->withQueryString();
        $customers    = User::where('is_admin', false)->orderBy('name')->get();

        // Stats for filtered result
        $stats = [
            'total'    => Transaction::count(),
            'deposits' => Transaction::where('type', 'deposit')->where('status', 'completed')->sum('amount'),
            'withdraws'=> Transaction::where('type', 'withdraw')->where('status', 'completed')->sum('amount'),
            'pending'  => Transaction::where('status', 'pending')->count(),
        ];

        return view('admin.transactions', compact('transactions', 'customers', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'          => 'required|exists:users,id',
            'type'             => 'required|in:deposit,withdraw',
            'amount'           => 'required|numeric|min:0.01',
            'reference'        => 'nullable|string|max:255',
            'status'           => 'required|in:completed,pending,failed',
            'transaction_date' => 'nullable|date',
        ]);

        $validated['transaction_date'] = $validated['transaction_date'] ?? now();

        $transaction = Transaction::create($validated);

        // Auto-update balance when status is completed
        if ($validated['status'] === 'completed') {
            $this->applyBalanceChange($validated['user_id'], $validated['type'], $validated['amount']);
        }

        return back()->with('success', 'Transaction saved successfully.');
    }

    public function update(Request $request, Transaction $transaction)
    {
        $oldStatus = $transaction->status;
        $newStatus = $request->status;

        $validated = $request->validate([
            'status'    => 'required|in:completed,pending,failed',
            'reference' => 'nullable|string|max:255',
        ]);

        $transaction->update($validated);

        // If status changed TO completed — apply balance
        if ($oldStatus !== 'completed' && $newStatus === 'completed') {
            $this->applyBalanceChange($transaction->user_id, $transaction->type, $transaction->amount);
        }

        // If status changed FROM completed — reverse balance
        if ($oldStatus === 'completed' && $newStatus !== 'completed') {
            $reverseType = $transaction->type === 'deposit' ? 'withdraw' : 'deposit';
            $this->applyBalanceChange($transaction->user_id, $reverseType, $transaction->amount);
        }

        return back()->with('success', 'Transaction updated.');
    }

    public function destroy(Transaction $transaction)
    {
        // Reverse balance if it was completed
        if ($transaction->status === 'completed') {
            $reverseType = $transaction->type === 'deposit' ? 'withdraw' : 'deposit';
            $this->applyBalanceChange($transaction->user_id, $reverseType, $transaction->amount);
        }

        $transaction->delete();
        return back()->with('success', 'Transaction deleted.');
    }

    // ── Private helper ──────────────────────────────────────

    private function applyBalanceChange(int $userId, string $type, float $amount): void
    {
        $user = User::find($userId);
        if (! $user) return;

        if ($type === 'deposit') {
            $user->increment('balance', $amount);
            $user->increment('total_deposited', $amount);
        } else {
            $user->decrement('balance', $amount);
            $user->increment('total_withdrawn', $amount);
        }
    }
}
