@extends('admin.layout')
@section('content')
@if ($flash)
    <div class="alert alert-{{ $flash_type }} alert-dismissible fade show">
        {{ $flash }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form method="POST">
    @csrf
    @foreach ($groups as $group => $items)
        <div class="card mb-4">
            <div class="card-header bg-body">
                <h2 class="card-title h6 mb-0">{{ $groupTitles[$group] ?? ucfirst($group) }}</h2>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach ($items as $s)
                        <div class="col-md-6">
                            <label class="form-label" for="f_{{ $s['setting_key'] }}">{{ $s['label'] }}</label>
                            @if ($s['input_type'] === 'textarea')
                                <textarea class="form-control" id="f_{{ $s['setting_key'] }}" name="{{ $s['setting_key'] }}" rows="2">{{ $s['setting_value'] }}</textarea>
                            @else
                                <input type="{{ in_array($s['input_type'], ['email','url','tel']) ? $s['input_type'] : 'text' }}"
                                       class="form-control" id="f_{{ $s['setting_key'] }}"
                                       name="{{ $s['setting_key'] }}" value="{{ $s['setting_value'] }}">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    <div class="mb-5">
        <button type="submit" class="btn btn-xjmu px-4"><i class="bi bi-save me-2"></i>Save Settings</button>
    </div>
</form>


@endsection
