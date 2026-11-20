{{-- FAQ section + FAQPage schema. Expects $faqs (rows of [question, answer]) and optional $faqTitle. --}}
@if (!empty($faqs))
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {!! \App\Support\FaqSchema::items($faqs) !!}
  ]
}
</script>
<section class="ds-section ds-section--soft" id="faq">
    <div class="container">
        <div class="ds-section-head"><span class="ds-eyebrow">FAQ</span><h2 class="ds-title">{{ $faqTitle ?? 'Frequently asked questions' }}</h2></div>
        <div style="max-width:820px;margin:0 auto">
            @foreach ($faqs as [$question, $answer])
            <details class="ds-faq">
                <summary>{{ $question }}</summary>
                <div class="ds-faq__body">{{ $answer }}</div>
            </details>
            @endforeach
        </div>
    </div>
</section>
@endif
