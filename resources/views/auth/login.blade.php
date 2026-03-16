<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login — Btfeth</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
</head>
<body>

<nav class="navbar">
  <div class="container">
    <a class="navbar-brand" href="{{ route('home') }}">BTF<span>ETH</span></a>
  </div>
</nav>

<div class="auth-wrap">
  <div class="container">
    <div class="auth-card fade-up">

      <div class="auth-logo">BTF<span>ETH</span></div>
      <h1 class="auth-title">Welcome back</h1>
      <p class="auth-sub">Sign in to your investment account</p>

      {{-- Error Messages --}}
      @if($errors->any())
        <div class="alert mb-3" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:8px;padding:12px 16px;font-size:13px;color:var(--red);">
          {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                 placeholder="you@example.com" value="{{ old('email') }}" required autofocus />
          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
          <label class="form-label mb-1">Password</label>
          <div class="input-group">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                   id="pwdField" placeholder="Enter your password" required />
            <button class="btn btn-outline-secondary" type="button" onclick="togglePwd(this)"
                    tabindex="-1" style="border-left:none;border-radius:0 8px 8px 0;">👁</button>
          </div>
          <div class="text-end mt-1">
            <a href="{{ route('password.request') }}" style="font-size:13px;color:var(--accent);">Forgot password?</a>
          </div>
        </div>

        <div class="mb-4 form-check">
          <input type="checkbox" name="remember" class="form-check-input" id="remember" />
          <label class="form-check-label" for="remember" style="font-size:14px;color:var(--muted);">Remember me</label>
        </div>

        <button type="submit" class="btn btn-primary w-100 btn-lg">Sign In</button>
      </form>

      <div class="auth-footer">
        Don't have an account? <a href="{{ route('register') }}">Create one</a>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function togglePwd(btn) {
    const f = document.getElementById('pwdField');
    f.type = f.type === 'password' ? 'text' : 'password';
    btn.textContent = f.type === 'password' ? '👁' : '🙈';
  }
</script>
</body>
</html>
