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

<?php if(session('success')): ?>
<div class="alert-success-bar mb-4"><span>✅ <?php echo e(session('success')); ?></span><button class="alert-close" onclick="dismissAlert(this)">&times;</button></div>
<?php endif; ?>

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
        <div class="bank-row"><span class="bank-key">Total Invested</span><span class="bank-val" style="color:var(--green);">$<?php echo e(number_format($user->total_deposited,2)); ?></span></div>
        <div class="bank-row"><span class="bank-key">Total Withdrawn</span><span class="bank-val" style="color:var(--red);">$<?php echo e(number_format($user->total_withdrawn,2)); ?></span></div>
        <div class="bank-row"><span class="bank-key">Registered</span><span class="bank-val"><?php echo e($user->created_at->format('d M Y')); ?></span></div>

        <form method="POST" action="<?php echo e(route('admin.customers.toggle', $user)); ?>" class="mt-2">
          <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
          <button type="submit" class="btn w-100 <?php echo e($user->is_active ? 'btn-warning' : 'btn-success'); ?>">
            <?php echo e($user->is_active ? '⏸ Disable Account' : '▶ Enable Account'); ?>

          </button>
        </form>
        <a href="<?php echo e(route('admin.plans')); ?>?txn_customer=<?php echo e($user->id); ?>" class="btn btn-ghost w-100">💳 View Transactions</a>
        <a href="<?php echo e(route('admin.payment-info')); ?>" class="btn btn-ghost w-100">⚙️ Set Payment Info</a>
        <button type="button" class="btn w-100" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#f87171;"
          onclick="openDeleteModal('<?php echo e(addslashes($user->name)); ?>')">
          🗑 Delete Customer
        </button>
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


<div class="card mt-4">
  <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
    <div>📨 Send Message</div>
    <div style="font-size:12px;color:var(--muted);">Customer will see this in their panel</div>
  </div>
  <div class="card-body">
    <form method="POST" action="<?php echo e(route('admin.messages.store', $user)); ?>" enctype="multipart/form-data">
      <?php echo csrf_field(); ?>
      <div class="form-group mb-3">
        <label class="form-label">Message</label>
        <textarea name="body" class="form-control" rows="4"
          placeholder="Write your message to the customer..."
          style="resize:vertical;" required></textarea>
      </div>

      
      <div class="form-group mb-3">
        <label class="form-label">Attachments <span style="color:var(--muted);font-size:11px;">(optional — PDF, image, doc, zip — max 10MB each)</span></label>
        <div id="dropZone"
          style="border:2px dashed var(--border);border-radius:10px;padding:24px;text-align:center;cursor:pointer;transition:border-color 0.2s;background:var(--surface2);"
          onclick="document.getElementById('attachmentInput').click()"
          ondragover="handleDragOver(event)"
          ondragleave="handleDragLeave(event)"
          ondrop="handleDrop(event)">
          <div style="font-size:28px;margin-bottom:8px;">📎</div>
          <div style="font-size:13px;color:var(--muted);">Click to select files or drag & drop here</div>
          <div style="font-size:11px;color:var(--muted);margin-top:4px;">PDF, image, doc, xls, zip, txt</div>
        </div>
        <input type="file" id="attachmentInput" name="attachments[]" multiple
          accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.gif,.zip,.txt"
          style="display:none;" onchange="handleFiles(this.files)"/>

        
        <div id="filePreviewList" style="display:none;margin-top:12px;display:flex;flex-direction:column;gap:8px;"></div>
      </div>

      <button type="submit" class="btn btn-primary" style="padding:9px 24px;">📨 Send Message</button>
    </form>
  </div>
</div>


<?php $messages = \App\Models\Message::where('user_id', $user->id)->latest()->get(); ?>
<?php if($messages->count() > 0): ?>
<div class="card mt-3">
  <div class="card-header">
    Message History
    <span class="badge badge-blue ms-2"><?php echo e($messages->count()); ?></span>
  </div>
  <div style="display:flex;flex-direction:column;gap:0;">
    <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div style="padding:14px 20px;border-bottom:1px solid var(--border);display:flex;gap:14px;align-items:flex-start;<?php echo e(!$msg->seen ? 'background:rgba(59,130,246,0.04);' : ''); ?>">
      <div style="flex:1;">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
          <span style="font-size:11px;color:var(--muted);"><?php echo e($msg->created_at->format('d M Y · H:i')); ?></span>
          <?php if(!$msg->seen): ?>
            <span style="background:rgba(59,130,246,0.15);color:var(--accent);border-radius:4px;padding:1px 7px;font-size:10px;font-weight:700;">Unread</span>
          <?php else: ?>
            <span style="font-size:10px;color:var(--muted);">✓ Seen</span>
          <?php endif; ?>
        </div>
        <div style="font-size:13px;color:var(--text);line-height:1.6;white-space:pre-wrap;"><?php echo e($msg->body); ?></div>
        <?php if($msg->attachment): ?>
        <div style="display:flex;flex-wrap:wrap;gap:6px;margin-top:8px;">
          <?php $__currentLoopData = $msg->attachmentFiles(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $path => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <a href="<?php echo e(asset('storage/' . $path)); ?>" target="_blank"
            style="display:inline-flex;align-items:center;gap:6px;font-size:12px;color:var(--accent);background:rgba(59,130,246,0.08);border:1px solid rgba(59,130,246,0.2);border-radius:6px;padding:4px 10px;text-decoration:none;">
            📎 <?php echo e($name); ?>

          </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
      </div>
      <form method="POST" action="<?php echo e(route('admin.messages.destroy', $msg)); ?>">
        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
        <button type="submit" class="btn btn-xs"
          style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);color:#f87171;"
          onclick="return confirm('Delete this message?')">🗑</button>
      </form>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
</div>
<?php endif; ?>

<?php $__env->startPush('modals'); ?>

<div class="modal fade" id="deleteCustomerModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom-color:rgba(239,68,68,0.25);">
        <h5 class="modal-title" style="color:#ef4444;">🗑 Delete Customer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div style="background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.2);border-radius:10px;padding:16px;margin-bottom:16px;text-align:center;">
          <div style="font-size:36px;margin-bottom:8px;">⚠️</div>
          <div style="font-size:15px;font-weight:700;color:#1a1a1a;" id="deleteModalName"></div>
        </div>
        <div style="font-size:13px;color:#555;text-align:center;line-height:1.7;">
          This will permanently delete this customer account.<br>
          <strong style="color:#1a1a1a;">Their transaction history will be preserved</strong> in the database.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteCustomerForm" method="POST" action="<?php echo e(route('admin.customers.destroy', $user)); ?>">
          <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
          <button type="submit" class="btn btn-sm" style="background:#ef4444;border-color:#ef4444;color:#fff;padding:7px 20px;">
            Yes, Delete Customer
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.bank-row{display:flex;justify-content:space-between;align-items:center;background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:9px 13px;}
.bank-key{font-size:12px;color:var(--muted);font-family:'JetBrains Mono',monospace;}
.bank-val{font-size:13px;font-weight:600;font-family:'JetBrains Mono',monospace;}
.file-preview-item{display:flex;align-items:center;gap:10px;background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:10px 12px;}
.file-preview-thumb{width:40px;height:40px;border-radius:6px;object-fit:cover;border:1px solid var(--border);}
.file-preview-icon{width:40px;height:40px;border-radius:6px;background:rgba(59,130,246,0.1);display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;}
#dropZone.dragover{border-color:var(--accent);background:rgba(59,130,246,0.05);}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function openDeleteModal(name) {
  document.getElementById('deleteModalName').textContent = name;
  new bootstrap.Modal(document.getElementById('deleteCustomerModal')).show();
}

// ── File upload with preview ──
let selectedFiles = [];

function handleDragOver(e) {
  e.preventDefault();
  document.getElementById('dropZone').classList.add('dragover');
}
function handleDragLeave(e) {
  document.getElementById('dropZone').classList.remove('dragover');
}
function handleDrop(e) {
  e.preventDefault();
  document.getElementById('dropZone').classList.remove('dragover');
  handleFiles(e.dataTransfer.files);
}

function handleFiles(files) {
  Array.from(files).forEach(file => {
    if (!selectedFiles.find(f => f.name === file.name && f.size === file.size)) {
      selectedFiles.push(file);
    }
  });
  updateFileInput();
  renderPreviews();
}

function updateFileInput() {
  const dt = new DataTransfer();
  selectedFiles.forEach(f => dt.items.add(f));
  document.getElementById('attachmentInput').files = dt.files;
}

function removeFile(index) {
  selectedFiles.splice(index, 1);
  updateFileInput();
  renderPreviews();
}

function renderPreviews() {
  const list = document.getElementById('filePreviewList');
  list.innerHTML = '';
  if (selectedFiles.length === 0) { list.style.display = 'none'; return; }
  list.style.display = 'flex';

  selectedFiles.forEach((file, i) => {
    const isImage = file.type.startsWith('image/');
    const item = document.createElement('div');
    item.className = 'file-preview-item';

    const icon = document.createElement('div');
    if (isImage) {
      const img = document.createElement('img');
      img.className = 'file-preview-thumb';
      img.src = URL.createObjectURL(file);
      icon.appendChild(img);
    } else {
      icon.className = 'file-preview-icon';
      icon.textContent = file.name.endsWith('.pdf') ? '📄' :
                         file.name.match(/\.(doc|docx)$/) ? '📝' :
                         file.name.match(/\.(xls|xlsx)$/) ? '📊' :
                         file.name.endsWith('.zip') ? '🗜️' : '📎';
    }

    const info = document.createElement('div');
    info.style.flex = '1';
    info.innerHTML = `<div style="font-size:13px;font-weight:600;color:var(--text);">${file.name}</div>
      <div style="font-size:11px;color:var(--muted);">${(file.size/1024).toFixed(1)} KB</div>`;

    const btn = document.createElement('button');
    btn.type = 'button';
    btn.innerHTML = '&times;';
    btn.style.cssText = 'background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#f87171;border-radius:6px;width:26px;height:26px;cursor:pointer;font-size:15px;line-height:1;flex-shrink:0;';
    btn.onclick = () => removeFile(i);

    item.appendChild(icon);
    item.appendChild(info);
    item.appendChild(btn);
    list.appendChild(item);
  });
}

function dismissAlert(el) {
  const alert = el.closest('[class*="alert"]');
  if (alert) { alert.style.transition='opacity 0.3s'; alert.style.opacity='0'; setTimeout(()=>alert.remove(),300); }
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\efteth\resources\views/admin/customer-detail.blade.php ENDPATH**/ ?>