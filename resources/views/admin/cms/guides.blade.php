@extends('admin.layout')
@section('content')

@if (!empty($flash))
  <div class="alert alert-success alert-dismissible fade show" role="alert">{{ $flash }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
@endif

<div class="card"><div class="card-body">
  <p class="small text-muted">Each guide is a long page written for one group of Google searches. You can edit its title, description, quick answer, key facts, section text, image captions, tables and FAQs. Lists and charts stay as they are.</p>
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>Guide</th><th>Address</th><th>FAQs</th><th>Updated</th><th></th><th></th></tr></thead>
      <tbody>
        @foreach ($guides as $g)
          <tr>
            <td><a href="guide-edit?slug={{ $g['slug'] }}" class="fw-semibold text-decoration-none">{{ $g['h1'] }}</a><div class="small text-muted">{{ $g['title'] }}</div></td>
            <td class="small"><a href="../{{ $g['slug'] }}" target="_blank" rel="noopener">/{{ $g['slug'] }}</a></td>
            <td class="small">{{ $g['faqs'] }}</td>
            <td class="small text-nowrap">{{ $g['updated'] }}</td>
            <td>@if ($g['edited'])<span class="badge text-bg-info">Edited</span>@endif</td>
            <td class="text-end"><a href="guide-edit?slug={{ $g['slug'] }}" class="btn btn-sm btn-outline-primary">Edit</a></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div></div>

@endsection
