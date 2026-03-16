<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Btfeth — Crypto Investment Platform</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container">
    <a class="navbar-brand" href="{{ route('home') }}">BTF<span>ETH</span></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
        <li class="nav-item"><a class="nav-link active" href="{{ route('home') }}">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('plans') }}">Plans</a></li>
        @auth
          @if(auth()->user()->is_admin)
            <li class="nav-item ms-lg-2"><a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
          @else
            <li class="nav-item ms-lg-2"><a class="btn btn-outline-secondary btn-sm" href="{{ route('dashboard') }}">Dashboard</a></li>
          @endif
          <li class="nav-item ms-1">
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
              @csrf
              <button type="submit" class="btn btn-primary btn-sm">Sign Out</button>
            </form>
          </li>
        @else
          <li class="nav-item ms-lg-2"><a class="btn btn-outline-secondary btn-sm" href="{{ route('login') }}">Login</a></li>
          <li class="nav-item ms-1"><a class="btn btn-primary btn-sm" href="{{ route('register') }}">Get Started</a></li>
        @endauth
      </ul>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero-section">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 fade-up">
        <span class="badge bg-primary-soft mb-4 py-2 px-3" style="font-size:12px;">
          ● Live · Powered by Binance
        </span>
        <h1 class="hero-title mb-4">
          Earn Daily<br>
          <span class="highlight">Crypto Yields</span><br>
          Effortlessly
        </h1>
        <p class="mb-5" style="font-size:16px; color:var(--muted); max-width:440px; line-height:1.75;">
          Professional DeFi investment platform with consistent daily returns. Secure, transparent, and powered by Binance liquidity.
        </p>
        <div class="d-flex gap-3 flex-wrap">
          @guest
          <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Create Account</a>
          @endguest
          <a href="{{ route('about') }}" class="btn btn-outline-secondary btn-lg">Learn More</a>
        </div>
      </div>

      <div class="col-lg-6 fade-up" style="animation-delay:0.1s">
        @auth
        {{-- Logged in: show real portfolio card --}}
        <div class="card p-2">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-4">
              <span class="fw-bold" style="font-size:15px;">Portfolio Overview</span>
              <span class="badge bg-success-soft">● Live</span>
            </div>
            <div class="row g-3 mb-4">
              <div class="col-6">
                <div class="stat-card">
                  <div class="stat-label"><span class="dot" style="background:var(--accent)"></span> Total Balance</div>
                  <div class="stat-value" style="font-size:24px;">${{ number_format(auth()->user()->balance, 2) }}</div>
                  <div class="stat-change text-green">↑ +{{ number_format(auth()->user()->daily_profit, 2) }} today</div>
                </div>
              </div>
              <div class="col-6">
                <div class="stat-card">
                  <div class="stat-label"><span class="dot" style="background:var(--green)"></span> Daily Profit</div>
                  <div class="stat-value" style="font-size:24px; color:var(--green)">+${{ number_format(auth()->user()->daily_profit, 2) }}</div>
                  <div class="stat-change text-muted">Today's earnings</div>
                </div>
              </div>
            </div>
            <div style="background:var(--surface2); border:1px solid var(--border); border-radius:10px; padding:16px;">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span style="font-size:13px; font-weight:600;">Welcome back, {{ auth()->user()->name }} 👋</span>
                <span class="badge bg-success-soft">Active</span>
              </div>
              <a href="{{ route('dashboard') }}" class="btn btn-primary w-100 mt-2">Go to Dashboard →</a>
            </div>
          </div>
        </div>
        @else
        {{-- Guest: show sign-up prompt instead --}}
        <div class="card p-2">
          <div class="card-body" style="text-align:center; padding: 40px 24px;">
            <div style="font-size:52px; margin-bottom:16px;">📈</div>
            <h4 style="font-weight:800; margin-bottom:10px;">Start Earning Today</h4>
            <p style="color:var(--muted); font-size:14px; margin-bottom:28px; line-height:1.7;">
              Join thousands of investors earning daily crypto yields. Create your free account in under a minute.
            </p>
            <div class="d-flex flex-column gap-3">
              <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Create Free Account</a>
              <a href="{{ route('login') }}" class="btn btn-outline-secondary">Sign In to Dashboard</a>
            </div>
            <div style="margin-top:24px; display:flex; justify-content:center; gap:28px;">
              <div style="text-align:center;">
                <div style="font-size:18px; font-weight:800; font-family:JetBrains Mono,monospace; color:var(--green);">7%</div>
                <div style="font-size:11px; color:var(--muted); text-transform:uppercase; letter-spacing:1px;">Max Daily</div>
              </div>
              <div style="text-align:center;">
                <div style="font-size:18px; font-weight:800; font-family:JetBrains Mono,monospace; color:var(--accent);">24/7</div>
                <div style="font-size:11px; color:var(--muted); text-transform:uppercase; letter-spacing:1px;">Support</div>
              </div>
              <div style="text-align:center;">
                <div style="font-size:18px; font-weight:800; font-family:JetBrains Mono,monospace; color:var(--gold);">USDT</div>
                <div style="font-size:11px; color:var(--muted); text-transform:uppercase; letter-spacing:1px;">Payouts</div>
              </div>
            </div>
          </div>
        </div>
        @endauth
      </div>
    </div>
  </div>
</section>

<!-- FEATURES STRIP -->
<section class="py-5" style="border-top:1px solid var(--border);">
  <div class="container">
    <div class="row g-4 text-center">
      <div class="col-6 col-md-3">
        <div style="font-size:28px; margin-bottom:10px;">🔒</div>
        <div style="font-size:14px; font-weight:700; margin-bottom:4px;">Secure Wallets</div>
        <p style="font-size:13px;">Multi-sig cold storage protection</p>
      </div>
      <div class="col-6 col-md-3">
        <div style="font-size:28px; margin-bottom:10px;">⚡</div>
        <div style="font-size:14px; font-weight:700; margin-bottom:4px;">Instant Deposits</div>
        <p style="font-size:13px;">Fund via Binance Pay in minutes</p>
      </div>
      <div class="col-6 col-md-3">
        <div style="font-size:28px; margin-bottom:10px;">💰</div>
        <div style="font-size:14px; font-weight:700; margin-bottom:4px;">Daily Payouts</div>
        <p style="font-size:13px;">Profits credited every 24 hours</p>
      </div>
      <div class="col-6 col-md-3">
        <div style="font-size:28px; margin-bottom:10px;">🌐</div>
        <div style="font-size:14px; font-weight:700; margin-bottom:4px;">24/7 Support</div>
        <p style="font-size:13px;">Expert team always available</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="py-5">
  <div class="container">
    <div class="card text-center" style="padding: 48px 24px;">
      <h2 class="mb-3" style="letter-spacing:-0.5px;">Ready to start earning?</h2>
      <p class="mb-4" style="color:var(--muted); max-width:400px; margin:0 auto 24px;">
        Join thousands of investors growing their wealth with Btfeth every day.
      </p>
      <div>
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5">Create Free Account</a>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="site-footer">
  <div class="container">
    <div class="mb-2"><strong>BTF<span style="color:var(--accent)">ETH</span></strong></div>
    <div>© 2026 Btfeth Investments Ltd. All rights reserved.</div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
