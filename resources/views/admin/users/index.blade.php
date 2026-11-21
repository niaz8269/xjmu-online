@extends('admin.layout')
@section('content')

<div class="card"><div class="card-body">
  @if ($is_superadmin)
  <a href="manage_users" class="btn btn-outline-secondary btn-sm mb-3">Manage Users (promote / demote)</a>
  @endif

  <!-- Search Box -->
  <div class="mb-3">
    <form method="GET" class="d-flex gap-2">
      <input type="text" name="search" class="form-control" placeholder="🔍 Search by name or email..." value="{{ $search }}" style="max-width: 400px;">
      <select name="verified" class="form-select" style="max-width: 190px;" aria-label="Email verification">
        <option value="">Verified or not</option>
        <option value="1" @selected($verified === '1')>Verified only</option>
        <option value="0" @selected($verified === '0')>Unverified only</option>
      </select>
      <button type="submit" class="btn btn-primary">Search</button>
      <div class="dropdown ms-auto">
        <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown"><i class="bi bi-download me-1"></i>Export (Excel/CSV)</button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="?export=csv{!! (!empty($search) ? '&search=' . urlencode($search) : '') . ($verified !== '' ? '&verified=' . $verified : '') !!}">{{ !empty($search) || $verified !== '' ? 'Users in this list' : 'All users' }}</a></li>
          <li><a class="dropdown-item" href="?export=csv&verified=0">Unverified users only</a></li>
          <li><a class="dropdown-item" href="?export=csv&verified=1">Verified users only</a></li>
        </ul>
      </div>
      @if (!empty($search) || $verified !== '')
        <a href="users" class="btn btn-secondary">Clear</a>
      @endif
    </form>
  </div>

  <!-- Results Info -->
  <div class="mb-3">
    <small class="text-muted">
      Showing {{ count($users) }} of {{ $totalRecords }} users
      @if (!empty($search))
        (filtered results)
      @endif
    </small>
  </div>

  @if (empty($users))
    <div class="alert alert-info">
      <h5>No users found</h5>
      <p>There are no users{{ !empty($search) ? ' matching your search criteria' : ' in the system' }}.</p>
      @if (!empty($search))
        <a href="users" class="btn btn-primary">Clear Search</a>
      @endif
    </div>
  @else

  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Email verified</th>
        <th>Registered At</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($users as $user)
      <tr>
        <td>{!! $user['id'] !!}</td>
        <td>{{ $user['full_name'] }}</td>
        <td>{{ $user['email'] }}</td>
        <td>{!! $user['role'] !!}</td>
        <td>@if (!empty($user['is_verified']))<span class="badge text-bg-success">Yes</span>@else<span class="badge text-bg-warning">No</span>@endif</td>
        <td>{!! isset($user['created_at']) ? $user['created_at'] : '—' !!}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @endif

  <!-- Pagination -->
  @if (!empty($users) && $totalPages > 1)
    <div class="mt-4">
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
          @if ($page > 1)
            <li class="page-item">
              <a class="page-link" href="?page={{ $page - 1 }}{!! (!empty($search) ? '&search=' . urlencode($search) : '') . ($verified !== '' ? '&verified=' . $verified : '') !!}">Previous</a>
            </li>
          @endif

          @php
          $startPage = max(1, $page - 2);
          $endPage = min($totalPages, $page + 2);
          @endphp
          @for ($i = $startPage; $i <= $endPage; $i++)
            <li class="page-item {{ $i === $page ? 'active' : '' }}">
              <a class="page-link" href="?page={{ $i }}{!! (!empty($search) ? '&search=' . urlencode($search) : '') . ($verified !== '' ? '&verified=' . $verified : '') !!}">{{ $i }}</a>
            </li>
          @endfor

          @if ($page < $totalPages)
            <li class="page-item">
              <a class="page-link" href="?page={{ $page + 1 }}{!! (!empty($search) ? '&search=' . urlencode($search) : '') . ($verified !== '' ? '&verified=' . $verified : '') !!}">Next</a>
            </li>
          @endif
        </ul>
      </nav>
    </div>
  @endif
</div></div>
@endsection
