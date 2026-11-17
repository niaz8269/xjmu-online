@foreach ((array) config('seo_guides') as $guideSlug => $guide)
<li><a href="/{{ $guideSlug }}">{{ $guide['crumb'] ?? $guide['h1'] }}</a></li>
@endforeach
