@extends('admin.layout')
@section('content')

@if (!empty($flash))
  <div class="alert alert-success alert-dismissible fade show" role="alert">{{ $flash }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
@endif

<div class="d-flex flex-wrap gap-2 mb-3">
  @foreach ($pages as $key => $label)
    <a href="page-faqs?page={{ $key }}" class="btn btn-sm {{ $page === $key ? 'btn-primary' : 'btn-outline-secondary' }}">{{ $label }}</a>
  @endforeach
</div>

<div class="card"><div class="card-body">
  <p class="small text-muted">
    These questions appear in the FAQ section of the <strong>{{ $pages[$page] }}</strong> page and are marked up for Google and AI answers.
    Keep each answer short and factual. {{ $edited ? 'You have edited this list.' : 'This is the built-in list.' }}
    @if (in_array($page, ['home', 'mbbs'], true)) FAQs added under Content → FAQs or the MBBS settings are shown first, before these. @endif
  </p>

  <form method="post">
    @csrf
    @include('admin.cms._pairs', ['rows' => $faqs, 'first' => 'q', 'second' => 'a', 'firstLabel' => 'Question', 'secondLabel' => 'Answer', 'addLabel' => 'Add a question', 'secondRows' => 3])
    <div class="mt-3"><button class="btn btn-primary" type="submit">Save FAQs</button></div>
  </form>

  @if ($edited)
    <form method="post" class="mt-3" onsubmit="return confirm('Replace your edited FAQs for this page with the built-in list?');">
      @csrf
      <input type="hidden" name="action" value="reset">
      <button class="btn btn-sm btn-outline-secondary" type="submit">Restore the built-in FAQs</button>
    </form>
  @endif
</div></div>

@endsection
