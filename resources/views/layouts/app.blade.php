<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Btfeth') — Btfeth Investments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
  @stack('styles')
</head>
<body>
  @yield('content')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
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

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/69b13c718d40471c377c850b/1jje586v4';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
</body>
</html>
