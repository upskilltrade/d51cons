<?php

namespace Tests\Feature;

use App\Enums\MailStatus;
use App\Mail\NewInquiryMail;
use App\Models\Inquiry;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['d51.inquiry_to' => 'team@d51cons.test']);
    }

    private function payload(array $overrides = []): array
    {
        return [
            'name' => 'สมชาย ใจดี',
            'email' => 'somchai@example.com',
            'phone' => '+66 81 234 5678',
            'service_type' => 'other',
            'location' => 'ภูเก็ต',
            'budget_range' => '3m_10m',
            'message' => 'อยากสร้างพูลวิลล่า 3 ห้องนอน',
            'privacy' => '1',
            ...$overrides,
        ];
    }

    public function test_valid_inquiry_is_saved_and_team_is_notified(): void
    {
        Mail::fake();

        $this->post('/en/contact', $this->payload())->assertRedirect('/en/contact/thanks');

        $inquiry = Inquiry::sole();
        $this->assertSame('สมชาย ใจดี', $inquiry->name);
        $this->assertSame('en', $inquiry->locale);
        $this->assertNotNull($inquiry->privacy_acknowledged_at);
        $this->assertSame(MailStatus::Sent, $inquiry->mail_status);
        $this->assertNotNull($inquiry->notified_at);

        Mail::assertSent(NewInquiryMail::class, function (NewInquiryMail $mail) use ($inquiry) {
            return $mail->hasTo('team@d51cons.test')
                && $mail->hasReplyTo('somchai@example.com')
                && $mail->hasSubject('New website inquiry #'.$inquiry->id);
        });
    }

    public function test_service_type_accepts_published_service_slugs_only(): void
    {
        Mail::fake();
        Service::factory()->create(['slug' => 'pool-villas']);
        Service::factory()->draft()->create(['slug' => 'hidden']);

        $this->post('/th/contact', $this->payload(['service_type' => 'pool-villas']))->assertSessionHasNoErrors();
        $this->post('/th/contact', $this->payload(['service_type' => 'hidden']))->assertSessionHasErrors('service_type');
    }

    public function test_provider_failure_keeps_the_inquiry_for_retry(): void
    {
        Mail::shouldReceive('to')->andThrow(new RuntimeException('provider down'));

        $this->post('/th/contact', $this->payload())->assertRedirect('/th/contact/thanks');

        $this->assertSame(MailStatus::Failed, Inquiry::sole()->mail_status);
    }

    public function test_missing_recipient_marks_mail_failed(): void
    {
        Mail::fake();
        config(['d51.inquiry_to' => null]);

        $this->post('/th/contact', $this->payload())->assertRedirect('/th/contact/thanks');

        $this->assertSame(MailStatus::Failed, Inquiry::sole()->mail_status);
        Mail::assertNothingSent();
    }

    public function test_invalid_input_is_rejected(): void
    {
        Mail::fake();

        $this->post('/th/contact', $this->payload([
            'name' => '',
            'email' => 'not-an-email',
            'message' => '',
            'privacy' => null,
            'budget_range' => 'billions',
            'phone' => '<script>',
        ]))->assertSessionHasErrors(['name', 'email', 'message', 'privacy', 'budget_range', 'phone']);

        $this->assertDatabaseCount('inquiries', 0);
    }

    public function test_honeypot_submissions_are_dropped_silently(): void
    {
        Mail::fake();

        $this->post('/th/contact', $this->payload(['website' => 'http://spam.test']))
            ->assertRedirect('/th/contact/thanks');

        $this->assertDatabaseCount('inquiries', 0);
        Mail::assertNothingSent();
    }

    public function test_submissions_are_rate_limited(): void
    {
        Mail::fake();

        for ($i = 0; $i < 3; $i++) {
            $this->post('/th/contact', $this->payload())->assertRedirect();
        }

        $this->post('/th/contact', $this->payload())->assertTooManyRequests();
        $this->assertDatabaseCount('inquiries', 3);
    }

    public function test_csrf_token_is_required(): void
    {
        $this->app->instance('env', 'production');

        $this->withMiddleware()
            ->post('/th/contact', $this->payload())
            ->assertStatus(419);

        $this->assertDatabaseCount('inquiries', 0);
    }
}
