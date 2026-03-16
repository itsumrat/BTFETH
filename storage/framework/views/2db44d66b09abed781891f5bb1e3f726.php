<?php $__env->startSection('title', 'Change Password'); ?>
<?php $__env->startSection('page-title', 'Change Password'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
  <h1>Change Password</h1>
  <p>Update your admin account password.</p>
</div>

<div class="row">
  <div class="col-lg-5">
    <div class="card">
      <div class="card-header">🔑 New Password</div>
      <div class="card-body">

        <?php if(session('success')): ?>
          <div class="alert-success mb-4">✓ <?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
          <div class="alert-warning mb-4">⚠ <?php echo e($errors->first()); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('admin.password.update')); ?>">
          <?php echo csrf_field(); ?>
          <div class="form-group">
            <label class="form-label">Current Password</label>
            <input type="password" name="current_password" class="form-control" placeholder="Enter current password" required/>
          </div>
          <div class="form-group">
            <label class="form-label">New Password</label>
            <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required minlength="8"/>
          </div>
          <div class="form-group">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password" required/>
          </div>
          <button type="submit" class="btn btn-primary w-100">Update Password</button>
        </form>

      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\efteth\resources\views/admin/change-password.blade.php ENDPATH**/ ?>