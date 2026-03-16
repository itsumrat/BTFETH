<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reset Password — Btfeth</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="<?php echo e(asset('css/style.css')); ?>" rel="stylesheet" />
</head>
<body>
<nav class="navbar"><div class="container">
  <a class="navbar-brand" href="<?php echo e(route('home')); ?>">BTF<span>ETH</span></a>
</div></nav>
<div class="auth-wrap"><div class="container">
  <div class="auth-card fade-up">
    <div class="auth-logo">BTF<span>ETH</span></div>
    <h1 class="auth-title">Reset Password</h1>
    <p class="auth-sub">Enter your email to receive a reset link</p>

    <?php if(session('status')): ?>
      <div class="mb-3" style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:8px;padding:12px 16px;font-size:13px;color:var(--green);">
        <?php echo e(session('status')); ?>

      </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('password.email')); ?>">
      <?php echo csrf_field(); ?>
      <div class="mb-4">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="you@example.com" value="<?php echo e(old('email')); ?>" required autofocus />
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:var(--red);font-size:12px;margin-top:4px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <button type="submit" class="btn btn-primary w-100 btn-lg">Send Reset Link</button>
    </form>

    <div class="auth-footer">
      <a href="<?php echo e(route('login')); ?>">← Back to Login</a>
    </div>
  </div>
</div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\efteth\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>