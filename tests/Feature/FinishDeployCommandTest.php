<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class FinishDeployCommandTest extends TestCase
{
    use RefreshDatabase;

    private string $idFile;

    private string $finishedFile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->idFile = base_path('bootstrap/deploy-id');
        $this->finishedFile = storage_path('app/deployed-id');
    }

    protected function tearDown(): void
    {
        File::delete([$this->idFile, $this->finishedFile]);
        $this->artisan('up');

        parent::tearDown();
    }

    public function test_it_does_nothing_without_a_new_deploy(): void
    {
        $this->artisan('deploy:finish')->assertSuccessful();
        $this->assertFileDoesNotExist($this->finishedFile);

        File::put($this->idFile, 'abc123');
        File::put($this->finishedFile, 'abc123');
        $this->artisan('down');

        $this->artisan('deploy:finish')->assertSuccessful();
        $this->assertTrue($this->app->isDownForMaintenance());
    }

    public function test_it_finishes_a_new_deploy_and_leaves_maintenance_mode(): void
    {
        File::put($this->idFile, "def456\n");
        $this->artisan('down');

        $this->artisan('deploy:finish')->assertSuccessful();

        $this->assertSame('def456', File::get($this->finishedFile));
        $this->assertFalse($this->app->isDownForMaintenance());
        $this->artisan('optimize:clear');
    }
}
