@extends('admin.layout')
@section('content')
@if ($flash)<div class="alert alert-info alert-dismissible fade show">{{ $flash }}<button class="btn-close" data-bs-dismiss="alert"></button></div>@endif

@if ($isForm)
    <div class="card"><div class="card-header bg-body"><h2 class="h6 mb-0">{{ $edit ? 'Edit' : 'Add' }} Image</h2></div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ (int) ($edit['id'] ?? 0) }}">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Title / Caption</label>
                    <input type="text" name="title" class="form-control" value="{{ $edit['title'] ?? '' }}"></div>
                <div class="col-md-6"><label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" placeholder="Campus, Events…" value="{{ $edit['category'] ?? '' }}"></div>
                <div class="col-md-8"><label class="form-label">Image {{ $edit && $edit['image_path'] ? '(leave empty to keep current)' : '' }}</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    @if ($edit && $edit['image_path'])<img src="../{{ $edit['image_path'] }}" class="mt-2 rounded" style="height:70px">@endif</div>
                <div class="col-md-2"><label class="form-label">Sort</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ (int) ($edit['sort_order'] ?? 0) }}"></div>
                <div class="col-md-2 d-flex align-items-end"><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_published" id="pub" {{ (!$edit || $edit['is_published']) ? 'checked' : '' }}>
                    <label class="form-check-label" for="pub">Published</label></div></div>
            </div>
            <div class="mt-4"><button class="btn btn-xjmu px-4"><i class="bi bi-save me-2"></i>Save</button>
                <a href="manage-gallery" class="btn btn-outline-secondary">Cancel</a></div>
        </form>
    </div></div>
@else
    <div class="d-flex justify-content-end mb-3"><a href="manage-gallery?action=new" class="btn btn-xjmu"><i class="bi bi-plus-lg me-1"></i>Add Image</a></div>
    <div class="row g-3">
        @if (!$rows)<div class="col-12"><div class="card"><div class="card-body text-center text-muted py-5">No images yet.</div></div></div>
        @else
@foreach ($rows as $r)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100">
                    <img src="../{{ $r['image_path'] }}" class="card-img-top" style="height:150px;object-fit:cover" alt="{{ $r['title'] }}">
                    <div class="card-body p-2">
                        <div class="small fw-semibold text-truncate">{{ $r['title'] ?: '—' }}</div>
                        <div class="small text-muted">{{ $r['category'] ?: '' }}
                            <span class="badge text-bg-{{ $r['is_published'] ? 'success' : 'secondary' }} ms-1">{{ $r['is_published'] ? 'On' : 'Off' }}</span></div>
                    </div>
                    <div class="card-footer p-2 d-flex justify-content-between bg-body">
                        <a href="manage-gallery?action=edit&id={{ (int) $r['id'] }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="manage-gallery" method="POST" onsubmit="return confirm('Delete this image?')">
                            @csrf<input type="hidden" name="_do" value="delete"><input type="hidden" name="id" value="{{ (int) $r['id'] }}">
                            <button class="btn btn-sm btn-outline-danger">Delete</button></form>
                    </div>
                </div>
            </div>
        @endforeach
@endif
    </div>
@endif
@endsection
