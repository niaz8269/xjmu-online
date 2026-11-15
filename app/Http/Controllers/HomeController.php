<?php

namespace App\Http\Controllers;

use App\Services\HomePage;
use App\Services\SiteMailer;
use Illuminate\Http\Request;

/** Home page (`/`) including its September-intake registration pop-up, which posts back to the same URL. */
class HomeController extends Controller
{
    public function __construct(private HomePage $page, private SiteMailer $mailer)
    {
    }

    public function index()
    {
        return $this->render(false, '');
    }

    /** POST `/`: the registration pop-up. Mails the registration, then shows the home page with the outcome message. */
    public function register(Request $request)
    {
        if (! $request->has('webinar_register')) {
            return $this->render(false, '');
        }

        $field = fn (string $key) => htmlspecialchars((string) $request->input($key, ''));
        $safeName = SiteMailer::headerSafe((string) $request->input('name', ''));
        $replyEmail = trim((string) $request->input('email', ''));
        $replyEmail = filter_var($replyEmail, FILTER_VALIDATE_EMAIL) ? $replyEmail : '';

        $message = "New Student Registration\n";
        $message .= "===========================\n\n";
        $message .= 'Applying For: ' . $field('applying_for') . "\n";
        $message .= 'Name: ' . $field('name') . "\n";
        $message .= 'Nationality: ' . $field('nationality') . "\n";
        $message .= 'Email: ' . $field('email') . "\n";
        $message .= 'WhatsApp: ' . $field('whatsapp') . "\n\n";
        $message .= 'Registration Date: ' . date('Y-m-d H:i:s');

        $headers = "From: no-reply@xjmu.online\r\n";
        if ($replyEmail !== '') {
            $headers .= "Reply-To: $replyEmail\r\n";
        }
        $headers .= 'X-Mailer: PHP/' . phpversion();

        $sent = $this->mailer->sendPlain(
            'studyatxjmu@gmail.com',
            SiteMailer::headerSafe($safeName . ' -MBBS Registration September 2026 Intake'),
            $message,
            $headers,
        );

        return $sent
            ? $this->render(true, 'Thank you for registering! Your registration has been submitted successfully.')
            : $this->render(false, 'There was an error submitting your registration. Please try again.');
    }

    private function render(bool $formSubmitted, string $successMessage)
    {
        return view('pages.index', config('public_pages.home') + $this->page->data() + [
            'formSubmitted' => $formSubmitted,
            'successMessage' => $successMessage,
        ]);
    }
}
