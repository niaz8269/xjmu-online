<?php

namespace App\Support;

use App\Models\Consultant;

/**
 * Data for the shared public partials (resources/views/partials/*).
 * Keeps all head/meta defaults, settings lookups and URL building out of the Blade markup.
 */
class SiteChrome
{
    public const DEFAULT_TITLE = 'Xinjiang Medical University | MBBS in China | Study Medicine in China';
    public const DEFAULT_DESCRIPTION = 'Xinjiang Medical University offers world-class MBBS programs in China. Study medicine in China with English medium MBBS degree. Recognized by WHO, MCI, and medical councils worldwide. Apply now for 2025 intake!';
    public const DEFAULT_KEYWORDS = 'Xinjiang Medical University, MBBS in China, study medicine China, medical university China, MBBS China, study abroad China, medical education China, international medical programs, Chinese medical university, MBBS degree China';
    public const SITE_ORIGIN = 'https://xjmu.online';

    /** @param array $v page variables: page_title, page_description, page_keywords, page_url, page_image, page_og_type, page_noindex, page_css, body_class */
    public function head(array $v): array
    {
        $url = $v['page_url'] ?? null;
        if ($url === null) {
            // Canonical = clean path only (no query string, no .php/.html suffix).
            $path = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
            $path = preg_replace('/\.(php|html)$/', '', (string) $path);
            $url = self::SITE_ORIGIN . $path;
        }
        // Canonical / OG / Twitter URLs must always be HTTPS.
        $url = preg_replace('#^http://#i', 'https://', (string) $url);

        $css = [];
        if (isset($v['page_css']) && is_array($v['page_css'])) {
            foreach ($v['page_css'] as $file) {
                $css[] = get_css_url($file);
            }
        }

        // Title / description / share image set in the admin panel (SEO & Guides > Page titles & SEO) win over the built-in ones.
        $path = '/' . trim((string) request()->path(), '/');
        $meta = Cms::meta(preg_replace('/\.(php|html)$/', '', $path));
        if (isset($meta['title'])) { $v['page_title'] = $meta['title']; }
        if (isset($meta['description'])) { $v['page_description'] = $meta['description']; }
        if (isset($meta['image'])) { $v['page_image'] = $meta['image']; }

        return [
            'base'        => rtrim(get_absolute_url(''), '/'),
            'title'       => (string) ($v['page_title'] ?? self::DEFAULT_TITLE),
            'description' => (string) ($v['page_description'] ?? self::DEFAULT_DESCRIPTION),
            'keywords'    => (string) ($v['page_keywords'] ?? self::DEFAULT_KEYWORDS),
            'url'         => $url,
            'image'       => (string) ($v['page_image'] ?? self::SITE_ORIGIN . '/images/logo.png'),
            'og_type'     => (string) ($v['page_og_type'] ?? 'website'),
            'noindex'     => ! empty($v['page_noindex']),
            'body_class'  => empty($v['body_class']) ? '' : (string) $v['body_class'],
            'body_attr'   => empty($v['body_class']) ? '' : ' class="' . e((string) $v['body_class']) . '"',
            'global_css'  => get_css_url('css/global.css'),
            'i18n_js'     => get_js_url('js/i18n.js'),
            'page_css'    => $css,
        ];
    }

    /** Settings-driven top bar (contacts + social icons). */
    public function top(): array
    {
        $cn = Settings::get('contact_phone2', '+86 180 4156 6030');
        $pk = Settings::get('contact_phone_pk', '+92 300 400 9526');

        return [
            'wa_cn'        => $cn,
            'wa_cn_digits' => preg_replace('/\D/', '', (string) $cn),
            'wa_pk'        => $pk,
            'wa_pk_digits' => preg_replace('/\D/', '', (string) $pk),
            'email'        => Settings::get('contact_email', 'studyatxjmu@gmail.com'),
            'youtube'      => Settings::get('social_youtube'),
            'facebook'     => Settings::get('social_facebook'),
        ];
    }

    public function footer(): array
    {
        $icons = [
            'social_youtube'   => 'fa-youtube',
            'social_linkedin'  => 'fa-linkedin',
            'social_twitter'   => 'fa-twitter',
            'social_instagram' => 'fa-instagram',
            'social_tiktok'    => 'fa-tiktok',
            'social_facebook'  => 'fa-facebook',
        ];
        $socials = [];
        foreach ($icons as $key => $icon) {
            if ($url = Settings::get($key)) {
                $socials[] = ['url' => $url, 'icon' => $icon];
            }
        }

        return [
            'address' => Settings::get('office_address', 'Ürümqi, Xinjiang, China'),
            'phone'   => Settings::get('contact_phone'),
            'phone2'  => Settings::get('contact_phone2'),
            'email'   => Settings::get('contact_email'),
            'socials' => $socials,
            'year'    => date('Y'),
        ];
    }

    /** Published admission consultants for the floating chat widget. */
    public function consultants(): array
    {
        $agents = [];
        $rows = Consultant::where('is_published', 1)->orderBy('sort_order')->orderBy('id')->get();
        foreach ($rows as $c) {
            $agents[] = [
                'name'      => (string) $c->name,
                'role'      => (string) $c->role,
                'photo_url' => get_absolute_url($c->photo ?: 'images/logo.png'),
                'qr_url'    => empty($c->qr_image) ? '' : get_absolute_url($c->qr_image),
                'whatsapp'  => (string) $c->whatsapp,
                'wa_digits' => preg_replace('/[^0-9]/', '', (string) $c->whatsapp),
                'wechat'    => (string) $c->wechat,
                'phone'     => (string) $c->phone,
                'tel'       => preg_replace('/[^0-9+]/', '', (string) $c->phone),
                'email'     => (string) $c->email,
            ];
        }

        return ['agents' => $agents, 'site_name' => Settings::get('site_name', 'XJMU')];
    }

    public function registerModal(): array
    {
        return [
            'csrf_field' => (string) csrf_field(),
            'action_url' => get_absolute_url('register-event.php'),
        ];
    }
}
