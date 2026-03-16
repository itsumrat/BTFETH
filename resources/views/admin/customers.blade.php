@extends('layouts.admin')
@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')

<div class="page-header">
  <h1>Customer List</h1>
  <p>View and manage all registered customers.</p>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.customers') }}" style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:20px;align-items:center;">
  <div class="search-wrap">
    <span style="color:var(--muted);">🔍</span>
    <input type="text" name="search" placeholder="Search name or email..." value="{{ request('search') }}" />
  </div>
  <select name="status" class="form-select" style="width:auto;font-size:13px;padding:8px 12px;">
    <option value="">All Status</option>
    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
  </select>
  <button type="submit" class="btn btn-primary btn-sm">Filter</button>
  <a href="{{ route('admin.customers') }}" class="btn btn-ghost btn-sm">✕ Clear</a>
</form>

<div class="card">
  <div class="card-header">
    All Customers
    <span class="badge badge-blue ms-2">{{ $customers->total() }}</span>
  </div>
  <div style="overflow-x:auto;">
    <table class="admin-table">
      <thead>
        <tr>
          <th>#</th><th>Customer</th><th>Email</th><th>Registered</th>
          <th>Deposited</th><th>Withdrawn</th><th>Balance</th><th>Status</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($customers as $i => $c)
        <tr>
          <td style="color:var(--muted);font-family:'JetBrains Mono',monospace;font-size:12px;">
            {{ str_pad($customers->firstItem() + $i, 2, '0', STR_PAD_LEFT) }}
          </td>
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <div class="avatar">{{ $c->initials() }}</div>
              <div>
                <div style="font-size:14px;font-weight:700;color:var(--text);">{{ $c->name }}</div>
                <div style="font-size:11px;color:var(--muted);">ID #{{ $c->id }}</div>
              </div>
            </div>
          </td>
          <td style="font-size:12px;color:var(--muted);font-family:'JetBrains Mono',monospace;">{{ $c->email }}</td>
          <td style="font-size:12px;color:var(--muted);font-family:'JetBrains Mono',monospace;">{{ $c->created_at->format('d M Y') }}</td>
          <td>
            <div style="font-family:'JetBrains Mono',monospace;font-size:15px;font-weight:800;color:#22c55e;">${{ number_format($c->total_deposited, 2) }}</div>
            <div style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;">Deposited</div>
          </td>
          <td>
            <div style="font-family:'JetBrains Mono',monospace;font-size:15px;font-weight:800;color:#ef4444;">${{ number_format($c->total_withdrawn, 2) }}</div>
            <div style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;">Withdrawn</div>
          </td>
          <td>
            <div style="font-family:'JetBrains Mono',monospace;font-size:16px;font-weight:800;color:var(--text);">${{ number_format($c->balance, 2) }}</div>
            <div style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;">Balance</div>
          </td>
          <td>
            <span class="badge {{ $c->is_active ? 'badge-green' : 'badge-gray' }}">
              {{ $c->is_active ? '● Active' : '○ Inactive' }}
            </span>
          </td>
          <td>
            <div style="display:flex;gap:6px;">
              <a href="{{ route('admin.customers.show', $c) }}" class="btn btn-ghost btn-xs">👁 View</a>
              <form method="POST" action="{{ route('admin.customers.toggle', $c) }}" style="display:inline;">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-xs {{ $c->is_active ? 'btn-warning' : 'btn-success' }}">
                  {{ $c->is_active ? '⏸ Disable' : '▶ Enable' }}
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="9" style="text-align:center;padding:32px;color:var(--muted);">No customers found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($customers->hasPages())
  <div style="padding:16px 20px;border-top:1px solid var(--border);">
    {{ $customers->links() }}
  </div>
  @endif
</div>

@endsection
