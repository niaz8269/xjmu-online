<?php

namespace App\Support\Admin;

/** Admin sidebar definition (was $adminMenu in layout_top.php). key => [label, icon, url, (optional) children]. */
class Menu
{
    public static function items(): array
    {
        return [
            'dashboard'   => ['Dashboard', 'bi-speedometer2', 'dashboard'],
            'leads'       => ['Leads', 'bi-person-lines-fill', 'leads'],
            'applications' => ['Applications', 'bi-file-earmark-text', 'applications'],
            'users'       => ['Users', 'bi-people', 'users'],
            '_content'    => ['Content', 'bi-collection', null, [
                'manage-blogs'        => ['Blogs', 'manage-blogs'],
                'manage-events'       => ['Events', 'manage-events'],
                'manage-programs'     => ['Programs', 'manage-programs'],
                'manage-scholarships' => ['Scholarships', 'manage-scholarships'],
                'manage-downloads'    => ['Downloads', 'manage-downloads'],
                'manage-gallery'      => ['Gallery', 'manage-gallery'],
                'manage-consultants'  => ['Consultants', 'manage-consultants'],
                'manage-testimonials' => ['Testimonials', 'manage-testimonials'],
                'manage-faqs'         => ['FAQs', 'manage-faqs'],
                'manage-pages'        => ['Page Content', 'manage-pages'],
            ]],
            '_homepage'   => ['Homepage Cards', 'bi-grid-1x2', null, [
                'manage-home-programs'       => ['Program Cards', 'manage-home-programs'],
                'manage-home-why'            => ['Why-Us Cards', 'manage-home-why'],
                'manage-home-steps'          => ['Apply Steps', 'manage-home-steps'],
                'manage-home-accreditations' => ['Accreditations', 'manage-home-accreditations'],
                'manage-home-facts'          => ['MBBS Facts', 'manage-home-facts'],
                'manage-mbbs-curriculum'     => ['MBBS Curriculum', 'manage-mbbs-curriculum'],
            ]],
            'settings'    => ['Site Settings', 'bi-gear', 'settings'],
            'activity'    => ['Activity & Security', 'bi-shield-lock', 'activity'],
            'test_email'  => ['Email Test', 'bi-envelope', 'test_email'],
        ];
    }

    /** ' active' when $key is the current page (was admin_is_active()). */
    public static function activeClass(string $key, string $active): string
    {
        return $key === $active ? ' active' : '';
    }
}
