@extends('layouts.admin')
@section('title', 'Change Password')
@section('page-title', 'Change Password')

@section('content')
<div class="page-header">
  <h1>Change Password</h1>
  <p>Update your admin account password.</p>
</div>

<div class="row">
  <div class="col-lg-5">
    <div class="card">
      <div class="card-header">🔑 New Password</div>
      <div class="card-body">

        @if(session('success'))
          <div class="alert-success mb-4">✓ {{ session('success') }}</div>
        @endif
        @if($errors->any())
          <div class="alert-warning mb-4"><span>⚠ {{ $errors->first() }}</span><button class="alert-close" onclick="dismissAlert(this)" title="Dismiss">&times;</button></div>
        @endif

        <form method="POST" action="{{ route('admin.password.update') }}">
          @csrf
          <div class="form-group">
            <label class="form-label">Current Password</label>
            <input type="password" name="current_password" class="form-control" placeholder="Enter current password" required/>
          </div>
          <div class="form-group">
            <label class="form-label">New Password</label>
            <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required minlength="8"/>
          </div>
          <div class="form-group">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password" required/>
          </div>
          <button type="submit" class="btn btn-primary w-100">Update Password</button>
        </form>

      </div>
    </div>
  </div>
</div>
@endsection
