<?php $__env->startSection('title', $user->name); ?>
<?php $__env->startSection('page-title', 'Customer Detail'); ?>

<?php $__env->startSection('content'); ?>

<div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
  <a href="<?php echo e(route('admin.customers')); ?>" class="btn btn-ghost btn-sm">← Back</a>
  <h1 style="font-size:20px;font-weight:800;margin:0;"><?php echo e($user->name); ?></h1>
  <span class="badge <?php echo e($user->is_active ? 'badge-green' : 'badge-gray'); ?>">
    <?php echo e($user->is_active ? 'Active' : 'Inactive'); ?>

  </span>
</div>

<div class="row g-4">
  
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">Profile</div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:8px;">
          <div class="avatar" style="width:54px;height:54px;font-size:20px;border-radius:12px;"><?php echo e($user->initials()); ?></div>
          <div>
            <div style="font-size:17px;font-weight:800;"><?php echo e($user->name); ?></div>
            <div style="font-size:13px;color:var(--muted);font-family:'JetBrains Mono',monospace;"><?php echo e($user->email); ?></div>
          </div>
        </div>
        <div class="bank-row"><span class="bank-key">Balance</span><span class="bank-val" style="color:var(--accent);">$<?php echo e(number_format($user->balance,2)); ?></span></div>
        <div class="bank-row"><span class="bank-key">Daily Profit</span><span class="bank-val" style="color:var(--green);">+$<?php echo e(number_format($user->daily_profit,2)); ?></span></div>
        <div class="bank-row"><span class="bank-key">Total Deposited</span><span class="bank-val" style="color:var(--green);">$<?php echo e(number_format($user->total_deposited,2)); ?></span></div>
        <div class="bank-row"><span class="bank-key">Total Withdrawn</span><span class="bank-val" style="color:var(--red);">$<?php echo e(number_format($user->total_withdrawn,2)); ?></span></div>
        <div class="bank-row"><span class="bank-key">Registered</span><span class="bank-val"><?php echo e($user->created_at->format('d M Y')); ?></span></div>
        <form method="POST" action="<?php echo e(route('admin.customers.toggle', $user)); ?>" class="mt-2">
          <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
          <button type="submit" class="btn w-100 <?php echo e($user->is_active ? 'btn-warning' : 'btn-success'); ?>">
            <?php echo e($user->is_active ? '⏸ Disable Account' : '▶ Enable Account'); ?>

          </button>
        </form>
        <a href="<?php echo e(route('admin.transactions')); ?>?customer=<?php echo e($user->id); ?>" class="btn btn-ghost w-100">💳 View Transactions</a>
        <a href="<?php echo e(route('admin.payment-info')); ?>" class="btn btn-ghost w-100">⚙️ Set Payment Info</a>
      </div>
    </div>
  </div>

  
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">Transaction History <span class="badge badge-blue ms-2"><?php echo e($transactions->total()); ?></span></div>
      <div style="overflow-x:auto;">
        <table class="admin-table">
          <thead>
            <tr><th>Type</th><th>Amount</th><th>Reference</th><th>Date</th><th>Status</th></tr>
          </thead>
          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $txn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td><span class="badge <?php echo e($txn->isDeposit() ? 'badge-green' : 'badge-red'); ?>"><?php echo e(ucfirst($txn->type)); ?></span></td>
              <td style="font-family:'JetBrains Mono',monospace;font-weight:700;color:<?php echo e($txn->isDeposit() ? 'var(--green)' : 'var(--red)'); ?>;"><?php echo e($txn->signedAmount()); ?></td>
              <td style="font-size:12px;color:var(--muted);"><?php echo e($txn->reference ?? '—'); ?></td>
              <td style="font-size:12px;font-family:'JetBrains Mono',monospace;color:var(--muted);"><?php echo e($txn->transaction_date->format('d M Y H:i')); ?></td>
              <td><span class="badge <?php echo e($txn->statusBadgeClass()); ?>"><?php echo e(ucfirst($txn->status)); ?></span></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="5" style="text-align:center;padding:28px;color:var(--muted);">No transactions yet.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <?php if($transactions->hasPages()): ?>
      <div style="padding:14px 20px;border-top:1px solid var(--border);"><?php echo e($transactions->links()); ?></div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
.bank-row{display:flex;justify-content:space-between;align-items:center;background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:9px 13px;}
.bank-key{font-size:12px;color:var(--muted);font-family:'JetBrains Mono',monospace;}
.bank-val{font-size:13px;font-weight:600;font-family:'JetBrains Mono',monospace;}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\onchain\resources\views/admin/customer-detail.blade.php ENDPATH**/ ?>