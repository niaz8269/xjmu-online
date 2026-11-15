@extends('admin.layout')
@section('content')

<div class="card"><div class="card-body">
    <!-- Search Box -->
    <div class="mb-3">
      <form method="GET" class="d-flex gap-2">
        <input type="text" name="search" class="form-control" placeholder="&#128269; Search by name or email..." value="{{ $search }}" style="max-width: 400px;">
        <button type="submit" class="btn btn-primary">Search</button>
        @if (!empty($search))
          <a href="manage_users" class="btn btn-secondary">Clear</a>
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
          <a href="manage_users" class="btn btn-primary">Clear Search</a>
        @endif
      </div>
    @else

    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Full Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Superadmin</th>
          <th style="width: 220px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($users as $u)
          <tr>
            <td>{!! $u['id'] !!}</td>
            <td>{{ $u['full_name'] }}</td>
            <td>{{ $u['email'] }}</td>
            <td>
                @if ($u['role'] == 'admin')
                    <span class="badge bg-primary">Admin</span>
                @else
                  {{ (string)$u['role'] }}
                @endif
            </td>
            <td>{!! $u['is_superadmin'] ? '&#9989;' : '&#10060;' !!}</td>
            <td>
              <div class="d-flex gap-2">
                @if ($u['is_superadmin'])
                    <span class="badge bg-success">Superadmin</span>

                @else

                    @if ($u['role'] === 'user')
                        <form action="promote_user" method="POST">
                        @csrf
                            <input type="hidden" name="user_id" value="{!! $u['id'] !!}">
                            <button class="btn btn-sm btn-success w-100" type="submit">Promote</button>
                        </form>
                    @elseif ($u['role'] === 'admin')
                        <form action="demote_user" method="POST">
                        @csrf
                            <input type="hidden" name="user_id" value="{!! $u['id'] !!}">
                            <button class="btn btn-sm btn-warning w-100" type="submit">Demote</button>
                        </form>
                    @endif

                    <form action="delete_user" method="POST" onsubmit="return confirm('Are you sure you want to PERMANENTLY delete this user?');">
                      @csrf
                        <input type="hidden" name="user_id" value="{!! $u['id'] !!}">
                        <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                    </form>

                @endif
              </div>
            </td>
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
