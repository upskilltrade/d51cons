<?php

namespace Tests\Feature;

use App\Enums\InquiryStatus;
use App\Enums\MailStatus;
use App\Mail\NewInquiryMail;
use App\Models\Inquiry;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        foreach (['/admin', '/admin/services', '/admin/projects', '/admin/inquiries', '/admin/settings'] as $path) {
            $this->get($path)->assertRedirect('/admin/login');
        }

        $this->post('/admin/services', ['slug' => 'x', 'title_th' => 'x'])->assertRedirect('/admin/login');
        $this->assertDatabaseCount('services', 0);
    }

    public function test_admin_can_log_in_and_out(): void
    {
        $user = User::factory()->create(['email' => 'admin@d51cons.test', 'password' => 'a-long-password']);

        $this->post('/admin/login', ['email' => 'admin@d51cons.test', 'password' => 'wrong'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post('/admin/login', ['email' => 'admin@d51cons.test', 'password' => 'a-long-password'])
            ->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);

        $this->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    public function test_login_is_throttled(): void
    {
        User::factory()->create(['email' => 'admin@d51cons.test', 'password' => 'a-long-password']);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', ['email' => 'admin@d51cons.test', 'password' => 'wrong']);
        }

        $this->post('/admin/login', ['email' => 'admin@d51cons.test', 'password' => 'a-long-password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_pages_render(): void
    {
        $this->actingAs(User::factory()->create());
        $service = Service::factory()->create();
        $project = Project::factory()->create();
        $inquiry = Inquiry::factory()->create();

        foreach ([
            '/admin', '/admin/services', '/admin/services/create', "/admin/services/{$service->slug}/edit",
            '/admin/projects', '/admin/projects/create', "/admin/projects/{$project->slug}/edit",
            '/admin/inquiries', "/admin/inquiries/{$inquiry->id}", '/admin/settings',
        ] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_admin_can_create_service_with_image(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        $this->post('/admin/services', [
            'slug' => 'Pool-Villas',
            'title_th' => 'พูลวิลล่า',
            'title_en' => 'Pool Villas',
            'is_published' => '1',
            'image' => UploadedFile::fake()->image('villa.jpg', 1200, 800),
        ])->assertRedirect('/admin/services/pool-villas/edit');

        $service = Service::sole();
        $this->assertSame('pool-villas', $service->slug);
        $this->assertTrue($service->is_published);
        $this->assertStringNotContainsString('villa', $service->image_path);
        Storage::disk('public')->assertExists($service->image_path);
    }

    public function test_svg_and_non_image_uploads_are_rejected(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        $svg = UploadedFile::fake()->createWithContent('logo.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>');
        $php = UploadedFile::fake()->createWithContent('shell.jpg', '<?php echo 1;');

        foreach ([$svg, $php] as $file) {
            $this->post('/admin/services', ['slug' => 'x', 'title_th' => 'x', 'image' => $file])
                ->assertSessionHasErrors('image');
        }

        $this->assertDatabaseCount('services', 0);
    }

    public function test_admin_can_manage_project_and_gallery(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        $this->post('/admin/projects', [
            'slug' => 'villa-a',
            'title_th' => 'วิลล่า A',
            'status' => 'completed',
            'is_concept' => '1',
            'published_at' => now()->subHour()->format('Y-m-d\TH:i'),
            'cover' => UploadedFile::fake()->image('cover.webp', 1600, 900),
        ])->assertRedirect('/admin/projects/villa-a/edit');

        $project = Project::sole();
        $this->assertTrue($project->is_concept);
        $this->assertTrue($project->isPublished());

        $this->post("/admin/projects/{$project->slug}/images", [
            'images' => [UploadedFile::fake()->image('a.png', 800, 600), UploadedFile::fake()->image('b.jpg', 800, 600)],
        ])->assertSessionHasNoErrors();

        $this->assertCount(2, $project->images);
        $image = $project->images->first();

        $this->put("/admin/projects/{$project->slug}/images/{$image->id}", [
            'alt_th' => 'ด้านหน้าอาคาร', 'sort_order' => 5,
        ])->assertSessionHasNoErrors();
        $this->assertSame('ด้านหน้าอาคาร', $image->fresh()->alt_th);

        $this->delete("/admin/projects/{$project->slug}/images/{$image->id}");
        Storage::disk('public')->assertMissing($image->path);

        $this->delete("/admin/projects/{$project->slug}")->assertRedirect('/admin/projects');
        $this->assertDatabaseCount('projects', 0);
        $this->assertDatabaseCount('project_images', 0);
    }

    public function test_gallery_image_must_belong_to_project(): void
    {
        $this->actingAs(User::factory()->create());
        $a = Project::factory()->create();
        $b = Project::factory()->create();
        $image = $b->images()->create(['path' => 'uploads/x.jpg', 'sort_order' => 1]);

        $this->delete("/admin/projects/{$a->slug}/images/{$image->id}")->assertNotFound();
        $this->assertModelExists($image);
    }

    public function test_admin_can_update_inquiry_status(): void
    {
        $this->actingAs(User::factory()->create());
        $inquiry = Inquiry::factory()->create();

        $this->patch("/admin/inquiries/{$inquiry->id}", ['status' => 'contacted'])->assertSessionHasNoErrors();

        $this->assertSame(InquiryStatus::Contacted, $inquiry->fresh()->status);
    }

    public function test_admin_can_resend_failed_notification_once(): void
    {
        Mail::fake();
        config(['d51.inquiry_to' => 'team@d51cons.test']);
        $this->actingAs(User::factory()->create());
        $failed = Inquiry::factory()->mailFailed()->create();
        $sent = Inquiry::factory()->create();

        $this->post("/admin/inquiries/{$failed->id}/resend")->assertSessionHas('status', __('admin.inquiry.resent'));
        $this->post("/admin/inquiries/{$failed->id}/resend")->assertSessionHas('status', __('admin.inquiry.not_retryable'));
        $this->post("/admin/inquiries/{$sent->id}/resend")->assertSessionHas('status', __('admin.inquiry.not_retryable'));

        $this->assertSame(MailStatus::Sent, $failed->fresh()->mail_status);
        Mail::assertSentCount(1);
        Mail::assertSent(NewInquiryMail::class, fn ($mail) => $mail->inquiry->is($failed));
    }

    public function test_settings_store_only_allowlisted_keys(): void
    {
        $this->actingAs(User::factory()->create());

        $this->put('/admin/settings', [
            'phone' => '+66 2 123 4567',
            'line_url' => 'https://line.me/ti/p/example',
            'not_a_setting' => 'nope',
        ])->assertSessionHasNoErrors();

        $this->assertSame('+66 2 123 4567', Setting::value('phone'));
        $this->assertDatabaseMissing('settings', ['key' => 'not_a_setting']);

        $this->put('/admin/settings', ['line_url' => 'javascript:alert(1)'])->assertSessionHasErrors('line_url');
    }

    public function test_settings_are_shown_on_public_pages_only_when_filled(): void
    {
        $this->get('/th')->assertDontSee('tel:');

        Setting::saveMany(['phone' => '021234567']);

        $this->get('/th')->assertSee('tel:021234567', false);
    }
}
