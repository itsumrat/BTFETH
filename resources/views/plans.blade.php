<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profit Plans — Btfeth</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container">
    <a class="navbar-brand" href="{{ route('home') }}">BTF<span>ETH</span></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="{{ route('plans') }}">Plans</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
        <li class="nav-item ms-lg-2"><a class="btn btn-outline-secondary btn-sm" href="{{ route('login') }}">Sign Out</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-5 fade-up">

  <!-- Header -->
  <div class="mb-5">
    <p class="text-muted mb-1" style="font-size:13px; text-transform:uppercase; letter-spacing:1px;">Investment Plans</p>
    <h2 class="mb-2" style="letter-spacing:-0.5px;">Profit Schedule</h2>
    <p class="text-muted" style="max-width:500px;">Choose the plan that fits your investment size. Profits are credited to your balance automatically every 24 hours.</p>
  </div>

  <!-- Notice -->
  <div class="d-flex align-items-start gap-3 p-3 mb-5" style="background:rgba(59,130,246,0.07); border:1px solid rgba(59,130,246,0.2); border-radius:12px;">
    <span style="font-size:20px; flex-shrink:0;">💡</span>
    <div>
      <div style="font-size:14px; font-weight:600; margin-bottom:3px;">Earn passive income daily</div>
      <div style="font-size:13px; color:var(--muted);">All plans are powered by Binance Smart Chain liquidity pools. Minimum deposit required to activate a plan.</div>
    </div>
  </div>

  <!-- Flash messages -->
@if(session('success'))
<div style="background:rgba(34,197,94,0.12);border:1px solid rgba(34,197,94,0.3);color:#22c55e;border-radius:10px;padding:14px 20px;margin:16px auto;max-width:1200px;font-size:14px;font-weight:600;display:flex;align-items:center;justify-content:space-between;"><span>✅ {{ session('success') }}</span><button class="alert-close" onclick="dismissAlert(this)" title="Dismiss">&times;</button></div>
@endif
@if(session('error'))
<div style="background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.3);color:#f87171;border-radius:10px;padding:14px 20px;margin:16px auto;max-width:1200px;font-size:14px;font-weight:600;display:flex;align-items:center;justify-content:space-between;"><span>⚠️ {{ session('error') }}</span><button class="alert-close" onclick="dismissAlert(this)" title="Dismiss">&times;</button></div>
@endif

<!-- Plan Cards -->
  <div class="row g-4 mb-5">

    <!-- Plan 1 -->
    <div class="col-sm-6 col-lg-3">
      <div class="plan-card">
        <div class="plan-tier">📅 Plan 1</div>
        <div class="plan-rate">1.5%</div>
        <div class="plan-period">Profit Total</div>
        <div class="plan-range">$500 – $1,000 USDT</div>
        <div class="d-flex flex-column gap-2 mt-3">
          <div class="d-flex justify-content-between align-items-center" style="background:var(--surface2);border:1px solid var(--border);border-radius:7px;padding:8px 12px;font-size:13px;">
            <span class="text-muted">Duration</span>
            <span style="font-weight:600;">1 – 7 Days</span>
          </div>
          <div class="d-flex justify-content-between align-items-center" style="background:var(--surface2);border:1px solid var(--border);border-radius:7px;padding:8px 12px;font-size:13px;">
            <span class="text-muted">Cycles</span>
            <span style="font-weight:600;">1 – 2 Cycles</span>
          </div>
        </div>
        <div class="plan-bar mt-3"><div class="plan-bar-fill" style="width:22%"></div></div>
        @auth
        <button class="btn btn-primary w-100 mt-3" style="font-size:13px;padding:9px;"
          onclick="openPlanRequest('Plan 1',500,1000,1.5,7,2)">
          Select Plan 1
        </button>
        @else
        <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 mt-3" style="font-size:13px;padding:9px;">
          Sign In to Select
        </a>
        @endauth
      </div>
    </div>

    <!-- Plan 2 -->
    <div class="col-sm-6 col-lg-3">
      <div class="plan-card">
        <div class="plan-tier">📅 Plan 2</div>
        <div class="plan-rate">3 – 3.5%</div>
        <div class="plan-period">Profit Total</div>
        <div class="plan-range">$10,000 – $15,000 USDT</div>
        <div class="d-flex flex-column gap-2 mt-3">
          <div class="d-flex justify-content-between align-items-center" style="background:var(--surface2);border:1px solid var(--border);border-radius:7px;padding:8px 12px;font-size:13px;">
            <span class="text-muted">Duration</span>
            <span style="font-weight:600;">1 – 15 Days</span>
          </div>
          <div class="d-flex justify-content-between align-items-center" style="background:var(--surface2);border:1px solid var(--border);border-radius:7px;padding:8px 12px;font-size:13px;">
            <span class="text-muted">Cycles</span>
            <span style="font-weight:600;">1 – 3 Cycles</span>
          </div>
        </div>
        <div class="plan-bar mt-3"><div class="plan-bar-fill" style="width:48%"></div></div>
        @auth
        <button class="btn btn-primary w-100 mt-3" style="font-size:13px;padding:9px;"
          onclick="openPlanRequest('Plan 2',10000,15000,3.0,15,3)">
          Select Plan 2
        </button>
        @else
        <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 mt-3" style="font-size:13px;padding:9px;">
          Sign In to Select
        </a>
        @endauth
      </div>
    </div>

    <!-- Plan 3 — VIP 1 -->
    <div class="col-sm-6 col-lg-3">
      <div class="plan-card featured">
        <div class="plan-tier">👑 VIP 1 <span class="badge bg-primary-soft ms-1">Popular</span></div>
        <div class="plan-rate">5%</div>
        <div class="plan-period">Profit Total</div>
        <div class="plan-range">$25,000 – $50,000 USDT</div>
        <div class="d-flex flex-column gap-2 mt-3">
          <div class="d-flex justify-content-between align-items-center" style="background:var(--surface2);border:1px solid var(--border);border-radius:7px;padding:8px 12px;font-size:13px;">
            <span class="text-muted">Duration</span>
            <span style="font-weight:600;">1 – 28 Days</span>
          </div>
          <div class="d-flex justify-content-between align-items-center" style="background:var(--surface2);border:1px solid var(--border);border-radius:7px;padding:8px 12px;font-size:13px;">
            <span class="text-muted">Cycles</span>
            <span style="font-weight:600;">1 – 6 Cycles</span>
          </div>
        </div>
        <div class="plan-bar mt-3"><div class="plan-bar-fill" style="width:70%"></div></div>
        @auth
        <button class="btn btn-primary w-100 mt-3" style="font-size:13px;padding:9px;"
          onclick="openPlanRequest('VIP 1',25000,50000,5.0,28,6)">
          Select VIP 1
        </button>
        @else
        <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 mt-3" style="font-size:13px;padding:9px;">
          Sign In to Select
        </a>
        @endauth
      </div>
    </div>

    <!-- Plan 4 — VIP 2 -->
    <div class="col-sm-6 col-lg-3">
      <div class="plan-card" style="border-color:rgba(245,158,11,0.4);">
        <div class="plan-tier">💎 VIP 2</div>
        <div class="plan-rate" style="color:var(--gold);">7%</div>
        <div class="plan-period">Profit Total</div>
        <div class="plan-range">$50,000 – $100,000+ USDT</div>
        <div class="d-flex flex-column gap-2 mt-3">
          <div class="d-flex justify-content-between align-items-center" style="background:var(--surface2);border:1px solid var(--border);border-radius:7px;padding:8px 12px;font-size:13px;">
            <span class="text-muted">Duration</span>
            <span style="font-weight:600;">1 – 28 Days</span>
          </div>
          <div class="d-flex justify-content-between align-items-center" style="background:var(--surface2);border:1px solid var(--border);border-radius:7px;padding:8px 12px;font-size:13px;">
            <span class="text-muted">Cycles</span>
            <span style="font-weight:600;">5 Cycles</span>
          </div>
        </div>
        <div class="plan-bar mt-3"><div class="plan-bar-fill" style="width:100%"></div></div>
        @auth
        <button class="btn btn-primary w-100 mt-3" style="font-size:13px;padding:9px;"
          onclick="openPlanRequest('VIP 2',50000,999999,7.0,28,5)">
          Select VIP 2
        </button>
        @else
        <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 mt-3" style="font-size:13px;padding:9px;">
          Sign In to Select
        </a>
        @endauth
      </div>
    </div>

  </div>

  <!-- Profit Projection -->
  <div class="card mb-4">
    <div class="card-header">
      Profit Projection Calculator
    </div>
    <div class="card-body">

      {{-- Controls --}}
      <div class="row g-3 mb-4 align-items-end">
        <div class="col-md-5">
          <label style="font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;display:block;margin-bottom:6px;">Select Plan</label>
          <select id="projPlan" class="form-control" onchange="calcProjection()">
            <option value="1.5,7">Plan 1 — 1.5%/day · 7 days</option>
            <option value="3.0,15">Plan 2 — 3.0%/day · 15 days</option>
            <option value="5.0,28">VIP 1 — 5.0%/day · 28 days</option>
            <option value="7.0,28">VIP 2 — 7.0%/day · 28 days</option>
          </select>
        </div>
        <div class="col-md-4">
          <label style="font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;display:block;margin-bottom:6px;">Investment Amount (USDT)</label>
          <input type="number" id="projAmount" class="form-control" value="1000" min="1" oninput="calcProjection()" />
        </div>
        <div class="col-md-3">
          <label style="font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;display:block;margin-bottom:6px;">Number of Cycles</label>
          <select id="projCycles" class="form-control" onchange="calcProjection()">
            <option value="1">1 Cycle</option>
            <option value="2">2 Cycles</option>
            <option value="3">3 Cycles</option>
            <option value="4">4 Cycles</option>
            <option value="5">5 Cycles</option>
            <option value="6">6 Cycles</option>
          </select>
        </div>
      </div>

      {{-- Results --}}
      <div class="row g-3 text-center">
        <div class="col-4">
          <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:18px 12px;">
            <div class="text-muted mb-2" style="font-size:11px;text-transform:uppercase;letter-spacing:1px;font-family:'JetBrains Mono',monospace;">Daily Profit</div>
            <div id="projDaily" style="font-size:24px;font-weight:800;font-family:'JetBrains Mono',monospace;color:var(--green);">$0.00</div>
          </div>
        </div>
        <div class="col-4">
          <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:18px 12px;">
            <div class="text-muted mb-2" style="font-size:11px;text-transform:uppercase;letter-spacing:1px;font-family:'JetBrains Mono',monospace;">Per Cycle Profit</div>
            <div id="projCycleProfit" style="font-size:24px;font-weight:800;font-family:'JetBrains Mono',monospace;color:var(--gold);">$0.00</div>
          </div>
        </div>
        <div class="col-4">
          <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:18px 12px;">
            <div class="text-muted mb-2" style="font-size:11px;text-transform:uppercase;letter-spacing:1px;font-family:'JetBrains Mono',monospace;">Total Returns</div>
            <div id="projTotal" style="font-size:24px;font-weight:800;font-family:'JetBrains Mono',monospace;color:var(--accent);">$0.00</div>
          </div>
        </div>
      </div>

      {{-- Summary line --}}
      <div id="projSummary" style="margin-top:16px;text-align:center;font-size:13px;color:var(--muted);"></div>

    </div>
  </div>

  <script>
  function calcProjection() {
    const [rate, days] = document.getElementById('projPlan').value.split(',').map(Number);
    const amount  = parseFloat(document.getElementById('projAmount').value) || 0;
    const cycles  = parseInt(document.getElementById('projCycles').value) || 1;
    const daily       = amount * (rate / 100);
    const cycleProfit = daily * days;
    const totalProfit = cycleProfit * cycles;
    const totalReturn = amount + totalProfit;
    document.getElementById('projDaily').textContent      = '+$' + daily.toFixed(2);
    document.getElementById('projCycleProfit').textContent = '+$' + cycleProfit.toFixed(2);
    document.getElementById('projTotal').textContent       = '$' + totalReturn.toFixed(2);
    document.getElementById('projSummary').innerHTML =
      '$' + amount.toLocaleString() + ' × ' + rate + '%/day × ' + days + ' days × ' + cycles + ' cycle(s) = <strong style="color:var(--green);">+$' + totalProfit.toFixed(2) + ' profit</strong>';
  }
  document.addEventListener('DOMContentLoaded', calcProjection);
  </script>

  <!-- FAQ -->
  <div class="section-title mt-5">Frequently Asked Questions</div>

  <div class="accordion" id="faqAccordion" style="--bs-accordion-bg: var(--surface); --bs-accordion-border-color: var(--border); --bs-accordion-btn-color: var(--text); --bs-accordion-active-color: var(--text); --bs-accordion-active-bg: var(--surface2); --bs-accordion-btn-focus-box-shadow: none; --bs-accordion-body-padding-y: 16px;">

    <div class="accordion-item mb-2" style="border-radius:10px !important; overflow:hidden; border:1px solid var(--border) !important;">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" style="font-size:14px; font-weight:600; background:var(--surface);" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
          When are profits paid out?
        </button>
      </h2>
      <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body" style="font-size:14px; color:var(--muted); background:var(--surface2);">
          Profits are automatically credited to your main balance every 24 hours, starting from the day after your deposit is confirmed.
        </div>
      </div>
    </div>

    <div class="accordion-item mb-2" style="border-radius:10px !important; overflow:hidden; border:1px solid var(--border) !important;">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" style="font-size:14px; font-weight:600; background:var(--surface);" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
          Can I withdraw my principal anytime?
        </button>
      </h2>
      <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body" style="font-size:14px; color:var(--muted); background:var(--surface2);">
          Yes. Withdrawals are processed within 12–24 hours. A 1.5% fee applies. Minimum withdrawal is $50 USDT.
        </div>
      </div>
    </div>

    <div class="accordion-item" style="border-radius:10px !important; overflow:hidden; border:1px solid var(--border) !important;">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" style="font-size:14px; font-weight:600; background:var(--surface);" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
          How do I upgrade my plan?
        </button>
      </h2>
      <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body" style="font-size:14px; color:var(--muted); background:var(--surface2);">
          Simply deposit additional funds to reach the next tier's minimum. Your plan will automatically upgrade once the threshold is met.
        </div>
      </div>
    </div>

  </div>

</div>

<!-- FOOTER -->
<footer class="site-footer">
  <div class="container">© 2026 Btfeth Investments Ltd.</div>
</footer>


@auth
<!-- Plan Request Modal -->
<div class="modal fade" id="planRequestModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:var(--surface);border:1px solid var(--border);">
      <div class="modal-header" style="border-bottom-color:var(--border);">
        <h5 class="modal-title" style="color:var(--accent);">📋 <span id="prModalTitle">Select Plan</span></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('plan-request.store') }}">
        @csrf
        <input type="hidden" name="plan_name" id="prPlanName">
        <div class="modal-body" style="background:var(--surface);">

          <!-- Plan summary -->
          <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:14px;margin-bottom:16px;display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <div><div style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);">Rate</div><div id="prRate" style="font-size:15px;font-weight:700;color:var(--gold);"></div></div>
            <div><div style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);">Duration</div><div id="prDays" style="font-size:15px;font-weight:700;color:var(--text);"></div></div>
            <div><div style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);">Range</div><div id="prRange" style="font-size:13px;font-weight:600;color:var(--muted);"></div></div>
            <div><div style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);">Max Cycles</div><div id="prCycles" style="font-size:15px;font-weight:700;color:var(--text);"></div></div>
          </div>

          <!-- Wallet balance hint -->
          <div style="background:rgba(59,130,246,0.08);border:1px solid rgba(59,130,246,0.2);border-radius:8px;padding:10px 14px;margin-bottom:14px;display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:12px;color:rgba(147,197,253,0.8);">💰 Your wallet balance</span>
            <span style="font-size:14px;font-weight:700;color:#93c5fd;font-family:'JetBrains Mono',monospace;">
              @auth ${{ number_format(auth()->user()->wallet_balance, 2) }} USDT @endauth
            </span>
          </div>

          <!-- Amount input -->
          <div style="margin-bottom:14px;">
            <label style="font-size:12px;color:var(--muted);display:block;margin-bottom:6px;">Investment Amount (USDT)</label>
            <input type="number" id="prAmount" name="amount" 
              style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:10px 14px;color:var(--text);font-size:14px;outline:none;"
              placeholder="Enter amount..." oninput="calcProfit()" />
            <div id="prAmountError" style="font-size:11px;color:#f87171;margin-top:4px;display:none;"></div>
          </div>

          <!-- Live profit preview -->
          <div id="prPreview" style="background:rgba(34,197,94,0.07);border:1px solid rgba(34,197,94,0.2);border-radius:10px;padding:14px;margin-bottom:14px;display:none;">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;text-align:center;">
              <div>
                <div style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);">Daily</div>
                <div id="prDaily" style="font-size:15px;font-weight:700;color:#22c55e;font-family:'JetBrains Mono',monospace;margin-top:3px;"></div>
              </div>
              <div>
                <div style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);">Total Profit</div>
                <div id="prTotal" style="font-size:15px;font-weight:700;color:var(--gold);font-family:'JetBrains Mono',monospace;margin-top:3px;"></div>
              </div>
              <div>
                <div style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);">You Get Back</div>
                <div id="prReturns" style="font-size:15px;font-weight:700;color:var(--accent);font-family:'JetBrains Mono',monospace;margin-top:3px;"></div>
              </div>
            </div>
          </div>

          <!-- Note -->
          <div>
            <label style="font-size:12px;color:var(--muted);display:block;margin-bottom:6px;">Note to admin <span style="color:var(--muted);opacity:0.6;">(optional)</span></label>
            <input type="text" name="notes"
              style="width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:10px 14px;color:var(--text);font-size:13px;outline:none;"
              placeholder="Any message for the admin..." />
          </div>
        </div>
        <div class="modal-footer" style="border-top-color:var(--border);background:var(--surface);">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" id="prSubmitBtn" class="btn btn-primary" style="padding:8px 24px;" disabled>Submit Request</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
let prMin=0,prMax=0,prRate=0,prDays=0;
function openPlanRequest(name,min,max,rate,days,maxCycles){
  prMin=min;prMax=max;prRate=rate;prDays=days;
  document.getElementById('prModalTitle').textContent=name+' — Investment Request';
  document.getElementById('prPlanName').value=name;
  document.getElementById('prRate').textContent=rate+'%/day';
  document.getElementById('prDays').textContent=days+' days';
  document.getElementById('prRange').textContent='$'+min.toLocaleString()+' – $'+(max>=999999?'100,000+':max.toLocaleString());
  document.getElementById('prCycles').textContent=maxCycles+' max';
  document.getElementById('prAmount').value='';
  document.getElementById('prAmount').min=min;
  document.getElementById('prAmount').max=max;
  document.getElementById('prPreview').style.display='none';
  document.getElementById('prAmountError').style.display='none';
  document.getElementById('prSubmitBtn').disabled=true;
  new bootstrap.Modal(document.getElementById('planRequestModal')).show();
}
function calcProfit(){
  const amt=parseFloat(document.getElementById('prAmount').value)||0;
  const errEl=document.getElementById('prAmountError');
  const preview=document.getElementById('prPreview');
  const btn=document.getElementById('prSubmitBtn');
  if(amt<prMin||amt>prMax){
    errEl.textContent='Amount must be between $'+prMin.toLocaleString()+' and $'+(prMax>=999999?'100,000+':prMax.toLocaleString());
    errEl.style.display='block';
    preview.style.display='none';
    btn.disabled=true;
    return;
  }
  errEl.style.display='none';
  const daily=amt*(prRate/100);
  const total=daily*prDays;
  const returns=amt+total;
  document.getElementById('prDaily').textContent='+$'+daily.toFixed(2);
  document.getElementById('prTotal').textContent='+$'+total.toFixed(2);
  document.getElementById('prReturns').textContent='$'+returns.toFixed(2);
  preview.style.display='block';
  btn.disabled=false;
}
</script>
@endauth

<script>
function dismissAlert(el) {
  const alert = el.closest('[style]');
  if (alert) { alert.style.transition='opacity 0.3s'; alert.style.opacity='0'; setTimeout(()=>alert.remove(),300); }
}
setTimeout(() => {
  document.querySelectorAll('.alert-close').forEach(btn => btn.click());
}, 6000);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
