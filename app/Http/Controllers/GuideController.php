<?php

namespace App\Http\Controllers;

use App\Support\FaqSchema;

/** Keyword landing pages ("guides") defined in config/seo_guides.php, served at /{slug}. */
class GuideController extends Controller
{
    public function show(string $slug)
    {
        $guide = config('seo_guides.' . $slug);
        abort_unless($guide, 404);

        $related = [];
        foreach ($guide['related'] ?? [] as $key) {
            if ($other = config('seo_guides.' . $key)) {
                $related[] = ['url' => get_absolute_url($key), 'title' => $other['h1'], 'lead' => $other['lead']];
            }
        }

        return view('pages.guide', [
            'guide' => $guide,
            'related' => $related,
            'faqSchema' => FaqSchema::items($guide['faqs'] ?? []),
            'updatedLabel' => date('F Y', strtotime($guide['updated'])),
            'page_title' => $guide['title'],
            'page_description' => $guide['description'],
            'page_keywords' => $guide['keywords'],
            'page_url' => 'https://xjmu.online/' . $slug,
            'page_image' => 'https://xjmu.online/images/logo.png',
            'body_class' => 'ds-page',
            'page_css' => ['css/design-system.css', 'css/mbbs.css'],
        ]);
    }
}
