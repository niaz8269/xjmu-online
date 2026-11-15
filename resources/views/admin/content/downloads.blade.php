@extends('admin.layout')
@section('content')
@if ($flash)<div class="alert alert-info alert-dismissible fade show">{{ $flash }}<button class="btn-close" data-bs-dismiss="alert"></button></div>@endif

@if ($isForm)
    <div class="card"><div class="card-header bg-body"><h2 class="h6 mb-0">{{ $edit ? 'Edit' : 'Add' }} Download</h2></div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ (int) ($edit['id'] ?? 0) }}">
            <div class="row g-3">
                <div class="col-md-8"><label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required value="{{ $edit['title'] ?? '' }}"></div>
                <div class="col-md-4"><label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" placeholder="Forms, Handbook…" value="{{ $edit['category'] ?? '' }}"></div>
                <div class="col-12"><label class="form-label">Description</label>
                    <input type="text" name="description" class="form-control" value="{{ $edit['description'] ?? '' }}"></div>
                <div class="col-md-8"><label class="form-label">File (PDF/DOC) {{ $edit && $edit['file_path'] ? '(leave empty to keep current)' : '' }}</label>
                    <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx">
                    @if ($edit && $edit['file_path'])<div class="small mt-1"><a href="../{{ $edit['file_path'] }}" target="_blank">Current file</a></div>@endif</div>
                <div class="col-md-2"><label class="form-label">Sort</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ (int) ($edit['sort_order'] ?? 0) }}"></div>
                <div class="col-md-2 d-flex align-items-end"><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_published" id="pub" {{ (!$edit || $edit['is_published']) ? 'checked' : '' }}>
                    <label class="form-check-label" for="pub">Published</label></div></div>
            </div>
            <div class="mt-4"><button class="btn btn-xjmu px-4"><i class="bi bi-save me-2"></i>Save</button>
                <a href="manage-downloads" class="btn btn-outline-secondary">Cancel</a></div>
        </form>
    </div></div>
@else
    <div class="d-flex justify-content-end mb-3"><a href="manage-downloads?action=new" class="btn btn-xjmu"><i class="bi bi-plus-lg me-1"></i>Add Download</a></div>
    <div class="card"><div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Title</th><th>Category</th><th>File</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @if (!$rows)<tr><td colspan="5" class="text-center text-muted py-4">No downloads yet.</td></tr>
            @else
@foreach ($rows as $r)
                <tr>
                    <td class="fw-semibold">{{ $r['title'] }}</td>
                    <td class="small">{{ $r['category'] ?: '—' }}</td>
                    <td class="small"><a href="../{{ $r['file_path'] }}" target="_blank"><i class="bi bi-file-earmark-arrow-down"></i> view</a></td>
                    <td><span class="badge text-bg-{{ $r['is_published'] ? 'success' : 'secondary' }}">{{ $r['is_published'] ? 'Published' : 'Hidden' }}</span></td>
                    <td class="text-end">
                        <a href="manage-downloads?action=edit&id={{ (int) $r['id'] }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="manage-downloads" method="POST" class="d-inline" onsubmit="return confirm('Delete this download?')">
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
