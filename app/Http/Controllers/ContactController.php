<?php

namespace App\Http\Controllers;

use App\Support\Settings;

class ContactController extends Controller
{
    /** /contact: page only. The form posts to /send_contact_email (FormMailController, no CSRF token as in the original). */
    public function index()
    {
        return view('pages.contact', [
            'phone' => (string) Settings::get('contact_phone', ''),
            'phone_tel' => preg_replace('/[^0-9+]/', '', (string) Settings::get('contact_phone', '')),
            'admissions_email' => (string) Settings::get('admissions_email', 'admissions@xjmu.online'),
            'contact_email' => (string) Settings::get('contact_email', 'info@xjmu.online'),
            'form_action' => get_absolute_url('send_contact_email'),
            'contact_js' => get_absolute_url('js/contact.js'),
            'page_title' => 'Contact XJMU | Admissions & Support',
            'page_description' => 'Contact Xinjiang Medical University for admissions, program info, and support. Reach us by phone, email, or WhatsApp. Our team in Urumqi is ready to help.',
            'page_keywords' => 'contact Xinjiang Medical University, XJMU contact, admissions contact, university address China, medical university contact, study in China contact',
            'page_url' => 'https://xjmu.online/contact',
            'page_image' => 'https://xjmu.online/images/logo.png',
            'body_class' => 'ds-page',
            'page_css' => ['css/design-system.css', 'css/contact.css'],
        ]);
    }
}
