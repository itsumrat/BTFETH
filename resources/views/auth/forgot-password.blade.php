<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reset Password — Btfeth</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
</head>
<body>
<nav class="navbar"><div class="container">
  <a class="navbar-brand" href="{{ route('home') }}">BTF<span>ETH</span></a>
</div></nav>
<div class="auth-wrap"><div class="container">
  <div class="auth-card fade-up">
    <div class="auth-logo">BTF<span>ETH</span></div>
    <h1 class="auth-title">Reset Password</h1>
    <p class="auth-sub">Enter your email to receive a reset link</p>

    @if(session('status'))
      <div class="mb-3" style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:8px;padding:12px 16px;font-size:13px;color:var(--green);">
        {{ session('status') }}
      </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf
      <div class="mb-4">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="you@example.com" value="{{ old('email') }}" required autofocus />
        @error('email')<div style="color:var(--red);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
      </div>
      <button type="submit" class="btn btn-primary w-100 btn-lg">Send Reset Link</button>
    </form>

    <div class="auth-footer">
      <a href="{{ route('login') }}">← Back to Login</a>
    </div>
  </div>
</div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
