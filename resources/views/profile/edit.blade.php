<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Profile — Btfeth</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="{{ asset('css/style.css') }}" rel="stylesheet"/>
</head>
<body>

{{-- Topbar --}}
<div class="cust-topbar">
  <div class="cust-topbar-left">
    <a href="{{ route('home') }}" class="cust-logo" style="text-decoration:none;">BTF<span>ETH</span></a>
    <div class="cust-topbar-divider"></div>
    <div class="cust-location-time">
      <div id="custTime" class="cust-time">--:-- --</div>
      <div id="custDate" class="cust-date">--- --, ----</div>
      <div id="custLocation" class="cust-loc">📍 Locating...</div>
    </div>
  </div>
  <div class="cust-topbar-right">
    <div class="dropdown">
      <button class="cust-user-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
        @if($user->avatar)
          <img src="{{ asset('avatars/' . $user->avatar) }}" class="cust-avatar" style="object-fit:cover;" alt=""/>
        @else
          <div class="cust-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        @endif
        <div class="cust-user-info">
          <div class="cust-user-name">{{ $user->name }}</div>
          <div class="cust-user-role">Customer</div>
        </div>
      </button>
      <ul class="dropdown-menu dropdown-menu-end cust-dropdown">
        <li><a class="dropdown-item cust-drop-item" href="{{ route('dashboard') }}">🏠 &nbsp;Dashboard</a></li>
        <li><a class="dropdown-item cust-drop-item" href="{{ route('profile.edit') }}">👤 &nbsp;Edit Profile</a></li>
        <li><a class="dropdown-item cust-drop-item" href="{{ route('password.change') }}">🔑 &nbsp;Change Password</a></li>
        <li><hr class="cust-drop-divider"></li>
        <li>
          <form method="POST" action="{{ route('logout') }}">@csrf
            <button type="submit" class="dropdown-item cust-drop-item cust-drop-danger">🚪 &nbsp;Sign Out</button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</div>

<div class="container py-5" style="max-width:520px;">

  @if(session('success'))
    <div class="mb-4" style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:10px;padding:14px 18px;font-size:13px;color:#22c55e;">
      ✓ {{ session('success') }}
    </div>
  @endif
  @if($errors->any())
    <div class="mb-4" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:10px;padding:14px 18px;font-size:13px;color:#ef4444;">
      {{ $errors->first() }}
    </div>
  @endif

  <div class="card">
    <div class="card-body" style="padding:28px;">

      <h5 style="font-weight:800;margin-bottom:4px;">Edit Profile</h5>
      <p style="color:var(--muted);font-size:13px;margin-bottom:28px;">Update your name and profile picture</p>

      {{-- Current Avatar --}}
      <div class="text-center mb-4">
        <div id="avatarPreviewWrap" style="position:relative;display:inline-block;">
          @if($user->avatar)
            <img id="avatarPreview" src="{{ asset('avatars/' . $user->avatar) }}"
              style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid var(--accent);" alt=""/>
          @else
            <div id="avatarInitial" style="width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg,var(--accent),#7c3aed);display:flex;align-items:center;justify-content:center;font-size:36px;font-weight:800;color:#fff;margin:0 auto;">
              {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <img id="avatarPreview" src="" style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid var(--accent);display:none;" alt=""/>
          @endif
          <label for="avatarInput" style="position:absolute;bottom:2px;right:2px;width:28px;height:28px;border-radius:50%;background:var(--accent);border:2px solid var(--bg);display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:13px;">✏️</label>
        </div>
        <div style="margin-top:10px;font-size:12px;color:var(--muted);">Click pencil to change photo</div>
      </div>

      <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display:none;" onchange="previewAvatar(this)"/>
        <input type="hidden" name="remove_avatar" id="removeAvatarInput" value="0"/>

        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required/>
        </div>

        <div class="mb-4">
          <label class="form-label">Email Address</label>
          <input type="email" class="form-control" value="{{ $user->email }}" disabled style="opacity:0.5;cursor:not-allowed;"/>
          <div style="font-size:11px;color:var(--muted);margin-top:4px;">Email cannot be changed. Contact support.</div>
        </div>

        @if($user->avatar)
        <div class="mb-4">
          <button type="button" onclick="removeAvatar()" class="btn btn-outline-secondary btn-sm w-100">
            🗑 Remove Profile Photo
          </button>
        </div>
        @endif

        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary w-100 mt-2">← Back to Dashboard</a>
      </form>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function previewAvatar(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = e => {
        const preview = document.getElementById('avatarPreview');
        const initial = document.getElementById('avatarInitial');
        preview.src = e.target.result;
        preview.style.display = 'block';
        if (initial) initial.style.display = 'none';
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  function removeAvatar() {
    document.getElementById('removeAvatarInput').value = '1';
    const preview = document.getElementById('avatarPreview');
    if (preview) preview.style.display = 'none';
    const initial = document.getElementById('avatarInitial');
    if (initial) initial.style.display = 'flex';
  }

  // Clock
  let tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
  function tick() {
    const now = new Date();
    document.getElementById('custTime').textContent = now.toLocaleTimeString('en-US',{timeZone:tz,hour:'2-digit',minute:'2-digit',second:'2-digit',hour12:true});
    document.getElementById('custDate').textContent = now.toLocaleDateString('en-US',{timeZone:tz,weekday:'short',day:'2-digit',month:'short',year:'numeric'});
  }
  setInterval(tick,1000); tick();
  if(navigator.geolocation){navigator.geolocation.getCurrentPosition(p=>{fetch(`https://nominatim.openstreetmap.org/reverse?lat=${p.coords.latitude}&lon=${p.coords.longitude}&format=json`).then(r=>r.json()).then(d=>{const c=d.address.city||d.address.town||d.address.county||'';const cc=(d.address.country_code||'').toUpperCase();document.getElementById('custLocation').textContent='📍 '+(c?c+', '+cc:tz);}).catch(()=>{document.getElementById('custLocation').textContent='📍 '+tz;});},()=>{document.getElementById('custLocation').textContent='📍 '+tz.split('/').pop().replace(/_/g,' ');});}
</script>
</body>
</html>
