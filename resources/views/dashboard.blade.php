@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

{{-- ── HEADER (admin-style topbar) ──────────────────────── --}}
<div class="cust-topbar">
  <div class="cust-topbar-left">
    <a href="{{ route('home') }}" class="cust-logo" style="text-decoration:none;">BTF<span>ETH</span></a>
    <div class="cust-topbar-divider"></div>
    <div class="cust-location-time">
      <div id="custTime" class="cust-time">--:-- --</div>
      <div id="custDate" class="cust-date">--- --, ----</div>
      <div id="custLocation" class="cust-loc">📍 Locating...</div>
    </div>
  </div>

  <div class="cust-topbar-right">
    {{-- Main Balance in header --}}
    <div style="display:flex;align-items:center;gap:8px;background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.25);border-radius:10px;padding:7px 14px;margin-right:8px;">
      <span style="font-size:13px;">💵</span>
      <div>
        <div style="font-size:9px;text-transform:uppercase;letter-spacing:1px;color:rgba(134,239,172,0.7);line-height:1;">Balance</div>
        <div style="font-size:13px;font-weight:700;color:#86efac;font-family:'JetBrains Mono',monospace;line-height:1.3;">${{ number_format($user->balance, 2) }}</div>
      </div>
    </div>
    {{-- User dropdown --}}
    <div class="dropdown">
      <button class="cust-user-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        @if($user->avatar)
          <img src="{{ asset('avatars/' . $user->avatar) }}" class="cust-avatar" style="object-fit:cover;" alt=""/>
        @else
          <div class="cust-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        @endif
        <div class="cust-user-info">
          <div class="cust-user-name">{{ $user->name }}</div>
        </div>
      </button>
      <ul class="dropdown-menu dropdown-menu-end cust-dropdown">
        <li>
          <a class="dropdown-item cust-drop-item" href="{{ route('dashboard') }}">
            🏠 &nbsp;Dashboard
          </a>
        </li>
        <li>
          <a class="dropdown-item cust-drop-item" href="{{ route('profile.edit') }}">
            👤 &nbsp;Edit Profile
          </a>
        </li>
        <li>
          <a class="dropdown-item cust-drop-item" href="{{ route('password.change') }}">
            🔑 &nbsp;Change Password
          </a>
        </li>
        <li><hr class="cust-drop-divider"></li>
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item cust-drop-item cust-drop-danger">
              🚪 &nbsp;Sign Out
            </button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</div>

{{-- ── FLASH MESSAGES ──────────────────────────────────────── --}}
@if(session('success'))
  <div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  </div>
@endif

<div class="container py-4">

  {{-- ── BALANCE CARDS ──────────────────────────────────────── --}}
  <div class="row g-2 mb-4">
    <div class="col-5">
      <div class="stat-card" style="padding:12px 14px;">
        <div class="stat-label" style="font-size:9px;">Daily Profit</div>
        <div class="stat-value text-green" style="font-size:18px;">+${{ number_format($user->daily_profit, 2) }}</div>
        <div class="stat-sub" style="font-size:10px;">Today's earnings</div>
      </div>
    </div>
    <div class="col-4">
      <div class="stat-card" style="padding:12px 14px;">
        <div class="stat-label" style="font-size:9px;">Total Invested</div>
        <div class="stat-value" style="font-size:18px;">${{ number_format($user->total_deposited, 2) }}</div>
        <div class="stat-sub" style="font-size:10px;">In plans</div>
      </div>
    </div>
    <div class="col-3">
      <div style="display:flex;flex-direction:column;gap:6px;height:100%;">
        <button class="balance-btn deposit-btn w-100" data-bs-toggle="modal" data-bs-target="#depositModal"
          style="flex:1;border-radius:10px;display:flex;align-items:center;justify-content:center;gap:6px;padding:0 10px;">
          <span style="font-size:16px;">⬆</span>
          <span style="font-size:12px;font-weight:700;">Deposit</span>
        </button>
        <button class="balance-btn withdraw-btn w-100" data-bs-toggle="modal" data-bs-target="#withdrawModal"
          style="flex:1;border-radius:10px;display:flex;align-items:center;justify-content:center;gap:6px;padding:0 10px;">
          <span style="font-size:16px;">⬇</span>
          <span style="font-size:12px;font-weight:700;">Withdraw</span>
        </button>
      </div>
    </div>
  </div>



  {{-- ── ACTIVE INVESTMENT PLANS ──────────────────────────── --}}
  @if($activePlans->count() > 0)
  <div class="mb-4">
    <div class="section-title mb-2" style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);font-weight:700;">Active Investment Plans</div>
    @foreach($activePlans as $plan)
    <div class="plan-card mb-3">
      <div class="plan-card-top">
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
          <span class="plan-name-badge">{{ $plan->plan_name }}</span>
          <span class="plan-status-badge plan-active">● Active</span>
          <span style="font-size:11px;color:var(--muted);">Cycle {{ $plan->cycle_number }}/{{ $plan->max_cycles }}</span>
        </div>
        <div class="plan-rate">{{ $plan->profit_rate }}%<span style="font-size:11px;font-weight:400;color:var(--muted);"> /day</span></div>
      </div>
      <div class="row g-2 mt-2 mb-3">
        <div class="col-6">
          <div class="plan-stat-label">Invested</div>
          <div class="plan-stat-val">${{ number_format($plan->amount, 2) }}</div>
        </div>
        <div class="col-6">
          <div class="plan-stat-label">Daily Profit</div>
          <div class="plan-stat-val" style="color:#22c55e;">+${{ number_format($plan->dailyProfit(), 2) }}</div>
        </div>
        <div class="col-6">
          <div class="plan-stat-label">Duration</div>
          <div class="plan-stat-val">{{ $plan->duration_days }} days</div>
        </div>
        <div class="col-6">
          <div class="plan-stat-label">Days Remaining</div>
          <div class="plan-stat-val {{ $plan->daysRemaining() <= 3 ? 'text-warning' : '' }}">{{ $plan->daysRemaining() }} days</div>
        </div>
      </div>
      <div>
        <div style="display:flex;justify-content:space-between;font-size:10px;color:var(--muted);margin-bottom:4px;">
          <span>{{ $plan->start_date->format('d M Y') }}</span>
          <span>{{ $plan->progressPercent() }}%</span>
          <span>{{ $plan->end_date->format('d M Y') }}</span>
        </div>
        <div class="plan-bar"><div class="plan-bar-fill" style="width:{{ $plan->progressPercent() }}%"></div></div>
      </div>
      @if($plan->notes)
      <div style="font-size:11px;color:var(--muted);margin-top:8px;padding-top:8px;border-top:1px solid var(--border);">📝 {{ $plan->notes }}</div>
      @endif
    </div>
    @endforeach
  </div>
  @endif

  {{-- ── QUICK MENU ──────────────────────────────────────── --}}
  <div class="row g-2 mb-4">
    <div class="col-6">
      <a class="menu-card" href="{{ route('plans') }}">
        <span class="menu-icon">📈</span>
        <span class="menu-label">Plans</span>
      </a>
    </div>
    <div class="col-6">
      <a class="menu-card" href="#history">
        <span class="menu-icon">🕐</span>
        <span class="menu-label">History</span>
      </a>
    </div>
  </div>

  {{-- ── TRANSACTION HISTORY ─────────────────────────────── --}}
  <div id="history">
    <div class="section-title">Transaction History</div>
    <div class="d-flex flex-column gap-2">

      @forelse($transactions as $txn)
        <div class="history-item">
          <div class="history-icon" style="background:{{ $txn->isDeposit() ? 'rgba(34,197,94,0.12)' : 'rgba(239,68,68,0.12)' }}">
            {{ $txn->isDeposit() ? '⬆️' : '⬇️' }}
          </div>
          <div class="flex-grow-1">
            <div class="history-type">{{ $txn->typeLabel() }}
              @if($txn->reference)
                <span style="font-size:11px;color:var(--muted);font-weight:400;"> · {{ $txn->reference }}</span>
              @endif
            </div>
            <div class="history-date"><span class="local-time" data-utc="{{ $txn->transaction_date->utc()->toISOString() }}">{{ $txn->transaction_date->format('d M Y · H:i:s') }}</span></div>
          </div>
          <div>
            <div class="history-amount {{ $txn->isDeposit() ? 'text-green' : 'text-red' }}">
              {{ $txn->signedAmount() }}
            </div>
            <div class="text-end mt-1">
              <span class="badge {{ $txn->statusBadgeClass() }}">{{ ucfirst($txn->status) }}</span>
            </div>
          </div>
        </div>
      @empty
        <div class="text-center py-4" style="color:var(--muted);">
          <div style="font-size:32px;margin-bottom:8px;">📋</div>
          No transactions yet.
        </div>
      @endforelse

    </div>
  </div>

</div>

{{-- ── FOOTER ──────────────────────────────────────────────── --}}
<footer class="site-footer">
  <div class="container">© {{ date('Y') }} Btfeth Investments Ltd.</div>
</footer>



{{-- ════════════ DEPOSIT MODAL ════════════ --}}
<div class="modal fade" id="depositModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">⬆️ &nbsp;Deposit USDT</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted mb-4" style="font-size:14px;">Send USDT via Binance Pay, Trust Wallet, or bank transfer, then contact support to confirm.</p>
        @if($depositInfo)

          {{-- ── Binance Section ── --}}
          @if($depositInfo->binance_link || $depositInfo->wallet_address)
          <div class="payment-section-header">
            <span style="font-size:16px;">🟡</span> Binance Pay
          </div>
          @if($depositInfo->binance_link)
          <div class="mb-3">
            <div class="form-label">Binance Pay Link</div>
            <div class="copy-box">
              <div class="copy-text">{{ $depositInfo->binance_link }}</div>
              <button class="copy-btn" onclick="copyText(this,'{{ $depositInfo->binance_link }}')">Copy</button>
            </div>
          </div>
          @endif
          @if($depositInfo->wallet_address)
          <div class="mb-3">
            <div class="form-label">Binance Wallet Address (BEP-20)</div>
            <div class="copy-box">
              <div class="copy-text">{{ $depositInfo->wallet_address }}</div>
              <button class="copy-btn" onclick="copyText(this,'{{ $depositInfo->wallet_address }}')">Copy</button>
            </div>
          </div>
          @endif
          @endif

          {{-- ── Trust Wallet Section ── --}}
          @if($depositInfo->trust_wallet_address)
          <div class="payment-section-header mt-3">
            <span style="font-size:16px;">🔵</span> Trust Wallet
          </div>
          <div class="mb-3">
            <div class="form-label">Trust Wallet Address
              @if($depositInfo->trust_network)
                <span class="network-badge">{{ $depositInfo->trust_network }}</span>
              @endif
            </div>
            <div class="copy-box">
              <div class="copy-text">{{ $depositInfo->trust_wallet_address }}</div>
              <button class="copy-btn" onclick="copyText(this,'{{ $depositInfo->trust_wallet_address }}')">Copy</button>
            </div>
          </div>
          @endif



          {{-- ── Bank Section ── --}}
          @if($depositInfo->account_name || $depositInfo->account_number)
          <div class="payment-section-header mt-3">
            🏦 Bank Transfer
          </div>
          <div class="d-flex flex-column gap-2">
            @foreach(['account_name'=>'Account Name','account_number'=>'Account Number','bank_name'=>'Bank Name','swift'=>'SWIFT / Routing','reference'=>'Reference'] as $field => $label)
              @if($depositInfo->$field)
              <div class="bank-row">
                <span class="bank-key">{{ $label }}</span>
                <span class="bank-val">{{ $depositInfo->$field }}</span>
              </div>
              @endif
            @endforeach
          </div>
          @endif

        @else
          <div class="alert-note">⚠️ Deposit information is not set yet. Please contact support.</div>
        @endif
        <div class="alert-note mt-4">⚠️ After transferring, contact support with your transaction ID to confirm your deposit within 30 minutes.</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

{{-- ════════════ WITHDRAW MODAL ════════════ --}}
<div class="modal fade" id="withdrawModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">⬇️ &nbsp;Withdraw USDT</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted mb-4" style="font-size:14px;">Withdraw to your Binance, Trust Wallet, or bank account.</p>
        @if($withdrawalInfo)

          {{-- ── Binance Withdraw ── --}}
          @if($withdrawalInfo->withdraw_link || $withdrawalInfo->withdraw_id)
          <div class="payment-section-header">
            <span style="font-size:16px;">🟡</span> Binance Pay
          </div>
          @if($withdrawalInfo->withdraw_link)
          <div class="mb-3">
            <div class="form-label">Withdrawal Request Link</div>
            <div class="copy-box">
              <div class="copy-text">{{ $withdrawalInfo->withdraw_link }}</div>
              <button class="copy-btn" onclick="copyText(this,'{{ $withdrawalInfo->withdraw_link }}')">Copy</button>
            </div>
          </div>
          @endif
          @if($withdrawalInfo->withdraw_id)
          <div class="mb-3">
            <div class="form-label">Withdrawal ID</div>
            <div class="copy-box">
              <div class="copy-text">{{ $withdrawalInfo->withdraw_id }}</div>
              <button class="copy-btn" onclick="copyText(this,'{{ $withdrawalInfo->withdraw_id }}')">Copy</button>
            </div>
          </div>
          @endif
          @endif

          {{-- ── Trust Wallet Withdraw ── --}}
          @if($withdrawalInfo->trust_withdraw_address)
          <div class="payment-section-header mt-3">
            <span style="font-size:16px;">🔵</span> Trust Wallet
          </div>
          <div class="mb-3">
            <div class="form-label">Send to Trust Wallet Address
              @if($withdrawalInfo->trust_network)
                <span class="network-badge">{{ $withdrawalInfo->trust_network }}</span>
              @endif
            </div>
            <div class="copy-box">
              <div class="copy-text">{{ $withdrawalInfo->trust_withdraw_address }}</div>
              <button class="copy-btn" onclick="copyText(this,'{{ $withdrawalInfo->trust_withdraw_address }}')">Copy</button>
            </div>
          </div>
          @endif

          {{-- ── General Info ── --}}
          @if($withdrawalInfo->min_withdrawal || $withdrawalInfo->processing_time || $withdrawalInfo->fee)
          <div class="payment-section-header mt-3">ℹ️ Withdrawal Info</div>
          <div class="d-flex flex-column gap-2">
            @foreach(['min_withdrawal'=>'Min. Withdrawal','processing_time'=>'Processing Time','fee'=>'Fee'] as $field => $label)
              @if($withdrawalInfo->$field)
              <div class="bank-row">
                <span class="bank-key">{{ $label }}</span>
                <span class="bank-val">{{ $withdrawalInfo->$field }}</span>
              </div>
              @endif
            @endforeach
          </div>
          @endif

          @if($withdrawalInfo->note)
          <div class="alert-note mt-4">⚠️ {{ $withdrawalInfo->note }}</div>
          @endif
        @else
          <div class="alert-note">⚠️ Withdrawal information is not set yet. Please contact support.</div>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

{{-- ════════════ SUPPORT MODAL ════════════ --}}
<div class="modal fade" id="supportModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">💬 &nbsp;Live Support</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="text-align:center;padding:32px 24px;">
        <div style="font-size:52px;margin-bottom:16px;">💬</div>
        <h5 style="font-weight:800;margin-bottom:8px;">We're Online</h5>
        <p style="color:var(--muted);font-size:14px;margin-bottom:28px;line-height:1.7;">
          Our support team is available 24/7 via live chat.<br>
          Click the chat bubble at the bottom of the screen to start chatting instantly.
        </p>
        <div style="background:var(--surface2);border:1px solid var(--border);border-radius:12px;padding:16px 20px;text-align:left;">
          <div style="font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">We can help with</div>
          <div class="d-flex flex-column gap-2" style="font-size:13px;color:var(--text);">
            <div>✅ &nbsp;Confirming your deposit</div>
            <div>✅ &nbsp;Processing your withdrawal</div>
            <div>✅ &nbsp;Account questions</div>
            <div>✅ &nbsp;Investment plan guidance</div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary w-100" onclick="if(window.Tawk_API) Tawk_API.toggle(); this.closest('.modal').querySelector('.btn-close').click();">
          Open Live Chat →
        </button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.local-time').forEach(el => {
  const d = new Date(el.dataset.utc);
  el.textContent = d.toLocaleDateString('en-GB', {day:'2-digit',month:'short',year:'numeric'})
    + ' · ' + d.toLocaleTimeString('en-GB', {hour:'2-digit',minute:'2-digit',second:'2-digit'});
});
</script>
<script>
  // ── Location-based date/time (12-hour format) ─────────────
  let userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
  let locationLabel = '';

  function updateClock() {
    const now = new Date();

    // 12-hour time with seconds
    const timeStr = now.toLocaleTimeString('en-US', {
      timeZone: userTimezone,
      hour:     '2-digit',
      minute:   '2-digit',
      second:   '2-digit',
      hour12:   true,
    });

    // Date: Mon, 09 Mar 2026
    const dateStr = now.toLocaleDateString('en-US', {
      timeZone: userTimezone,
      weekday: 'short',
      day:     '2-digit',
      month:   'short',
      year:    'numeric',
    });

    const timeEl = document.getElementById('custTime');
    const dateEl = document.getElementById('custDate');
    if (timeEl) timeEl.textContent = timeStr;
    if (dateEl) dateEl.textContent = dateStr;
  }

  // Get city from coordinates using reverse geocoding (no API key needed)
  function getLocation() {
    if (!navigator.geolocation) {
      document.getElementById('custLocation').textContent = '📍 ' + userTimezone;
      return;
    }
    navigator.geolocation.getCurrentPosition(
      function(pos) {
        const lat = pos.coords.latitude;
        const lon = pos.coords.longitude;
        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`)
          .then(r => r.json())
          .then(data => {
            const city = data.address.city || data.address.town || data.address.village || data.address.county || '';
            const country = data.address.country_code ? data.address.country_code.toUpperCase() : '';
            locationLabel = city ? `📍 ${city}, ${country}` : `📍 ${userTimezone}`;
            document.getElementById('custLocation').textContent = locationLabel;
          })
          .catch(() => {
            document.getElementById('custLocation').textContent = '📍 ' + userTimezone;
          });
      },
      function() {
        // Permission denied — fall back to timezone name
        const parts = userTimezone.split('/');
        const city = parts[parts.length - 1].replace(/_/g,' ');
        document.getElementById('custLocation').textContent = '📍 ' + city;
      }
    );
  }

  setInterval(updateClock, 1000);
  updateClock();
  getLocation();

  // ── Copy helper ───────────────────────────────────────────
  function copyText(btn, text) {
    navigator.clipboard.writeText(text).then(() => {
      const orig = btn.textContent;
      btn.textContent = '✓ Copied';
      btn.style.background = 'var(--green)';
      btn.style.color = '#000';
      setTimeout(() => { btn.textContent = orig; btn.style.background = ''; btn.style.color = ''; }, 2000);
    });
  }
</script>
@endpush

@endsection
