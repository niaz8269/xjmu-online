@extends('admin.layout')
@section('content')
@if ($flash)<div class="alert alert-info alert-dismissible fade show">{{ $flash }}<button class="btn-close" data-bs-dismiss="alert"></button></div>@endif

@if ($isForm)
    <div class="card"><div class="card-header bg-body"><h2 class="h6 mb-0">{{ $edit ? 'Edit' : 'Add' }} Testimonial</h2></div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ (int) ($edit['id'] ?? 0) }}">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required value="{{ $edit['name'] ?? '' }}"></div>
                <div class="col-md-6"><label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="student" {{ (($edit['category'] ?? 'student') === 'student') ? 'selected' : '' }}>Recently Admitted Student</option>
                        <option value="alumni"  {{ (($edit['category'] ?? '') === 'alumni') ? 'selected' : '' }}>Distinguished Alumni</option>
                    </select></div>
                <div class="col-md-6"><label class="form-label">Role / Batch</label>
                    <input type="text" name="role" class="form-control" placeholder="MBBS Program | Batch 2024" value="{{ $edit['role'] ?? '' }}"></div>
                <div class="col-md-6"><label class="form-label">Location / Position</label>
                    <input type="text" name="location" class="form-control" placeholder="Beijing, China" value="{{ $edit['location'] ?? '' }}"></div>
                <div class="col-12"><label class="form-label">Quote</label>
                    <textarea name="quote" class="form-control" rows="3" required>{{ $edit['quote'] ?? '' }}</textarea></div>
                <div class="col-md-6"><label class="form-label">Photo / Avatar {{ $edit && !empty($edit['photo']) ? '(leave empty to keep current)' : '' }}</label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                    @if ($edit && !empty($edit['photo']))<img src="../{{ $edit['photo'] }}" class="mt-2 rounded" style="height:60px">@endif</div>
                <div class="col-md-3"><label class="form-label">Sort order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ (int) ($edit['sort_order'] ?? 0) }}"></div>
                <div class="col-md-3 d-flex align-items-end"><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_published" id="pub" {{ (!$edit || $edit['is_published']) ? 'checked' : '' }}>
                    <label class="form-check-label" for="pub">Published</label></div></div>
            </div>
            <div class="mt-4"><button class="btn btn-xjmu px-4"><i class="bi bi-save me-2"></i>Save</button>
                <a href="manage-testimonials" class="btn btn-outline-secondary">Cancel</a></div>
        </form>
    </div></div>
@else
    <div class="alert alert-primary">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div><i class="bi bi-link-45deg me-1"></i><strong>Public submission link</strong> — share with students &amp; alumni. Their entries arrive here as <span class="badge text-bg-secondary">Hidden</span> for your review, then publish them.</div>
        </div>
        <div class="input-group mt-2" style="max-width:520px">
            <input type="text" class="form-control" id="shareLink" value="{{ $shareUrl }}" readonly onclick="this.select()">
            <a href="{{ $shareUrl }}" target="_blank" rel="noopener" class="btn btn-outline-secondary"><i class="bi bi-box-arrow-up-right"></i></a>
            <button type="button" class="btn btn-outline-secondary" onclick="navigator.clipboard&&navigator.clipboard.writeText(document.getElementById('shareLink').value);this.innerHTML='<i class=\'bi bi-check2\'></i> Copied';"><i class="bi bi-clipboard"></i> Copy</button>
        </div>
    </div>
    <!-- Submission summary -->
    <div class="row g-3 mb-3">
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm h-100"><div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning-subtle text-warning-emphasis d-flex align-items-center justify-content-center" style="width:48px;height:48px"><i class="bi bi-hourglass-split fs-4"></i></div>
                <div><div class="fs-3 fw-bold lh-1">{{ $counts['pending'] }}</div><div class="text-muted small">Pending review</div></div>
            </div></div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm h-100"><div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success-subtle text-success-emphasis d-flex align-items-center justify-content-center" style="width:48px;height:48px"><i class="bi bi-check2-circle fs-4"></i></div>
                <div><div class="fs-3 fw-bold lh-1">{{ $counts['published'] }}</div><div class="text-muted small">Published (live)</div></div>
            </div></div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm h-100"><div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary-subtle text-primary-emphasis d-flex align-items-center justify-content-center" style="width:48px;height:48px"><i class="bi bi-people fs-4"></i></div>
                <div><div class="fs-3 fw-bold lh-1">{{ $counts['total'] }}</div><div class="text-muted small">Total submissions</div></div>
            </div></div>
        </div>
    </div>

    <!-- Status filter + Add -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link {{ $filter === 'all' ? 'active' : '' }}" href="manage-testimonials">All <span class="badge text-bg-light ms-1">{{ $counts['total'] }}</span></a></li>
            <li class="nav-item"><a class="nav-link {{ $filter === 'pending' ? 'active' : '' }}" href="manage-testimonials?filter=pending">Pending <span class="badge text-bg-warning ms-1">{{ $counts['pending'] }}</span></a></li>
            <li class="nav-item"><a class="nav-link {{ $filter === 'published' ? 'active' : '' }}" href="manage-testimonials?filter=published">Published <span class="badge text-bg-success ms-1">{{ $counts['published'] }}</span></a></li>
        </ul>
        <a href="manage-testimonials?action=new" class="btn btn-xjmu"><i class="bi bi-plus-lg me-1"></i>Add Testimonial</a>
    </div>

    <div class="card"><div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th style="width:32px"></th><th></th><th>Submitter</th><th>Category</th><th>Testimonial</th><th>Submitted</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody id="ttbody">
            @if (!$rows)<tr><td colspan="8" class="text-center text-muted py-4">No {!! $filter === 'all' ? '' : e($filter) . ' ' !!}testimonials.</td></tr>
            @else
@foreach ($rows as $r)
                @php
                $quoteFull = (string) $r['quote'];
                $quotePrev = mb_strimwidth($quoteFull, 0, 90, '…');
                $meta = trim(($r['role'] ?? '') . ($r['role'] && $r['location'] ? ' · ' : '') . ($r['location'] ?? ''));
                @endphp
                <tr data-id="{{ (int) $r['id'] }}">
                    <td class="drag-handle text-muted" style="cursor:grab" title="Drag to reorder"><i class="bi bi-grip-vertical"></i></td>
                    <td>@if (!empty($r['photo']))<img src="../{{ $r['photo'] }}" class="rounded-circle" style="width:38px;height:38px;object-fit:cover">@else<i class="bi bi-person-circle fs-3 text-muted"></i>@endif</td>
                    <td><div class="fw-semibold">{{ $r['name'] }}</div>
                        @if ($meta !== '')<div class="text-muted small">{{ $meta }}</div>@endif</td>
                    <td><span class="badge text-bg-{{ $r['category'] === 'alumni' ? 'primary' : 'info' }}">{{ $r['category'] === 'alumni' ? 'Alumni' : 'Student' }}</span></td>
                    <td class="small" style="max-width:280px"><span title="{{ $quoteFull }}">{{ $quotePrev }}</span></td>
                    <td class="small text-muted text-nowrap">{!! !empty($r['created_at']) ? e(date('d M Y', strtotime($r['created_at']))) : '—' !!}</td>
                    <td><span class="badge text-bg-{{ $r['is_published'] ? 'success' : 'warning' }}">{{ $r['is_published'] ? 'Published' : 'Pending' }}</span></td>
                    <td class="text-end text-nowrap">
                        @if (!$r['is_published'])
                            <form action="manage-testimonials" method="POST" class="d-inline">
                                @csrf<input type="hidden" name="_do" value="approve"><input type="hidden" name="id" value="{{ (int) $r['id'] }}">
                                <button class="btn btn-sm btn-success" title="Approve & publish"><i class="bi bi-check-lg me-1"></i>Approve</button></form>
                        @else
                            <form action="manage-testimonials" method="POST" class="d-inline">
                                @csrf<input type="hidden" name="_do" value="hide"><input type="hidden" name="id" value="{{ (int) $r['id'] }}">
                                <button class="btn btn-sm btn-outline-secondary" title="Hide from website"><i class="bi bi-eye-slash me-1"></i>Hide</button></form>
                        @endif
                        <a href="manage-testimonials?action=edit&id={{ (int) $r['id'] }}" class="btn btn-sm btn-outline-primary" title="Verify / modify details"><i class="bi bi-pencil me-1"></i>Edit</a>
                        <form action="manage-testimonials" method="POST" class="d-inline" onsubmit="return confirm('Delete this testimonial permanently?')">
                            @csrf<input type="hidden" name="_do" value="delete"><input type="hidden" name="id" value="{{ (int) $r['id'] }}">
                            <button class="btn btn-sm btn-outline-danger" title="Reject / delete permanently"><i class="bi bi-trash"></i></button></form>
                    </td>
                </tr>
            @endforeach
@endif
            </tbody>
        </table>
    </div></div></div>
    <p class="text-muted small mt-2"><i class="bi bi-grip-vertical me-1"></i>Drag the grip handle to reorder how testimonials appear on the website — the new order saves automatically. (Reorder from the <strong>All</strong> or <strong>Published</strong> view for the public display order.)</p>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <script>
    (function () {
        var tb = document.getElementById('ttbody');
        if (!tb || !window.Sortable) return;
        var CSRF = {!! json_encode(csrf_token()) !!};
        new Sortable(tb, {
            handle: '.drag-handle', animation: 150, ghostClass: 'table-active',
            onEnd: function () {
                var body = new URLSearchParams();
                body.append('_do', 'reorder');
                body.append('_token', CSRF);
                tb.querySelectorAll('tr[data-id]').forEach(function (tr) { body.append('ids[]', tr.getAttribute('data-id')); });
                fetch('manage-testimonials', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: body })
                    .then(function (r) { return r.json(); })
                    .catch(function () { alert('Could not save the new order — please refresh and try again.'); });
            }
        });
    })();
    </script>
@endif
@endsection
