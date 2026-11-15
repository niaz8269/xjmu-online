@extends('admin.layout')
@section('content')
<div class="card"><div class="card-body">

        @if ($error_message)
            <div class="alert alert-danger">{{ $error_message }}</div>
        @endif

        <form method="post" enctype="multipart/form-data" class="card p-4">
            @csrf
            <div class="mb-3">
                <label class="form-label">Title *</label>
                <input type="text" name="title" class="form-control" value="{{ $event['title'] ?? $post['title'] ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Slug (URL)</label>
                <input type="text" name="slug" class="form-control" value="{{ $event['slug'] ?? $post['slug'] ?? '' }}" placeholder="auto from title if empty">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Event date *</label>
                    <input type="date" name="event_date" class="form-control" value="{{ $event['event_date'] ?? $post['event_date'] ?? date('Y-m-d') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">End date (optional)</label>
                    <input type="date" name="event_end_date" class="form-control" value="{{ $event['event_end_date'] ?? $post['event_end_date'] ?? '' }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">From time (optional)</label>
                    <input type="time" name="event_time_start" class="form-control" value="{{ substr($event['event_time_start'] ?? $post['event_time_start'] ?? '', 0, 5) }}" placeholder="e.g. 14:00 for 2 PM">
                    <small class="text-muted">For webinars/seminars: start time</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">To time (optional)</label>
                    <input type="time" name="event_time_end" class="form-control" value="{{ substr($event['event_time_end'] ?? $post['event_time_end'] ?? '', 0, 5) }}" placeholder="e.g. 17:00 for 5 PM">
                    <small class="text-muted">End time</small>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Venue</label>
                <input type="text" name="venue" class="form-control" value="{{ $event['venue'] ?? $post['venue'] ?? '' }}" placeholder="e.g. Main Hall, Online">
            </div>
            <div class="mb-3">
                <label class="form-label">Excerpt (short description)</label>
                <textarea name="excerpt" class="form-control" rows="2">{{ $event['excerpt'] ?? $post['excerpt'] ?? '' }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Content (HTML allowed)</label>
                <textarea name="content" class="form-control" rows="8">{{ $event['content'] ?? $post['content'] ?? '' }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Featured image</label>
                <input type="file" name="featured_image" class="form-control" accept="image/*">
                @if ($editing && !empty($event['featured_image']))
                    <p class="small text-muted mt-1">Current: <a href="../{{ $event['featured_image'] }}" target="_blank" rel="noopener noreferrer">{{ $event['featured_image'] }}</a></p>
                @endif
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="draft" {!! ($event['status'] ?? '') === 'draft' ? 'selected' : '' !!}>Draft</option>
                    <option value="published" {!! ($event['status'] ?? '') === 'published' ? 'selected' : '' !!}>Published</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">{!! $editing ? 'Update' : 'Create' !!} Event</button>
        </form>
</div></div>

@endsection
