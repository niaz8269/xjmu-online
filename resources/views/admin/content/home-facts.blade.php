@extends('admin.layout')
@section('content')
@if ($flash)<div class="alert alert-info alert-dismissible fade show">{{ $flash }}<button class="btn-close" data-bs-dismiss="alert"></button></div>@endif
@if ($isForm)
    <div class="card"><div class="card-header bg-body"><h2 class="h6 mb-0">{{ $edit ? 'Edit' : 'Add' }} Fact</h2></div>
    <div class="card-body"><form method="POST">@csrf
        <input type="hidden" name="id" value="{{ (int) ($edit['id'] ?? 0) }}">
        <div class="row g-3">
            <div class="col-md-5"><label class="form-label">Label</label><input type="text" name="label" class="form-control" required placeholder="Duration" value="{{ $edit['label'] ?? '' }}"></div>
            <div class="col-md-7"><label class="form-label">Value</label><input type="text" name="value" class="form-control" placeholder="6 years (incl. 1-yr internship)" value="{{ $edit['value'] ?? '' }}"></div>
            <div class="col-md-3"><label class="form-label">Sort order</label><input type="number" name="sort_order" class="form-control" value="{{ (int) ($edit['sort_order'] ?? 0) }}"></div>
            <div class="col-md-3 d-flex align-items-end"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="is_published" id="pub" {{ (!$edit || $edit['is_published']) ? 'checked' : '' }}><label class="form-check-label" for="pub">Published</label></div></div>
        </div>
        <div class="mt-4"><button class="btn btn-xjmu px-4"><i class="bi bi-save me-2"></i>Save</button> <a href="manage-home-facts" class="btn btn-outline-secondary">Cancel</a></div>
    </form></div></div>
@else
    <div class="d-flex justify-content-end mb-3"><a href="manage-home-facts?action=new" class="btn btn-xjmu"><i class="bi bi-plus-lg me-1"></i>Add Fact</a></div>
    <div class="card"><div class="card-body p-0"><div class="table-responsive"><table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>Label</th><th>Value</th><th>Status</th><th></th></tr></thead><tbody>
        @if (!$rows)<tr><td colspan="4" class="text-center text-muted py-4">No facts yet.</td></tr>
        @else
@foreach ($rows as $r)
            <tr><td class="fw-semibold">{{ $r['label'] }}</td><td class="small">{{ $r['value'] ?? '' }}</td>
            <td><span class="badge text-bg-{{ $r['is_published'] ? 'success' : 'secondary' }}">{{ $r['is_published'] ? 'Published' : 'Hidden' }}</span></td>
            <td class="text-end"><a href="manage-home-facts?action=edit&id={{ (int) $r['id'] }}" class="btn btn-sm btn-outline-primary">Edit</a>
                <form action="manage-home-facts" method="POST" class="d-inline" onsubmit="return confirm('Delete this fact?')">@csrf<input type="hidden" name="_do" value="delete"><input type="hidden" name="id" value="{{ (int) $r['id'] }}"><button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>
        @endforeach
@endif
        </tbody></table></div></div></div>
@endif
@endsection
