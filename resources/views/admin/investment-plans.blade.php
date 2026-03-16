@extends('layouts.admin')
@section('title', 'Investments & Transactions')
@section('page-title', 'Investments & Transactions')

@section('content')

{{-- Alerts --}}
@if(session('success'))
<div class="alert-success-bar mb-4"><span>{{ session('success') }}</span><button class="alert-close" onclick="dismissAlert(this)" title="Dismiss">&times;</button></div>
@endif
@if($errors->has('cycle'))
<div class="alert-error-bar mb-4"><span>🚫 {{ $errors->first('cycle') }}</span><button class="alert-close" onclick="dismissAlert(this)" title="Dismiss">&times;</button></div>
@endif
@if($errors->any() && !$errors->has('cycle'))
<div class="alert-error-bar mb-4"><span>⚠️ {{ $errors->first() }}</span><button class="alert-close" onclick="dismissAlert(this)" title="Dismiss">&times;</button></div>
@endif

{{-- ── STATS ROW ── --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-lg-3">
    <div class="stat-card" style="padding:16px;">
      <div class="stat-label">Total Txns</div>
      <div class="stat-value">{{ $stats['total'] }}</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card" style="padding:16px;">
      <div class="stat-label">Total Deposited</div>
      <div class="stat-value" style="color:var(--green);">${{ number_format($stats['deposits'],0) }}</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card" style="padding:16px;">
      <div class="stat-label">Total Withdrawn</div>
      <div class="stat-value" style="color:var(--red);">${{ number_format($stats['withdraws'],0) }}</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card" style="padding:16px;">
      <div class="stat-label">Pending</div>
      <div class="stat-value" style="color:var(--gold);">{{ $stats['pending'] }}</div>
    </div>
  </div>
</div>

{{-- ── PLAN REFERENCE CARDS ── --}}
@php
$planDefs = [
  ['name'=>'Plan 1','color'=>'#f59e0b','max'=>2,'rate'=>'1.5%','days'=>'1–7d','range'=>'$500–$1K'],
  ['name'=>'Plan 2','color'=>'#3b82f6','max'=>3,'rate'=>'3–3.5%','days'=>'1–15d','range'=>'$10K–$15K'],
  ['name'=>'VIP 1', 'color'=>'#22c55e','max'=>6,'rate'=>'5%',   'days'=>'1–28d','range'=>'$25K–$50K'],
  ['name'=>'VIP 2', 'color'=>'#a855f7','max'=>5,'rate'=>'7%',   'days'=>'1–28d','range'=>'$50K+'],
];
@endphp
<div class="row g-2 mb-4">
  @foreach($planDefs as $pd)
  <div class="col-6 col-lg-3">
    <div style="background:var(--surface);border:1px solid var(--border);border-top:3px solid {{ $pd['color'] }};border-radius:10px;padding:12px;">
      <div style="font-weight:800;color:{{ $pd['color'] }};font-size:13px;">{{ $pd['name'] }}</div>
      <div style="font-size:11px;color:var(--muted);margin-bottom:8px;">{{ $pd['range'] }}</div>
      <div style="display:flex;gap:4px;flex-wrap:wrap;">
        <span style="background:rgba(255,255,255,0.05);border:1px solid var(--border);border-radius:5px;padding:2px 7px;font-size:10px;color:var(--text);">{{ $pd['rate'] }}/day</span>
        <span style="background:rgba(255,255,255,0.05);border:1px solid var(--border);border-radius:5px;padding:2px 7px;font-size:10px;color:var(--text);">{{ $pd['days'] }}</span>
        <span style="background:{{ $pd['color'] }}18;border:1px solid {{ $pd['color'] }}44;border-radius:5px;padding:2px 7px;font-size:10px;font-weight:700;color:{{ $pd['color'] }};">Max {{ $pd['max'] }} cycles</span>
      </div>
    </div>
  </div>
  @endforeach
</div>

{{-- ── MAIN CONTENT: FORMS LEFT | PLANS TABLE RIGHT ── --}}
<div class="row g-4 mb-4">

  {{-- ── LEFT COLUMN: ASSIGN PLAN + WITHDRAWAL ── --}}
  <div class="col-lg-4">

    {{-- TABS --}}
    <div style="display:flex;gap:0;margin-bottom:0;border-bottom:2px solid var(--border);">
      <button id="tabInvest" onclick="switchTab('invest')"
        style="flex:1;padding:11px;background:var(--surface);border:1px solid var(--border);border-bottom:none;border-radius:10px 0 0 0;font-size:13px;font-weight:700;color:#22c55e;cursor:pointer;">
        📈 Assign Investment
      </button>
      <button id="tabWithdraw" onclick="switchTab('withdraw')"
        style="flex:1;padding:11px;background:var(--surface2);border:1px solid var(--border);border-left:none;border-bottom:none;border-radius:0 10px 0 0;font-size:13px;font-weight:700;color:var(--muted);cursor:pointer;">
        ⬇️ Record Withdrawal
      </button>
    </div>

    {{-- ASSIGN INVESTMENT FORM --}}
    <div id="panelInvest" class="card" style="border-radius:0 0 12px 12px;border-top:none;">
      <div class="card-body">

        {{-- Cycle tracker --}}
        <div id="cycleTracker" style="display:none;margin-bottom:16px;">
          <div style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:8px;">Cycle Usage for Selected Customer</div>
          <div id="cycleTrackerGrid" class="row g-2"></div>
        </div>

        <form method="POST" action="{{ route('admin.plans.store') }}" id="investForm">
          @csrf

          <div class="mb-3">
            <label class="form-label">Customer</label>
            <select name="user_id" class="form-control" id="customerSelect" required onchange="loadCustomerCycles(this.value)">
              <option value="">-- Select Customer --</option>
              @foreach($customers as $c)
                <option value="{{ $c->id }}" {{ old('user_id')==$c->id?'selected':'' }}>{{ $c->name }} — {{ $c->email }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Plan</label>
            <select name="plan_name" class="form-control" id="planSelect" required onchange="onPlanChange(this.value)">
              <option value="">-- Select Plan --</option>
              <option value="Plan 1">Plan 1 — 1.5%/day | Max 2 cycles</option>
              <option value="Plan 2">Plan 2 — 3.2%/day | Max 3 cycles</option>
              <option value="VIP 1">VIP 1 — 5%/day | Max 6 cycles</option>
              <option value="VIP 2">VIP 2 — 7%/day | Max 5 cycles</option>
            </select>
            <div id="cycleWarning" style="display:none;margin-top:8px;"></div>
          </div>

          <div id="planFields" style="display:none;">
            <div class="mb-3">
              <label class="form-label">Invested Amount (USDT)</label>
              <div style="position:relative;">
                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);font-weight:700;">$</span>
                <input type="number" name="amount" id="fAmount" class="form-control" style="padding-left:26px;" step="0.01" min="1" value="{{ old('amount') }}" required/>
              </div>
              <div id="amountHint" style="font-size:11px;color:var(--muted);margin-top:3px;"></div>
            </div>

            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label">Profit Rate (%/day)</label>
                <div style="position:relative;">
                  <input type="number" name="profit_rate" id="fRate" class="form-control" step="0.01" min="0.01" max="100" value="{{ old('profit_rate') }}" required/>
                  <span style="position:absolute;right:10px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:12px;">%</span>
                </div>
                <div style="font-size:10px;color:var(--muted);margin-top:2px;">Admin can override</div>
              </div>
              <div class="col-6">
                <label class="form-label">Duration (days)</label>
                <input type="number" name="duration_days" id="fDuration" class="form-control" min="1" value="{{ old('duration_days') }}" required/>
                <div id="durationHint" style="font-size:10px;color:var(--muted);margin-top:2px;"></div>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Start Date</label>
              <input type="date" name="start_date" id="fStart" class="form-control" value="{{ old('start_date', date('Y-m-d')) }}" required/>
            </div>

            {{-- Live preview --}}
            <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:14px;margin-bottom:14px;">
              <div style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:10px;">Preview — auto deposit will be created</div>
              <div class="row g-2 text-center">
                <div class="col-6">
                  <div style="font-size:10px;color:var(--muted);">Daily Profit</div>
                  <div id="prevDaily" style="font-size:18px;font-weight:800;color:#22c55e;font-family:'JetBrains Mono',monospace;">$0.00</div>
                </div>
                <div class="col-6">
                  <div style="font-size:10px;color:var(--muted);">Total Expected</div>
                  <div id="prevTotal" style="font-size:18px;font-weight:800;color:#f59e0b;font-family:'JetBrains Mono',monospace;">$0.00</div>
                </div>
                <div class="col-6">
                  <div style="font-size:10px;color:var(--muted);">End Date</div>
                  <div id="prevEnd" style="font-size:13px;font-weight:700;color:var(--text);font-family:'JetBrains Mono',monospace;">—</div>
                </div>
                <div class="col-6">
                  <div style="font-size:10px;color:var(--muted);">Cycle #</div>
                  <div id="prevCycle" style="font-size:13px;font-weight:700;color:var(--accent);font-family:'JetBrains Mono',monospace;">—</div>
                </div>
              </div>
              <div style="margin-top:10px;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);border-radius:7px;padding:8px 12px;font-size:11px;color:#86efac;">
                💡 Assigning this plan will <strong>automatically</strong> create a deposit transaction and update customer balance.
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Notes (optional)</label>
              <input type="text" name="notes" class="form-control" placeholder="Any note..." value="{{ old('notes') }}"/>
            </div>

            <button type="submit" class="btn btn-primary w-100" style="padding:12px;">📈 Assign Plan & Record Deposit</button>
          </div>
        </form>
      </div>
    </div>

    {{-- WITHDRAWAL FORM --}}
    <div id="panelWithdraw" class="card" style="display:none;border-radius:0 0 12px 12px;border-top:none;">
      <div class="card-body">
        <form method="POST" action="{{ route('admin.plans.withdraw') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Customer</label>
            <select name="user_id" class="form-control" required>
              <option value="">-- Select Customer --</option>
              @foreach($customers as $c)
                <option value="{{ $c->id }}">{{ $c->name }} — {{ $c->email }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Amount (USDT)</label>
            <div style="position:relative;">
              <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);font-weight:700;">$</span>
              <input type="number" name="amount" class="form-control" style="padding-left:26px;" step="0.01" min="0.01" required/>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Reference (optional)</label>
            <input type="text" name="reference" class="form-control" placeholder="e.g. Bank Transfer, Binance withdrawal..."/>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label">Date</label>
              <input type="date" name="transaction_date" class="form-control" value="{{ date('Y-m-d') }}"/>
            </div>
            <div class="col-6">
              <label class="form-label">Status</label>
              <select name="status" class="form-control">
                <option value="completed">Completed</option>
                <option value="pending">Pending</option>
                <option value="failed">Failed</option>
              </select>
            </div>
          </div>
          <button type="submit" class="btn w-100" style="padding:12px;background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.4);color:#f87171;font-weight:700;">⬇️ Record Withdrawal</button>
        </form>
      </div>
    </div>

  </div>

  {{-- ── RIGHT COLUMN: ACTIVE INVESTMENT PLANS ── --}}
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        Active Investment Cycles
        <span class="badge badge-blue">{{ $plans->total() }}</span>
      </div>
      <div style="overflow-x:auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Customer</th>
              <th>Plan / Cycle</th>
              <th>Amount & Rate</th>
              <th>Progress</th>
              <th>Daily</th>
              <th>Total Profit</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse($plans as $plan)
            <tr>
              <td>
                <div style="font-weight:700;color:var(--text);font-size:13px;">{{ $plan->user->name }}</div>
                <div style="font-size:11px;color:var(--muted);">{{ $plan->user->email }}</div>
              </td>
              <td>
                <span class="badge badge-blue">{{ $plan->plan_name }}</span>
                <div style="font-size:11px;color:var(--muted);margin-top:3px;">Cycle {{ $plan->cycle_number }}/{{ $plan->max_cycles }} · {{ $plan->duration_days }}d</div>
              </td>
              <td>
                <div style="font-family:'JetBrains Mono',monospace;font-weight:700;color:var(--text);font-size:13px;">${{ number_format($plan->amount,2) }}</div>
                <div style="font-size:12px;color:#f59e0b;font-weight:700;">{{ $plan->profit_rate }}%/day</div>
              </td>
              <td style="min-width:110px;">
                <div style="font-size:10px;color:var(--muted);margin-bottom:3px;">{{ $plan->start_date->format('d M') }} → {{ $plan->end_date->format('d M') }}</div>
                <div style="background:var(--surface2);border-radius:20px;height:5px;overflow:hidden;margin-bottom:2px;">
                  <div style="width:{{ $plan->progressPercent() }}%;height:100%;background:linear-gradient(90deg,var(--accent),#22c55e);border-radius:20px;"></div>
                </div>
                <div style="font-size:10px;color:var(--muted);">{{ $plan->progressPercent() }}% · {{ $plan->daysRemaining() }}d left</div>
              </td>
              <td style="font-family:'JetBrains Mono',monospace;font-weight:700;color:#22c55e;font-size:13px;">
                +${{ number_format($plan->dailyProfit(),2) }}
              </td>
              <td style="font-family:'JetBrains Mono',monospace;font-weight:700;color:#f59e0b;font-size:13px;">
                ${{ number_format($plan->totalExpectedProfit(),2) }}
                <div style="font-size:10px;color:var(--muted);font-weight:400;">{{ $plan->duration_days }}d full cycle</div>
              </td>
              <td>
                <span class="badge {{ $plan->status==='active'?'badge-green':($plan->status==='completed'?'badge-blue':'badge-red') }}">
                  {{ ucfirst($plan->status) }}
                </span>
              </td>
              <td>
                <div style="display:flex;flex-direction:column;gap:4px;">
                  @if($plan->status==='active')
                  <button class="btn btn-xs btn-success w-100"
                    onclick="openCompleteModal({{ $plan->id }}, '{{ $plan->plan_name }}', {{ $plan->cycle_number }}, '{{ $plan->user->name }}', '{{ number_format($plan->totalExpectedProfit(),2) }}', {{ $plan->duration_days }}, '{{ $plan->profit_rate }}')">
                    ✅ Complete
                  </button>
                  @endif
                  <form method="POST" action="{{ route('admin.plans.destroy',$plan) }}" onsubmit="return confirm('Delete this cycle and reverse deposit?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-xs w-100">🗑 Delete</button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:32px;color:var(--muted);">No cycles assigned yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($plans->hasPages())
        <div style="padding:14px;">{{ $plans->links() }}</div>
      @endif
    </div>
  </div>

</div>

{{-- ── TRANSACTION HISTORY ── --}}
<div class="card">
  <div class="card-header">
    Transaction History
    <div style="display:flex;gap:8px;align-items:center;">
      <form method="GET" style="display:flex;gap:6px;align-items:center;">
        <select name="txn_customer" class="form-control" style="padding:5px 8px;font-size:12px;width:auto;" onchange="this.form.submit()">
          <option value="">All Customers</option>
          @foreach($customers as $c)
            <option value="{{ $c->id }}" {{ request('txn_customer')==$c->id?'selected':'' }}>{{ $c->name }}</option>
          @endforeach
        </select>
        <select name="txn_type" class="form-control" style="padding:5px 8px;font-size:12px;width:auto;" onchange="this.form.submit()">
          <option value="">All Types</option>
          <option value="deposit"  {{ request('txn_type')==='deposit'?'selected':'' }}>Deposits</option>
          <option value="withdraw" {{ request('txn_type')==='withdraw'?'selected':'' }}>Withdrawals</option>
        </select>
        @if(request('txn_customer') || request('txn_type'))
          <a href="{{ route('admin.plans') }}" class="btn btn-ghost btn-xs">✕ Clear</a>
        @endif
      </form>
      <span class="badge badge-blue">{{ $transactions->total() }}</span>
    </div>
  </div>
  <div style="overflow-x:auto;">
    <table class="admin-table">
      <thead>
        <tr><th>ID</th><th>Customer</th><th>Type</th><th>Amount</th><th>Reference</th><th>Date</th><th>Status</th><th></th></tr>
      </thead>
      <tbody>
        @forelse($transactions as $txn)
        <tr>
          <td style="font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--muted);">#{{ $txn->id }}</td>
          <td>
            <div style="font-weight:700;font-size:13px;color:var(--text);">{{ $txn->user->name }}</div>
            <div style="font-size:11px;color:var(--muted);">{{ $txn->user->email }}</div>
          </td>
          <td><span class="badge {{ $txn->isDeposit()?'badge-green':'badge-red' }}">{{ ucfirst($txn->type) }}</span></td>
          <td style="font-family:'JetBrains Mono',monospace;font-weight:700;color:{{ $txn->isDeposit()?'var(--green)':'var(--red)' }};">{{ $txn->signedAmount() }}</td>
          <td style="font-size:12px;color:var(--muted);">{{ $txn->reference ?? '—' }}</td>
          <td style="font-size:11px;color:var(--muted);font-family:'JetBrains Mono',monospace;white-space:nowrap;">{{ $txn->transaction_date->format('d M Y') }}</td>
          <td>
            <form method="POST" action="{{ route('admin.plans.txn.update',$txn) }}">
              @csrf @method('PATCH')
              <select name="status" class="form-control" style="padding:3px 6px;font-size:11px;width:auto;" onchange="this.form.submit()">
                <option value="completed" {{ $txn->status==='completed'?'selected':'' }}>Completed</option>
                <option value="pending"   {{ $txn->status==='pending'?'selected':'' }}>Pending</option>
                <option value="failed"    {{ $txn->status==='failed'?'selected':'' }}>Failed</option>
              </select>
            </form>
          </td>
          <td>
            <form method="POST" action="{{ route('admin.plans.txn.destroy',$txn) }}" onsubmit="return confirm('Delete this transaction?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger btn-xs">🗑</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:32px;color:var(--muted);">No transactions yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($transactions->hasPages())
    <div style="padding:14px;">{{ $transactions->links() }}</div>
  @endif
</div>

@push('modals')
{{-- Complete Confirmation Modal --}}
<div class="modal fade" id="completeModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom-color:rgba(34,197,94,0.25);">
        <h5 class="modal-title" style="color:#22c55e;">✅ Complete Investment Cycle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div style="background:rgba(34,197,94,0.06);border:1px solid rgba(34,197,94,0.2);border-radius:10px;padding:16px;margin-bottom:16px;">
          <div class="row g-3 text-center">
            <div class="col-6">
              <div style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);">Customer</div>
              <div id="cModalCustomer" style="font-size:14px;font-weight:700;color:var(--text);margin-top:3px;"></div>
            </div>
            <div class="col-6">
              <div style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);">Plan / Cycle</div>
              <div id="cModalPlan" style="font-size:14px;font-weight:700;color:var(--accent);margin-top:3px;"></div>
            </div>
            <div class="col-6">
              <div style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);">Duration</div>
              <div id="cModalDuration" style="font-size:14px;font-weight:700;color:var(--text);margin-top:3px;"></div>
            </div>
            <div class="col-6">
              <div style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);">Full Profit to Credit</div>
              <div id="cModalProfit" style="font-size:20px;font-weight:800;color:#22c55e;font-family:'JetBrains Mono',monospace;margin-top:3px;"></div>
            </div>
          </div>
        </div>
        <div style="font-size:13px;color:var(--muted);text-align:center;">
          This will credit the <strong style="color:var(--text);">full cycle profit</strong> to the customer's balance and mark the cycle as completed.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <form id="completeForm" method="POST">
          @csrf
          <button type="submit" class="btn btn-success" style="padding:8px 24px;">✅ Yes, Complete & Credit Profit</button>
        </form>
      </div>
    </div>
  </div>
</div>
{{-- EDIT MODAL --}}
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">✏️ Edit Cycle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" id="editForm">
        @csrf @method('PATCH')
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-6">
              <label class="form-label">Profit Rate (%/day)</label>
              <input type="number" name="profit_rate" id="eProfitRate" class="form-control" step="0.01" required/>
            </div>
            <div class="col-6">
              <label class="form-label">Duration (days)</label>
              <input type="number" name="duration_days" id="eDuration" class="form-control" required/>
            </div>
            <div class="col-6">
              <label class="form-label">Start Date</label>
              <input type="date" name="start_date" id="eStart" class="form-control" required/>
            </div>
            <div class="col-6">
              <label class="form-label">Status</label>
              <select name="status" id="eStatus" class="form-control">
                <option value="active">Active</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Notes</label>
              <input type="text" name="notes" id="eNotes" class="form-control"/>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endpush

@push('scripts')
<script>
const planConfigs     = @json(\App\Models\InvestmentPlan::$planConfig);
const customerSummary = @json($customerPlanSummary);

// ── TABS ──
function switchTab(tab) {
  const isInvest = tab === 'invest';
  document.getElementById('panelInvest').style.display   = isInvest ? 'block' : 'none';
  document.getElementById('panelWithdraw').style.display = isInvest ? 'none'  : 'block';
  document.getElementById('tabInvest').style.color    = isInvest ? '#22c55e'      : 'var(--muted)';
  document.getElementById('tabInvest').style.background = isInvest ? 'var(--surface)' : 'var(--surface2)';
  document.getElementById('tabWithdraw').style.color  = isInvest ? 'var(--muted)' : '#f87171';
  document.getElementById('tabWithdraw').style.background = isInvest ? 'var(--surface2)' : 'var(--surface)';
}

// ── CYCLE TRACKER ──
function loadCustomerCycles(userId) {
  const tracker = document.getElementById('cycleTracker');
  const grid    = document.getElementById('cycleTrackerGrid');
  if (!userId || !customerSummary[userId]) { tracker.style.display='none'; return; }

  const summary = customerSummary[userId];
  const colors  = {'Plan 1':'#f59e0b','Plan 2':'#3b82f6','VIP 1':'#22c55e','VIP 2':'#a855f7'};
  grid.innerHTML = '';

  for (const [plan, data] of Object.entries(summary)) {
    const pct  = Math.round((data.used / data.max) * 100);
    const full = data.used >= data.max;
    grid.innerHTML += `
      <div class="col-6">
        <div style="background:var(--surface2);border:1px solid ${full?colors[plan]+'55':'var(--border)'};border-radius:8px;padding:9px;">
          <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
            <span style="font-size:12px;font-weight:700;color:${colors[plan]};">${plan}</span>
            <span style="font-size:11px;font-weight:700;color:${full?'#ef4444':'var(--muted)'};">${data.used}/${data.max}${full?' 🔒':''}</span>
          </div>
          <div style="background:var(--border);border-radius:10px;height:4px;overflow:hidden;">
            <div style="width:${pct}%;height:100%;background:${full?'#ef4444':colors[plan]};border-radius:10px;"></div>
          </div>
        </div>
      </div>`;
  }
  tracker.style.display = 'block';

  const planVal = document.getElementById('planSelect').value;
  if (planVal) onPlanChange(planVal, userId);
}

// ── PLAN CHANGE ──
function onPlanChange(planName, forceUserId) {
  const userId  = forceUserId || document.getElementById('customerSelect').value;
  const warning = document.getElementById('cycleWarning');
  const fields  = document.getElementById('planFields');

  if (!planName) { warning.style.display='none'; fields.style.display='none'; return; }

  const config = planConfigs[planName];
  if (config) {
    document.getElementById('fRate').value     = config.default_rate;
    document.getElementById('fDuration').value = config.max_days;
    document.getElementById('amountHint').textContent  = `Range: $${config.min_amount.toLocaleString()} – $${config.max_amount === 999999 ? '100,000+' : config.max_amount.toLocaleString()}`;
    document.getElementById('durationHint').textContent = `Max ${config.max_days} days`;
  }

  if (!userId) { fields.style.display='block'; warning.style.display='none'; updatePreview(); return; }

  fetch(`/admin/plans/check-cycle?user_id=${userId}&plan_name=${encodeURIComponent(planName)}`)
    .then(r => r.json())
    .then(res => {
      if (!res.allowed) {
        warning.innerHTML = `<div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:8px;padding:12px;">
          <div style="font-weight:700;color:#ef4444;font-size:13px;margin-bottom:3px;">🚫 Cannot Assign</div>
          <div style="font-size:12px;color:#fca5a5;">${res.reason}</div>
        </div>`;
        warning.style.display = 'block';
        fields.style.display  = 'none';
      } else {
        const nextCycle = res.used + 1;
        warning.innerHTML = `<div style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);border-radius:8px;padding:9px 12px;font-size:12px;color:#86efac;">
          ✅ Cycle ${nextCycle} of ${res.max} — ${res.max - res.used - 1} remaining after this
        </div>`;
        warning.style.display = 'block';
        fields.style.display  = 'block';
        document.getElementById('prevCycle').textContent = `Cycle ${nextCycle}`;
        updatePreview();
      }
    });
}

// ── PREVIEW ──
function updatePreview() {
  const amount   = parseFloat(document.getElementById('fAmount')?.value) || 0;
  const rate     = parseFloat(document.getElementById('fRate')?.value)   || 0;
  const duration = parseInt(document.getElementById('fDuration')?.value) || 0;
  const startVal = document.getElementById('fStart')?.value;
  if (!amount || !rate || !duration) return;
  const daily = (amount * rate / 100).toFixed(2);
  const total = (daily * duration).toFixed(2);
  document.getElementById('prevDaily').textContent = '$' + Number(daily).toLocaleString('en-US',{minimumFractionDigits:2});
  document.getElementById('prevTotal').textContent = '$' + Number(total).toLocaleString('en-US',{minimumFractionDigits:2});
  if (startVal) {
    const end = new Date(startVal);
    end.setDate(end.getDate() + duration);
    document.getElementById('prevEnd').textContent = end.toLocaleDateString('en-GB',{day:'2-digit',month:'short',year:'numeric'});
  }
}

['fAmount','fRate','fDuration'].forEach(id => document.getElementById(id)?.addEventListener('input', updatePreview));
document.getElementById('fStart')?.addEventListener('change', updatePreview);

// ── COMPLETE MODAL ──
function openCompleteModal(id, planName, cycleNum, customer, profit, days, rate) {
  document.getElementById('cModalCustomer').textContent = customer;
  document.getElementById('cModalPlan').textContent     = planName + ' — Cycle ' + cycleNum;
  document.getElementById('cModalDuration').textContent = days + ' days × ' + rate + '%';
  document.getElementById('cModalProfit').textContent   = '+$' + profit;
  document.getElementById('completeForm').action        = '/admin/plans/' + id + '/complete';
  new bootstrap.Modal(document.getElementById('completeModal')).show();
}

// ── EDIT MODAL ──
function openEdit(id, data) {
  document.getElementById('editForm').action = `/admin/plans/${id}`;
  document.getElementById('eProfitRate').value = data.profit_rate;
  document.getElementById('eDuration').value   = data.duration_days;
  document.getElementById('eStart').value      = data.start_date;
  document.getElementById('eStatus').value     = data.status;
  document.getElementById('eNotes').value      = data.notes || '';
  new bootstrap.Modal(document.getElementById('editModal')).show();
}

@if(old('user_id'))
  document.getElementById('customerSelect').value = '{{ old("user_id") }}';
  loadCustomerCycles('{{ old("user_id") }}');
@endif
</script>
@endpush
@endsection
