@extends('layouts.admin')
@section('title', 'Payment Info')
@section('page-title', 'Payment Info')

@section('content')

@if(session('success'))
<div class="alert-success-bar mb-4"><span>✅ {{ session('success') }}</span><button class="alert-close" onclick="dismissAlert(this)" title="Dismiss">&times;</button></div>
@endif
@if($errors->any())
<div class="alert-error-bar mb-4"><span>⚠️ {{ $errors->first() }}</span><button class="alert-close" onclick="dismissAlert(this)" title="Dismiss">&times;</button></div>
@endif

{{-- ── TABS ── --}}
<div style="display:flex;gap:0;margin-bottom:0;border-bottom:2px solid var(--border);margin-bottom:24px;">
  <button id="tabGlobal" onclick="switchTab('global')"
    style="padding:11px 24px;background:var(--surface);border:1px solid var(--border);border-bottom:2px solid var(--accent);border-radius:10px 10px 0 0;font-size:13px;font-weight:700;color:var(--accent);cursor:pointer;margin-bottom:-2px;">
    🌍 Global (All Customers)
  </button>
  <button id="tabCustomer" onclick="switchTab('customer')"
    style="padding:11px 24px;background:var(--surface2);border:1px solid var(--border);border-bottom:none;border-radius:10px 10px 0 0;font-size:13px;font-weight:700;color:var(--muted);cursor:pointer;margin-bottom:-2px;margin-left:4px;">
    👤 Customer Override
  </button>
</div>

{{-- ══════════════════════════════════════════════════════
     GLOBAL TAB
══════════════════════════════════════════════════════ --}}
<div id="panelGlobal">
  <p style="color:var(--muted);font-size:13px;margin-bottom:20px;">Default payment info shown to <strong style="color:var(--text);">all customers</strong> unless a custom override is set for them.</p>

  <div class="row g-4">
    {{-- DEPOSIT --}}
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header" style="background:rgba(34,197,94,0.08);border-bottom:2px solid rgba(34,197,94,0.3);">
          <div style="font-weight:800;color:#22c55e;">⬆️ Deposit Information</div>
          <div style="font-size:11px;color:var(--muted);">Shown to all customers by default</div>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.payment-info.deposit') }}">
            @csrf
            <input type="hidden" name="user_id" value="">
            
          <div class="form-section-label">🟡 Binance Pay</div>
          <div class="form-group"><label class="form-label">Binance Pay Link</label>
          <input type="text" name="binance_link" class="form-control" value="{{ $globalDeposit->binance_link ?? '' }}" placeholder="https://pay.binance.com/..."/></div>
          <div class="form-group"><label class="form-label">Binance Wallet Address (BEP-20)</label>
          <input type="text" name="wallet_address" class="form-control" value="{{ $globalDeposit->wallet_address ?? '' }}" placeholder="0x..."/></div>
          <div class="form-section-label mt-3">🔵 Trust Wallet</div>
          <div class="form-group"><label class="form-label">Trust Wallet Address</label>
          <input type="text" name="trust_wallet_address" class="form-control" value="{{ $globalDeposit->trust_wallet_address ?? '' }}" placeholder="0x... or TRX..."/></div>
          <div class="form-group"><label class="form-label">Network</label>
          <select name="trust_network" class="form-control">
            <option value="">-- Select Network --</option>
            @foreach(['BEP-20 (BSC)','ERC-20 (ETH)','TRC-20 (TRON)','BEP-2','Polygon','Solana'] as $net)
              <option value="{{ $net }}" {{ (($globalDeposit->trust_network ?? '') == $net) ? 'selected' : '' }}>{{ $net }}</option>
            @endforeach
          </select></div>
          <div class="form-section-label mt-3">🏦 Bank Transfer</div>
          <div class="form-group"><label class="form-label">Account Name</label>
          <input type="text" name="account_name" class="form-control" value="{{ $globalDeposit->account_name ?? '' }}"/></div>
          <div class="form-group"><label class="form-label">Account Number</label>
          <input type="text" name="account_number" class="form-control" value="{{ $globalDeposit->account_number ?? '' }}"/></div>
          <div class="form-group"><label class="form-label">Bank Name</label>
          <input type="text" name="bank_name" class="form-control" value="{{ $globalDeposit->bank_name ?? '' }}"/></div>
          <div class="form-group"><label class="form-label">SWIFT / Routing</label>
          <input type="text" name="swift" class="form-control" value="{{ $globalDeposit->swift ?? '' }}"/></div>
          <div class="form-group"><label class="form-label">Reference</label>
          <input type="text" name="reference" class="form-control" value="{{ $globalDeposit->reference ?? '' }}"/></div>
            <button type="submit" class="btn btn-primary w-100 mt-3" style="background:#22c55e;border-color:#22c55e;">💾 Save Global Deposit Info</button>
          </form>
        </div>
      </div>
    </div>

    {{-- WITHDRAWAL --}}
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header" style="background:rgba(239,68,68,0.08);border-bottom:2px solid rgba(239,68,68,0.3);">
          <div style="font-weight:800;color:#ef4444;">⬇️ Withdrawal Information</div>
          <div style="font-size:11px;color:var(--muted);">Shown to all customers by default</div>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.payment-info.withdraw') }}">
            @csrf
            <input type="hidden" name="user_id" value="">
            
          <div class="form-section-label">🟡 Binance Pay</div>
          <div class="form-group"><label class="form-label">Withdrawal Request Link</label>
          <input type="text" name="withdraw_link" class="form-control" value="{{ $globalWithdrawal->withdraw_link ?? '' }}" placeholder="https://pay.binance.com/..."/></div>
          <div class="form-group"><label class="form-label">Withdrawal ID</label>
          <input type="text" name="withdraw_id" class="form-control" value="{{ $globalWithdrawal->withdraw_id ?? '' }}" placeholder="WD-ONX-..."/></div>
          <div class="form-section-label mt-3">🔵 Trust Wallet</div>
          <div class="form-group"><label class="form-label">Trust Wallet Address</label>
          <input type="text" name="trust_withdraw_address" class="form-control" value="{{ $globalWithdrawal->trust_withdraw_address ?? '' }}" placeholder="0x..."/></div>
          <div class="form-group"><label class="form-label">Network</label>
          <select name="trust_network" class="form-control">
            <option value="">-- Select Network --</option>
            @foreach(['BEP-20 (BSC)','ERC-20 (ETH)','TRC-20 (TRON)','BEP-2','Polygon','Solana'] as $net)
              <option value="{{ $net }}" {{ (($globalWithdrawal->trust_network ?? '') == $net) ? 'selected' : '' }}>{{ $net }}</option>
            @endforeach
          </select></div>
          <div class="form-section-label mt-3">ℹ️ General Info</div>
          <div class="form-group"><label class="form-label">Min. Withdrawal</label>
          <input type="text" name="min_withdrawal" class="form-control" value="{{ $globalWithdrawal->min_withdrawal ?? '' }}" placeholder="$50 USDT"/></div>
          <div class="form-group"><label class="form-label">Processing Time</label>
          <input type="text" name="processing_time" class="form-control" value="{{ $globalWithdrawal->processing_time ?? '' }}" placeholder="12–24 Hours"/></div>
          <div class="form-group"><label class="form-label">Fee</label>
          <input type="text" name="fee" class="form-control" value="{{ $globalWithdrawal->fee ?? '' }}" placeholder="1.5%"/></div>
          <div class="form-group"><label class="form-label">Note for Customers</label>
          <input type="text" name="note" class="form-control" value="{{ $globalWithdrawal->note ?? '' }}" placeholder="Contact live chat to submit your withdrawal request."/></div>
            <button type="submit" class="btn btn-primary w-100 mt-3" style="background:#ef4444;border-color:#ef4444;">💾 Save Global Withdrawal Info</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════════════════
     CUSTOMER OVERRIDE TAB
══════════════════════════════════════════════════════ --}}
<div id="panelCustomer" style="display:none;">
  <p style="color:var(--muted);font-size:13px;margin-bottom:20px;">
    Set <strong style="color:var(--text);">custom payment info</strong> for a specific customer. They will see this instead of the global info.
    If cleared, they revert to global.
  </p>

  {{-- Customer selector --}}
  <div class="card mb-4">
    <div class="card-body" style="padding:16px;">
      <div class="row g-3 align-items-end">
        <div class="col-md-5">
          <label class="form-label">Select Customer</label>
          <select id="overrideCustomerSelect" class="form-control" onchange="loadOverride(this.value)">
            <option value="">-- Select a customer --</option>
            @foreach($customers as $c)
              <option value="{{ $c->id }}"
                data-dep="{{ json_encode($depOverrides[$c->id] ?? null) }}"
                data-wd="{{ json_encode($wdOverrides[$c->id] ?? null) }}">
                {{ $c->name }} — {{ $c->email }}
                @if(isset($depOverrides[$c->id]) || isset($wdOverrides[$c->id]))
                  ★ Override active
                @endif
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-7">
          <div id="overrideStatus" style="font-size:12px;color:var(--muted);padding:8px 0;">Select a customer to view or set their custom payment info.</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Override forms (hidden until customer selected) --}}
  <div id="overrideForms" style="display:none;">
    <div class="row g-4">

      {{-- DEPOSIT OVERRIDE --}}
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header" style="background:rgba(34,197,94,0.08);border-bottom:2px solid rgba(34,197,94,0.3);">
            <div>
              <div style="font-weight:800;color:#22c55e;">⬆️ Custom Deposit Info</div>
              <div id="depOverrideLabel" style="font-size:11px;color:var(--muted);"></div>
            </div>
            <div id="depOverrideBadge" style="display:none;">
              <span style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);color:#22c55e;border-radius:6px;padding:3px 10px;font-size:11px;font-weight:700;">✓ Override Active</span>
            </div>
          </div>
          <div class="card-body">
            <form method="POST" action="{{ route('admin.payment-info.deposit') }}" id="depOverrideForm">
              @csrf
              <input type="hidden" name="user_id" id="depUserId" value="">
              <div id="depOverrideFields"></div>
              <div style="display:flex;gap:8px;margin-top:12px;">
                <button type="submit" class="btn btn-primary flex-1" style="background:#22c55e;border-color:#22c55e;">💾 Save</button>
                <button type="button" id="depRemoveBtn" onclick="removeOverride('deposit')" class="btn btn-sm" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#f87171;display:none;">🗑 Remove Override</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      {{-- WITHDRAWAL OVERRIDE --}}
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header" style="background:rgba(239,68,68,0.08);border-bottom:2px solid rgba(239,68,68,0.3);">
            <div>
              <div style="font-weight:800;color:#ef4444;">⬇️ Custom Withdrawal Info</div>
              <div id="wdOverrideLabel" style="font-size:11px;color:var(--muted);"></div>
            </div>
            <div id="wdOverrideBadge" style="display:none;">
              <span style="background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.3);color:#f87171;border-radius:6px;padding:3px 10px;font-size:11px;font-weight:700;">✓ Override Active</span>
            </div>
          </div>
          <div class="card-body">
            <form method="POST" action="{{ route('admin.payment-info.withdraw') }}" id="wdOverrideForm">
              @csrf
              <input type="hidden" name="user_id" id="wdUserId" value="">
              <div id="wdOverrideFields"></div>
              <div style="display:flex;gap:8px;margin-top:12px;">
                <button type="submit" class="btn btn-primary flex-1" style="background:#ef4444;border-color:#ef4444;">💾 Save</button>
                <button type="button" id="wdRemoveBtn" onclick="removeOverride('withdraw')" class="btn btn-sm" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#f87171;display:none;">🗑 Remove Override</button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

{{-- Remove override forms --}}
<form method="POST" id="removeDepForm" action="" style="display:none;">
  @csrf @method('DELETE')
</form>
<form method="POST" id="removeWdForm" action="" style="display:none;">
  @csrf @method('DELETE')
</form>

@push('scripts')
<script>
const networks = ['BEP-20 (BSC)','ERC-20 (ETH)','TRC-20 (TRON)','BEP-2','Polygon','Solana'];

function switchTab(tab) {
  const isGlobal = tab === 'global';
  document.getElementById('panelGlobal').style.display   = isGlobal ? 'block' : 'none';
  document.getElementById('panelCustomer').style.display = isGlobal ? 'none'  : 'block';
  document.getElementById('tabGlobal').style.color       = isGlobal ? 'var(--accent)' : 'var(--muted)';
  document.getElementById('tabGlobal').style.background  = isGlobal ? 'var(--surface)' : 'var(--surface2)';
  document.getElementById('tabGlobal').style.borderBottom = isGlobal ? '2px solid var(--accent)' : 'none';
  document.getElementById('tabCustomer').style.color      = isGlobal ? 'var(--muted)' : 'var(--accent)';
  document.getElementById('tabCustomer').style.background = isGlobal ? 'var(--surface2)' : 'var(--surface)';
  document.getElementById('tabCustomer').style.borderBottom = isGlobal ? 'none' : '2px solid var(--accent)';
}

function loadOverride(userId) {
  if (!userId) { document.getElementById('overrideForms').style.display = 'none'; return; }

  const sel = document.getElementById('overrideCustomerSelect');
  const opt = sel.options[sel.selectedIndex];
  const dep = JSON.parse(opt.dataset.dep || 'null');
  const wd  = JSON.parse(opt.dataset.wd  || 'null');
  const name = opt.text.split(' —')[0];

  document.getElementById('depUserId').value = userId;
  document.getElementById('wdUserId').value  = userId;
  document.getElementById('overrideForms').style.display = 'block';

  // Status label
  document.getElementById('overrideStatus').innerHTML =
    `Showing override settings for <strong style="color:var(--text);">${name}</strong>`;

  // Deposit
  document.getElementById('depOverrideLabel').textContent =
    dep ? 'Custom override active for this customer' : 'No override — will show global info';
  document.getElementById('depOverrideBadge').style.display = dep ? 'block' : 'none';
  document.getElementById('depRemoveBtn').style.display     = dep ? 'inline-flex' : 'none';
  document.getElementById('removeDepForm').action = `/admin/payment-info/deposit/${userId}`;
  document.getElementById('depOverrideFields').innerHTML = buildDepositFields(dep);

  // Withdrawal
  document.getElementById('wdOverrideLabel').textContent =
    wd ? 'Custom override active for this customer' : 'No override — will show global info';
  document.getElementById('wdOverrideBadge').style.display = wd ? 'block' : 'none';
  document.getElementById('wdRemoveBtn').style.display     = wd ? 'inline-flex' : 'none';
  document.getElementById('removeWdForm').action = `/admin/payment-info/withdraw/${userId}`;
  document.getElementById('wdOverrideFields').innerHTML = buildWithdrawFields(wd);
}

function buildDepositFields(d) {
  const v = (f) => d ? (d[f] || '') : '';
  const sel = (f) => networks.map(n => `<option value="${n}" ${v(f)===n?'selected':''}>${n}</option>`).join('');
  return `
    <div class="form-section-label">🟡 Binance Pay</div>
    <div class="form-group"><label class="form-label">Binance Pay Link</label>
    <input type="text" name="binance_link" class="form-control" value="${v('binance_link')}" placeholder="https://pay.binance.com/..."/></div>
    <div class="form-group"><label class="form-label">Binance Wallet Address (BEP-20)</label>
    <input type="text" name="wallet_address" class="form-control" value="${v('wallet_address')}" placeholder="0x..."/></div>
    <div class="form-section-label mt-3">🔵 Trust Wallet</div>
    <div class="form-group"><label class="form-label">Trust Wallet Address</label>
    <input type="text" name="trust_wallet_address" class="form-control" value="${v('trust_wallet_address')}" placeholder="0x..."/></div>
    <div class="form-group"><label class="form-label">Network</label>
    <select name="trust_network" class="form-control"><option value="">-- Select --</option>${sel('trust_network')}</select></div>
    <div class="form-section-label mt-3">🏦 Bank Transfer</div>
    <div class="form-group"><label class="form-label">Account Name</label><input type="text" name="account_name" class="form-control" value="${v('account_name')}"/></div>
    <div class="form-group"><label class="form-label">Account Number</label><input type="text" name="account_number" class="form-control" value="${v('account_number')}"/></div>
    <div class="form-group"><label class="form-label">Bank Name</label><input type="text" name="bank_name" class="form-control" value="${v('bank_name')}"/></div>
    <div class="form-group"><label class="form-label">SWIFT</label><input type="text" name="swift" class="form-control" value="${v('swift')}"/></div>
    <div class="form-group"><label class="form-label">Reference</label><input type="text" name="reference" class="form-control" value="${v('reference')}"/></div>`;
}

function buildWithdrawFields(d) {
  const v = (f) => d ? (d[f] || '') : '';
  const sel = (f) => networks.map(n => `<option value="${n}" ${v(f)===n?'selected':''}>${n}</option>`).join('');
  return `
    <div class="form-section-label">🟡 Binance Pay</div>
    <div class="form-group"><label class="form-label">Withdrawal Request Link</label>
    <input type="text" name="withdraw_link" class="form-control" value="${v('withdraw_link')}" placeholder="https://pay.binance.com/..."/></div>
    <div class="form-group"><label class="form-label">Withdrawal ID</label>
    <input type="text" name="withdraw_id" class="form-control" value="${v('withdraw_id')}" placeholder="WD-..."/></div>
    <div class="form-section-label mt-3">🔵 Trust Wallet</div>
    <div class="form-group"><label class="form-label">Trust Wallet Address</label>
    <input type="text" name="trust_withdraw_address" class="form-control" value="${v('trust_withdraw_address')}" placeholder="0x..."/></div>
    <div class="form-group"><label class="form-label">Network</label>
    <select name="trust_network" class="form-control"><option value="">-- Select --</option>${sel('trust_network')}</select></div>
    <div class="form-section-label mt-3">ℹ️ General Info</div>
    <div class="form-group"><label class="form-label">Min. Withdrawal</label><input type="text" name="min_withdrawal" class="form-control" value="${v('min_withdrawal')}" placeholder="$50 USDT"/></div>
    <div class="form-group"><label class="form-label">Processing Time</label><input type="text" name="processing_time" class="form-control" value="${v('processing_time')}" placeholder="12–24 Hours"/></div>
    <div class="form-group"><label class="form-label">Fee</label><input type="text" name="fee" class="form-control" value="${v('fee')}" placeholder="1.5%"/></div>
    <div class="form-group"><label class="form-label">Note</label><input type="text" name="note" class="form-control" value="${v('note')}"/></div>`;
}

function removeOverride(type) {
  if (!confirm('Remove custom override? This customer will revert to global payment info.')) return;
  document.getElementById(type === 'deposit' ? 'removeDepForm' : 'removeWdForm').submit();
}
</script>
@endpush

@endsection
