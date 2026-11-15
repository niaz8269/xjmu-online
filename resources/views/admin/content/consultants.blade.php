@extends('admin.layout')
@section('content')
@if ($flash)<div class="alert alert-info alert-dismissible fade show">{{ $flash }}<button class="btn-close" data-bs-dismiss="alert"></button></div>@endif

@if ($isForm)
    <div class="card"><div class="card-header bg-body"><h2 class="h6 mb-0">{{ $edit ? 'Edit' : 'Add' }} Consultant</h2></div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ (int) ($edit['id'] ?? 0) }}">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required value="{{ $edit['name'] ?? '' }}"></div>
                <div class="col-md-6"><label class="form-label">Role / Title</label>
                    <input type="text" name="role" class="form-control" placeholder="Admissions Counsellor" value="{{ $edit['role'] ?? '' }}"></div>
                <div class="col-md-4"><label class="form-label">WhatsApp (digits)</label>
                    <input type="text" name="whatsapp" class="form-control" placeholder="8617590820634" value="{{ $edit['whatsapp'] ?? '' }}"></div>
                <div class="col-md-4"><label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ $edit['phone'] ?? '' }}"></div>
                <div class="col-md-4"><label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $edit['email'] ?? '' }}"></div>
                <div class="col-md-4"><label class="form-label">WeChat ID</label>
                    <input type="text" name="wechat" class="form-control" value="{{ $edit['wechat'] ?? '' }}"></div>
                <div class="col-md-6"><label class="form-label">Photo / Avatar {{ $edit && $edit['photo'] ? '(leave empty to keep current)' : '' }}</label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                    @if ($edit && $edit['photo'])<img src="../{{ $edit['photo'] }}" class="mt-2 rounded" style="height:60px">@endif</div>
                <div class="col-md-6"><label class="form-label">QR Code image {{ $edit && !empty($edit['qr_image']) ? '(leave empty to keep current)' : '' }}</label>
                    <input type="file" name="qr_image" class="form-control" accept="image/*">
                    @if ($edit && !empty($edit['qr_image']))<img src="../{{ $edit['qr_image'] }}" class="mt-2 rounded" style="height:60px">@endif</div>
                <div class="col-md-3"><label class="form-label">Sort order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ (int) ($edit['sort_order'] ?? 0) }}"></div>
                <div class="col-md-3 d-flex align-items-end"><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_published" id="pub" {{ (!$edit || $edit['is_published']) ? 'checked' : '' }}>
                    <label class="form-check-label" for="pub">Published</label></div></div>
            </div>
            <div class="mt-4"><button class="btn btn-xjmu px-4"><i class="bi bi-save me-2"></i>Save</button>
                <a href="manage-consultants" class="btn btn-outline-secondary">Cancel</a></div>
        </form>
    </div></div>
@else
    <div class="d-flex justify-content-end mb-3"><a href="manage-consultants?action=new" class="btn btn-xjmu"><i class="bi bi-plus-lg me-1"></i>Add Consultant</a></div>
    <div class="card"><div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th></th><th>Name</th><th>Role</th><th>WhatsApp</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @if (!$rows)<tr><td colspan="6" class="text-center text-muted py-4">No consultants yet.</td></tr>
            @else
@foreach ($rows as $r)
                <tr>
                    <td>@if ($r['photo'])<img src="../{{ $r['photo'] }}" class="rounded-circle" style="width:38px;height:38px;object-fit:cover">@else<i class="bi bi-person-circle fs-3 text-muted"></i>@endif</td>
                    <td class="fw-semibold">{{ $r['name'] }}</td>
                    <td class="small">{{ $r['role'] ?: '—' }}</td>
                    <td class="small">{{ $r['whatsapp'] ?: '—' }}</td>
                    <td><span class="badge text-bg-{{ $r['is_published'] ? 'success' : 'secondary' }}">{{ $r['is_published'] ? 'Published' : 'Hidden' }}</span></td>
                    <td class="text-end">
                        <a href="manage-consultants?action=edit&id={{ (int) $r['id'] }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="manage-consultants" method="POST" class="d-inline" onsubmit="return confirm('Delete this consultant?')">
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
