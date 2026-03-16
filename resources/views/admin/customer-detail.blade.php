@extends('layouts.admin')
@section('title', $user->name)
@section('page-title', 'Customer Detail')

@section('content')

<div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
  <a href="{{ route('admin.customers') }}" class="btn btn-ghost btn-sm">← Back</a>
  <h1 style="font-size:20px;font-weight:800;margin:0;">{{ $user->name }}</h1>
  <span class="badge {{ $user->is_active ? 'badge-green' : 'badge-gray' }}">
    {{ $user->is_active ? 'Active' : 'Inactive' }}
  </span>
</div>

<div class="row g-4">
  {{-- Profile card --}}
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">Profile</div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:8px;">
          <div class="avatar" style="width:54px;height:54px;font-size:20px;border-radius:12px;">{{ $user->initials() }}</div>
          <div>
            <div style="font-size:17px;font-weight:800;">{{ $user->name }}</div>
            <div style="font-size:13px;color:var(--muted);font-family:'JetBrains Mono',monospace;">{{ $user->email }}</div>
          </div>
        </div>
        <div class="bank-row"><span class="bank-key">Balance</span><span class="bank-val" style="color:var(--accent);">${{ number_format($user->balance,2) }}</span></div>
        <div class="bank-row"><span class="bank-key">Daily Profit</span><span class="bank-val" style="color:var(--green);">+${{ number_format($user->daily_profit,2) }}</span></div>
        <div class="bank-row"><span class="bank-key">Total Deposited</span><span class="bank-val" style="color:var(--green);">${{ number_format($user->total_deposited,2) }}</span></div>
        <div class="bank-row"><span class="bank-key">Total Withdrawn</span><span class="bank-val" style="color:var(--red);">${{ number_format($user->total_withdrawn,2) }}</span></div>
        <div class="bank-row"><span class="bank-key">Registered</span><span class="bank-val">{{ $user->created_at->format('d M Y') }}</span></div>
        <form method="POST" action="{{ route('admin.customers.toggle', $user) }}" class="mt-2">
          @csrf @method('PATCH')
          <button type="submit" class="btn w-100 {{ $user->is_active ? 'btn-warning' : 'btn-success' }}">
            {{ $user->is_active ? '⏸ Disable Account' : '▶ Enable Account' }}
          </button>
        </form>
        <a href="{{ route('admin.plans') }}?txn_customer={{ $user->id }}" class="btn btn-ghost w-100">💳 View Transactions</a>
        <a href="{{ route('admin.payment-info') }}" class="btn btn-ghost w-100">⚙️ Set Payment Info</a>
      </div>
    </div>
  </div>

  {{-- Transactions --}}
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">Transaction History <span class="badge badge-blue ms-2">{{ $transactions->total() }}</span></div>
      <div style="overflow-x:auto;">
        <table class="admin-table">
          <thead>
            <tr><th>Type</th><th>Amount</th><th>Reference</th><th>Date</th><th>Status</th></tr>
          </thead>
          <tbody>
            @forelse($transactions as $txn)
            <tr>
              <td><span class="badge {{ $txn->isDeposit() ? 'badge-green' : 'badge-red' }}">{{ ucfirst($txn->type) }}</span></td>
              <td style="font-family:'JetBrains Mono',monospace;font-weight:700;color:{{ $txn->isDeposit() ? 'var(--green)' : 'var(--red)' }};">{{ $txn->signedAmount() }}</td>
              <td style="font-size:12px;color:var(--muted);">{{ $txn->reference ?? '—' }}</td>
              <td style="font-size:12px;font-family:'JetBrains Mono',monospace;color:var(--muted);">{{ $txn->transaction_date->format('d M Y H:i') }}</td>
              <td><span class="badge {{ $txn->statusBadgeClass() }}">{{ ucfirst($txn->status) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;padding:28px;color:var(--muted);">No transactions yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($transactions->hasPages())
      <div style="padding:14px 20px;border-top:1px solid var(--border);">{{ $transactions->links() }}</div>
      @endif
    </div>
  </div>
</div>

@push('styles')
<style>
.bank-row{display:flex;justify-content:space-between;align-items:center;background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:9px 13px;}
.bank-key{font-size:12px;color:var(--muted);font-family:'JetBrains Mono',monospace;}
.bank-val{font-size:13px;font-weight:600;font-family:'JetBrains Mono',monospace;}
</style>
@endpush

@endsection
