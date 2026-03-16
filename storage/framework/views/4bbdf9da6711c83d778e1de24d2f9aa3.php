<?php $__env->startSection('title', 'Plan Requests'); ?>
<?php $__env->startSection('page-title', 'Plan Requests'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
<div class="alert-success-bar mb-4"><span>✅ <?php echo e(session('success')); ?></span><button class="alert-close" onclick="dismissAlert(this)" title="Dismiss">&times;</button></div>
<?php endif; ?>
<?php if(session('error')): ?>
<div class="alert-warning mb-4"><span>⚠️ <?php echo e(session('error')); ?></span><button class="alert-close" onclick="dismissAlert(this)" title="Dismiss">&times;</button></div>
<?php endif; ?>

<div class="page-header">
  <h1>Plan Requests</h1>
  <p style="color:var(--muted);font-size:13px;">Customer-submitted investment plan requests. Approve to activate instantly.</p>
</div>


<?php
  $pending  = $requests->where('status','pending')->count();
  $approved = $requests->where('status','approved')->count();
  $rejected = $requests->where('status','rejected')->count();
?>
<div class="row g-3 mb-4">
  <div class="col-4">
    <div class="stat-card">
      <div class="stat-label">Pending</div>
      <div class="stat-value" style="color:var(--gold);"><?php echo e($requests->total() ? \App\Models\PlanRequest::where('status','pending')->count() : 0); ?></div>
    </div>
  </div>
  <div class="col-4">
    <div class="stat-card">
      <div class="stat-label">Approved</div>
      <div class="stat-value" style="color:var(--green);"><?php echo e(\App\Models\PlanRequest::where('status','approved')->count()); ?></div>
    </div>
  </div>
  <div class="col-4">
    <div class="stat-card">
      <div class="stat-label">Rejected</div>
      <div class="stat-value" style="color:var(--red);"><?php echo e(\App\Models\PlanRequest::where('status','rejected')->count()); ?></div>
    </div>
  </div>
</div>


<div class="card">
  <div class="card-header">All Requests</div>
  <div style="overflow-x:auto;">
    <table class="admin-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Customer</th>
          <th>Plan / Cycle</th>
          <th>Amount</th>
          <th>Daily Profit</th>
          <th>Total Profit</th>
          <th>Duration</th>
          <th>Note</th>
          <th>Submitted</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr style="<?php echo e($req->status === 'pending' ? 'background:rgba(245,158,11,0.04);' : ''); ?>">
          <td style="color:var(--muted);font-size:12px;">#<?php echo e($req->id); ?></td>
          <td>
            <div style="font-weight:600;font-size:13px;color:var(--text);"><?php echo e($req->user->name); ?></div>
            <div style="font-size:11px;color:var(--muted);"><?php echo e($req->user->email); ?></div>
          </td>
          <td>
            <span class="badge badge-blue" style="font-size:11px;"><?php echo e($req->plan_name); ?></span>
            <div style="font-size:11px;color:var(--muted);margin-top:3px;">Cycle <?php echo e($req->cycle_number); ?>/<?php echo e($req->max_cycles); ?></div>
          </td>
          <td style="font-family:'JetBrains Mono',monospace;font-weight:700;color:var(--text);">$<?php echo e(number_format($req->amount,2)); ?></td>
          <td style="font-family:'JetBrains Mono',monospace;color:#22c55e;font-weight:700;">+$<?php echo e(number_format($req->dailyProfit(),2)); ?></td>
          <td style="font-family:'JetBrains Mono',monospace;color:var(--gold);font-weight:700;">$<?php echo e(number_format($req->totalProfit(),2)); ?></td>
          <td style="font-size:12px;color:var(--muted);"><?php echo e($req->duration_days); ?>d</td>
          <td style="font-size:12px;color:var(--muted);max-width:140px;"><?php echo e($req->notes ?? '—'); ?></td>
          <td style="font-size:12px;color:var(--muted);">
            <span class="local-time" data-utc="<?php echo e($req->created_at->utc()->toISOString()); ?>"><?php echo e($req->created_at->format('d M Y · H:i')); ?></span>
          </td>
          <td>
            <?php if($req->status === 'pending'): ?>
              <span class="badge" style="background:rgba(245,158,11,0.15);color:var(--gold);border:1px solid rgba(245,158,11,0.3);">⏳ Pending</span>
            <?php elseif($req->status === 'approved'): ?>
              <span class="badge badge-green">✅ Approved</span>
            <?php else: ?>
              <span class="badge badge-red">✗ Rejected</span>
              <?php if($req->admin_note): ?>
                <div style="font-size:10px;color:var(--muted);margin-top:3px;"><?php echo e($req->admin_note); ?></div>
              <?php endif; ?>
            <?php endif; ?>
          </td>
          <td>
            <?php if($req->status === 'pending'): ?>
              <div style="display:flex;flex-direction:column;gap:6px;min-width:100px;">
                <button class="btn btn-xs btn-success w-100"
                  onclick="openApprove(<?php echo e($req->id); ?>, '<?php echo e(addslashes($req->user->name)); ?>', '<?php echo e($req->plan_name); ?>', <?php echo e($req->cycle_number); ?>, '<?php echo e(number_format($req->amount,2)); ?>', '<?php echo e(number_format($req->totalProfit(),2)); ?>')">
                  ✅ Approve
                </button>
                <button class="btn btn-xs w-100" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#f87171;"
                  onclick="openReject(<?php echo e($req->id); ?>, '<?php echo e(addslashes($req->user->name)); ?>', '<?php echo e($req->plan_name); ?>')">
                  ✗ Reject
                </button>
              </div>
            <?php else: ?>
              <span style="font-size:11px;color:var(--muted);">—</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="11" style="text-align:center;padding:40px;color:var(--muted);">No plan requests yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php if($requests->hasPages()): ?>
  <div style="padding:14px 20px;border-top:1px solid var(--border);"><?php echo e($requests->links()); ?></div>
  <?php endif; ?>
</div>

<?php $__env->startPush('modals'); ?>

<div class="modal fade" id="approveModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom-color:rgba(34,197,94,0.25);">
        <h5 class="modal-title" style="color:#22c55e;">✅ Approve Plan Request</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="approveForm" method="POST">
        <?php echo csrf_field(); ?>
        <div class="modal-body">
          <div id="approveInfo" style="background:rgba(34,197,94,0.06);border:1px solid rgba(34,197,94,0.2);border-radius:10px;padding:16px;margin-bottom:16px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;text-align:center;">
              <div>
                <div style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);">Customer</div>
                <div id="apCustomer" style="font-size:14px;font-weight:700;color:#1a1a1a;margin-top:3px;"></div>
              </div>
              <div>
                <div style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);">Plan / Cycle</div>
                <div id="apPlan" style="font-size:14px;font-weight:700;color:var(--accent);margin-top:3px;"></div>
              </div>
              <div>
                <div style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);">Deposit Amount</div>
                <div id="apAmount" style="font-size:18px;font-weight:800;color:#1a1a1a;font-family:'JetBrains Mono',monospace;margin-top:3px;"></div>
              </div>
              <div>
                <div style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);">Full Cycle Profit</div>
                <div id="apProfit" style="font-size:18px;font-weight:800;color:#22c55e;font-family:'JetBrains Mono',monospace;margin-top:3px;"></div>
              </div>
            </div>
          </div>
          <div style="font-size:13px;color:#666;text-align:center;">
            This will <strong style="color:#1a1a1a;">activate the cycle</strong> and credit the deposit to the customer's balance immediately.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success" style="padding:8px 24px;">✅ Yes, Approve & Activate</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="rejectModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom-color:rgba(239,68,68,0.25);">
        <h5 class="modal-title" style="color:#ef4444;">✗ Reject Request</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="rejectForm" method="POST">
        <?php echo csrf_field(); ?>
        <div class="modal-body">
          <div id="rejectInfo" style="font-size:13px;color:var(--muted);margin-bottom:16px;"></div>
          <label class="form-label">Reason (optional — shown to admin log)</label>
          <input type="text" name="admin_note" class="form-control" placeholder="e.g. Insufficient funds, duplicate request..."/>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm" style="background:#ef4444;border-color:#ef4444;color:#fff;padding:7px 20px;">Confirm Reject</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.querySelectorAll('.local-time').forEach(el => {
  const d = new Date(el.dataset.utc);
  const formatted = d.toLocaleDateString('en-GB', {day:'2-digit',month:'short',year:'numeric'})
    + ' · ' + d.toLocaleTimeString('en-GB', {hour:'2-digit',minute:'2-digit'});
  el.textContent = formatted;
});
</script>
<script>
function openApprove(id, name, plan, cycle, amount, profit) {
  document.getElementById('apCustomer').textContent = name;
  document.getElementById('apPlan').textContent     = plan + ' — Cycle ' + cycle;
  document.getElementById('apAmount').textContent   = '$' + amount;
  document.getElementById('apProfit').textContent   = '+$' + profit;
  document.getElementById('approveForm').action     = '/admin/plan-requests/' + id + '/approve';
  new bootstrap.Modal(document.getElementById('approveModal')).show();
}
function openReject(id, name, plan) {
  document.getElementById('rejectInfo').innerHTML =
    'Rejecting <strong style="color:var(--text);">' + plan + '</strong> request from <strong style="color:var(--text);">' + name + '</strong>.';
  document.getElementById('rejectForm').action = '/admin/plan-requests/' + id + '/reject';
  new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\efteth\resources\views/admin/plan-requests.blade.php ENDPATH**/ ?>