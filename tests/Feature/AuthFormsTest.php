<?php

namespace Tests\Feature;

use App\Models\Testimonial;
use App\Services\SiteMailer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuthFormsTest extends TestCase
{
    use RefreshDatabase;

    private SiteMailer $mailer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mailer = new SiteMailer();
        $this->app->instance(SiteMailer::class, $this->mailer);
        @unlink(storage_path('legacy/logs/contact_rate_limit.json'));
    }

    private function contact(array $o = []): array
    {
        return array_merge(['name' => 'Bob', 'email' => 'bob@example.com', 'nationality' => 'X', 'subject' => 'Fees', 'message' => 'Hello'], $o);
    }

    public function test_contact_validation_and_success(): void
    {
        $this->get('/send_contact_email')->assertStatus(405)->assertExactJson(['status' => 'error', 'message' => 'Method not allowed']);
        $this->post('/send_contact_email', ['name' => 'x'])->assertStatus(400)->assertExactJson(['status' => 'error', 'message' => 'Please fill in all required fields']);
        $this->post('/send_contact_email', $this->contact(['message' => str_repeat('a', 401)]))->assertStatus(400)->assertJsonPath('message', 'Message must be 400 characters or less');
        $this->post('/send_contact_email', $this->contact(['email' => 'nope']))->assertStatus(400)->assertJsonPath('message', 'Invalid email address');
        $this->assertCount(0, $this->mailer->outbox);

        @unlink(storage_path('legacy/logs/contact_rate_limit.json'));
        $this->post('/send_contact_email', $this->contact(['newsletter' => '1']))->assertOk()
            ->assertExactJson(['status' => 'success', 'message' => 'Your message has been sent successfully. We will get back to you soon.']);
        $m = $this->mailer->outbox[0];
        $this->assertSame('guide@xjmu.online', $m['to']);
        $this->assertSame('XJMU Contact Form: Fees - Bob', $m['subject']);
        $this->assertStringContainsString('NEWSLETTER SUBSCRIPTION: Yes', $m['body']);
        $this->assertStringContainsString('Reply-To: bob@example.com', $m['headers']);
    }

    public function test_contact_rate_limit_after_five(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/send_contact_email', $this->contact())->assertOk();
        }
        $this->post('/send_contact_email', $this->contact())->assertStatus(429)
            ->assertExactJson(['status' => 'error', 'message' => 'Too many requests. Please try again later.']);
    }

    public function test_contact_mail_failure_is_500(): void
    {
        $this->app->instance(SiteMailer::class, new class extends SiteMailer {
            public function sendPlain(string $to, string $subject, string $body, string $headers): bool { return false; }
        });
        $this->post('/send_contact_email', $this->contact())->assertStatus(500)->assertJsonPath('message', 'Failed to send message. Please try again or contact us directly.');
    }

    public function test_partner_registration(): void
    {
        $this->get('/send_partner_registration')->assertStatus(405);
        $this->post('/send_partner_registration', ['first_name' => 'A'])->assertStatus(400)->assertExactJson(['status' => 'error', 'message' => 'Please fill all required fields.']);

        $d = array_fill_keys(['first_name', 'last_name', 'mobile', 'company_name', 'current_business', 'address', 'street_address', 'city', 'state', 'pincode', 'region', 'whatsapp'], 'v');
        $this->post('/send_partner_registration', $d + ['email' => 'bad'])->assertStatus(400)->assertJsonPath('message', 'Please enter a valid email address.');
        $this->post('/send_partner_registration', $d + ['email' => 'p@example.com'])->assertOk()->assertJsonPath('status', 'success');
        $this->assertSame('partners@xjmu.online', $this->mailer->outbox[0]['to']);
        $this->assertSame('New Partner Registration - v v', $this->mailer->outbox[0]['subject']);
    }

    public function test_register_event(): void
    {
        $this->get('/register-event')->assertStatus(405)->assertExactJson(['success' => false, 'message' => 'Method not allowed']);
        $this->post('/register-event', ['name' => 'A', 'email' => 'a@example.com'])->assertStatus(403)
            ->assertJsonPath('message', 'Your session expired. Please refresh the page and try again.');

        $tok = ['_token' => 'tok123'];
        $s = ['_token' => 'tok123'];
        $this->withSession($s)->post('/register-event', $tok + ['name' => '', 'email' => ''])->assertExactJson(['success' => false, 'message' => 'Name and Email are required.']);
        $this->withSession($s)->post('/register-event', $tok + ['name' => 'A', 'email' => 'bad'])->assertJsonPath('message', 'Please enter a valid email address.');
        $this->withSession($s)->post('/register-event', $tok + ['name' => 'A', 'email' => 'a@example.com', 'event_title' => 'Webinar', 'event_date' => '1 Jan'])
            ->assertExactJson(['success' => true, 'message' => 'Thank you for registering! We will contact you shortly.']);
        $this->assertSame('webinar@xjmu.online', $this->mailer->outbox[0]['to']);
        $this->assertSame('Event Registration: Webinar', $this->mailer->outbox[0]['subject']);

        // the event modal now sends Laravel's `_token` (the old lg_csrf_token field is gone)
        $this->withSession(['lg_csrf_token' => 'legacy1'])->post('/register-event', ['lg_csrf_token' => 'legacy1', 'name' => 'A', 'email' => 'a@example.com'])->assertStatus(403);
    }

    public function test_testimonial_form_and_submission(): void
    {
        $this->get('/submit-testimonial')->assertOk()->assertSee('Share your XJMU experience')->assertSee('name="_token"', false);

        $s = ['_token' => 't'];
        $post = fn (array $d) => $this->withSession($s)->post('/submit-testimonial', ['_token' => 't'] + $d);

        $this->post('/submit-testimonial', ['name' => 'Al', 'quote' => 'x'])->assertSee('Your session expired. Please refresh the page and try again.');
        $post(['name' => 'A', 'quote' => ''])->assertSee('Please enter your name and your testimonial.');
        $post(['name' => 'Al', 'quote' => 'short'])->assertSee('at least 10 characters');
        $post(['name' => 'Al', 'quote' => str_repeat('q', 1001)])->assertSee('too long');
        $post(['name' => 'Bot', 'quote' => 'long enough quote', 'website' => 'spam'])->assertSee('Thank you!');
        $this->assertSame(0, Testimonial::count());

        $post(['name' => 'Alice', 'quote' => 'Wonderful experience here', 'category' => 'alumni', 'role' => 'MBBS'])->assertSee('Your testimonial has been submitted successfully.');
        $t = Testimonial::first();
        $this->assertSame('alumni', $t->category);
        $this->assertEquals(0, $t->is_published);
        $this->assertNull($t->photo);
    }

    public function test_testimonial_rate_limit_per_session(): void
    {
        $this->withSession(['_token' => 't', 'tst_hits' => [time() - 5]])
            ->post('/submit-testimonial', ['_token' => 't', 'name' => 'Al', 'quote' => 'long enough quote'])
            ->assertSee('Please wait a few seconds before submitting again.');
        $this->withSession(['_token' => 't', 'tst_hits' => array_fill(0, 5, time() - 100)])
            ->post('/submit-testimonial', ['_token' => 't', 'name' => 'Al', 'quote' => 'long enough quote'])
            ->assertSee('You have submitted several testimonials recently.');
    }

    public function test_sitemap_xml(): void
    {
        DB::table('blogs')->insert(['title' => 'B', 'slug' => 'my blog', 'excerpt' => 'e', 'content' => 'c', 'status' => 'published', 'published_at' => '2026-03-04 10:00:00']);
        DB::table('blogs')->insert(['title' => 'D', 'slug' => 'draft', 'excerpt' => 'e', 'content' => 'c', 'status' => 'draft']);
        DB::table('events')->insert(['title' => 'E', 'slug' => 'ev1', 'event_date' => '2026-05-06', 'status' => 'published']);

        $r = $this->get('/sitemap');
        $r->assertOk();
        $this->assertSame('application/xml; charset=UTF-8', $r->headers->get('Content-Type'));
        $x = $r->getContent();
        $this->assertStringStartsWith('<?xml version="1.0" encoding="UTF-8"?>', $x);
        $this->assertStringContainsString('<loc>https://xjmu.online/blog/my%20blog</loc>', $x);
        $this->assertStringContainsString('<lastmod>2026-03-04</lastmod>', $x);
        $this->assertStringContainsString('<loc>https://xjmu.online/event?slug=ev1</loc>', $x);
        $this->assertStringNotContainsString('draft', $x);
        $this->assertNotFalse(simplexml_load_string($x));
        $this->assertSame(20 + 2 + count(config("seo_guides")), substr_count($x, '<url>'));
    }
}
