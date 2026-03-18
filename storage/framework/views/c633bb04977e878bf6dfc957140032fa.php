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
      <div class="stat-label">Total Invested</div>
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
        <a href="<?php echo e(route('admin.plans')); ?>" class="btn btn-ghost btn-sm">View All</a>
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
                    <div style="font-weight:700;font-size:14px;color:var(--text);"><?php echo e($txn->user->name); ?></div>
                    <div style="font-size:12px;color:#9ca3af;"><?php echo e($txn->user->email); ?></div>
                  </div>
                </div>
              </td>
              <td><span class="badge <?php echo e($txn->isDeposit() ? 'badge-green' : 'badge-red'); ?>"><?php echo e(ucfirst($txn->type)); ?></span></td>
              <td style="font-family:'JetBrains Mono',monospace;color:<?php echo e($txn->isDeposit() ? 'var(--green)' : 'var(--red)'); ?>;font-weight:600;"><?php echo e($txn->signedAmount()); ?></td>
              <td style="font-size:13px;color:#c4c9d4;font-family:'JetBrains Mono',monospace;font-weight:500;"><?php echo e($txn->transaction_date->format('d M Y')); ?></td>
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
      <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
        📋 Plan Requests
        <?php $pendingCount = \App\Models\PlanRequest::where('status','pending')->count(); ?>
        <?php if($pendingCount > 0): ?>
          <span style="background:#ef4444;color:#fff;border-radius:12px;padding:2px 10px;font-size:11px;font-weight:700;"><?php echo e($pendingCount); ?> pending</span>
        <?php else: ?>
          <span style="background:rgba(34,197,94,0.15);color:#22c55e;border-radius:12px;padding:2px 10px;font-size:11px;font-weight:700;">All clear</span>
        <?php endif; ?>
      </div>
      <div class="card-body" style="padding:0;">
        <?php $latestRequests = \App\Models\PlanRequest::with('user')->where('status','pending')->latest()->take(4)->get(); ?>
        <?php $__empty_1 = true; $__currentLoopData = $latestRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid var(--border);">
          <div style="display:flex;align-items:center;gap:10px;">
            <div class="avatar"><?php echo e($req->user->initials()); ?></div>
            <div>
              <div style="font-size:13px;font-weight:700;color:var(--text);"><?php echo e($req->user->name); ?></div>
              <div style="font-size:11px;color:var(--muted);"><?php echo e($req->plan_name); ?> · $<?php echo e(number_format($req->amount,0)); ?></div>
            </div>
          </div>
          <span style="background:rgba(245,158,11,0.15);color:var(--gold);border:1px solid rgba(245,158,11,0.3);border-radius:6px;padding:2px 8px;font-size:10px;font-weight:700;">⏳ Pending</span>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div style="padding:20px;text-align:center;color:var(--muted);font-size:13px;">No pending requests.</div>
        <?php endif; ?>
        <div style="padding:12px 16px;">
          <a href="<?php echo e(route('admin.plan-requests')); ?>" class="btn btn-primary w-100" style="font-size:13px;">View All Requests</a>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header">New Registrations</div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:12px;">
        <?php $__empty_1 = true; $__currentLoopData = $newCustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div style="display:flex;align-items:center;gap:10px;">
          <div class="avatar"><?php echo e($c->initials()); ?></div>
          <div style="flex:1;">
            <div style="font-size:14px;font-weight:700;color:var(--text);"><?php echo e($c->name); ?></div>
            <div style="font-size:12px;color:#9ca3af;"><?php echo e($c->created_at->format('d M Y')); ?></div>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\efteth\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>