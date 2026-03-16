<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>


<div class="cust-topbar">
  <div class="cust-topbar-left">
    <a href="<?php echo e(route('home')); ?>" class="cust-logo" style="text-decoration:none;">BTF<span>ETH</span></a>
    <div class="cust-topbar-divider"></div>
    <div class="cust-location-time">
      <div id="custTime" class="cust-time">--:-- --</div>
      <div id="custDate" class="cust-date">--- --, ----</div>
      <div id="custLocation" class="cust-loc">📍 Locating...</div>
    </div>
  </div>

  <div class="cust-topbar-right">
    
    <div class="dropdown">
      <button class="cust-user-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <?php if($user->avatar): ?>
          <img src="<?php echo e(asset('avatars/' . $user->avatar)); ?>" class="cust-avatar" style="object-fit:cover;" alt=""/>
        <?php else: ?>
          <div class="cust-avatar"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></div>
        <?php endif; ?>
        <div class="cust-user-info">
          <div class="cust-user-name"><?php echo e($user->name); ?></div>
        </div>
      </button>
      <ul class="dropdown-menu dropdown-menu-end cust-dropdown">
        <li>
          <a class="dropdown-item cust-drop-item" href="<?php echo e(route('dashboard')); ?>">
            🏠 &nbsp;Dashboard
          </a>
        </li>
        <li>
          <a class="dropdown-item cust-drop-item" href="<?php echo e(route('profile.edit')); ?>">
            👤 &nbsp;Edit Profile
          </a>
        </li>
        <li>
          <a class="dropdown-item cust-drop-item" href="<?php echo e(route('password.change')); ?>">
            🔑 &nbsp;Change Password
          </a>
        </li>
        <li><hr class="cust-drop-divider"></li>
        <li>
          <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="dropdown-item cust-drop-item cust-drop-danger">
              🚪 &nbsp;Sign Out
            </button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</div>


<?php if(session('success')): ?>
  <div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?php echo e(session('success')); ?>

      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  </div>
<?php endif; ?>

<div class="container py-4">

  
  <div class="row g-3 mb-4">
    <div class="col-12">
      <div class="balance-card">
        <div class="balance-card-top">
          <div>
            <div class="balance-label">MAIN BALANCE</div>
            <div class="balance-amount">$<?php echo e(number_format($user->balance, 2)); ?> <span class="balance-currency">USDT</span></div>
          </div>
          <div class="balance-badge">● Live</div>
        </div>
        <div class="balance-actions">
          <button class="balance-btn deposit-btn" data-bs-toggle="modal" data-bs-target="#depositModal">
            <span class="balance-btn-icon">⬆</span>
            <span class="balance-btn-text">
              <span class="balance-btn-title">Deposit</span>
              <span class="balance-btn-sub">Add funds</span>
            </span>
          </button>
          <button class="balance-btn withdraw-btn" data-bs-toggle="modal" data-bs-target="#withdrawModal">
            <span class="balance-btn-icon">⬇</span>
            <span class="balance-btn-text">
              <span class="balance-btn-title">Withdraw</span>
              <span class="balance-btn-sub">Cash out</span>
            </span>
          </button>
        </div>
      </div>
    </div>
    <div class="col-6">
      <div class="stat-card">
        <div class="stat-label">Daily Profit</div>
        <div class="stat-value text-green">+$<?php echo e(number_format($user->daily_profit, 2)); ?></div>
        <div class="stat-sub">Today's earnings</div>
      </div>
    </div>
    <div class="col-6">
      <div class="stat-card">
        <div class="stat-label">Total Deposited</div>
        <div class="stat-value">$<?php echo e(number_format($user->total_deposited, 2)); ?></div>
        <div class="stat-sub">All time</div>
      </div>
    </div>
  </div>

  
  <div class="row g-2 mb-4">
    <div class="col-6">
      <a class="menu-card" href="<?php echo e(route('plans')); ?>">
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

  
  <div id="history">
    <div class="section-title">Transaction History</div>
    <div class="d-flex flex-column gap-2">

      <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $txn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="history-item">
          <div class="history-icon" style="background:<?php echo e($txn->isDeposit() ? 'rgba(34,197,94,0.12)' : 'rgba(239,68,68,0.12)'); ?>">
            <?php echo e($txn->isDeposit() ? '⬆️' : '⬇️'); ?>

          </div>
          <div class="flex-grow-1">
            <div class="history-type"><?php echo e(ucfirst($txn->type)); ?>

              <?php if($txn->reference): ?>
                <span style="font-size:11px;color:var(--muted);font-weight:400;"> · <?php echo e($txn->reference); ?></span>
              <?php endif; ?>
            </div>
            <div class="history-date"><?php echo e($txn->transaction_date->format('d M Y · H:i:s')); ?></div>
          </div>
          <div>
            <div class="history-amount <?php echo e($txn->isDeposit() ? 'text-green' : 'text-red'); ?>">
              <?php echo e($txn->signedAmount()); ?>

            </div>
            <div class="text-end mt-1">
              <span class="badge <?php echo e($txn->statusBadgeClass()); ?>"><?php echo e(ucfirst($txn->status)); ?></span>
            </div>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-4" style="color:var(--muted);">
          <div style="font-size:32px;margin-bottom:8px;">📋</div>
          No transactions yet.
        </div>
      <?php endif; ?>

    </div>
  </div>

</div>


<footer class="site-footer">
  <div class="container">© <?php echo e(date('Y')); ?> Btfeth Investments Ltd.</div>
</footer>




<div class="modal fade" id="depositModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">⬆️ &nbsp;Deposit USDT</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted mb-4" style="font-size:14px;">Send USDT via Binance Pay, Trust Wallet, or bank transfer, then contact support to confirm.</p>
        <?php if($depositInfo): ?>

          
          <?php if($depositInfo->binance_link || $depositInfo->wallet_address): ?>
          <div class="payment-section-header">
            <span style="font-size:16px;">🟡</span> Binance Pay
          </div>
          <?php if($depositInfo->binance_link): ?>
          <div class="mb-3">
            <div class="form-label">Binance Pay Link</div>
            <div class="copy-box">
              <div class="copy-text"><?php echo e($depositInfo->binance_link); ?></div>
              <button class="copy-btn" onclick="copyText(this,'<?php echo e($depositInfo->binance_link); ?>')">Copy</button>
            </div>
          </div>
          <?php endif; ?>
          <?php if($depositInfo->wallet_address): ?>
          <div class="mb-3">
            <div class="form-label">Binance Wallet Address (BEP-20)</div>
            <div class="copy-box">
              <div class="copy-text"><?php echo e($depositInfo->wallet_address); ?></div>
              <button class="copy-btn" onclick="copyText(this,'<?php echo e($depositInfo->wallet_address); ?>')">Copy</button>
            </div>
          </div>
          <?php endif; ?>
          <?php endif; ?>

          
          <?php if($depositInfo->trust_wallet_address): ?>
          <div class="payment-section-header mt-3">
            <span style="font-size:16px;">🔵</span> Trust Wallet
          </div>
          <div class="mb-3">
            <div class="form-label">Trust Wallet Address
              <?php if($depositInfo->trust_network): ?>
                <span class="network-badge"><?php echo e($depositInfo->trust_network); ?></span>
              <?php endif; ?>
            </div>
            <div class="copy-box">
              <div class="copy-text"><?php echo e($depositInfo->trust_wallet_address); ?></div>
              <button class="copy-btn" onclick="copyText(this,'<?php echo e($depositInfo->trust_wallet_address); ?>')">Copy</button>
            </div>
          </div>
          <?php endif; ?>



          
          <?php if($depositInfo->account_name || $depositInfo->account_number): ?>
          <div class="payment-section-header mt-3">
            🏦 Bank Transfer
          </div>
          <div class="d-flex flex-column gap-2">
            <?php $__currentLoopData = ['account_name'=>'Account Name','account_number'=>'Account Number','bank_name'=>'Bank Name','swift'=>'SWIFT / Routing','reference'=>'Reference']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php if($depositInfo->$field): ?>
              <div class="bank-row">
                <span class="bank-key"><?php echo e($label); ?></span>
                <span class="bank-val"><?php echo e($depositInfo->$field); ?></span>
              </div>
              <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
          <?php endif; ?>

        <?php else: ?>
          <div class="alert-note">⚠️ Deposit information is not set yet. Please contact support.</div>
        <?php endif; ?>
        <div class="alert-note mt-4">⚠️ After transferring, contact support with your transaction ID to confirm your deposit within 30 minutes.</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="withdrawModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">⬇️ &nbsp;Withdraw USDT</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted mb-4" style="font-size:14px;">Withdraw to your Binance, Trust Wallet, or bank account.</p>
        <?php if($withdrawalInfo): ?>

          
          <?php if($withdrawalInfo->withdraw_link || $withdrawalInfo->withdraw_id): ?>
          <div class="payment-section-header">
            <span style="font-size:16px;">🟡</span> Binance Pay
          </div>
          <?php if($withdrawalInfo->withdraw_link): ?>
          <div class="mb-3">
            <div class="form-label">Withdrawal Request Link</div>
            <div class="copy-box">
              <div class="copy-text"><?php echo e($withdrawalInfo->withdraw_link); ?></div>
              <button class="copy-btn" onclick="copyText(this,'<?php echo e($withdrawalInfo->withdraw_link); ?>')">Copy</button>
            </div>
          </div>
          <?php endif; ?>
          <?php if($withdrawalInfo->withdraw_id): ?>
          <div class="mb-3">
            <div class="form-label">Withdrawal ID</div>
            <div class="copy-box">
              <div class="copy-text"><?php echo e($withdrawalInfo->withdraw_id); ?></div>
              <button class="copy-btn" onclick="copyText(this,'<?php echo e($withdrawalInfo->withdraw_id); ?>')">Copy</button>
            </div>
          </div>
          <?php endif; ?>
          <?php endif; ?>

          
          <?php if($withdrawalInfo->trust_withdraw_address): ?>
          <div class="payment-section-header mt-3">
            <span style="font-size:16px;">🔵</span> Trust Wallet
          </div>
          <div class="mb-3">
            <div class="form-label">Send to Trust Wallet Address
              <?php if($withdrawalInfo->trust_network): ?>
                <span class="network-badge"><?php echo e($withdrawalInfo->trust_network); ?></span>
              <?php endif; ?>
            </div>
            <div class="copy-box">
              <div class="copy-text"><?php echo e($withdrawalInfo->trust_withdraw_address); ?></div>
              <button class="copy-btn" onclick="copyText(this,'<?php echo e($withdrawalInfo->trust_withdraw_address); ?>')">Copy</button>
            </div>
          </div>
          <?php endif; ?>

          
          <?php if($withdrawalInfo->min_withdrawal || $withdrawalInfo->processing_time || $withdrawalInfo->fee): ?>
          <div class="payment-section-header mt-3">ℹ️ Withdrawal Info</div>
          <div class="d-flex flex-column gap-2">
            <?php $__currentLoopData = ['min_withdrawal'=>'Min. Withdrawal','processing_time'=>'Processing Time','fee'=>'Fee']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php if($withdrawalInfo->$field): ?>
              <div class="bank-row">
                <span class="bank-key"><?php echo e($label); ?></span>
                <span class="bank-val"><?php echo e($withdrawalInfo->$field); ?></span>
              </div>
              <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
          <?php endif; ?>

          <?php if($withdrawalInfo->note): ?>
          <div class="alert-note mt-4">⚠️ <?php echo e($withdrawalInfo->note); ?></div>
          <?php endif; ?>
        <?php else: ?>
          <div class="alert-note">⚠️ Withdrawal information is not set yet. Please contact support.</div>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


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

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\onchain\resources\views/dashboard.blade.php ENDPATH**/ ?>