<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $__env->yieldContent('title', 'Btfeth'); ?> — Btfeth Investments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="<?php echo e(asset('css/style.css')); ?>" rel="stylesheet" />
  <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
  <?php echo $__env->yieldContent('content'); ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <?php echo $__env->yieldPushContent('scripts'); ?>
<script>
function dismissAlert(el) {
  const alert = el.closest('[class*="alert"]');
  if (alert) { alert.style.transition='opacity 0.3s'; alert.style.opacity='0'; setTimeout(()=>alert.remove(),300); }
}
// Auto dismiss after 6 seconds
setTimeout(() => {
  document.querySelectorAll('[class*="alert-success"],[class*="alert-error"],[class*="alert-warning"]').forEach(el => {
    el.style.transition='opacity 0.5s'; el.style.opacity='0'; setTimeout(()=>el.remove(),500);
  });
}, 6000);
</script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\efteth\resources\views/layouts/app.blade.php ENDPATH**/ ?>