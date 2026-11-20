<?php

namespace App\Http\Controllers;

use App\Services\PublicContent;
use App\Support\FaqSchema;

/** MBBS program page (`/mbbs`): admin settings lists (facts, why, fees, FAQ...) + curriculum + free-form sections. */
class MbbsController extends Controller
{
    public function index(PublicContent $content)
    {
        $faqs = [];
        foreach ($content->settingRows('mbbs_faq_list') as $r) {
            if (! empty($r[0]) && ! empty($r[1])) {
                $faqs[] = ['q' => $r[0], 'a' => $r[1]];
            }
        }
        // Code-side SEO FAQs (config/seo_faqs.php) after the admin ones.
        $faqs = array_map(fn ($r) => ['q' => $r[0], 'a' => $r[1]],
            \App\Support\SeoFaqs::merge(array_map(fn ($f) => [$f['q'], $f['a']], $faqs), 'mbbs'));

        $feeRows = array_map(
            fn (array $r) => $r + ['is_total' => isset($r[2]) && strtolower($r[2]) === 'total'],
            $content->settingRows('mbbs_fee_rows'),
        );

        return view('pages.mbbs', config('public_pages.mbbs') + [
            't' => $content->settings(config('public_pages.mbbs_settings')),
            'mbbsFacts' => $content->settingRows('mbbs_facts_list'),
            'mbbsWhy' => $content->settingRows('mbbs_why_list'),
            'mbbsCurriculum' => $content->curriculum()->all(),
            'mbbsEligibility' => $content->settingRows('mbbs_eligibility_list'),
            'mbbsFeeRows' => $feeRows,
            'mbbsRecognition' => $content->settingRows('mbbs_recognition_list'),
            'mbbsApply' => $content->settingRows('mbbs_apply_list'),
            'mbbsSections' => $content->pageSections('mbbs')->all(),
            'mbbsFaqs' => $faqs,
            'faqSchema' => FaqSchema::items(array_map(fn ($f) => [$f['q'], $f['a']], $faqs)),
        ]);
    }
}
