@extends('admin.layout')
@section('content')
@if ($flash)<div class="alert alert-info alert-dismissible fade show">{{ $flash }}<button class="btn-close" data-bs-dismiss="alert"></button></div>@endif

@if ($isForm)
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <div class="card"><div class="card-header bg-body"><h2 class="h6 mb-0">{{ $edit ? 'Edit' : 'Add' }} Section — {{ $pagesAvailable[$page_key] ?? '' }}</h2></div>
    <div class="card-body">
        <form method="POST" id="sectionForm">
            @csrf
            <input type="hidden" name="id" value="{{ (int) ($edit['id'] ?? 0) }}">
            <input type="hidden" name="page_key" value="{{ $page_key }}">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Heading (optional)</label>
                    <input type="text" name="heading" class="form-control" value="{{ $edit['heading'] ?? '' }}"></div>
                <div class="col-md-3"><label class="form-label">Section key</label>
                    <input type="text" name="section_key" class="form-control" placeholder="history" value="{{ $edit['section_key'] ?? '' }}"></div>
                <div class="col-md-3"><label class="form-label">Sort order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ (int) ($edit['sort_order'] ?? 0) }}"></div>
                <div class="col-12">
                    <label class="form-label">Content</label>
                    <div id="editor" style="min-height:240px;background:#fff;">{!! $edit['body'] ?? '' !!}</div>
                    <input type="hidden" name="body" id="bodyInput">
                </div>
                <div class="col-12"><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_published" id="pub" {{ (!$edit || $edit['is_published']) ? 'checked' : '' }}>
                    <label class="form-check-label" for="pub">Published</label></div></div>
            </div>
            <div class="mt-4"><button type="submit" class="btn btn-xjmu px-4"><i class="bi bi-save me-2"></i>Save</button>
                <a href="manage-pages?page={{ $page_key }}" class="btn btn-outline-secondary">Cancel</a></div>
        </form>
    </div></div>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script>
        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: { toolbar: [
                [{ header: [2, 3, false] }],
                ['bold', 'italic', 'underline', 'link'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['blockquote'], ['clean']
            ]}
        });
        document.getElementById('sectionForm').addEventListener('submit', function () {
            document.getElementById('bodyInput').value = quill.root.innerHTML;
        });
    </script>
@else
    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
        <ul class="nav nav-pills">
            @foreach ($pagesAvailable as $pk => $pl)
                <li class="nav-item"><a class="nav-link{{ $pk === $page_key ? ' active' : '' }}" href="manage-pages?page={{ $pk }}">{{ $pl }}</a></li>
            @endforeach
        </ul>
        <a href="manage-pages?action=new&page={{ $page_key }}" class="btn btn-xjmu"><i class="bi bi-plus-lg me-1"></i>Add Section</a>
    </div>
    <div class="card"><div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Heading</th><th>Section key</th><th>Order</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @if (!$rows)<tr><td colspan="5" class="text-center text-muted py-4">No sections for this page yet. Click “Add Section”.</td></tr>
            @else
@foreach ($rows as $r)
                <tr>
                    <td class="fw-semibold">{{ $r['heading'] ?: '(no heading)' }}</td>
                    <td class="small text-muted">{{ $r['section_key'] }}</td>
                    <td>{{ (int) $r['sort_order'] }}</td>
                    <td><span class="badge text-bg-{{ $r['is_published'] ? 'success' : 'secondary' }}">{{ $r['is_published'] ? 'Published' : 'Hidden' }}</span></td>
                    <td class="text-end">
                        <a href="manage-pages?action=edit&id={{ (int) $r['id'] }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="manage-pages" method="POST" class="d-inline" onsubmit="return confirm('Delete this section?')">
                            @csrf<input type="hidden" name="_do" value="delete"><input type="hidden" name="page_key" value="{{ $page_key }}"><input type="hidden" name="id" value="{{ (int) $r['id'] }}">
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
