<?php $__env->startSection('title', 'Investments'); ?>
<?php $__env->startSection('page-title', 'Investments'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
  <h1>Investment Plans</h1>
  <p>Assign and manage investment plans for customers.</p>
</div>


<div class="card mb-4">
  <div class="card-header" style="background:rgba(34,197,94,0.08);border-bottom:2px solid rgba(34,197,94,0.3);">
    <div style="font-size:15px;font-weight:800;color:#22c55e;">➕ Assign New Investment Plan</div>
  </div>
  <div class="card-body">
    <form method="POST" action="<?php echo e(route('admin.investments.store')); ?>">
      <?php echo csrf_field(); ?>
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label">Customer</label>
          <select name="user_id" class="form-control" required>
            <option value="">-- Select Customer --</option>
            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?> (<?php echo e($c->email); ?>)</option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">Plan</label>
          <select name="plan_name" id="planSelect" class="form-control" required onchange="loadDefaults(this.value)">
            <option value="">-- Select Plan --</option>
            <option value="Plan 1">Plan 1 (1.5%)</option>
            <option value="Plan 2">Plan 2 (3.2%)</option>
            <option value="VIP 1">VIP 1 (5%)</option>
            <option value="VIP 2">VIP 2 (7%)</option>
            <option value="Custom">Custom</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">Deposit Amount ($)</label>
          <input type="number" name="amount" id="amountInput" class="form-control" step="0.01" min="1" required placeholder="e.g. 5000"/>
        </div>
        <div class="col-md-1">
          <label class="form-label">Profit %</label>
          <input type="number" name="profit_percent" id="profitInput" class="form-control" step="0.01" min="0.01" max="100" required placeholder="e.g. 5"/>
        </div>
        <div class="col-md-1">
          <label class="form-label">Days</label>
          <input type="number" name="duration_days" id="daysInput" class="form-control" min="1" required placeholder="28"/>
        </div>
        <div class="col-md-1">
          <label class="form-label">Cycles</label>
          <input type="number" name="total_cycles" id="cyclesInput" class="form-control" min="1" required placeholder="6"/>
        </div>
        <div class="col-md-2">
          <label class="form-label">Start Date</label>
          <input type="date" name="start_date" class="form-control" value="<?php echo e(date('Y-m-d')); ?>" required/>
        </div>
        <div class="col-12">
          <label class="form-label">Notes (optional)</label>
          <input type="text" name="notes" class="form-control" placeholder="e.g. Special VIP rate approved by admin"/>
        </div>
      </div>
      <div class="mt-3">
        <button type="submit" class="btn btn-primary">Assign Investment Plan</button>
      </div>
    </form>
  </div>
</div>


<div class="card">
  <div class="card-header">All Investment Plans</div>
  <div class="card-body p-0">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Customer</th>
          <th>Plan</th>
          <th>Amount</th>
          <th>Profit %</th>
          <th>Duration</th>
          <th>Cycles</th>
          <th>Progress</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $investments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
          <td>
            <div style="font-weight:700;font-size:14px;color:var(--text);"><?php echo e($inv->user->name); ?></div>
            <div style="font-size:11px;color:#9ca3af;"><?php echo e($inv->user->email); ?></div>
          </td>
          <td>
            <span style="font-size:12px;font-weight:800;padding:4px 10px;border-radius:6px;background:<?php echo e($inv->planBadgeColor()); ?>22;color:<?php echo e($inv->planBadgeColor()); ?>;border:1px solid <?php echo e($inv->planBadgeColor()); ?>44;">
              <?php echo e($inv->plan_name); ?>

            </span>
          </td>
          <td style="font-family:'JetBrains Mono',monospace;font-weight:700;color:var(--text);">$<?php echo e(number_format($inv->amount, 2)); ?></td>
          <td>
            <span style="font-family:'JetBrains Mono',monospace;font-weight:800;color:#f59e0b;"><?php echo e($inv->profit_percent); ?>%</span>
            <div style="font-size:10px;color:#9ca3af;">≈ $<?php echo e(number_format($inv->estimatedProfit(), 2)); ?>/cycle</div>
          </td>
          <td style="font-size:13px;color:var(--text);">
            <?php echo e($inv->duration_days); ?> days<br>
            <span style="font-size:11px;color:#9ca3af;"><?php echo e($inv->start_date->format('d M')); ?> → <?php echo e($inv->end_date->format('d M Y')); ?></span>
          </td>
          <td>
            <span style="font-size:13px;font-weight:700;color:var(--text);"><?php echo e($inv->completed_cycles); ?>/<?php echo e($inv->total_cycles); ?></span>
            <div style="font-size:11px;color:#9ca3af;"><?php echo e($inv->daysRemaining()); ?> days left</div>
          </td>
          <td style="min-width:100px;">
            <div style="background:var(--surface2);border-radius:4px;height:6px;overflow:hidden;">
              <div style="width:<?php echo e($inv->progressPercent()); ?>%;height:100%;background:linear-gradient(90deg,var(--accent),#22c55e);border-radius:4px;"></div>
            </div>
            <div style="font-size:10px;color:#9ca3af;margin-top:3px;"><?php echo e($inv->progressPercent()); ?>% complete</div>
          </td>
          <td>
            <?php if($inv->status === 'active'): ?>
              <span class="badge badge-green">● Active</span>
            <?php elseif($inv->status === 'completed'): ?>
              <span class="badge badge-blue">✓ Completed</span>
            <?php else: ?>
              <span class="badge badge-gray">✗ Cancelled</span>
            <?php endif; ?>
          </td>
          <td>
            <div style="display:flex;gap:6px;">
              <button class="btn btn-ghost btn-xs" onclick="openEdit(<?php echo e($inv->id); ?>, <?php echo e(json_encode($inv)); ?>)">✏️ Edit</button>
              <form method="POST" action="<?php echo e(route('admin.investments.destroy', $inv)); ?>" onsubmit="return confirm('Delete this investment?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-xs" style="background:rgba(239,68,68,0.15);color:#ef4444;border:1px solid rgba(239,68,68,0.3);">🗑</button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="9" style="text-align:center;padding:32px;color:#9ca3af;">No investment plans assigned yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>


<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">✏️ Edit Investment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" id="editForm">
        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label">Plan</label>
              <select name="plan_name" id="edit_plan" class="form-control" required>
                <option value="Plan 1">Plan 1</option>
                <option value="Plan 2">Plan 2</option>
                <option value="VIP 1">VIP 1</option>
                <option value="VIP 2">VIP 2</option>
                <option value="Custom">Custom</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Amount ($)</label>
              <input type="number" name="amount" id="edit_amount" class="form-control" step="0.01" required/>
            </div>
            <div class="col-md-2">
              <label class="form-label">Profit %</label>
              <input type="number" name="profit_percent" id="edit_profit" class="form-control" step="0.01" required/>
            </div>
            <div class="col-md-2">
              <label class="form-label">Duration (days)</label>
              <input type="number" name="duration_days" id="edit_days" class="form-control" required/>
            </div>
            <div class="col-md-2">
              <label class="form-label">Total Cycles</label>
              <input type="number" name="total_cycles" id="edit_cycles" class="form-control" required/>
            </div>
            <div class="col-md-2">
              <label class="form-label">Completed Cycles</label>
              <input type="number" name="completed_cycles" id="edit_completed" class="form-control" min="0"/>
            </div>
            <div class="col-md-3">
              <label class="form-label">Start Date</label>
              <input type="date" name="start_date" id="edit_start" class="form-control" required/>
            </div>
            <div class="col-md-3">
              <label class="form-label">Status</label>
              <select name="status" id="edit_status" class="form-control">
                <option value="active">Active</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Notes</label>
              <input type="text" name="notes" id="edit_notes" class="form-control"/>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
const planDefaults = {
  'Plan 1': { profit: 1.5,  duration: 7,  cycles: 2 },
  'Plan 2': { profit: 3.2,  duration: 15, cycles: 3 },
  'VIP 1':  { profit: 5.0,  duration: 28, cycles: 6 },
  'VIP 2':  { profit: 7.0,  duration: 28, cycles: 5 },
};

function loadDefaults(plan) {
  const d = planDefaults[plan];
  if (!d) return;
  document.getElementById('profitInput').value = d.profit;
  document.getElementById('daysInput').value   = d.duration;
  document.getElementById('cyclesInput').value = d.cycles;
}

function openEdit(id, inv) {
  document.getElementById('editForm').action = `/admin/investments/${id}`;
  document.getElementById('edit_plan').value      = inv.plan_name;
  document.getElementById('edit_amount').value    = inv.amount;
  document.getElementById('edit_profit').value    = inv.profit_percent;
  document.getElementById('edit_days').value      = inv.duration_days;
  document.getElementById('edit_cycles').value    = inv.total_cycles;
  document.getElementById('edit_completed').value = inv.completed_cycles;
  document.getElementById('edit_start').value     = inv.start_date;
  document.getElementById('edit_status').value    = inv.status;
  document.getElementById('edit_notes').value     = inv.notes || '';
  new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\efteth\resources\views/admin/investments.blade.php ENDPATH**/ ?>