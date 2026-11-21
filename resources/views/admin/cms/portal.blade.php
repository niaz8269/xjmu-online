@extends('admin.layout')
@section('content')

@if (!empty($flash))
  <div class="alert alert-success alert-dismissible fade show" role="alert">{{ $flash }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
@endif

@php
  $labels = [
      'notice' => ['Notice at the top of the dashboard', 'Optional. For example a deadline reminder. Leave empty to show nothing.'],
      'dashboard_intro' => ['Dashboard introduction', 'The line under "Your application".'],
      'form_intro' => ['Application form introduction', 'The line under "My application".'],
      'file_hint' => ['Accepted files and sizes', 'Shown beside the document uploads. Changing this text does not change the real upload limits.'],
      'download_note' => ['Note shown after submission', 'Shown under the download buttons.'],
      'copyright' => ['Footer copyright line', 'Shown at the bottom of the portal pages.'],
  ];
@endphp

<div class="card"><div class="card-body">
  <p class="small text-muted">Phone numbers, WhatsApp and email shown in the portal come from <a href="settings">Site Settings → Contact Details</a>.</p>
  <form method="post">
    @csrf
    @foreach ($fields as $field => $f)
      <div class="mb-3">
        <label class="form-label mb-1" for="{{ $field }}">{{ $labels[$field][0] }}</label>
        <textarea id="{{ $field }}" name="{{ $field }}" class="form-control" rows="2" maxlength="1000" placeholder="{{ $f['default'] }}">{{ $f['value'] }}</textarea>
        <div class="form-text">{{ $labels[$field][1] }}</div>
      </div>
    @endforeach
    <button class="btn btn-primary" type="submit">Save</button>
  </form>
</div></div>

@endsection
