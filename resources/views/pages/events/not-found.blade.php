@extends('layouts.site')
@section('content')
<div class="container ev-404"><h1>404</h1><p>Event not found or has been removed.</p><a href="{{ get_absolute_url('events') }}" class="ds-btn ds-btn--primary">&larr; Back to Events</a></div>
@endsection
