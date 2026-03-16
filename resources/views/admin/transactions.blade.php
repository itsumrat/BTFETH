@extends('layouts.admin')
@section('title', 'Transactions')
@section('page-title', 'Transactions')

@section('content')

<div class="page-header">
  <h1>Transactions</h1>
  <p>Add deposits or withdrawals per customer. Balance updates automatically.</p>
</div>

<div class="row g-4">

  {{-- ── ADD TRANSACTION FORM ── --}}
  <div class="col-lg-4">
    <div class="card" style="position:sticky;top:78px;">
      <div class="card-header">➕ Add Transaction</div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.transactions.store') }}">
          @csrf

          {{-- Type toggle --}}
          <div class="form-group">
            <label class="form-label">Type</label>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
              <label style="cursor:pointer;">
                <input type="radio" name="type" value="deposit" checked style="display:none;" id="r_deposit" />
                <div class="type-toggle-btn" id="btn_deposit" onclick="selectType('deposit')"
                  style="padding:12px;border-radius:9px;border:2px solid var(--green);background:rgba(34,197,94,0.08);text-align:center;font-weight:700;color:var(--green);font-size:13px;cursor:pointer;">
                  ⬆️ Deposit
                </div>
              </label>
              <label style="cursor:pointer;">
                <input type="radio" name="type" value="withdraw" style="display:none;" id="r_withdraw" />
                <div class="type-toggle-btn" id="btn_withdraw" onclick="selectType('withdraw')"
                  style="padding:12px;border-radius:9px;border:2px solid var(--border);background:var(--surface2);text-align:center;font-weight:700;color:var(--muted);font-size:13px;cursor:pointer;">
                  ⬇️ Withdraw
                </div>
              </label>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Customer</label>
            <select name="user_id" class="form-select" required>
              <option value="">— Select customer —</option>
              @foreach($customers as $c)
                <option value="{{ $c->id }}" {{ old('user_id') == $c->id ? 'selected' : '' }}>
                  {{ $c->name }} — {{ $c->email }}
                </option>
              @endforeach
            </select>
            @error('user_id')<div style="color:var(--red);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
          </div>

          <div class="form-group">
            <label class="form-label">Amount (USDT)</label>
            <div style="position:relative;">
              <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted);font-weight:700;">$</span>
              <input type="number" name="amount" class="form-control" placeholder="0.00"
                     min="0.01" step="0.01" value="{{ old('amount') }}" style="padding-left:30px;" required />
            </div>
            @error('amount')<div style="color:var(--red);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
          </div>

          <div class="form-group">
            <label class="form-label">Reference <span style="font-weight:400;text-transform:none;color:var(--muted);">(optional)</span></label>
            <input type="text" name="reference" class="form-control" placeholder="e.g. Binance Pay transfer" value="{{ old('reference') }}" />
          </div>

          <div class="form-group">
            <label class="form-label">Date & Time</label>
            <input type="datetime-local" name="transaction_date" class="form-control" value="{{ old('transaction_date', now()->format('Y-m-d\TH:i')) }}" />
          </div>

          <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
              <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
              <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
          </div>

          <div class="alert-info mb-3" style="font-size:12px;">
            💡 Balance & totals update automatically when status is <strong>Completed</strong>.
          </div>

          <button type="submit" class="btn btn-success w-100" style="padding:11px;">✓ Save Transaction</button>
        </form>
      </div>
    </div>
  </div>

  {{-- ── TRANSACTION LIST ── --}}
  <div class="col-lg-8">

    {{-- Stats --}}
    <div class="row g-2 mb-3">
      <div class="col-6 col-md-3">
        <div class="stat-card" style="padding:14px;">
          <div class="stat-label" style="font-size:10px;">All Txns</div>
          <div class="stat-value" style="font-size:20px;">{{ $stats['total'] }}</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card" style="padding:14px;">
          <div class="stat-label" style="font-size:10px;">Deposits</div>
          <div class="stat-value" style="font-size:20px;color:var(--green);">${{ number_format($stats['deposits'], 0) }}</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card" style="padding:14px;">
          <div class="stat-label" style="font-size:10px;">Withdrawn</div>
          <div class="stat-value" style="font-size:20px;color:var(--red);">${{ number_format($stats['withdraws'], 0) }}</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card" style="padding:14px;">
          <div class="stat-label" style="font-size:10px;">Pending</div>
          <div class="stat-value" style="font-size:20px;color:var(--gold);">{{ $stats['pending'] }}</div>
        </div>
      </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.transactions') }}" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px;">
      <div class="search-wrap">
        <span style="color:var(--muted);">🔍</span>
        <input type="text" name="search" placeholder="Search customer..." value="{{ request('search') }}" />
      </div>
      <select name="customer" class="form-select" style="width:auto;font-size:13px;padding:7px 10px;">
        <option value="">All Customers</option>
        @foreach($customers as $c)
          <option value="{{ $c->id }}" {{ request('customer') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
        @endforeach
      </select>
      <select name="type" class="form-select" style="width:auto;font-size:13px;padding:7px 10px;">
        <option value="">All Types</option>
        <option value="deposit" {{ request('type') === 'deposit' ? 'selected' : '' }}>Deposit</option>
        <option value="withdraw" {{ request('type') === 'withdraw' ? 'selected' : '' }}>Withdraw</option>
      </select>
      <select name="status" class="form-select" style="width:auto;font-size:13px;padding:7px 10px;">
        <option value="">All Status</option>
        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
      </select>
      <button type="submit" class="btn btn-primary btn-sm">Filter</button>
      <a href="{{ route('admin.transactions') }}" class="btn btn-ghost btn-sm">✕</a>
    </form>

    <div class="card">
      <div class="card-header">
        History <span class="badge badge-blue ms-2">{{ $transactions->total() }}</span>
      </div>
      <div style="overflow-x:auto;">
        <table class="admin-table">
          <thead>
            <tr><th>ID</th><th>Customer</th><th>Type</th><th>Amount</th><th>Ref</th><th>Date</th><th>Status</th><th></th></tr>
          </thead>
          <tbody>
            @forelse($transactions as $txn)
            <tr>
              <td style="font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--muted);">#{{ $txn->id }}</td>
              <td>
                <div style="display:flex;align-items:center;gap:8px;">
                  <div class="avatar" style="width:28px;height:28px;font-size:11px;border-radius:6px;">{{ $txn->user->initials() }}</div>
                  <span style="font-size:13px;font-weight:600;">{{ $txn->user->name }}</span>
                </div>
              </td>
              <td><span class="badge {{ $txn->isDeposit() ? 'badge-green' : 'badge-red' }}">{{ ucfirst($txn->type) }}</span></td>
              <td style="font-family:'JetBrains Mono',monospace;font-weight:700;color:{{ $txn->isDeposit() ? 'var(--green)' : 'var(--red)' }};">{{ $txn->signedAmount() }}</td>
              <td style="font-size:12px;color:var(--muted);">{{ $txn->reference ?? '—' }}</td>
              <td style="font-size:11px;color:var(--muted);font-family:'JetBrains Mono',monospace;white-space:nowrap;">{{ $txn->transaction_date->format('d M Y H:i') }}</td>
              <td>
                <form method="POST" action="{{ route('admin.transactions.update', $txn) }}">
                  @csrf @method('PATCH')
                  <select name="status" class="form-select" style="padding:4px 8px;font-size:11px;width:auto;" onchange="this.form.submit()">
                    <option value="completed" {{ $txn->status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="pending"   {{ $txn->status === 'pending'   ? 'selected' : '' }}>Pending</option>
                    <option value="failed"    {{ $txn->status === 'failed'    ? 'selected' : '' }}>Failed</option>
                  </select>
                </form>
              </td>
              <td>
                <form method="POST" action="{{ route('admin.transactions.destroy', $txn) }}" onsubmit="return confirm('Delete this transaction?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-xs">🗑</button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:32px;color:var(--muted);">No transactions found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($transactions->hasPages())
      <div style="padding:14px 20px;border-top:1px solid var(--border);">
        {{ $transactions->links() }}
      </div>
      @endif
    </div>
  </div>
</div>

@push('scripts')
<script>
  function selectType(type) {
    const isDeposit = type === 'deposit';
    document.getElementById('r_deposit').checked  = isDeposit;
    document.getElementById('r_withdraw').checked = !isDeposit;
    document.getElementById('btn_deposit').style.cssText  = isDeposit
      ? 'padding:12px;border-radius:9px;border:2px solid var(--green);background:rgba(34,197,94,0.08);text-align:center;font-weight:700;color:var(--green);font-size:13px;cursor:pointer;'
      : 'padding:12px;border-radius:9px;border:2px solid var(--border);background:var(--surface2);text-align:center;font-weight:700;color:var(--muted);font-size:13px;cursor:pointer;';
    document.getElementById('btn_withdraw').style.cssText = !isDeposit
      ? 'padding:12px;border-radius:9px;border:2px solid var(--red);background:rgba(239,68,68,0.08);text-align:center;font-weight:700;color:var(--red);font-size:13px;cursor:pointer;'
      : 'padding:12px;border-radius:9px;border:2px solid var(--border);background:var(--surface2);text-align:center;font-weight:700;color:var(--muted);font-size:13px;cursor:pointer;';
  }
</script>
@endpush

@endsection
