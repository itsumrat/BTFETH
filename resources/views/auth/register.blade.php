<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register — Btfeth</title>
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
      <h1 class="auth-title">Create account</h1>
      <p class="auth-sub">Start earning daily crypto profits today</p>

      @if($errors->any())
        <div class="alert mb-3" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:8px;padding:12px 16px;font-size:13px;color:var(--red);">
          {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                 placeholder="John Doe" value="{{ old('name') }}" required />
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                 placeholder="you@example.com" value="{{ old('email') }}" required />
          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <div class="input-group">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                   id="pwdField" placeholder="Min. 8 characters" required minlength="8" />
            <button class="btn btn-outline-secondary" type="button" onclick="togglePwd(this)"
                    tabindex="-1" style="border-left:none;border-radius:0 8px 8px 0;">👁</button>
          </div>
          <div class="form-text mt-1">Use at least 8 characters with letters and numbers.</div>
          @error('password')<div style="color:var(--red);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        {{-- Password strength bar --}}
        <div class="mb-3">
          <div style="height:4px;background:var(--surface2);border-radius:2px;overflow:hidden;">
            <div id="strengthBar" style="height:100%;width:0%;border-radius:2px;transition:all 0.3s;"></div>
          </div>
          <div id="strengthLabel" style="font-size:11px;color:var(--muted);margin-top:4px;"></div>
        </div>

        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input type="password" name="password_confirmation" class="form-control"
                 placeholder="Repeat your password" required />
        </div>

        <div class="mb-4 form-check">
          <input type="checkbox" class="form-check-input" id="agree" required />
          <label class="form-check-label" for="agree" style="font-size:13px;color:var(--muted);">
            I agree to the <a href="#" style="color:var(--accent);">Terms of Service</a> and <a href="#" style="color:var(--accent);">Privacy Policy</a>
          </label>
        </div>

        <button type="submit" class="btn btn-primary w-100 btn-lg">Create Account</button>
      </form>

      <div class="auth-footer">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
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

  document.getElementById('pwdField').addEventListener('input', function() {
    const val = this.value;
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const cfgs = [
      {w:'0%',color:'transparent',text:''},
      {w:'25%',color:'var(--red)',text:'Weak'},
      {w:'50%',color:'var(--gold)',text:'Fair'},
      {w:'75%',color:'var(--accent)',text:'Good'},
      {w:'100%',color:'var(--green)',text:'Strong'},
    ];
    const c = cfgs[score];
    document.getElementById('strengthBar').style.width = c.w;
    document.getElementById('strengthBar').style.background = c.color;
    document.getElementById('strengthLabel').textContent = c.text;
    document.getElementById('strengthLabel').style.color = c.color;
  });
</script>
</body>
</html>
