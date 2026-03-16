<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About — Btfeth</title>
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
        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('plans') }}">Plans</a></li>
        <li class="nav-item"><a class="nav-link active" href="{{ route('about') }}">About</a></li>
        <li class="nav-item ms-lg-2"><a class="btn btn-outline-secondary btn-sm" href="{{ route('login') }}">Sign Out</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-5 fade-up">

  <!-- Hero -->
  <div class="text-center mb-5 py-4">
    <h1 class="mb-3" style="font-size:clamp(32px,5vw,52px); font-weight:800; letter-spacing:-2px;">
      About <span style="color:var(--accent);">Btfeth</span>
    </h1>
    <p class="text-muted mx-auto" style="font-size:16px; max-width:520px; line-height:1.75;">
      Your trusted gateway to decentralized finance — designed for everyday investors who want consistent, transparent crypto returns.
    </p>
  </div>

  <!-- Stats -->
  <div class="row g-3 mb-5 text-center">
    <div class="col-4">
      <div class="stat-card">
        <div style="font-size:28px; font-weight:800; font-family:'JetBrains Mono',monospace; color:var(--accent); margin-bottom:4px;">$2.4B+</div>
        <div style="font-size:12px; color:var(--muted); text-transform:uppercase; letter-spacing:1px;">Total Volume</div>
      </div>
    </div>
    <div class="col-4">
      <div class="stat-card">
        <div style="font-size:28px; font-weight:800; font-family:'JetBrains Mono',monospace; color:var(--green); margin-bottom:4px;">48K+</div>
        <div style="font-size:12px; color:var(--muted); text-transform:uppercase; letter-spacing:1px;">Active Users</div>
      </div>
    </div>
    <div class="col-4">
      <div class="stat-card">
        <div style="font-size:28px; font-weight:800; font-family:'JetBrains Mono',monospace; color:var(--gold); margin-bottom:4px;">99.9%</div>
        <div style="font-size:12px; color:var(--muted); text-transform:uppercase; letter-spacing:1px;">Uptime</div>
      </div>
    </div>
  </div>

  <!-- Who We Are -->
  <div class="row g-4 mb-5 align-items-center">
    <div class="col-lg-6">
      <p class="text-muted mb-2" style="font-size:12px; text-transform:uppercase; letter-spacing:1.2px; font-weight:700;">Who We Are</p>
      <h3 class="mb-3" style="letter-spacing:-0.5px;">Built by blockchain engineers & financial experts</h3>
      <p class="text-muted mb-3" style="line-height:1.8;">
        Btfeth is a next-generation cryptocurrency investment platform that bridges the gap between traditional finance and decentralized technology. Founded by a team with deep expertise in DeFi, smart contracts, and capital markets.
      </p>
      <p class="text-muted" style="line-height:1.8;">
        We leverage cutting-edge Binance Smart Chain liquidity pools to deliver consistent, predictable returns — accessible to everyone, not just institutional players.
      </p>
    </div>
    <div class="col-lg-6">
      <div class="card p-1">
        <div class="card-body" style="background:var(--surface2); border-radius:10px;">
          <div class="d-flex flex-column gap-3">
            <div class="d-flex align-items-start gap-3">
              <span style="font-size:20px; margin-top:2px;">✅</span>
              <div>
                <div style="font-size:14px; font-weight:700; margin-bottom:3px;">Registered Company</div>
                <div style="font-size:13px; color:var(--muted);">Btfeth Investments Ltd — incorporated and fully compliant.</div>
              </div>
            </div>
            <div class="d-flex align-items-start gap-3">
              <span style="font-size:20px; margin-top:2px;">🔍</span>
              <div>
                <div style="font-size:14px; font-weight:700; margin-bottom:3px;">Audited Smart Contracts</div>
                <div style="font-size:13px; color:var(--muted);">Regular third-party audits by leading blockchain security firms.</div>
              </div>
            </div>
            <div class="d-flex align-items-start gap-3">
              <span style="font-size:20px; margin-top:2px;">🛡️</span>
              <div>
                <div style="font-size:14px; font-weight:700; margin-bottom:3px;">AML / KYC Compliant</div>
                <div style="font-size:13px; color:var(--muted);">Full compliance with international anti-money-laundering regulations.</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- CTA -->
  <div class="card text-center" style="padding: 44px 24px;">
    <h3 class="mb-3">Ready to grow your wealth?</h3>
    <p class="text-muted mb-4">Join 48,000+ investors already earning with Btfeth.</p>
    <div class="d-flex gap-3 justify-content-center flex-wrap">
      <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5">Get Started</a>
      <a href="{{ route('plans') }}" class="btn btn-outline-secondary btn-lg">View Plans</a>
    </div>
  </div>

</div>

<!-- FOOTER -->
<footer class="site-footer">
  <div class="container">© 2026 Btfeth Investments Ltd. All rights reserved.</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
