@extends('layouts.site')
@section('content')
<div class="container bp-404"><h1>404</h1><p>Blog post not found or has been removed.</p><a href="{{ get_absolute_url('blogs') }}" class="ds-btn ds-btn--primary">&larr; Back to Blogs</a></div>
@endsection
