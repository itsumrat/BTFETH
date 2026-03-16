<?php $__env->startSection('title', 'Payment Info'); ?>
<?php $__env->startSection('page-title', 'Payment Info'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
  <h1>Payment Information</h1>
  <p>Manage global deposit & withdrawal details shown to all customers.</p>
</div>

<div class="row g-4">

  
  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header" style="background:rgba(34,197,94,0.08);border-bottom:2px solid rgba(34,197,94,0.3);">
        <div class="d-flex align-items-center gap-2">
          <span style="font-size:20px;">⬆️</span>
          <div>
            <div style="font-size:15px;font-weight:800;color:#22c55e;">Deposit Information</div>
            <div style="font-size:11px;color:var(--muted);">What customers see when depositing</div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form method="POST" action="<?php echo e(route('admin.payment-info.deposit')); ?>">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="user_id" value="">

          <div class="form-section-label">
            <span style="font-size:14px;">🟡</span> Binance Pay
          </div>
          <div class="form-group">
            <label class="form-label">Binance Pay Link</label>
            <input type="text" name="binance_link" class="form-control" value="<?php echo e($globalDeposit->binance_link ?? ''); ?>" placeholder="https://pay.binance.com/..."/>
          </div>
          <div class="form-group">
            <label class="form-label">Binance Wallet Address (BEP-20)</label>
            <input type="text" name="wallet_address" class="form-control" value="<?php echo e($globalDeposit->wallet_address ?? ''); ?>" placeholder="0x..."/>
          </div>

          <div class="form-section-label mt-3">
            <span style="font-size:14px;">🔵</span> Trust Wallet
          </div>
          <div class="form-group">
            <label class="form-label">Trust Wallet Address</label>
            <input type="text" name="trust_wallet_address" class="form-control" value="<?php echo e($globalDeposit->trust_wallet_address ?? ''); ?>" placeholder="0x... or TRX..."/>
          </div>
          <div class="form-group">
            <label class="form-label">Network</label>
            <select name="trust_network" class="form-control">
              <option value="">-- Select Network --</option>
              <?php $__currentLoopData = ['BEP-20 (BSC)','ERC-20 (ETH)','TRC-20 (TRON)','BEP-2','Polygon','Solana']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $net): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($net); ?>" <?php echo e((($globalDeposit->trust_network ?? '') == $net) ? 'selected' : ''); ?>><?php echo e($net); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>

          <div class="form-section-label mt-3">🏦 Bank Transfer</div>
          <div class="form-group">
            <label class="form-label">Account Name</label>
            <input type="text" name="account_name" class="form-control" value="<?php echo e($globalDeposit->account_name ?? ''); ?>"/>
          </div>
          <div class="form-group">
            <label class="form-label">Account Number</label>
            <input type="text" name="account_number" class="form-control" value="<?php echo e($globalDeposit->account_number ?? ''); ?>"/>
          </div>
          <div class="form-group">
            <label class="form-label">Bank Name</label>
            <input type="text" name="bank_name" class="form-control" value="<?php echo e($globalDeposit->bank_name ?? ''); ?>"/>
          </div>
          <div class="form-group">
            <label class="form-label">SWIFT / Routing</label>
            <input type="text" name="swift" class="form-control" value="<?php echo e($globalDeposit->swift ?? ''); ?>"/>
          </div>
          <div class="form-group">
            <label class="form-label">Reference</label>
            <input type="text" name="reference" class="form-control" value="<?php echo e($globalDeposit->reference ?? ''); ?>"/>
          </div>

          <button type="submit" class="btn btn-primary w-100 mt-2" style="background:#22c55e;border-color:#22c55e;">
            💾 &nbsp;Save Deposit Info
          </button>
        </form>
      </div>
    </div>
  </div>

  
  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header" style="background:rgba(239,68,68,0.08);border-bottom:2px solid rgba(239,68,68,0.3);">
        <div class="d-flex align-items-center gap-2">
          <span style="font-size:20px;">⬇️</span>
          <div>
            <div style="font-size:15px;font-weight:800;color:#ef4444;">Withdrawal Information</div>
            <div style="font-size:11px;color:var(--muted);">Customers contact live chat to withdraw</div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="alert-note mb-4" style="background:rgba(59,130,246,0.08);border-color:rgba(59,130,246,0.3);color:var(--accent);">
          💬 Withdrawal requests are handled via <strong>Live Chat</strong>. Customers send their wallet address through chat and you process manually.
        </div>

        <form method="POST" action="<?php echo e(route('admin.payment-info.withdraw')); ?>">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="user_id" value="">

          <div class="form-section-label">
            <span style="font-size:14px;">🟡</span> Binance Pay
          </div>
          <div class="form-group">
            <label class="form-label">Withdrawal Request Link</label>
            <input type="text" name="withdraw_link" class="form-control" value="<?php echo e($globalWithdrawal->withdraw_link ?? ''); ?>" placeholder="https://pay.binance.com/..."/>
          </div>
          <div class="form-group">
            <label class="form-label">Withdrawal ID</label>
            <input type="text" name="withdraw_id" class="form-control" value="<?php echo e($globalWithdrawal->withdraw_id ?? ''); ?>" placeholder="WD-ONX-..."/>
          </div>

          <div class="form-section-label mt-3">
            <span style="font-size:14px;">🔵</span> Trust Wallet
          </div>
          <div class="form-group">
            <label class="form-label">Trust Wallet Address</label>
            <input type="text" name="trust_withdraw_address" class="form-control" value="<?php echo e($globalWithdrawal->trust_withdraw_address ?? ''); ?>" placeholder="0x..."/>
          </div>
          <div class="form-group">
            <label class="form-label">Network</label>
            <select name="trust_network" class="form-control">
              <option value="">-- Select Network --</option>
              <?php $__currentLoopData = ['BEP-20 (BSC)','ERC-20 (ETH)','TRC-20 (TRON)','BEP-2','Polygon','Solana']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $net): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($net); ?>" <?php echo e((($globalWithdrawal->trust_network ?? '') == $net) ? 'selected' : ''); ?>><?php echo e($net); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>

          <div class="form-section-label mt-3">ℹ️ General Info</div>
          <div class="form-group">
            <label class="form-label">Min. Withdrawal</label>
            <input type="text" name="min_withdrawal" class="form-control" value="<?php echo e($globalWithdrawal->min_withdrawal ?? ''); ?>" placeholder="$50 USDT"/>
          </div>
          <div class="form-group">
            <label class="form-label">Processing Time</label>
            <input type="text" name="processing_time" class="form-control" value="<?php echo e($globalWithdrawal->processing_time ?? ''); ?>" placeholder="12–24 Hours"/>
          </div>
          <div class="form-group">
            <label class="form-label">Fee</label>
            <input type="text" name="fee" class="form-control" value="<?php echo e($globalWithdrawal->fee ?? ''); ?>" placeholder="1.5%"/>
          </div>
          <div class="form-group">
            <label class="form-label">Note for Customers</label>
            <input type="text" name="note" class="form-control" value="<?php echo e($globalWithdrawal->note ?? ''); ?>" placeholder="Contact live chat to submit your withdrawal request."/>
          </div>

          <button type="submit" class="btn btn-primary w-100 mt-2" style="background:#ef4444;border-color:#ef4444;">
            💾 &nbsp;Save Withdrawal Info
          </button>
        </form>
      </div>
    </div>
  </div>

</div>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\onchain\resources\views/admin/payment-info.blade.php ENDPATH**/ ?>