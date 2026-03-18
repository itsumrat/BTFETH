@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<div class="page-header">
  <h1>Overview</h1>
  <p>Welcome back. Here's what's happening today.</p>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(59,130,246,0.12);">👥</div>
      <div class="stat-label">Total Customers</div>
      <div class="stat-value">{{ $totalCustomers }}</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(34,197,94,0.12);">⬆️</div>
      <div class="stat-label">Total Invested</div>
      <div class="stat-value" style="color:var(--green);">${{ number_format($totalDeposited, 0) }}</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(239,68,68,0.12);">⬇️</div>
      <div class="stat-label">Total Withdrawn</div>
      <div class="stat-value" style="color:var(--red);">${{ number_format($totalWithdrawn, 0) }}</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(245,158,11,0.12);">📋</div>
      <div class="stat-label">Pending</div>
      <div class="stat-value" style="color:var(--gold);">{{ $pendingCount }}</div>
    </div>
  </div>
</div>

<div class="row g-3">
  {{-- Recent Transactions --}}
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        Recent Transactions
        <a href="{{ route('admin.plans') }}" class="btn btn-ghost btn-sm">View All</a>
      </div>
      <div style="overflow-x:auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Customer</th><th>Type</th><th>Amount</th><th>Date</th><th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentTxns as $txn)
            <tr>
              <td>
                <div style="display:flex;align-items:center;gap:10px;">
                  <div class="avatar">{{ $txn->user->initials() }}</div>
                  <div>
                    <div style="font-weight:700;font-size:14px;color:var(--text);">{{ $txn->user->name }}</div>
                    <div style="font-size:12px;color:#9ca3af;">{{ $txn->user->email }}</div>
                  </div>
                </div>
              </td>
              <td><span class="badge {{ $txn->isDeposit() ? 'badge-green' : 'badge-red' }}">{{ ucfirst($txn->type) }}</span></td>
              <td style="font-family:'JetBrains Mono',monospace;color:{{ $txn->isDeposit() ? 'var(--green)' : 'var(--red)' }};font-weight:600;">{{ $txn->signedAmount() }}</td>
              <td style="font-size:13px;color:#c4c9d4;font-family:'JetBrains Mono',monospace;font-weight:500;">{{ $txn->transaction_date->format('d M Y') }}</td>
              <td><span class="badge {{ $txn->statusBadgeClass() }}">{{ ucfirst($txn->status) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--muted);">No transactions yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Quick Actions + New Customers --}}
  <div class="col-lg-4">
    <div class="card mb-3">
      <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
        📋 Plan Requests
        @php $pendingCount = \App\Models\PlanRequest::where('status','pending')->count(); @endphp
        @if($pendingCount > 0)
          <span style="background:#ef4444;color:#fff;border-radius:12px;padding:2px 10px;font-size:11px;font-weight:700;">{{ $pendingCount }} pending</span>
        @else
          <span style="background:rgba(34,197,94,0.15);color:#22c55e;border-radius:12px;padding:2px 10px;font-size:11px;font-weight:700;">All clear</span>
        @endif
      </div>
      <div class="card-body" style="padding:0;">
        @php $latestRequests = \App\Models\PlanRequest::with('user')->where('status','pending')->latest()->take(4)->get(); @endphp
        @forelse($latestRequests as $req)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid var(--border);">
          <div style="display:flex;align-items:center;gap:10px;">
            <div class="avatar">{{ $req->user->initials() }}</div>
            <div>
              <div style="font-size:13px;font-weight:700;color:var(--text);">{{ $req->user->name }}</div>
              <div style="font-size:11px;color:var(--muted);">{{ $req->plan_name }} · ${{ number_format($req->amount,0) }}</div>
            </div>
          </div>
          <span style="background:rgba(245,158,11,0.15);color:var(--gold);border:1px solid rgba(245,158,11,0.3);border-radius:6px;padding:2px 8px;font-size:10px;font-weight:700;">⏳ Pending</span>
        </div>
        @empty
        <div style="padding:20px;text-align:center;color:var(--muted);font-size:13px;">No pending requests.</div>
        @endforelse
        <div style="padding:12px 16px;">
          <a href="{{ route('admin.plan-requests') }}" class="btn btn-primary w-100" style="font-size:13px;">View All Requests</a>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header">New Registrations</div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:12px;">
        @forelse($newCustomers as $c)
        <div style="display:flex;align-items:center;gap:10px;">
          <div class="avatar">{{ $c->initials() }}</div>
          <div style="flex:1;">
            <div style="font-size:14px;font-weight:700;color:var(--text);">{{ $c->name }}</div>
            <div style="font-size:12px;color:#9ca3af;">{{ $c->created_at->format('d M Y') }}</div>
          </div>
          <span class="badge badge-blue">New</span>
        </div>
        @empty
          <p style="color:var(--muted);font-size:13px;">No customers yet.</p>
        @endforelse
      </div>
    </div>
  </div>
</div>

@endsection
