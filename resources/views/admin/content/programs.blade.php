@extends('admin.layout')
@section('content')
@if ($flash)<div class="alert alert-info alert-dismissible fade show">{{ $flash }}<button class="btn-close" data-bs-dismiss="alert"></button></div>@endif

@if ($isForm)
    <div class="card"><div class="card-header bg-body"><h2 class="h6 mb-0">{{ $edit ? 'Edit' : 'Add' }} Program</h2></div>
    <div class="card-body">
        <form method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ (int) ($edit['id'] ?? 0) }}">
            <div class="row g-3">
                <div class="col-md-8"><label class="form-label">Program name</label>
                    <input type="text" name="name" class="form-control" required value="{{ $edit['name'] ?? '' }}"></div>
                <div class="col-md-4"><label class="form-label">Degree</label>
                    <input type="text" name="degree" class="form-control" placeholder="MBBS, BSc…" value="{{ $edit['degree'] ?? '' }}"></div>
                <div class="col-md-4"><label class="form-label">Category (filter tab)</label>
                    <select name="category" class="form-select">
                        @foreach (['bachelor', 'master', 'doctoral', 'non-degree', 'associate'] as $c)
                            <option value="{{ $c }}" {{ (($edit['category'] ?? 'bachelor') === $c) ? 'selected' : '' }}>{{ ucfirst($c) }}</option>
                        @endforeach
                    </select></div>
                <div class="col-md-4"><label class="form-label">Duration</label>
                    <input type="text" name="duration" class="form-control" placeholder="6 years" value="{{ $edit['duration'] ?? '' }}"></div>
                <div class="col-md-4"><label class="form-label">Language</label>
                    <input type="text" name="language" class="form-control" placeholder="English" value="{{ $edit['language'] ?? '' }}"></div>
                <div class="col-md-4"><label class="form-label">Tuition</label>
                    <input type="text" name="tuition" class="form-control" placeholder="$3,800 / year" value="{{ $edit['tuition'] ?? '' }}"></div>
                <div class="col-12"><label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ $edit['description'] ?? '' }}</textarea></div>
                <div class="col-md-3"><label class="form-label">Sort order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ (int) ($edit['sort_order'] ?? 0) }}"></div>
                <div class="col-md-3 d-flex align-items-end"><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_published" id="pub" {{ (!$edit || $edit['is_published']) ? 'checked' : '' }}>
                    <label class="form-check-label" for="pub">Published</label></div></div>
            </div>
            <div class="mt-4"><button class="btn btn-xjmu px-4"><i class="bi bi-save me-2"></i>Save</button>
                <a href="manage-programs" class="btn btn-outline-secondary">Cancel</a></div>
        </form>
    </div></div>
@else
    <div class="d-flex justify-content-end mb-3"><a href="manage-programs?action=new" class="btn btn-xjmu"><i class="bi bi-plus-lg me-1"></i>Add Program</a></div>
    <div class="card"><div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Name</th><th>Degree</th><th>Duration</th><th>Tuition</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @if (!$rows)<tr><td colspan="6" class="text-center text-muted py-4">No programs yet.</td></tr>
            @else
@foreach ($rows as $r)
                <tr>
                    <td class="fw-semibold">{{ $r['name'] }}</td>
                    <td>{{ $r['degree'] ?: '—' }}</td>
                    <td class="small">{{ $r['duration'] ?: '—' }}</td>
                    <td class="small">{{ $r['tuition'] ?: '—' }}</td>
                    <td><span class="badge text-bg-{{ $r['is_published'] ? 'success' : 'secondary' }}">{{ $r['is_published'] ? 'Published' : 'Hidden' }}</span></td>
                    <td class="text-end">
                        <a href="manage-programs?action=edit&id={{ (int) $r['id'] }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="manage-programs" method="POST" class="d-inline" onsubmit="return confirm('Delete this program?')">
                            @csrf<input type="hidden" name="_do" value="delete"><input type="hidden" name="id" value="{{ (int) $r['id'] }}">
                            <button class="btn btn-sm btn-outline-danger">Delete</button></form>
                    </td>
                </tr>
            @endforeach
@endif
            </tbody>
        </table>
    </div></div></div>
@endif
@endsection
