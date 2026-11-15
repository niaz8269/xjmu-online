<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Services\ImageUpload;
use Illuminate\Http\Request;

/** submit-testimonial: public form; saved as pending (is_published = 0) until an admin approves it. */
class TestimonialSubmissionController extends Controller
{
    public function __construct(private ImageUpload $uploads)
    {
    }

    public function show(Request $request)
    {
        return $this->page($request, false, '');
    }

    public function store(Request $request)
    {
        $session = $request->session();
        $submitted = false;
        $error = '';

        // Rolling 1-hour window of this session's submissions (anti-flood).
        $now = time();
        $hits = array_values(array_filter($session->get('tst_hits', []), fn ($t) => $t > $now - 3600));

        if (! CsrfCheck::passes($request)) {
            $error = 'Your session expired. Please refresh the page and try again.';
        } elseif (! empty($request->input('website'))) {
            // Honeypot filled by a bot: pretend success, save nothing.
            $submitted = true;
        } elseif (! empty($hits) && ($now - end($hits)) < 30) {
            $error = 'Please wait a few seconds before submitting again.';
        } elseif (count($hits) >= 5) {
            $error = 'You have submitted several testimonials recently. Please try again later.';
        } else {
            $name = trim((string) $request->input('name', ''));
            $quote = trim((string) $request->input('quote', ''));
            $role = trim((string) $request->input('role', ''));
            $location = trim((string) $request->input('location', ''));
            $category = ($request->input('category', 'student') === 'alumni') ? 'alumni' : 'student';

            if (mb_strlen($name) < 2 || $quote === '') {
                $error = 'Please enter your name and your testimonial.';
            } elseif (mb_strlen($quote) < 10) {
                $error = 'Please write a little more about your experience (at least 10 characters).';
            } elseif (mb_strlen($quote) > 1000) {
                $error = 'Your testimonial is too long (please keep it under 1000 characters).';
            } else {
                $name = mb_substr($name, 0, 150);
                $role = mb_substr($role, 0, 200);
                $location = mb_substr($location, 0, 200);
                try {
                    $photo = $this->uploads->save($request->file('photo'), 'testimonials', 4 * 1024 * 1024);
                    Testimonial::query()->create([
                        'name' => $name, 'role' => $role, 'location' => $location, 'quote' => $quote,
                        'photo' => $photo, 'category' => $category, 'sort_order' => 0, 'is_published' => 0,
                    ]);
                    $hits[] = $now;
                    $session->put('tst_hits', $hits);
                    $submitted = true;
                } catch (\Throwable $e) {
                    error_log('submit-testimonial: ' . $e->getMessage());
                    $error = 'Sorry, we could not save your testimonial right now. Please try again later.';
                }
            }
        }

        return $this->page($request, $submitted, $error);
    }

    private function page(Request $request, bool $submitted, string $error)
    {
        return response()->view('submit-testimonial', [
            'submitted' => $submitted,
            'error' => $error,
            'input' => $request->isMethod('POST') ? $request->only(['name', 'category', 'role', 'location', 'quote']) : [],
            'page_title' => 'Share Your Testimonial | Xinjiang Medical University',
            'page_description' => 'Are you an XJMU student or graduate? Share your experience to be featured on the Xinjiang Medical University website.',
            'page_url' => 'https://xjmu.online/submit-testimonial',
            'page_noindex' => true,
            'body_class' => 'ds-page',
            'page_css' => ['css/design-system.css', 'css/contact.css'],
        ]);
    }
}
