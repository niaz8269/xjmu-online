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
      <button type="submit" class="btn btn-primary">Search</button>
      @if (!empty($search))
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
              <a class="page-link" href="?page={{ $page - 1 }}{!! !empty($search) ? '&search=' . urlencode($search) : '' !!}">Previous</a>
            </li>
          @endif

          @php
          $startPage = max(1, $page - 2);
          $endPage = min($totalPages, $page + 2);
          @endphp
          @for ($i = $startPage; $i <= $endPage; $i++)
            <li class="page-item {{ $i === $page ? 'active' : '' }}">
              <a class="page-link" href="?page={{ $i }}{!! !empty($search) ? '&search=' . urlencode($search) : '' !!}">{{ $i }}</a>
            </li>
          @endfor

          @if ($page < $totalPages)
            <li class="page-item">
              <a class="page-link" href="?page={{ $page + 1 }}{!! !empty($search) ? '&search=' . urlencode($search) : '' !!}">Next</a>
            </li>
          @endif
        </ul>
      </nav>
    </div>
  @endif
</div></div>
@endsection
