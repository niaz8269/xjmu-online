<?php

namespace App\Http\Controllers;

use App\Models\Download;

class DownloadController extends Controller
{
    private const CATEGORY_IDS = ['Admission Forms' => 'admission', 'Brochures' => 'brochures', 'Academic' => 'academic'];

    /** /downloads: published documents grouped by category (links point straight at the stored file path). */
    public function index()
    {
        $items = Download::query()->where('is_published', 1)->orderBy('sort_order')->orderBy('id')->get();

        $groups = [];
        foreach ($items as $d) {
            $cat = $d->category ?: 'Documents';
            $ext = strtolower(pathinfo((string) parse_url((string) $d->file_path, PHP_URL_PATH), PATHINFO_EXTENSION));
            $groups[$cat]['name'] = $cat;
            $groups[$cat]['id'] = self::CATEGORY_IDS[$cat] ?? strtolower(preg_replace('/[^a-z0-9]+/i', '-', $cat));
            $groups[$cat]['items'][] = [
                'title' => (string) $d->title,
                'file_path' => (string) $d->file_path,
                'description' => (string) $d->description,
                'icon_class' => $ext === 'pdf' ? 'pdf-icon' : 'doc-icon',
                'fa_icon' => $ext === 'pdf' ? 'fa-file-pdf' : (in_array($ext, ['doc', 'docx']) ? 'fa-file-word' : 'fa-file-alt'),
            ];
        }

        return view('pages.downloads', [
            'has_items' => $items->isNotEmpty(),
            'groups' => array_values($groups),
            'page_title' => 'Downloads | XJMU Forms & Documents',
            'page_description' => 'Download official XJMU application forms, brochures, and academic documents. Get admission requirements, fee structures, and resources for MBBS applicants.',
            'page_keywords' => 'Xinjiang Medical University downloads, MBBS application form, admission forms, university brochures, academic documents, medical university forms',
            'page_url' => 'https://xjmu.online/downloads',
            'page_image' => 'https://xjmu.online/images/logo.png',
            'body_class' => 'ds-page',
            'page_css' => ['css/design-system.css', 'css/downloads.css'],
        ]);
    }
}
