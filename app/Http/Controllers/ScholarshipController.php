<?php

namespace App\Http\Controllers;

use App\Services\PublicContent;
use App\Support\Settings;

/** Scholarships table and contact block (`/scholarships`). */
class ScholarshipController extends Controller
{
    public function index(PublicContent $content)
    {
        return view('pages.scholarships', config('public_pages.scholarships') + [
            'scholarships' => $content->scholarships(),
            'contact' => [
                'email' => Settings::get('admissions_email', 'admissions@xjmu.online'),
                'phone' => Settings::get('contact_phone', ''),
                'address' => Settings::get('office_address', 'Urumqi, Xinjiang, China'),
            ],
        ]);
    }
}
