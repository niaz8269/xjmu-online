{{-- Repeating rows of [short text, long text]. $rows, $first (input name), $second (textarea name), $firstLabel, $secondLabel, $addLabel --}}
<div class="cms-pairs" data-first="{{ $first }}" data-second="{{ $second }}">
  @foreach ($rows as $row)
    <div class="cms-pair border rounded-3 p-2 mb-2">
      <div class="d-flex gap-2 align-items-start">
        <div class="flex-grow-1">
          <input name="{{ $first }}[]" class="form-control form-control-sm mb-1" value="{{ $row[0] ?? '' }}" placeholder="{{ $firstLabel }}" aria-label="{{ $firstLabel }}">
          <textarea name="{{ $second }}[]" class="form-control form-control-sm" rows="{{ $secondRows ?? 2 }}" placeholder="{{ $secondLabel }}" aria-label="{{ $secondLabel }}">{{ $row[1] ?? '' }}</textarea>
        </div>
        <div class="d-flex flex-column gap-1">
          <button type="button" class="btn btn-sm btn-outline-secondary" data-move="-1" title="Move up" aria-label="Move up"><i class="bi bi-arrow-up"></i></button>
          <button type="button" class="btn btn-sm btn-outline-secondary" data-move="1" title="Move down" aria-label="Move down"><i class="bi bi-arrow-down"></i></button>
          <button type="button" class="btn btn-sm btn-outline-danger" data-remove title="Remove" aria-label="Remove"><i class="bi bi-x-lg"></i></button>
        </div>
      </div>
    </div>
  @endforeach
  <template>
    <div class="cms-pair border rounded-3 p-2 mb-2">
      <div class="d-flex gap-2 align-items-start">
        <div class="flex-grow-1">
          <input name="{{ $first }}[]" class="form-control form-control-sm mb-1" placeholder="{{ $firstLabel }}" aria-label="{{ $firstLabel }}">
          <textarea name="{{ $second }}[]" class="form-control form-control-sm" rows="{{ $secondRows ?? 2 }}" placeholder="{{ $secondLabel }}" aria-label="{{ $secondLabel }}"></textarea>
        </div>
        <div class="d-flex flex-column gap-1">
          <button type="button" class="btn btn-sm btn-outline-secondary" data-move="-1" title="Move up" aria-label="Move up"><i class="bi bi-arrow-up"></i></button>
          <button type="button" class="btn btn-sm btn-outline-secondary" data-move="1" title="Move down" aria-label="Move down"><i class="bi bi-arrow-down"></i></button>
          <button type="button" class="btn btn-sm btn-outline-danger" data-remove title="Remove" aria-label="Remove"><i class="bi bi-x-lg"></i></button>
        </div>
      </div>
    </div>
  </template>
  <button type="button" class="btn btn-sm btn-outline-primary" data-add><i class="bi bi-plus-lg me-1"></i>{{ $addLabel }}</button>
</div>

@once
@push('scripts')
<script>
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('button');
    if (!btn) { return; }
    var box = btn.closest('.cms-pairs');
    if (!box) { return; }
    var row = btn.closest('.cms-pair');
    if (btn.hasAttribute('data-add')) {
      var node = box.querySelector('template').content.firstElementChild.cloneNode(true);
      box.insertBefore(node, btn);
      node.querySelector('input').focus();
    } else if (btn.hasAttribute('data-remove') && row) {
      row.remove();
    } else if (btn.hasAttribute('data-move') && row) {
      var up = btn.getAttribute('data-move') === '-1';
      var other = up ? row.previousElementSibling : row.nextElementSibling;
      if (other && other.classList.contains('cms-pair')) { box.insertBefore(up ? row : other, up ? other : row); }
    }
  });
</script>
@endpush
@endonce
