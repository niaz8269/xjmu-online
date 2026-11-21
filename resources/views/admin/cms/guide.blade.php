@extends('admin.layout')
@section('content')

@if (!empty($flash))
  <div class="alert alert-success alert-dismissible fade show" role="alert">{{ $flash }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
@endif

<p class="d-flex gap-3">
  <a href="guides" class="text-decoration-none">← All guides</a>
  <a href="../{{ $slug }}" target="_blank" rel="noopener">View the page <i class="bi bi-box-arrow-up-right"></i></a>
</p>

<form method="post">
  @csrf

  <div class="card mb-3"><div class="card-body">
    <h2 class="h6 text-uppercase text-muted mb-3">Search result</h2>
    <div class="mb-2">
      <label class="form-label small mb-1" for="title">Title (under 70 characters, main phrase first) <span class="text-muted" data-count="title"></span></label>
      <input id="title" name="title" class="form-control" maxlength="120" value="{{ $guide['title'] }}">
    </div>
    <div class="mb-2">
      <label class="form-label small mb-1" for="description">Description (under 170 characters) <span class="text-muted" data-count="description"></span></label>
      <textarea id="description" name="description" class="form-control" rows="2" maxlength="320">{{ $guide['description'] }}</textarea>
    </div>
    <div class="row g-2">
      <div class="col-md-8"><label class="form-label small mb-1" for="h1">Page heading</label><input id="h1" name="h1" class="form-control" maxlength="200" value="{{ $guide['h1'] }}"></div>
      <div class="col-md-4"><label class="form-label small mb-1" for="updated">"Updated" date shown on the page</label><input id="updated" type="date" name="updated" class="form-control" value="{{ $guide['updated'] }}"></div>
    </div>
    <div class="mt-2">
      <label class="form-label small mb-1" for="lead">Introduction under the heading</label>
      <textarea id="lead" name="lead" class="form-control" rows="3" maxlength="1000">{{ $guide['lead'] }}</textarea>
    </div>
  </div></div>

  <div class="card mb-3"><div class="card-body">
    <h2 class="h6 text-uppercase text-muted mb-3">Quick answer and key facts</h2>
    <label class="form-label small mb-1" for="answer">Quick answer (40 to 60 words; this is what Google and AI answers are most likely to quote) <span class="text-muted" data-words="answer"></span></label>
    <textarea id="answer" name="answer" class="form-control mb-3" rows="4" maxlength="1000">{{ $guide['answer'] ?? '' }}</textarea>
    <p class="form-label small mb-1">Key facts table</p>
    @include('admin.cms._pairs', ['rows' => $guide['facts'] ?? [], 'first' => 'fact_label', 'second' => 'fact_value', 'firstLabel' => 'Label, e.g. Duration', 'secondLabel' => 'Value', 'addLabel' => 'Add a fact', 'secondRows' => 1])
  </div></div>

  <div class="card mb-3"><div class="card-body">
    <h2 class="h6 text-uppercase text-muted mb-1">Sections</h2>
    <p class="small text-muted">Leave a blank line between paragraphs. Lists and charts in a section are kept as they are.</p>
    <div class="accordion" id="secs">
      @foreach ($guide['sections'] as $i => $sec)
        <div class="accordion-item">
          <h3 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sec{{ $i }}">{{ $i + 1 }}. {{ $sec['h'] }}</button></h3>
          <div id="sec{{ $i }}" class="accordion-collapse collapse" data-bs-parent="#secs"><div class="accordion-body">
            <label class="form-label small mb-1" for="sh{{ $i }}">Section heading</label>
            <input id="sh{{ $i }}" name="sec[{{ $i }}][h]" class="form-control form-control-sm mb-2" maxlength="200" value="{{ $sec['h'] }}">
            <label class="form-label small mb-1" for="sp{{ $i }}">Text</label>
            <textarea id="sp{{ $i }}" name="sec[{{ $i }}][p]" class="form-control form-control-sm" rows="{{ min(14, max(4, count($sec['p'] ?? []) * 3)) }}">{{ implode("\n\n", $sec['p'] ?? []) }}</textarea>
            @if (!empty($sec['img']))
              <div class="border rounded-3 p-2 mt-3">
                <div class="d-flex gap-3 align-items-start">
                  <img src="../{{ $sec['img']['src'] }}" alt="" style="width:120px;height:80px;object-fit:cover;border-radius:8px">
                  <div class="flex-grow-1">
                    <label class="form-label small mb-1" for="ic{{ $i }}">Image caption (shown under the picture)</label>
                    <input id="ic{{ $i }}" name="sec[{{ $i }}][img][caption]" class="form-control form-control-sm mb-2" maxlength="300" value="{{ $sec['img']['caption'] ?? '' }}">
                    <label class="form-label small mb-1" for="ia{{ $i }}">Image description (for Google and screen readers)</label>
                    <input id="ia{{ $i }}" name="sec[{{ $i }}][img][alt]" class="form-control form-control-sm mb-2" maxlength="300" value="{{ $sec['img']['alt'] ?? '' }}">
                    <label class="form-label small mb-1" for="is{{ $i }}">Image file</label>
                    <input id="is{{ $i }}" name="sec[{{ $i }}][img][src]" class="form-control form-control-sm" maxlength="200" value="{{ $sec['img']['src'] }}">
                    <div class="form-text">A picture already on the site, for example <code>images/class1.webp</code> or a blog image from <code>uploads/blogs/</code>. A file that does not exist is ignored.</div>
                  </div>
                </div>
              </div>
            @endif
            @if (!empty($sec['table']))
              <div class="border rounded-3 p-2 mt-3">
                <label class="form-label small mb-1" for="tc{{ $i }}">Table caption</label>
                <input id="tc{{ $i }}" name="sec[{{ $i }}][table][caption]" class="form-control form-control-sm mb-2" maxlength="300" value="{{ $sec['table']['caption'] ?? '' }}">
                <label class="form-label small mb-1" for="tr{{ $i }}">Table (one row per line, cells separated by <code>|</code>; the first line is the heading row)</label>
                <textarea id="tr{{ $i }}" name="sec[{{ $i }}][table][rows]" class="form-control form-control-sm font-monospace" rows="{{ min(16, count($sec['table']['rows']) + 2) }}" wrap="off">{{ implode(' | ', $sec['table']['head']) }}
@foreach ($sec['table']['rows'] as $tr){{ implode(' | ', $tr) }}
@endforeach</textarea>
                <div class="form-text">Every row needs the same number of cells as the heading row, or the table is left as it was.</div>
              </div>
            @endif
            @php $extras = array_filter(['a list' => !empty($sec['list']), 'a chart' => !empty($sec['charts'])]); @endphp
            @if ($extras)<div class="form-text">This section also has {{ implode(', ', array_keys($extras)) }} (not editable here).</div>@endif
            @foreach ($sec['sub'] ?? [] as $j => $sub)
              <div class="border-start border-3 ps-3 mt-3">
                <label class="form-label small mb-1" for="uh{{ $i }}_{{ $j }}">Sub-heading</label>
                <input id="uh{{ $i }}_{{ $j }}" name="sec[{{ $i }}][sub][{{ $j }}][h]" class="form-control form-control-sm mb-2" maxlength="200" value="{{ $sub['h'] }}">
                <label class="form-label small mb-1" for="up{{ $i }}_{{ $j }}">Text</label>
                <textarea id="up{{ $i }}_{{ $j }}" name="sec[{{ $i }}][sub][{{ $j }}][p]" class="form-control form-control-sm" rows="{{ min(10, max(3, count($sub['p'] ?? []) * 3)) }}">{{ implode("\n\n", $sub['p'] ?? []) }}</textarea>
              </div>
            @endforeach
          </div></div>
        </div>
      @endforeach
    </div>
  </div></div>

  <div class="card mb-3"><div class="card-body">
    <h2 class="h6 text-uppercase text-muted mb-3">FAQs at the end of the page</h2>
    @include('admin.cms._pairs', ['rows' => $guide['faqs'] ?? [], 'first' => 'q', 'second' => 'a', 'firstLabel' => 'Question', 'secondLabel' => 'Answer', 'addLabel' => 'Add a question', 'secondRows' => 3])
  </div></div>

  <button class="btn btn-primary" type="submit">Save guide</button>
</form>

@if ($edited)
  <form method="post" class="mt-3" onsubmit="return confirm('Replace all your edits to this guide with the built-in text?');">
    @csrf
    <input type="hidden" name="action" value="reset">
    <button class="btn btn-sm btn-outline-secondary" type="submit">Restore the built-in text</button>
  </form>
@endif

@push('scripts')
<script>
  document.querySelectorAll('[data-count], [data-words]').forEach(function (out) {
    var words = out.hasAttribute('data-words');
    var input = document.getElementById(out.getAttribute(words ? 'data-words' : 'data-count'));
    var show = function () {
      var v = input.value.trim();
      out.textContent = words ? '(' + (v ? v.split(/\s+/).length : 0) + ' words)' : '(' + input.value.length + ' characters)';
    };
    input.addEventListener('input', show); show();
  });
</script>
@endpush
@endsection
