@extends('admin.layout')
@section('content')

@if (!empty($flash))
  <div class="alert alert-success alert-dismissible fade show" role="alert">{{ $flash }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
@endif

<div class="alert alert-info small">
  Leave a box empty to keep the built-in text. Aim for a title under 60 characters that starts with the main search phrase, and a description under 160 characters.
  Guides have their own title and description under <a href="guides">Guides</a>.
</div>

<form method="post">
  @csrf
  @foreach ($pages as $i => $p)
    <div class="card mb-3"><div class="card-body">
      <div class="d-flex align-items-center mb-2">
        <h2 class="h6 mb-0">{{ $p['label'] }}</h2>
        <a href="..{{ $p['path'] === '/' ? '/' : $p['path'] }}" target="_blank" rel="noopener" class="small ms-2">{{ $p['path'] }} <i class="bi bi-box-arrow-up-right"></i></a>
      </div>
      <div class="row g-2">
        <div class="col-lg-5">
          <label class="form-label small mb-1" for="t{{ $i }}">Title <span class="text-muted" data-count="t{{ $i }}"></span></label>
          <input id="t{{ $i }}" name="title[{{ $i }}]" class="form-control form-control-sm" maxlength="120" value="{{ $p['value']['title'] ?? '' }}" placeholder="{{ $p['builtTitle'] ?: 'Built-in title' }}">
        </div>
        <div class="col-lg-7">
          <label class="form-label small mb-1" for="d{{ $i }}">Description <span class="text-muted" data-count="d{{ $i }}"></span></label>
          <input id="d{{ $i }}" name="description[{{ $i }}]" class="form-control form-control-sm" maxlength="320" value="{{ $p['value']['description'] ?? '' }}" placeholder="{{ $p['builtDescription'] ?: 'Built-in description' }}">
        </div>
        <div class="col-12">
          <label class="form-label small mb-1" for="g{{ $i }}">Share image (optional, full https address)</label>
          <input id="g{{ $i }}" name="image[{{ $i }}]" type="url" class="form-control form-control-sm" maxlength="300" value="{{ $p['value']['image'] ?? '' }}" placeholder="https://xjmu.online/images/logo.png">
        </div>
      </div>
    </div></div>
  @endforeach
  <button class="btn btn-primary" type="submit">Save all</button>
</form>

@push('scripts')
<script>
  document.querySelectorAll('[data-count]').forEach(function (out) {
    var input = document.getElementById(out.getAttribute('data-count'));
    var show = function () { out.textContent = input.value.length ? '(' + input.value.length + ' characters)' : ''; };
    input.addEventListener('input', show); show();
  });
</script>
@endpush
@endsection
