<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Change Password — Btfeth</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="<?php echo e(asset('css/style.css')); ?>" rel="stylesheet"/>
</head>
<body>
<nav class="navbar"><div class="container">
  <a class="navbar-brand" href="<?php echo e(route('home')); ?>">BTF<span>ETH</span></a>
  <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
</div></nav>

<div class="auth-wrap"><div class="container">
  <div class="auth-card fade-up">
    <div class="auth-logo">BTF<span>ETH</span></div>
    <h1 class="auth-title">Change Password</h1>
    <p class="auth-sub">Choose a strong new password for your account</p>

    <?php if(session('success')): ?>
      <div class="mb-3" style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:8px;padding:12px 16px;font-size:13px;color:var(--green);">
        ✓ <?php echo e(session('success')); ?>

      </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
      <div class="mb-3" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:8px;padding:12px 16px;font-size:13px;color:var(--red);">
        <?php echo e($errors->first()); ?>

      </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('password.update')); ?>">
      <?php echo csrf_field(); ?>
      <div class="mb-3">
        <label class="form-label">Current Password</label>
        <input type="password" name="current_password" class="form-control" placeholder="Enter current password" required/>
      </div>
      <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" name="password" class="form-control" id="newPwd" placeholder="Min. 8 characters" required minlength="8"/>
      </div>
      <div class="mb-4">
        <label class="form-label">Confirm New Password</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password" required/>
      </div>
      <button type="submit" class="btn btn-primary w-100 btn-lg">Update Password</button>
    </form>
  </div>
</div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\onchain\resources\views/auth/change-password.blade.php ENDPATH**/ ?>