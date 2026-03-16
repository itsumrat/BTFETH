<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Admin') — Btfeth Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="{{ asset('css/admin-style.css') }}" rel="stylesheet" />
  @stack('styles')
</head>
<body>
<div class="admin-layout">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-logo">BTF<span>ETH</span><small>Admin Panel</small></div>
    <nav class="sidebar-nav">
      <div class="nav-section-label">Main</div>
      <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
        <span class="s-icon">🏠</span> Dashboard
      </a>
      <a class="sidebar-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }}" href="{{ route('admin.customers') }}">
        <span class="s-icon">👥</span> Customers
      </a>
      <div class="nav-section-label">Investments & Transactions</div>
      <a class="sidebar-link {{ request()->routeIs('admin.plans*') ? 'active' : '' }}" href="{{ route('admin.plans') }}">
        <span class="s-icon">📈</span> Investments
      </a>
      <a class="sidebar-link {{ request()->routeIs('admin.plan-requests*') ? 'active' : '' }}" href="{{ route('admin.plan-requests') }}" style="position:relative;">
        <span class="s-icon">📋</span> Plan Requests
        @php $badge = \App\Models\PlanRequest::unseenCount(); @endphp
        @if($badge > 0)
          <span style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:#ef4444;color:#fff;border-radius:50%;width:18px;height:18px;font-size:10px;font-weight:700;display:flex;align-items:center;justify-content:center;line-height:1;">{{ $badge }}</span>
        @endif
      </a>

      <div class="nav-section-label">Settings</div>
      <a class="sidebar-link {{ request()->routeIs('admin.payment-info*') ? 'active' : '' }}" href="{{ route('admin.payment-info') }}">
        <span class="s-icon">⚙️</span> Payment Info
      </a>
      <div class="nav-section-label">Account</div>
      <a class="sidebar-link {{ request()->routeIs('admin.password*') ? 'active' : '' }}" href="{{ route('admin.password.change') }}"><span class="s-icon">🔑</span> Change Password</a>
      <a class="sidebar-link" href="{{ route('dashboard') }}"><span class="s-icon">🌐</span> Customer Site</a>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="sidebar-link w-100 text-start" style="background:none;border:none;">
          <span class="s-icon">🚪</span> Sign Out
        </button>
      </form>
    </nav>
    <div class="sidebar-footer">
      <div style="font-size:12px;color:var(--muted);display:flex;align-items:center;gap:8px;">
        <div style="width:8px;height:8px;border-radius:50%;background:var(--green);flex-shrink:0;"></div>
        {{ auth()->user()->name }}
      </div>
    </div>
  </aside>

  <!-- MAIN -->
  <div class="main-content">
    <div class="topbar">
      <div class="topbar-title">@yield('page-title', 'Admin')</div>
      <div class="topbar-right">
        <div class="topbar-time-wrap">
          <div class="topbar-time" id="adminTime">--:-- --</div>
          <div class="topbar-date" id="adminDate">--- --, ----</div>
          <div class="topbar-loc" id="adminLoc">📍 Locating...</div>
        </div>
        <span class="admin-badge">ADMIN</span>
      </div>
    </div>

    <div class="page-body fade-up">

      @yield('content')
    </div>
  </div>
</div>

<div class="toast" id="toast"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  let adminTZ = Intl.DateTimeFormat().resolvedOptions().timeZone;

  function tick() {
    const now = new Date();
    const timeStr = now.toLocaleTimeString('en-US', {
      timeZone: adminTZ, hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true
    });
    const dateStr = now.toLocaleDateString('en-US', {
      timeZone: adminTZ, weekday: 'short', day: '2-digit', month: 'short', year: 'numeric'
    });
    document.getElementById('adminTime').textContent = timeStr;
    document.getElementById('adminDate').textContent = dateStr;
  }
  setInterval(tick, 1000); tick();

  // Geolocation
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(pos) {
      fetch(`https://nominatim.openstreetmap.org/reverse?lat=${pos.coords.latitude}&lon=${pos.coords.longitude}&format=json`)
        .then(r => r.json())
        .then(d => {
          const city = d.address.city || d.address.town || d.address.village || d.address.county || '';
          const cc = d.address.country_code ? d.address.country_code.toUpperCase() : '';
          document.getElementById('adminLoc').textContent = '📍 ' + (city ? city + ', ' + cc : adminTZ);
        }).catch(() => { document.getElementById('adminLoc').textContent = '📍 ' + adminTZ; });
    }, function() {
      const parts = adminTZ.split('/');
      document.getElementById('adminLoc').textContent = '📍 ' + parts[parts.length-1].replace(/_/g,' ');
    });
  } else {
    document.getElementById('adminLoc').textContent = '📍 ' + adminTZ;
  }

  function showToast(msg) {
    const t=document.getElementById('toast');
    t.textContent=msg; t.classList.add('show');
    setTimeout(()=>t.classList.remove('show'),2600);
  }
</script>
@stack('modals')
@stack('scripts')
<script>
function dismissAlert(el) {
  const alert = el.closest('[class*="alert"]');
  if (alert) { alert.style.transition='opacity 0.3s'; alert.style.opacity='0'; setTimeout(()=>alert.remove(),300); }
}
setTimeout(() => {
  document.querySelectorAll('[class*="alert-success"],[class*="alert-error"],[class*="alert-warning"]').forEach(el => {
    el.style.transition='opacity 0.5s'; el.style.opacity='0'; setTimeout(()=>el.remove(),500);
  });
}, 6000);
</script>
</body>
</html>
