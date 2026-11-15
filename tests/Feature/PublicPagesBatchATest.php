<?php

namespace Tests\Feature;

use App\Services\SiteMailer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesBatchATest extends TestCase
{
    use RefreshDatabase;

    public function test_pages_render_on_clean_and_php_urls(): void
    {
        foreach (['about', 'mbbs', 'programs', 'admissions', 'fees', 'scholarships', 'support', 'privacy', 'terms', 'be-our-partner'] as $page) {
            $this->get('/' . $page)->assertOk()->assertSee('<link rel="canonical" href="https://xjmu.online/' . $page . '"', false);
            $this->get('/' . $page . '.php')->assertStatus(301)->assertRedirect(url('/' . $page));
        }
        $this->get('/programs.html')->assertOk();
        $this->get('/')->assertOk()->assertSee('FAQPage', false)->assertSee('Is Xinjiang Medical University recognized for MBBS?', false);
        $this->assertSame('/', route('home', [], false));
    }

    public function test_empty_programs_and_scholarships_show_empty_states(): void
    {
        $this->get('/programs')->assertSee('No programs available at the moment.');
        $this->get('/scholarships')->assertSee('No scholarships available at the moment.');
    }

    public function test_partner_form_keeps_its_action_and_has_no_csrf_field(): void
    {
        $this->get('/be-our-partner')->assertSee('action="send_partner_registration"', false)->assertDontSee('name="_token"', false);
    }

    public function test_home_registration_popup_mails_and_shows_confirmation(): void
    {
        $mailer = new SiteMailer();
        $this->app->instance(SiteMailer::class, $mailer);

        $this->post('/', [
            'webinar_register' => '', 'applying_for' => 'MBBS', 'name' => "Jane\r\nBcc: x@y.z", 'nationality' => 'PK',
            'email' => 'jane@example.com', 'whatsapp' => '+92 300',
        ])->assertOk()
            ->assertSee('Thank you for registering! Your registration has been submitted successfully.')
            ->assertSee('id="successAlert"', false);

        $this->assertCount(1, $mailer->outbox);
        $mail = $mailer->outbox[0];
        $this->assertSame('studyatxjmu@gmail.com', $mail['to']);
        $this->assertStringNotContainsString("\n", $mail['subject']);
        $this->assertStringContainsString('Reply-To: jane@example.com', $mail['headers']);
        $this->assertStringContainsString('Applying For: MBBS', $mail['body']);
    }

    public function test_home_post_without_registration_flag_just_shows_home(): void
    {
        $this->post('/', ['foo' => 'bar'])->assertOk()->assertDontSee('id="successAlert"', false);
    }
}
