<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $__env->yieldContent('title', 'Admin'); ?> — Btfeth Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="<?php echo e(asset('css/admin-style.css')); ?>" rel="stylesheet" />
  <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
<div class="admin-layout">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-logo">BTF<span>ETH</span><small>Admin Panel</small></div>
    <nav class="sidebar-nav">
      <div class="nav-section-label">Main</div>
      <a class="sidebar-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('admin.dashboard')); ?>">
        <span class="s-icon">🏠</span> Dashboard
      </a>
      <a class="sidebar-link <?php echo e(request()->routeIs('admin.customers*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.customers')); ?>">
        <span class="s-icon">👥</span> Customers
      </a>
      <div class="nav-section-label">Transactions</div>
      <a class="sidebar-link <?php echo e(request()->routeIs('admin.transactions*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.transactions')); ?>">
        <span class="s-icon">💳</span> Transactions
      </a>
      <div class="nav-section-label">Settings</div>
      <a class="sidebar-link <?php echo e(request()->routeIs('admin.payment-info*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.payment-info')); ?>">
        <span class="s-icon">⚙️</span> Payment Info
      </a>
      <div class="nav-section-label">Account</div>
      <a class="sidebar-link <?php echo e(request()->routeIs('admin.password*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.password.change')); ?>"><span class="s-icon">🔑</span> Change Password</a>
      <a class="sidebar-link" href="<?php echo e(route('dashboard')); ?>"><span class="s-icon">🌐</span> Customer Site</a>
      <form method="POST" action="<?php echo e(route('logout')); ?>">
        <?php echo csrf_field(); ?>
        <button type="submit" class="sidebar-link w-100 text-start" style="background:none;border:none;">
          <span class="s-icon">🚪</span> Sign Out
        </button>
      </form>
    </nav>
    <div class="sidebar-footer">
      <div style="font-size:12px;color:var(--muted);display:flex;align-items:center;gap:8px;">
        <div style="width:8px;height:8px;border-radius:50%;background:var(--green);flex-shrink:0;"></div>
        <?php echo e(auth()->user()->name); ?>

      </div>
    </div>
  </aside>

  <!-- MAIN -->
  <div class="main-content">
    <div class="topbar">
      <div class="topbar-title"><?php echo $__env->yieldContent('page-title', 'Admin'); ?></div>
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

      
      <?php if(session('success')): ?>
        <div class="alert-success mb-4" style="display:flex;align-items:center;gap:10px;">
          ✓ <?php echo e(session('success')); ?>

        </div>
      <?php endif; ?>
      <?php if(session('error')): ?>
        <div class="alert-warning mb-4">⚠ <?php echo e(session('error')); ?></div>
      <?php endif; ?>

      <?php echo $__env->yieldContent('content'); ?>
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
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\onchain\resources\views/layouts/admin.blade.php ENDPATH**/ ?>