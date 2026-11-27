<?php

namespace App\Http\Controllers;

use App\Services\SiteMailer;
use Illuminate\Http\Request;

/**
 * Public mail-out forms: contact (send_contact_email), partner registration (send_partner_registration)
 * and event/webinar registration (register-event). JSON shapes, status codes and messages as the originals.
 */
class FormMailController extends Controller
{
    public function __construct(private SiteMailer $mailer)
    {
    }

    private function logDir(): string
    {
        $dir = storage_path('legacy/logs');
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        return $dir;
    }

    private function headers(string $replyTo): string
    {
        return "From: no-reply@xjmu.online\r\n"
            . "Reply-To: {$replyTo}\r\n"
            . 'X-Mailer: PHP/' . phpversion() . "\r\n"
            . "Content-Type: text/plain; charset=UTF-8\r\n";
    }

    // ------------------------------------------------------------------ contact
    public function contact(Request $request)
    {
        if (! $request->isMethod('POST')) {
            return response()->json(['status' => 'error', 'message' => 'Method not allowed'], 405);
        }

        // Rate limiting: max 5 submissions per hour per IP.
        $rateFile = $this->logDir() . '/contact_rate_limit.json';
        $ip = $request->ip() ?? 'unknown';
        $now = time();
        $window = 3600;
        $max = 5;

        $data = [];
        if (file_exists($rateFile)) {
            $data = json_decode((string) file_get_contents($rateFile), true) ?: [];
        }
        if (isset($data[$ip])) {
            $data[$ip] = array_values(array_filter($data[$ip], fn ($t) => ($now - $t) < $window));
            if (count($data[$ip]) >= $max) {
                return response()->json(['status' => 'error', 'message' => 'Too many requests. Please try again later.'], 429);
            }
        } else {
            $data[$ip] = [];
        }
        $data[$ip][] = $now;
        file_put_contents($rateFile, json_encode($data));

        $log = function (string $message) use ($ip) {
            @error_log('[' . date('Y-m-d H:i:s') . "] IP: $ip - $message\n", 3, $this->logDir() . '/contact_errors.log');
        };

        $name = trim((string) $request->input('name', ''));
        $email = trim((string) $request->input('email', ''));
        $phone = trim((string) $request->input('phone', ''));
        $nationality = trim((string) $request->input('nationality', ''));
        $subject = trim((string) $request->input('subject', ''));
        $program = trim((string) $request->input('program', ''));
        $message = trim((string) $request->input('message', ''));
        $newsletter = $request->has('newsletter') ? 'Yes' : 'No';

        if (empty($name) || empty($email) || empty($nationality) || empty($subject) || empty($message)) {
            return response()->json(['status' => 'error', 'message' => 'Please fill in all required fields'], 400);
        }
        if (strlen($message) > 400) {
            return response()->json(['status' => 'error', 'message' => 'Message must be 400 characters or less'], 400);
        }
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid email address'], 400);
        }

        $emailSubject = SiteMailer::headerSafe('XJMU Contact Form: ' . $subject . ' - ' . $name);

        $body = "XINJIANG MEDICAL UNIVERSITY - CONTACT FORM SUBMISSION\n\n";
        $body .= "APPLICANT INFORMATION:\n";
        $body .= "• Name: {$name}\n";
        $body .= "• Email: {$email}\n";
        $body .= '• Phone: ' . ($phone ? $phone : 'Not provided') . "\n";
        $body .= "• Nationality: {$nationality}\n\n";
        $body .= "ACADEMIC INTEREST:\n";
        $body .= "• Inquiry Type: {$subject}\n";
        $body .= '• Program Interest: ' . ($program ? $program : 'Not specified') . "\n\n";
        $body .= "MESSAGE:\n{$message}\n\n";
        $body .= "NEWSLETTER SUBSCRIPTION: {$newsletter}\n\n";
        $body .= "SUBMISSION DETAILS:\n";
        $body .= '• Submitted: ' . date('Y-m-d H:i:s') . "\n";
        $body .= "• Source: XJMU Website Contact Form\n";
        $body .= '• IP Address: ' . ($request->ip() ?? 'Unknown') . "\n";

        // Save the enquiry as a lead (admin panel > Leads). It never blocks the email below.
        \App\Services\Leads::capture('contact', compact('name', 'email', 'phone', 'nationality', 'subject', 'program', 'message'));

        if ($this->mailer->sendPlain((string) config('forms.notify_to'), $emailSubject, $body, $this->headers($email))) {
            $log("Contact form submitted successfully from: $email");
            return response()->json(['status' => 'success', 'message' => 'Your message has been sent successfully. We will get back to you soon.']);
        }

        $log("Failed to send email for contact form from: $email");
        return response()->json(['status' => 'error', 'message' => 'Failed to send message. Please try again or contact us directly.'], 500);
    }

    // ------------------------------------------------------------------ partner
    public function partner(Request $request)
    {
        if (! $request->isMethod('POST')) {
            return response()->json(['status' => 'error', 'message' => 'Method not allowed'], 405);
        }

        $required = [
            'first_name', 'last_name', 'email', 'mobile', 'company_name', 'current_business',
            'address', 'street_address', 'city', 'state', 'pincode', 'region', 'whatsapp',
        ];
        $d = [];
        foreach ($required as $field) {
            $d[$field] = trim((string) $request->input($field, ''));
            if ($d[$field] === '') {
                return response()->json(['status' => 'error', 'message' => 'Please fill all required fields.'], 400);
            }
        }
        if (! filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
            return response()->json(['status' => 'error', 'message' => 'Please enter a valid email address.'], 400);
        }

        $subject = SiteMailer::headerSafe('New Partner Registration - ' . $d['first_name'] . ' ' . $d['last_name']);

        $body = "Xinjiang Medical University - Partner Registration\n";
        $body .= "================================================\n\n";
        $body .= "First Name: {$d['first_name']}\n";
        $body .= "Last Name: {$d['last_name']}\n";
        $body .= "Email: {$d['email']}\n";
        $body .= "Mobile Number: {$d['mobile']}\n";
        $body .= "Company Name: {$d['company_name']}\n";
        $body .= "Current Business: {$d['current_business']}\n";
        $body .= "Address: {$d['address']}\n";
        $body .= "Street Address: {$d['street_address']}\n";
        $body .= "City: {$d['city']}\n";
        $body .= "State: {$d['state']}\n";
        $body .= "Pin Code/Zip Code: {$d['pincode']}\n";
        $body .= "State/Province/Region: {$d['region']}\n";
        $body .= "WhatsApp Number: {$d['whatsapp']}\n";
        $body .= "\n";
        $body .= 'Submitted At: ' . date('Y-m-d H:i:s') . "\n";
        $body .= 'IP Address: ' . ($request->ip() ?? 'Unknown') . "\n";

        $logFile = $this->logDir() . '/partner_registration.log';
        \App\Services\Leads::capture('partner', [
            'name' => $d['first_name'] . ' ' . $d['last_name'], 'email' => $d['email'], 'phone' => $d['whatsapp'] ?: $d['mobile'],
            'nationality' => $d['region'], 'subject' => 'Partner registration: ' . $d['company_name'],
            'message' => 'Company: ' . $d['company_name'] . '. Business: ' . $d['current_business'] . '. City: ' . $d['city'] . ', ' . $d['state'] . '.',
        ]);
        $sent = $this->mailer->sendPlain((string) config('forms.notify_to'), $subject, $body, $this->headers($d['email']));

        if ($sent) {
            @file_put_contents($logFile, date('Y-m-d H:i:s') . " OK - {$d['first_name']} {$d['last_name']} <{$d['email']}>\n", FILE_APPEND | LOCK_EX);
            return response()->json(['status' => 'success', 'message' => 'Warm welcome! Your partner registration has been submitted successfully. Our partnership team will contact you soon.']);
        }

        @file_put_contents($logFile, date('Y-m-d H:i:s') . " FAILED - {$d['first_name']} {$d['last_name']} <{$d['email']}>\n", FILE_APPEND | LOCK_EX);
        return response()->json(['status' => 'error', 'message' => 'Unable to submit registration right now. Please try again.'], 500);
    }

    // ------------------------------------------------------------------ event / webinar
    public function event(Request $request)
    {
        if (! $request->isMethod('POST')) {
            return response()->json(['success' => false, 'message' => 'Method not allowed'], 405);
        }

        if (! CsrfCheck::passes($request)) {
            return response()->json(['success' => false, 'message' => 'Your session expired. Please refresh the page and try again.'], 403);
        }

        $name = trim((string) $request->input('name', ''));
        $nationality = trim((string) $request->input('nationality', ''));
        $email = trim((string) $request->input('email', ''));
        $whatsapp = trim((string) $request->input('whatsapp', ''));
        $event_title = trim((string) $request->input('event_title', ''));
        $event_date = trim((string) $request->input('event_date', ''));
        $event_time = trim((string) $request->input('event_time', ''));

        if (empty($name) || empty($email)) {
            return response()->json(['success' => false, 'message' => 'Name and Email are required.']);
        }
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['success' => false, 'message' => 'Please enter a valid email address.']);
        }

        $to = (string) config('forms.notify_to');
        $subject = SiteMailer::headerSafe('Event Registration: ' . ($event_title ?: 'Xinjiang Medical University'));

        $body = "Event/Webinar Registration\n";
        $body .= "===========================\n\n";
        $body .= "Name: $name\n";
        $body .= "Nationality: $nationality\n";
        $body .= "Email: $email\n";
        $body .= "WhatsApp: $whatsapp\n\n";
        $body .= "Event/Webinar:\n";
        $body .= 'Title: ' . ($event_title ?: 'Xinjiang Medical University – MBBS (English Taught) March 2027 intake') . "\n";
        if ($event_date) $body .= "Date: $event_date\n";
        if ($event_time) $body .= "Time: $event_time (Beijing Time)\n";
        $body .= "\nRegistration submitted: " . date('Y-m-d H:i:s');
        $body .= "\nIP: " . ($request->ip() ?? 'Unknown');

        $logFile = $this->logDir() . '/event_registration.log';
        \App\Services\Leads::capture('event', [
            'name' => $name, 'email' => $email, 'phone' => $whatsapp, 'nationality' => $nationality,
            'subject' => 'Event registration: ' . ($event_title ?: 'webinar'), 'program' => 'MBBS',
            'message' => trim('Registered for ' . ($event_title ?: 'the webinar') . ' ' . $event_date . ' ' . $event_time),
        ]);
        $sent = $this->mailer->sendPlain($to, $subject, $body, $this->headers($email));

        if ($sent) {
            @file_put_contents($logFile, date('Y-m-d H:i:s') . " OK – sent to $to – $name <$email>\n", FILE_APPEND | LOCK_EX);
            return response()->json(['success' => true, 'message' => 'Thank you for registering! We will contact you shortly.']);
        }

        @file_put_contents($logFile, date('Y-m-d H:i:s') . " FAILED – to=$to – $name <$email> – mail() returned false\n", FILE_APPEND | LOCK_EX);
        return response()->json(['success' => false, 'message' => 'Unable to send registration. Please try again or contact us directly at ' . $to . '.']);
    }
}
