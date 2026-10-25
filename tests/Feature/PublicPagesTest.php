<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_redirects_permanently_to_thai(): void
    {
        $this->get('/')->assertStatus(301)->assertRedirect('/th');
    }

    public function test_pages_render_in_both_locales(): void
    {
        foreach (['th', 'en'] as $locale) {
            foreach (['', '/services', '/projects', '/about', '/contact', '/privacy'] as $path) {
                $this->get("/{$locale}{$path}")
                    ->assertOk()
                    ->assertSee("<html lang=\"{$locale}\">", false)
                    ->assertSee('hreflang="en"', false);
            }
        }
    }

    public function test_unknown_locale_is_not_found(): void
    {
        $this->get('/fr')->assertNotFound();
        $this->get('/fr/services')->assertNotFound();
    }

    public function test_content_uses_locale_and_falls_back_to_thai(): void
    {
        Service::factory()->create(['slug' => 'homes', 'title_th' => 'บ้านพักอาศัย', 'title_en' => 'Custom Homes']);
        Service::factory()->create(['slug' => 'villas', 'title_th' => 'พูลวิลล่า', 'title_en' => null]);

        $this->get('/en/services')->assertSee('Custom Homes')->assertSee('พูลวิลล่า');
        $this->get('/th/services')->assertSee('บ้านพักอาศัย');
    }

    public function test_only_published_services_are_visible(): void
    {
        Service::factory()->create(['slug' => 'live', 'title_th' => 'บริการที่เผยแพร่']);
        Service::factory()->draft()->create(['slug' => 'draft', 'title_th' => 'บริการร่าง']);

        $this->get('/th/services')->assertSee('บริการที่เผยแพร่')->assertDontSee('บริการร่าง');
        $this->get('/th/services/live')->assertOk();
        $this->get('/th/services/draft')->assertNotFound();
    }

    public function test_only_published_projects_are_visible(): void
    {
        Project::factory()->create(['slug' => 'done', 'title_th' => 'งานที่เผยแพร่']);
        Project::factory()->draft()->create(['slug' => 'draft', 'title_th' => 'งานร่าง']);
        Project::factory()->create(['slug' => 'later', 'title_th' => 'งานตั้งเวลา', 'published_at' => now()->addDay()]);

        $this->get('/th/projects')
            ->assertSee('งานที่เผยแพร่')
            ->assertDontSee('งานร่าง')
            ->assertDontSee('งานตั้งเวลา');

        $this->get('/th/projects/done')->assertOk();
        $this->get('/th/projects/draft')->assertNotFound();
        $this->get('/th/projects/later')->assertNotFound();
    }

    public function test_concepts_are_labeled_and_kept_out_of_real_projects(): void
    {
        Project::factory()->concept()->create(['slug' => 'idea', 'title_en' => 'Villa Idea']);

        $response = $this->get('/en/projects');
        $response->assertSee('Villa Idea')->assertSee('Illustrative concept');
        $this->assertTrue($response->viewData('projects')->isEmpty());
        $this->assertCount(1, $response->viewData('concepts'));

        $this->get('/th/projects/idea')->assertOk()->assertSee('ภาพแนวคิดประกอบ');
    }

    public function test_projects_can_be_filtered_by_service(): void
    {
        $homes = Service::factory()->create(['slug' => 'homes']);
        Project::factory()->for($homes)->create(['title_en' => 'Home One']);
        Project::factory()->create(['title_en' => 'Shop One']);

        $this->get('/en/projects?category=homes')->assertSee('Home One')->assertDontSee('Shop One');
    }

    public function test_sitemap_lists_published_pages_only(): void
    {
        Project::factory()->create(['slug' => 'done']);
        Project::factory()->draft()->create(['slug' => 'draft']);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee(url('/th/projects/done'))
            ->assertSee(url('/en/projects/done'))
            ->assertDontSee('draft');
    }

    public function test_noindex_header_on_staging_and_admin(): void
    {
        $this->get('/th')->assertHeaderMissing('X-Robots-Tag');
        $this->get('/admin/login')->assertHeader('X-Robots-Tag', 'noindex, nofollow');

        config(['d51.noindex' => true]);
        $this->get('/th')->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }
}
