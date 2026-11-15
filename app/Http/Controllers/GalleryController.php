<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;

class GalleryController extends Controller
{
    private const TITLES = [
        'campus' => 'Campus Photos',
        'events' => 'University Events',
        'student-life' => 'Student Life',
        'facilities' => 'Facilities',
        'virtual-tour' => 'Virtual Tour',
    ];

    /** /gallery: published images grouped by category. */
    public function index()
    {
        $items = GalleryItem::query()->where('is_published', 1)->orderBy('sort_order')->orderBy('id')->get();

        $sections = [];
        foreach ($items as $g) {
            $cat = $g->category ?: 'gallery';
            $sections[$cat]['id'] = $cat;
            $sections[$cat]['title'] = self::TITLES[$cat] ?? ucwords(str_replace('-', ' ', $cat));
            $sections[$cat]['images'][] = [
                'src' => (string) $g->image_path,
                'title' => (string) $g->title,
                'alt' => $g->title ?: 'Gallery image',
            ];
        }

        return view('pages.gallery', [
            'has_items' => $items->isNotEmpty(),
            'sections' => array_values($sections),
            'page_title' => 'Gallery | XJMU Campus Photos & Events',
            'page_description' => "Explore XJMU's campus through photos and virtual tours. Discover student life, facilities, and events at Xinjiang Medical University in Urumqi, China.",
            'page_keywords' => 'Xinjiang Medical University gallery, campus photos, university facilities, student life photos, medical university China, campus tour, virtual tour',
            'page_url' => 'https://xjmu.online/gallery',
            'page_image' => 'https://xjmu.online/images/logo.png',
            'body_class' => 'ds-page',
            'page_css' => ['css/design-system.css', 'css/gallery.css'],
        ]);
    }
}
