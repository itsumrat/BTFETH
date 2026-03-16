<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
  <h1>Overview</h1>
  <p>Welcome back. Here's what's happening today.</p>
</div>


<div class="row g-3 mb-4">
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(59,130,246,0.12);">👥</div>
      <div class="stat-label">Total Customers</div>
      <div class="stat-value"><?php echo e($totalCustomers); ?></div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(34,197,94,0.12);">⬆️</div>
      <div class="stat-label">Total Deposits</div>
      <div class="stat-value" style="color:var(--green);">$<?php echo e(number_format($totalDeposited, 0)); ?></div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(239,68,68,0.12);">⬇️</div>
      <div class="stat-label">Total Withdrawn</div>
      <div class="stat-value" style="color:var(--red);">$<?php echo e(number_format($totalWithdrawn, 0)); ?></div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(245,158,11,0.12);">📋</div>
      <div class="stat-label">Pending</div>
      <div class="stat-value" style="color:var(--gold);"><?php echo e($pendingCount); ?></div>
    </div>
  </div>
</div>

<div class="row g-3">
  
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        Recent Transactions
        <a href="<?php echo e(route('admin.transactions')); ?>" class="btn btn-ghost btn-sm">View All</a>
      </div>
      <div style="overflow-x:auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Customer</th><th>Type</th><th>Amount</th><th>Date</th><th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $recentTxns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $txn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td>
                <div style="display:flex;align-items:center;gap:10px;">
                  <div class="avatar"><?php echo e($txn->user->initials()); ?></div>
                  <div>
                    <div style="font-weight:600;font-size:14px;"><?php echo e($txn->user->name); ?></div>
                    <div style="font-size:11px;color:var(--muted);"><?php echo e($txn->user->email); ?></div>
                  </div>
                </div>
              </td>
              <td><span class="badge <?php echo e($txn->isDeposit() ? 'badge-green' : 'badge-red'); ?>"><?php echo e(ucfirst($txn->type)); ?></span></td>
              <td style="font-family:'JetBrains Mono',monospace;color:<?php echo e($txn->isDeposit() ? 'var(--green)' : 'var(--red)'); ?>;font-weight:600;"><?php echo e($txn->signedAmount()); ?></td>
              <td style="font-size:12px;color:var(--muted);font-family:'JetBrains Mono',monospace;"><?php echo e($txn->transaction_date->format('d M Y')); ?></td>
              <td><span class="badge <?php echo e($txn->statusBadgeClass()); ?>"><?php echo e(ucfirst($txn->status)); ?></span></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--muted);">No transactions yet.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  
  <div class="col-lg-4">
    <div class="card mb-3">
      <div class="card-header">Quick Actions</div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
        <a href="<?php echo e(route('admin.transactions')); ?>" class="btn btn-success" style="justify-content:flex-start;gap:10px;">⬆️ Add Transaction</a>
        <a href="<?php echo e(route('admin.customers')); ?>" class="btn btn-primary" style="justify-content:flex-start;gap:10px;">👥 View Customers</a>
        <a href="<?php echo e(route('admin.payment-info')); ?>" class="btn btn-ghost" style="justify-content:flex-start;gap:10px;">⚙️ Payment Info</a>
      </div>
    </div>
    <div class="card">
      <div class="card-header">New Registrations</div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:12px;">
        <?php $__empty_1 = true; $__currentLoopData = $newCustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div style="display:flex;align-items:center;gap:10px;">
          <div class="avatar"><?php echo e($c->initials()); ?></div>
          <div style="flex:1;">
            <div style="font-size:13px;font-weight:600;"><?php echo e($c->name); ?></div>
            <div style="font-size:11px;color:var(--muted);"><?php echo e($c->created_at->format('d M Y')); ?></div>
          </div>
          <span class="badge badge-blue">New</span>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <p style="color:var(--muted);font-size:13px;">No customers yet.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\onchain\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>