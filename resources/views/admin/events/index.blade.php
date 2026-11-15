@extends('admin.layout')
@section('content')
<div class="card"><div class="card-body">
    <div class="d-flex justify-content-end mb-3">
        <a href="edit-event" class="btn btn-primary">Add Event</a>
    </div>

    @if (!is_null($event_message))
        <div class="alert alert-success">{{ $event_message }}</div>
    @endif

    @if (empty($events))
        <div class="card">
            <div class="card-body text-center py-5">
                <p class="text-muted">No events yet.</p>
                <p class="small text-muted">Run <code>install_events_table.sql</code> in phpMyAdmin if you haven't.</p>
                <a href="edit-event" class="btn btn-primary mt-2">Add first event</a>
            </div>
        </div>
    @else
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Venue</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $fmt_time = function($start, $end) {
                            if (empty($start)) return '&mdash;';
                            $s = date('g:i A', strtotime('1970-01-01 ' . $start));
                            if (!empty($end) && $end !== $start) $s .= ' &ndash; ' . date('g:i A', strtotime('1970-01-01 ' . $end));
                            return $s;
                        };
                        @endphp
                        @foreach ($events as $ev)
                            <tr>
                                <td><strong>{{ $ev['title'] }}</strong></td>
                                <td>{!! date('M j, Y', strtotime($ev['event_date'])) !!}</td>
                                <td>{!! $fmt_time($ev['event_time_start'] ?? '', $ev['event_time_end'] ?? null) !!}</td>
                                <td>{{ $ev['venue'] ?? '—' }}</td>
                                <td><span class="badge bg-{!! $ev['status'] === 'published' ? 'success' : 'secondary' !!}">{{ $ev['status'] }}</span></td>
                                <td>
                                    <a href="edit-event?id={!! (int)$ev['id'] !!}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <a href="../event?slug={!! urlencode($ev['slug']) !!}" class="btn btn-sm btn-outline-info" target="_blank" rel="noopener noreferrer">View</a>
                                    <form action="delete-event" method="POST" class="d-inline" onsubmit="return confirm('Delete this event?');">
                                        @csrf
                                        <input type="hidden" name="id" value="{!! (int)$ev['id'] !!}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div></div>

@endsection
